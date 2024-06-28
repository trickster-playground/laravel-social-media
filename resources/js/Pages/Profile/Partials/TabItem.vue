<script setup>
defineProps({
  selected: Boolean,
  text: String,
});
</script>

<template>
  <button
    :class="[
      'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
      '',
      selected
        ? 'border-b-2 border-blue-400 text-white  text-xl font-extrabold outline-none '
        : 'hover:bg-blue-600 text-white text-md',
    ]"
  >
    {{ text }}
  </button>
</template>

<style></style>
