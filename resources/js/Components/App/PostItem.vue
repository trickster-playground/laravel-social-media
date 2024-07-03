<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import {
  PencilIcon,
  TrashIcon,
  Cog8ToothIcon,
  HandThumbUpIcon,
  ChatBubbleLeftEllipsisIcon,
  FolderArrowDownIcon,
} from "@heroicons/vue/24/outline";
import UpdatePostHeader from "./UpdatePostHeader.vue";
import { router } from "@inertiajs/vue3";
import { isImage } from "@/helpers";
import { DocumentMagnifyingGlassIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
  post: Object,
});

const emit = defineEmits(["editClick", "attachmentClick"]);

function openEditModal() {
  emit("editClick", props.post);
}

function deletePost() {
  if (window.confirm("Are you sure you want to delete this post?")) {
    router.delete(route("posts.destroy", props.post.id), {
      preserveScroll: true,
    });
    console.log("deletePost");
  }
}

function openAttachment(index) {
  emit("attachmentClick", props.post, index);
}
</script>

<template>
  <div
    class="bg-gray-800 rounded-lg px-4 py-4 my-4 shadow-lg border-b-2 border-t-2 border-blue-500"
  >
    <div class="flex items-center justify-between">
      <UpdatePostHeader :post="post" />
      <div class="">
        <Menu as="div" class="relative inline-block text-left z-30">
          <div>
            <MenuButton
              class="inline-flex w-full justify-center rounded-md bg-black/20 px-4 py-2 text-sm font-medium text-blue-500 hover:bg-black/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
            >
              <Cog8ToothIcon
                class="h-5 w-5 text-blue-500 hover:text-blue-400"
                aria-hidden="true"
              />
            </MenuButton>
          </div>

          <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
          >
            <MenuItems
              class="absolute right-0 mt-2 w-36 origin-top-right divide-y divide-gray-100 rounded-md bg-gray-600 shadow-lg ring-1 ring-black/5 focus:outline-none"
            >
              <div class="px-1 py-1 mx-1 my-1">
                <MenuItem v-slot="{ active }">
                  <button
                    @click="openEditModal"
                    :class="[
                      active ? 'bg-blue-600 text-white' : 'text-white',
                      'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                    ]"
                  >
                    <PencilIcon
                      class="mr-2 h-5 w-5 text-blue-500"
                      aria-hidden="true"
                    />
                    Edit
                  </button>
                </MenuItem>
                <MenuItem v-slot="{ active }">
                  <button
                    @click="deletePost"
                    :class="[
                      active ? 'bg-red-600 text-white' : 'text-white',
                      'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                    ]"
                  >
                    <TrashIcon
                      class="mr-2 h-5 w-5 text-red-500"
                      aria-hidden="true"
                    />
                    Delete
                  </button>
                </MenuItem>
              </div>
            </MenuItems>
          </transition>
        </Menu>
      </div>
    </div>

    <div class="my-3">
      <Disclosure v-slot="{ open }">
        <div
          v-if="!open"
          v-html="post.body.substring(0, 200)"
          class="ck-content-output px-4 pb-2 pt-4 text-md text-justify first-letter:uppercase"
        ></div>
        <template v-if="post.body.length > 200">
          <DisclosurePanel class="px-4 pb-2 pt-4 text-md">
            <div
              class="ck-content-output text-justify"
              v-html="post.body"
            ></div>
          </DisclosurePanel>
          <div class="flex justify-end mt-4">
            <DisclosureButton class="btn btn-primary rounded-xl hover:btn-info">
              {{ open ? "Read Less" : "Read More" }}
            </DisclosureButton>
          </div>
        </template>
      </Disclosure>
    </div>
    <div
      class="grid gap-3"
      :class="
        post.attachments.length === 1
          ? 'grid-cols-1'
          : post.attachments.length === 2
          ? 'grid-cols-2'
          : 'grid-cols-3'
      "
    >
      <template v-for="(attachment, index) of post.attachments.slice(0, 3)">
        <div
          @click="openAttachment(index)"
          class="group bg-gray-900 flex flex-col items-center justify-center relative cursor-pointer"
        >
          <div v-if="index === 2 && post.attachments.length > 3">
            <div
              class="absolute left-0 top-0 right-0 bottom-0 z-10 bg-black/60 text-white flex items-center justify-center text-2xl"
            >
              +{{ post.attachments.length - 3 }} more
            </div>
          </div>

          <a
            :href="route('posts.download', attachment)"
            class="opacity-0 group-hover:opacity-100 transition-all w-8 h-8 flex items-center justify-center bg-gray-500 rounded absolute right-2 top-2 text-white hover:bg-gray-800 z-20"
          >
            <FolderArrowDownIcon class="size-6" />
          </a>

          <img
            v-if="isImage(attachment)"
            :src="attachment.url"
            class="object-cover w-full h-full"
          />

          <div v-else class="flex justify-center items-center flex-col">
            <DocumentMagnifyingGlassIcon class="size-12" />

            <small class="text-white text-xs text-center my-2">{{
              attachment.name
            }}</small>
          </div>
        </div>
      </template>
    </div>
    <div class="flex gap-4 my-2">
      <button
        class="flex gap-1 flex-1 items-center justify-center py-2 px-4 bg-gray-600 hover:bg-red-700 rounded-lg"
      >
        <HandThumbUpIcon class="size-6" />

        Like
      </button>
      <button
        class="flex gap-1 flex-1 items-center justify-center py-2 px-4 bg-gray-600 hover:bg-blue-700 rounded-lg"
      >
        <ChatBubbleLeftEllipsisIcon class="size-6" />

        Comment
      </button>
    </div>
  </div>
</template>

<style></style>
