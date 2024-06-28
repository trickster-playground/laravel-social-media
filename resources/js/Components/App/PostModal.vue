<template>
  <teleport to="body">
    <TransitionRoot appear :show="show" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-10">
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

        <div class="fixed inset-0 overflow-y-auto">
          <div
            class="flex min-h-full items-center justify-center p-4 text-center"
          >
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
                class="w-full max-w-3xl transform overflow-hidden rounded-2xl border-2 border-white bg-gray-800 p-6 text-left align-middle shadow-xl transition-all"
              >
                <div class="flex justify-center items-center">
                  <DialogTitle
                    as="h3"
                    class="text-lg font-medium leading-6 py-4 rounded-lg w-96 text-white bg-blue-600/40 justify-center flex"
                  >
                    Update Post
                  </DialogTitle>
                </div>
                <div class="my-4">
                  <UpdatePostHeader :post="post" />
                  <TextAreaInput
                    v-model="form.body"
                    rows="5"
                    placeholder="Update your post"
                  />
                </div>

                <div class="mt-4 flex justify-center gap-2">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-300 px-2 py-2 text-sm font-medium text-red-500 hover:bg-red-500 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="closeModal"
                  >
                    Cancel
                    <XMarkIcon class="w-5 h-4 ml-1" />
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-300 px-2 py-2 text-sm font-medium text-green-700 hover:bg-green-500 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="SubmitEvent"
                  >
                    Submit
                    <CheckIcon class="w-5 h-4 ml-1" />
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </teleport>
</template>

<script setup>
import { computed, watch } from "vue";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from "@headlessui/vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import UpdatePostHeader from "@/Components/App/UpdatePostHeader.vue";
import { CheckIcon, XMarkIcon } from "@heroicons/vue/24/solid";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
  post: {
    type: Object,
    required: true,
  },
  modelValue: Boolean,
});

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

const emit = defineEmits(["update:modelValue"]);

function closeModal() {
  show.value = false;
}

const form = useForm({
  id: "",
  body: "",
});

watch(
  () => props.post,
  () => {
    form.id = props.post.id;
    form.body = props.post.body;
  }
);

function SubmitEvent() {
  form.put(route("posts.update", props.post.id), {
    preserveScroll: true,
    onSuccess: () => {
      closeModal();
    },
    onError: () => {
      alert("Failed to update post");
    },
  });
}
</script>
