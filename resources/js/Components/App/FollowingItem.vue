<script setup>
defineProps({
  image: String,
  title: String,
  description: String,
});
</script>

<template>
  <div class="mb-3 hover:bg-blue-600 rounded-lg cursor-pointer">
    <div class="flex items-center gap-3 py-2 px-3">
      <img :src="image" class="w-[48px] rounded-full" />
      <div>
        <h3 class="font-black text-lg text-white">{{ title }}</h3>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
