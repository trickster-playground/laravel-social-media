<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import { PencilIcon, TrashIcon, Cog8ToothIcon } from "@heroicons/vue/24/solid";
import UpdatePostHeader from "./UpdatePostHeader.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  post: Object,
});

const emit = defineEmits(["editClick"]);

function openEditModal() {
  emit("editClick", props.post);
}

function isImage(attachment) {
  const mime = attachment.mime.split("/");
  return mime[0].toLowerCase() === "image";
}

function deletePost() {
  if (window.confirm("Are you sure you want to delete this post?")) {
    router.delete(route("posts.destroy", props.post.id), {
      preserveScroll: true,
    });
    console.log("deletePost");
  }
}
</script>

<template>
  <div class="bg-gray-800 rounded-lg px-4 py-4 my-4 shadow-lg">
    <div class="flex items-center justify-between">
      <UpdatePostHeader :post="post" />
      <div class="">
        <Menu as="div" class="relative inline-block text-left">
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
    <div class="grid sm:grid-cols-1 grid-cols-2 lg:grid-cols-3 gap-3">
      <template v-for="attachment of post.attachments">
        <div
          class="group aspect-square bg-gray-600 flex flex-col items-center justify-center relative"
        >
          <button
            class="opacity-0 group-hover:opacity-100 transition-all w-8 h-8 flex items-center justify-center bg-gray-500 rounded absolute right-2 top-2 text-white hover:bg-gray-800"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="size-4"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"
              />
            </svg>
          </button>

          <img
            v-if="isImage(attachment)"
            :src="attachment.url"
            class="object-cover aspect-square"
          />

          <template v-else>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="size-16"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"
              />
            </svg>

            {{ attachment.name }}
          </template>
        </div>
      </template>
    </div>
    <div class="flex gap-4 my-2">
      <button
        class="flex gap-1 flex-1 items-center justify-center py-2 px-4 bg-gray-600 hover:bg-red-700 rounded-lg"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="currentColor"
          class="size-6"
        >
          <path
            d="M7.493 18.5c-.425 0-.82-.236-.975-.632A7.48 7.48 0 0 1 6 15.125c0-1.75.599-3.358 1.602-4.634.151-.192.373-.309.6-.397.473-.183.89-.514 1.212-.924a9.042 9.042 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75A.75.75 0 0 1 15 2a2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H14.23c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23h-.777ZM2.331 10.727a11.969 11.969 0 0 0-.831 4.398 12 12 0 0 0 .52 3.507C2.28 19.482 3.105 20 3.994 20H4.9c.445 0 .72-.498.523-.898a8.963 8.963 0 0 1-.924-3.977c0-1.708.476-3.305 1.302-4.666.245-.403-.028-.959-.5-.959H4.25c-.832 0-1.612.453-1.918 1.227Z"
          />
        </svg>

        Like
      </button>
      <button
        class="flex gap-1 flex-1 items-center justify-center py-2 px-4 bg-gray-600 hover:bg-blue-700 rounded-lg"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="currentColor"
          class="size-6"
        >
          <path
            fill-rule="evenodd"
            d="M4.848 2.771A49.144 49.144 0 0 1 12 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 0 1-3.476.383.39.39 0 0 0-.297.17l-2.755 4.133a.75.75 0 0 1-1.248 0l-2.755-4.133a.39.39 0 0 0-.297-.17 48.9 48.9 0 0 1-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.68 3.348-3.97ZM6.75 8.25a.75.75 0 0 1 .75-.75h9a.75.75 0 0 1 0 1.5h-9a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H7.5Z"
            clip-rule="evenodd"
          />
        </svg>

        Comment
      </button>
    </div>
  </div>
</template>

<style></style>
