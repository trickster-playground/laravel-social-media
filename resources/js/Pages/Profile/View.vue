<template>
  <AuthenticatedLayout>
    <div class="container mx-auto max-w-6xl">
      <div
        v-show="showNotification && message"
        class="alert alert-info mt-2 font-bold text-lg text-white dark:text-white"
      >
        <CheckBadgeIcon class="size-6" />
        {{ message }}
      </div>
      <div
        v-show="errors.cover"
        class="alert alert-error mt-2 font-bold text-lg text-white dark:text-white"
      >
        <ExclamationTriangleIcon class="size-6" />
        {{ errors.cover }}
      </div>
      <div
        class="group/cover relative mb-2 mt-2 bg-blue-900/20 rounded-md border border-blue-500"
      >
        <img
          :src="coverImageSrc || user.cover_url || '/img/backgroundDefault.jpg'"
          alt=""
          class="w-full h-[350px] object-cover rounded-xl"
        />
        <div class="absolute top-2 right-2">
          <button
            v-if="isMyProfile && !coverImageSrc"
            class="rounded-md bg-gray-400 hover:bg-blue-600 text-white py-1 px-2 text-xs flex items-center opacity-0 group-hover/cover:opacity-100 cursor-pointer"
          >
            <PhotoIcon class="size-6 mr-2" />
            <input
              type="file"
              class="absolute left-0 top-0 bottom-0 right-0 opacity-0 w-42 rounded-lg h-8 cursor-pointer"
              @change="onCoverChange"
            />
            Change Cover Image
          </button>
          <div v-else-if="isMyProfile && coverImageSrc" class="flex gap-2">
            <button
              class="rounded-md bg-gray-400 hover:bg-red-600 text-white py-1 px-2 text-xs flex items-center opacity-0 group-hover/cover:opacity-100 cursor-pointer"
              @click="onCoverCancel"
            >
              <XCircleIcon class="size-6 mr-2" />
              Cancel
            </button>
            <button
              class="rounded-md bg-gray-400 hover:bg-blue-600 text-white py-1 px-2 text-xs flex items-center opacity-0 group-hover/cover:opacity-100 cursor-pointer"
              @click="onCoverSubmit"
            >
              <CheckIcon class="size-6 mr-2" />
              Save
            </button>
          </div>
        </div>

        <div class="flex">
          <div
            class="flex justify-center items-center relative group/avatar ml-[40px] -mt-[85px] w-[200px] h-[200px]"
          >
            <div class="avatar w-full h-full">
              <img
                class="relative w-full h-full border-2 rounded-full border-blue-600"
                :src="
                  avatarImageSrc || user.avatar_url || `/img/avatarDefault.jpg`
                "
              />
            </div>

            <button
              v-if="isMyProfile && !avatarImageSrc"
              class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 rounded-full bg-gray-400 hover:bg-blue-6000 text-white py-1 px-2 text-xs flex items-center opacity-0 group-hover/avatar:opacity-100 hover:cursor-pointer"
            >
              <CameraIcon class="size-4" />
              <input
                type="file"
                class="absolute left-0 top-0 bottom-0 right-0 opacity-0 w-42 rounded-lg h-5 cursor-target"
                @change="onAvatarChange"
              />
            </button>
            <div
              v-else-if="isMyProfile && avatarImageSrc"
              class="flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 rounded-full bg-gray-400 mt-16 px-2 py-2"
            >
              <button
                class="rounded-md bg-gray-400 hover:bg-red-600 text-white py-1 px-2 text-xs flex items-center cursor-pointer"
                @click="onAvatarCancel"
              >
                <XCircleIcon class="size-6" />
              </button>
              <button
                class="rounded-md bg-gray-400 hover:bg-blue-600 text-white py-1 px-2 text-xs flex items-center cursor-pointer"
                @click="onAvatarSubmit"
              >
                <CheckIcon class="size-6" />
              </button>
            </div>
          </div>
          <div class="flex justify-between items-center flex-1 p-3">
            <h2
              class="text-2xl bg-blue-900/20 font-bold text-blue-200 px-2 py-2 rounded-md shadow-md shadow-blue-500"
            >
              {{ user.name }}
              <p class="text-xs">{{ user.email }}</p>
            </h2>
          </div>
        </div>
      </div>
      <div class="border-t border-blue-500 rounded-lg">
        <TabGroup>
          <TabList
            class="flex space-x-1 rounded-xl bg-blue-900/20 p-1 gap-6 shadow-lg shadow-blue-400"
          >
            <Tab v-if="isMyProfile" v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="About" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="Posts" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="Followers" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="Followings" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem :selected="selected" text="Photos" />
            </Tab>
          </TabList>

          <TabPanels class="mt-2">
            <TabPanel
              :key="about"
              :class="['rounded-xl bg-base p-3 bg-blue-900/20 text-white']"
              v-if="isMyProfile"
            >
              <Edit :mustVerifyEmail="mustVerifyEmail" :status="status" />
            </TabPanel>
            <TabPanel
              :key="posts"
              :class="[
                'rounded-xl bg-base p-3 bg-blue-900/20 hover:bg-blue-600 text-white',
              ]"
            >
              Posts Content
            </TabPanel>
            <TabPanel
              :key="posts"
              :class="[
                'rounded-xl bg-base p-3 bg-blue-900/20 hover:bg-blue-600 text-white',
              ]"
            >
              Posts Content
            </TabPanel>
            <TabPanel
              :key="posts"
              :class="[
                'rounded-xl bg-base p-3 bg-blue-900/20 hover:bg-blue-600 text-white',
              ]"
            >
              Posts Content
            </TabPanel>
            <TabPanel
              :key="followers"
              :class="[
                'rounded-xl bg-base p-3 bg-blue-900/20 hover:bg-blue-600 text-white',
              ]"
            >
              Followers
            </TabPanel>
          </TabPanels>
        </TabGroup>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { usePage, useForm } from "@inertiajs/vue3";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TabItem from "./Partials/TabItem.vue";
