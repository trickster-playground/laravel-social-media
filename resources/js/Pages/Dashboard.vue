<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import GroupList from "@/Components/App/GroupList.vue";
import FollowingList from "@/Components/App/FollowingList.vue";
import CreatePost from "@/Components/App/CreatePost.vue";
import PostList from "@/Components/App/PostList.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
  posts: Object,
  user: Object,
});
</script>

<template>
  <Head title="Homepage" />

  <AuthenticatedLayout>
    <div class="grid lg:grid-cols-12 gap-3 mt-4">
      <div class="lg:col-span-3 lg:order-1 h-full">
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <GroupList />
        </div>
      </div>
      <div class="lg:col-span-3 lg:order-3 h-full">
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <FollowingList />
        </div>
      </div>
      <div class="lg:col-span-6 lg:order-2 h-full">
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <CreatePost />
        </div>
        <div class="bg-gray-800 mt-2 overflow-hidden shadow-sm sm:rounded-lg">
          <PostList :posts="posts.data" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style></style>
