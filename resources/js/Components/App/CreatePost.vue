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
   class="font-semibold text-center text-xl text-gray-800 dark:text-blue-400 leading-tight px-5"
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

<!-- <div>
        <div class="flex gap-2 justify-between mt-2">
          <button
            type="button"
            class="btn btn-secondary rounded-xl hover:bg-gray-200"
            @click="handleClick"
          >
            Attach File
          </button>

          Hidden File Input
          <input
            type="file"
            id="dropzone-file"
            class="hidden"
            ref="fileInput"
          />

          <button
            @click="submit"
            type="submit"
            class="btn btn-primary rounded-xl hover:btn-info w-24"
          >
            Post
          </button>
        </div>
      </div> -->