import Edit from "./Edit.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { computed, ref } from "vue";
import {
  PhotoIcon,
  PencilSquareIcon,
  XCircleIcon,
  CheckIcon,
  CheckBadgeIcon,
  ExclamationTriangleIcon,
  CameraIcon,
} from "@heroicons/vue/24/solid";

const imagesForm = useForm({
  cover: null,
  avatar: null,
});

let showNotification = ref(true);
const coverImageSrc = ref("");
const avatarImageSrc = ref("");
const authUser = usePage().props.auth.user;

const isMyProfile = computed(() => {
  return authUser && authUser.id === props.user.id;
});

const props = defineProps({
  errors: {
    type: Object,
  },
  mustVerifyEmail: {
    type: Boolean,
  },
  message: {
    type: String,
  },
  user: {
    type: Object,
  },
});

function onCoverChange(event) {
  imagesForm.cover = event.target.files[0];
  if (imagesForm.cover) {
    const reader = new FileReader();
    reader.onload = () => {
      coverImageSrc.value = reader.result;
    };
    reader.readAsDataURL(imagesForm.cover);
  }
}
function onAvatarChange(event) {
  imagesForm.avatar = event.target.files[0];
  if (imagesForm.avatar) {
    const reader = new FileReader();
    reader.onload = () => {
      avatarImageSrc.value = reader.result;
    };
    reader.readAsDataURL(imagesForm.avatar);
  }
}

function onCoverSubmit() {
  imagesForm.post(route("profile.update-images"), {
    onSuccess: () => {
      onCoverCancel();
      setTimeout(() => {
        showNotification.value = false;
        console.log("tertutup");
      }, 3000);
      showNotification.value = true;
    },
    onError: () => {
      console.log("error");
      onCoverCancel();
    },
  });
}

function onAvatarSubmit() {
  imagesForm.post(route("profile.update-images"), {
    onSuccess: () => {
      onAvatarCancel();
      setTimeout(() => {
        showNotification.value = false;
        console.log("tertutup");
      }, 3000);
      showNotification.value = true;
    },
    onError: () => {
      console.log("error");
      onAvatarCancel();
    },
  });
}

function onCoverCancel() {
  imagesForm.cover = null;
  coverImageSrc.value = null;
}

function onAvatarCancel() {
  imagesForm.avatar = null;
  avatarImageSrc.value = null;
}
</script>

<style scoped></style>
