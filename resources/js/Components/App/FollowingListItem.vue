<script setup>
import FollowingItem from "./FollowingItem.vue";
import TextInput from "@/Components/TextInput.vue";

import { ref } from "vue";

const searchKeyword = ref("");
</script>

<template>
  <TextInput
    :model-value="searchKeyword"
    placeholder="Search Groups"
    class="w-full my-2"
  />
  <div class="py-8">
    <div v-if="false" class="text-gray-200 flex justify-center align-middle">
      You don't have any friends yet.
    </div>
    <div
      v-else
      class="h-[200px] lg:h-full overflow-auto max-h-[calc(100vh-7rem)]"
    >
      <FollowingItem
        image="https://picsum.photos/100"
        title="Laravel Developers"
      />
      <FollowingItem
        image="https://picsum.photos/100"
        title="Laravel Developers"
      />
      <FollowingItem
        image="https://picsum.photos/100"
        title="Laravel Developers"
      />
    </div>
  </div>
</template>

<style scoped></style>
