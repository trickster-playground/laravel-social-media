<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import GroupList from "@/Components/App/GroupList.vue";
import FollowingList from "@/Components/App/FollowingList.vue";
import CreatePost from "@/Components/App/CreatePost.vue";
import PostList from "@/Components/App/PostList.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
  posts: Object,
});
</script>

<template>
  <Head title="Homepage" />

  <AuthenticatedLayout>
    <div class="py-2 h-full">
      <div class="grid lg:grid-cols-12 gap-3">
        <div class="lg:col-span-3 px-3 lg:order-1 h-full">
          <GroupList />
        </div>
        <div class="lg:col-span-3 px-3 lg:order-3 h-full">
          <FollowingList />
        </div>
        <div class="lg:col-span-6 px-2 my-2 lg:order-2 h-full">
          <CreatePost />
          <PostList :posts="posts.data" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style></style>
