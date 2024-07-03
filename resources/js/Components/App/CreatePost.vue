<script setup>
import { ref } from "vue";
import PostModal from "@/Components/App/PostModal.vue";
import { usePage } from "@inertiajs/vue3";

const authUser = usePage().props.auth.user;

// Define reactive variables
const showModal = ref(false);
const fileInput = ref(null);
const newPost = ref({
 id: null,
 body: "",
 user: authUser,
});

const showCreatePostModal = () => {
 showModal.value = true;
};
</script>

<template>
 <div class="px-3 bg-gray-800 py-2 rounded-2xl mb-2">
  <h2
   class="text-2xl font-black justify-center text-center text-blue-500"
  >
   Timeline
  </h2>
  <div class="py-8">
   <div
    @click="showCreatePostModal"
    class="textarea textarea-info w-full py-2 px-2 text-white border-2 bg-gray-700 border-blue-400 rounded-lg"
   >
    Click here to create new post
   </div>
   <PostModal :post="newPost" v-model="showModal" />
  </div>
 </div>
</template>

<style scoped>
.cursor-pointer {
 cursor: pointer;
}
</style>
