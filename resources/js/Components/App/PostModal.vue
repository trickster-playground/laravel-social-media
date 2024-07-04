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
  ArrowPathIcon,
} from "@heroicons/vue/24/solid";
import { useForm } from "@inertiajs/vue3";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { isImage } from "@/helpers";
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";

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

const attachmentExtensions = usePage().props.attachmentExtensions;

const props = defineProps({
  post: {
    type: Object,
    required: true,
  },
  modelValue: Boolean,
});

const attachmentFiles = ref([]);
const attachmentErrors = ref([]);
const formErrors = ref({});

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

const emit = defineEmits(["update:modelValue", "hide"]);

function closeModal() {
  show.value = false;
  emit("hide");
  resetModal();
}

function resetModal() {
  form.reset();
  formErrors.value = {};
  attachmentErrors.value = [];
  attachmentFiles.value = [];
  if (props.post.attachments) {
    props.post.attachments.forEach((file) => (file.deleted = false));
  }
}

const form = useForm({
  body: "",
  attachments: [],
  deletedIds: [],
  _method: "POST",
});

watch(
  () => props.post,
  () => {
    form.body = props.post.body || "";
  }
);

function SubmitEvent() {
  form.attachments = attachmentFiles.value.map((fileInfo) => fileInfo.file);
  if (props.post.id) {
    form._method = "PUT";
    form.post(route("posts.update", props.post.id), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
      },
      onError: (errors) => {
        processErrors(errors);
      },
    });
  } else {
    form.post(route("posts.store"), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
      },
      onError: (errors) => {
        processErrors(errors);
      },
    });
  }
}

function processErrors(errors) {
  formErrors.value = errors;
  for (const key in errors) {
    if (key.includes(".")) {
      const [, index] = key.split(".");
      attachmentErrors.value[index] = errors[key];
    }
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

const computedAttachments = computed(() => {
  return [...attachmentFiles.value, ...(props.post.attachments || [])];
});

const showExtensionsText = computed(() => {
  for (let fileInfo of attachmentFiles.value) {
    const file = fileInfo.file;
    let parts = file.name.split(".");
    let ext = parts.pop().toLowerCase();
    if (!attachmentExtensions.includes(ext)) {
      return true;
    }
  }
  return false;
});

function removeAttachment(fileInfo) {
  if (fileInfo.file) {
    attachmentFiles.value = attachmentFiles.value.filter(
      (file) => file !== fileInfo
    );
  } else {
    form.deletedIds.push(fileInfo.id);
    fileInfo.deleted = true;
  }
}

function undoDeleted(fileInfo) {
  form.deletedIds = form.deletedIds.filter((id) => id !== fileInfo.id);
  fileInfo.deleted = false;
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
                    v-if="showExtensionsText"
                    class="border-l-4 border-blue-500 bg-blue-400/10 text-white text-xs py-2 px-2 mt-3"
                  >
                    Trickster just allow following files type, please check
                    before submit: <br />
                    {{ attachmentExtensions.join(", ") }}
                  </div>
                  <div
                    v-if="formErrors.attachments"
                    class="text-red-500 text-xs"
                  >
                    <div v-for="error in formErrors.attachments">
                      {{ error }}
                    </div>
                  </div>

                  <div
                    class="grid gap-3"
                    :class="
                      computedAttachments.length === 1
                        ? 'grid-cols-1'
                        : 'grid-cols-2'
                    "
                  >
                    <div v-for="(fileInfo, index) of computedAttachments">
                      <div
                        class="group aspect-square bg-gray-900 flex flex-col items-center justify-center relative my-4 w-full h-full"
                        :class="
                          attachmentErrors[index]
                            ? 'border-red-500 bg-red-900/20 border-2'
                            : ''
                        "
                      >
                        <div
                          v-if="fileInfo.deleted"
                          class="absolute left-0 bottom-0 right-0 py-2 px-3 bg-red-700 text-white text-sm z-30 flex items-center justify-between"
                        >
                          To be deleted

                          <ArrowPathIcon
                            @click="undoDeleted(fileInfo)"
                            class="w-5 h-5 ml-1 z-30 cursor-pointer"
                          />
                        </div>

                        <button
                          class="absolute right-1 top-1 rounded-full bg-gray-400 hover:bg-red-600 text-white text-xs flex items-center cursor-pointer w-10 h-10 justify-center"
                          @click="removeAttachment(fileInfo)"
                        >
                          <XCircleIcon class="size-6" />
                        </button>
                        <img
                          v-if="isImage(fileInfo.file || fileInfo)"
                          :src="fileInfo.url"
                          class="object-contain w-full h-full"
                          :class="fileInfo.deleted ? 'opacity-80' : ''"
                        />

                        <div
                          v-else
                          class="flex justify-center items-center flex-col"
                          :class="fileInfo.deleted ? 'opacity-80' : ''"
                        >
                          <DocumentMagnifyingGlassIcon class="w-16 h-16" />

                          <p class="text-white text-center">
                            {{ (fileInfo.file || fileInfo).name }}
                          </p>
                        </div>
                        <small class="text-red-500 font-black mt-6">
                          {{ attachmentErrors[index] }}
                        </small>
                      </div>
                    </div>
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
