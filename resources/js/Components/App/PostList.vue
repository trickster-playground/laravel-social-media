<script setup>
import PostItem from "@/Components/App/PostItem.vue";
import { ref } from "vue";
import PostModal from "@/Components/App/PostModal.vue";

defineProps({
  posts: Array,
});

const showEditModal = ref(false);

const editPost = ref({});

function openEditModal(post) {
  editPost.value = post;
  showEditModal.value = true;
}

const post1 = {
  user: {
    id: 1,
    avatar: `https://randomuser.me/api/portraits/men/76.jpg`,
    name: "John Smith",
  },
  group: null,
  body: `
  <p>  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt harum incidunt quam, esse cumque dolor vel tempora est eligendi magni velit aperiam, obcaecati non nostrum deserunt sapiente officia expedita aspernatur!
  </p> <p>  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt harum incidunt quam, esse cumque dolor vel tempora est eligendi magni velit aperiam, obcaecati non nostrum deserunt sapiente officia expedita aspernatur!
  </p><p>  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt harum incidunt quam, esse cumque dolor vel tempora est eligendi magni velit aperiam, obcaecati non nostrum deserunt sapiente officia expedita aspernatur!
  </p>
  `,
  created_at: `2023-11-19 15:12`,
};

const post2 = {
  user: {
    id: 1,
    avatar: `https://randomuser.me/api/portraits/men/77.jpg`,
    name: "Jaden Smith",
  },
  group: {
    id: 1,
    name: `Laravel Developers`,
  },
  attachments: [
    {
      id: 1,
      name: `test.png`,
      mime: `image/png`,
      url: `https://picsum.photos/1000`,
    },
    {
      id: 2,
      name: `test.png`,
      mime: `image/png`,
      url: `https://picsum.photos/1000`,
    },
    {
      id: 3,
      name: `MyDocument.docx`,
      mime: `application/mssword`,
      url: ``,
    },
  ],
  body: `
  <p>  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt harum incidunt quam, esse cumque dolor vel tempora est eligendi magni velit aperiam, obcaecati non nostrum deserunt sapiente officia expedita aspernatur!
  </p>
  <p>  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt harum incidunt quam, esse cumque dolor vel tempora est eligendi magni velit aperiam, obcaecati non nostrum deserunt sapiente officia expedita aspernatur!
  </p>
  <p>  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt harum incidunt quam, esse cumque dolor vel tempora est eligendi magni velit aperiam, obcaecati non nostrum deserunt sapiente officia expedita aspernatur!
  </p>
  `,
  created_at: `2023-11-19 15:12`,
};
</script>

<template>
  <div
    class="px-3 bg-gray-700 py-3 rounded-lg h-fit overflow-auto max-h-[calc(100vh-7rem)]"
  >
    <PostItem
      v-for="post of posts"
      :key="post.id"
      :post="post"
      @editClick="openEditModal"
    />
    <PostModal :post="editPost" v-model="showEditModal" />
  </div>
</template>

<style></style>
