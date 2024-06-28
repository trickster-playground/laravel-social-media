<template>
  <div class="flex items-center gap-2 my-2">
    <a href="javascript:void(0)">
      <img
        :src="post.user.avatar_url"
        class="w-[60px] rounded-full border-2 transition-all hover:border-blue-500"
      />
    </a>
    <div>
      <h4 class="text-white font-bold">
        <a href="javascript:void(0)" class="hover:text-blue-500">
          {{ post.user.name }}
        </a>
        <template v-if="post.group">
          >
          <a href="javascript:void(0)" class="hover:text-blue-500">
            {{ post.group.name }}
          </a>
        </template>
      </h4>
      <small class="text-gray-400 text-sm">{{ post.updated_at }}</small>
    </div>
  </div>
</template>
<script setup>
defineProps({
  post: {
    type: Object,
    required: true,
  },
});
</script>
<style scoped></style>
