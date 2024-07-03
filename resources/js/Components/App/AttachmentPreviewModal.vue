<script setup>
import { computed } from "vue";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from "@headlessui/vue";
import {
  XMarkIcon,
  DocumentMagnifyingGlassIcon,
  ChevronDoubleLeftIcon,
  ChevronDoubleRightIcon,
} from "@heroicons/vue/24/solid";
import { isImage } from "@/helpers";

const props = defineProps({
  attachments: {
    type: Array,
    required: true,
  },
  index: Number,
  modelValue: Boolean,
});

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

const currentIndex = computed({
  get: () => props.index,
  set: (value) => emit("update:index", value),
});

const attachment = computed(() => {
  return props.attachments[currentIndex.value];
});

const emit = defineEmits(["update:modelValue", "update:index", "hide"]);

function closeModal() {
  show.value = false;
  emit("hide");
}

function previous() {
  if (currentIndex.value === 0) {
    return (currentIndex.value = props.attachments.length - 1);
  }
  currentIndex.value--;
}
function next() {
  if (currentIndex.value === props.attachments.length - 1) {
    return (currentIndex.value = 0);
  }
  currentIndex.value++;
}
</script>

<template>
  <teleport to="body">
    <TransitionRoot appear :show="show" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-40">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black/25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto flex justify-center items-center">
          <div class="p-12 h-screen w-screen fixed">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                class="w-full h-full transform overflow-hidden rounded-2xl border-2 border-white bg-gray-800 p-6 text-left align-middle shadow-xl transition-all"
              >
                <button
                  @click="closeModal"
                  class="absolute right-3 top-3 w-8 h-8 rounded-full border-1 hover:bg-black/50 transition-all flex items-center justify-center z-30"
                >
                  <XMarkIcon class="size-10" />
                </button>
                <div class="relative group h-full">
                  <div
                    @click="previous"
                    class="absolute opacity-0 group-hover:opacity-100 flex items-center h-full left-0 cursor-pointer"
                  >
                    <ChevronDoubleLeftIcon class="size-12" />
                  </div>

                  <div
                    @click="next"
                    class="absolute opacity-0 group-hover:opacity-100 flex -ml-24 items-center h-full right-0 cursor-pointer"
                  >
                    <ChevronDoubleRightIcon class="size-16" />
                  </div>

                  <div class="flex items-center justify-center w-full h-full">
                    <img
                      v-if="isImage(attachment)"
                      :src="attachment.url"
                      class="max-w-full max-h-full"
                    />
                    <div
                      v-else
                      class="p-32 flex flex-col justify-center items-center text-center"
                    >
                      <DocumentMagnifyingGlassIcon class="size-16" />

                      <small>{{ attachment.name }}</small>
                    </div>
                  </div>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </teleport>
</template>
