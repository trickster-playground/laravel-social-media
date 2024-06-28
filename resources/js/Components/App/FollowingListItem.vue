<script setup>
import FollowingItem from "./FollowingItem.vue";
import TextInput from "@/Components/TextInput.vue";

import { ref } from "vue";

const searchKeyword = ref("");
</script>

<template>
  <TextInput
    :model-value="searchKeyword"
    placeholder="Search Friends"
    class="w-full my-2"
  />
  <div class="py-8">
    <div v-if="false" class="flex justify-center">
      <h1 class="text-7xl font-black align-middle">
        You don't have any friends, sad :(
      </h1>
    </div>
    <div
      v-else
      class="h-[200px] lg:h-full overflow-auto max-h-[calc(100vh-7rem)]"
    >
      <FollowingItem
        image="https://picsum.photos/100"
        title="Mark Zuckerberg"
      />
      <FollowingItem
        image="https://picsum.photos/100"
        title="Elon Reeve Musk"
      />
      <FollowingItem image="https://picsum.photos/100" title="Bill Gates" />
    </div>
  </div>
</template>

<style scoped></style>
