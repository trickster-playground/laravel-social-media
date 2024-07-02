<script setup>
import { computed, watch } from "vue";
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from "@headlessui/vue";
import UpdatePostHeader from "@/Components/App/UpdatePostHeader.vue";
import {
  CheckIcon,
  XMarkIcon,
  PaperClipIcon,
  XCircleIcon,
  DocumentMagnifyingGlassIcon,
} from "@heroicons/vue/24/solid";
import { useForm } from "@inertiajs/vue3";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { isImage } from "@/helpers";
import { ref } from "vue";

const editor = ClassicEditor;

const editorConfig = {
  toolbar: [
    "heading",
    "|",
    "bold",
    "italic",
    "|",
    "link",
    "|",
    "bulletedList",
    "numberedList",
    "|",
    "outdent",
    "indent",
    "|",
    "blockQuote",
    "|",
    "undo",
    "redo",
  ],
};

const props = defineProps({
  post: {
    type: Object,
    required: true,
  },
  modelValue: Boolean,
});

const attachmentFiles = ref([]);

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

const emit = defineEmits(["update:modelValue"]);

function closeModal() {
  show.value = false;
  resetModal();
}

function resetModal() {
  form.reset();
  attachmentFiles.value = [];
}

const form = useForm({
  id: "",
  body: "",
  attachments: [],
});

watch(
  () => props.post,
  () => {
    form.id = props.post.id;
    form.body = props.post.body;
  }
);

function SubmitEvent() {
  form.attachments = attachmentFiles.value.map((fileInfo) => fileInfo.file);
  if (form.id) {
    form.put(route("posts.update", props.post.id), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
      },
      onError: () => {
        alert("Failed to update post");
      },
    });
  } else {
    form.post(route("posts.store"), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
      },
      onError: () => {
        alert("Failed to create post");
      },
    });
  }
}

async function onAttachmentChoose(event) {
  for (const file of event.target.files) {
    const fileInfo = {
      file,
      url: await readFile(file),
    };

    attachmentFiles.value.push(fileInfo);
  }
  event.target.value = null;
  console.log(attachmentFiles.value);
}

async function readFile(file) {
  return new Promise((res, rej) => {
    if (isImage(file)) {
      const reader = new FileReader();
      reader.onload = () => {
        res(reader.result);
      };
      reader.onerror = rej;
      reader.readAsDataURL(file);
    } else {
      res(null);
    }
  });
}

function removeAttachment(fileInfo) {
  attachmentFiles.value = attachmentFiles.value.filter(
    (file) => file !== fileInfo
  );
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
                    {{ post.id ? "Update Post" : "Create Post" }}
                  </DialogTitle>
                </div>
                <div class="my-4">
                  <UpdatePostHeader :post="post" />
                  <ckeditor
                    :editor="editor"
                    v-model="form.body"
                    :config="editorConfig"
                  ></ckeditor>

                  <div
                    class="grid gap-3 w-full h-full"
                    :class="
                      attachmentFiles.length === 1
                        ? 'grid-cols-1'
                        : attachmentFiles.length === 2
                        ? 'grid-cols-2'
                        : 'grid-cols-3'
                    "
                  >
                    <template v-for="fileInfo of attachmentFiles">
                      <div
                        class="group aspect-square bg-gray-500 flex flex-col items-center justify-center relative my-4 w-full h-full"
                      >
                        <button
                          class="absolute right-1 top-1 rounded-full bg-gray-400 hover:bg-red-600 text-white text-xs flex items-center cursor-pointer w-10 h-10 justify-center"
                          @click="removeAttachment(fileInfo)"
                        >
                          <XCircleIcon class="size-6" />
                        </button>
                        <img
                          v-if="isImage(fileInfo.file)"
                          :src="fileInfo.url"
                          class="object-cover w-full h-full"
                        />

                        <template v-else>
                          <DocumentMagnifyingGlassIcon class="w-16 h-16" />

                          <p class="text-white text-center">
                            {{ fileInfo.file.name }}
                          </p>
                        </template>
                      </div>
                    </template>
                  </div>
                </div>

                <div class="mt-6 flex justify-center gap-2 w-full">
                  <button
                    type="button"
                    class="flex w-full justify-center items-center rounded-md border border-transparent bg-blue-300 px-2 py-2 text-sm font-medium text-blue-700 hover:bg-blue-500 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 relative"
                  >
                    Attach File
                    <PaperClipIcon class="w-5 h-5 ml-1" />
                    <input
                      @click.stop
                      @change="onAttachmentChoose"
                      type="file"
                      multiple
                      class="absolute left-0 top-0 right-0 bottom-0 opacity-0 hover:cursor-pointer"
                    />
                  </button>
                  <button
                    type="button"
                    class="flex justify-center items-center rounded-md border border-transparent bg-red-300 px-2 py-2 text-sm font-medium text-red-500 hover:bg-red-500 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="closeModal"
                  >
                    Cancel
                    <XMarkIcon class="w-5 h-5 ml-1" />
                  </button>

                  <button
                    type="button"
                    class="flex justify-center items-center rounded-md border border-transparent bg-green-300 px-2 py-2 text-sm font-medium text-green-700 hover:bg-green-500 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="SubmitEvent"
                  >
                    Submit
                    <CheckIcon class="w-5 h-5 ml-1" />
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
