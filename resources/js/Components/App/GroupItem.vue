<script setup>
defineProps({
  image: String,
  title: String,
  description: String,
});
</script>

<template>
  <div class="mb-3 group hover:bg-blue-700 rounded-lg cursor-pointer hover:border-b-2 hover:border-t-2 hover:border-blue-200">
    <div class="flex items-start gap-3 py-2 px-2">
      <img :src="image" class="w-[48px] rounded-full group-hover:border-2 group-hover:border-blue-500" />
      <div>
        <h3 class="font-black text-lg text-white">{{ title }}</h3>
        <div class="text-xs text-gray-300 group-hover:text-white">
          {{ description }}
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
