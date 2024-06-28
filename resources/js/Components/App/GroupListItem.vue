<script setup>
import GroupItem from "@/Components/App/GroupItem.vue";
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
      You are not joined to any groups
    </div>
    <div
      v-else
      class="h-[200px] lg:h-full overflow-auto max-h-[calc(100vh-7rem)]"
    >
      <GroupItem
        image="https://picsum.photos/100"
        title="Laravel Developers"
        description="Lorem ipsum dolor sit amet consectetur adipisicing elit."
      />
    </div>
  </div>
</template>

<style></style>
