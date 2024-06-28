<script setup>
import GroupListItem from "./GroupListItem.vue";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
</script>

<template>
  <div class="px-3 bg-gray-800 py-3 rounded-2xl">
    <div class="block lg:hidden">
      <Disclosure v-slot="{ open }">
        <DisclosureButton class="w-full">
          <div class="flex justify-center items-center gap-2">
            <h2
              class="font-semibold text-xl text-gray-800 dark:text-blue-400 leading-tight"
            >
              My Groups
            </h2>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="currentColor"
              class="size-6 text-blue-400"
              :class="
                open
                  ? 'rotate-90 transform transition duration-700 ease-out data-[closed]:scale-95 data-[closed]:opacity-0'
                  : 'transition duration-700 ease-out data-[closed]:scale-95 data-[closed]:opacity-0'
              "
            >
              <path
                fill-rule="evenodd"
                d="M13.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L11.69 12 4.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z"
                clip-rule="evenodd"
              />
              <path
                fill-rule="evenodd"
                d="M19.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 1 1-1.06-1.06L17.69 12l-6.97-6.97a.75.75 0 0 1 1.06-1.06l7.5 7.5Z"
                clip-rule="evenodd"
              />
            </svg>
          </div>
        </DisclosureButton>

        <DisclosurePanel class="px-4 pb-2 pt-4 text-md">
          <GroupListItem />
        </DisclosurePanel>
      </Disclosure>
    </div>
    <div class="hidden lg:block">
      <h2
        class="font-semibold text-xl text-gray-800 dark:text-blue-400 leading-tight text-center mb-4"
      >
        My Groups
      </h2>
      <GroupListItem />
    </div>
  </div>
</template>

<style scoped></style>
