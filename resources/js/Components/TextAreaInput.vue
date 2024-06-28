<script setup>
import { onMounted, ref } from "vue";

const model = defineModel({
  type: String,
  required: true,
});

const props = defineProps({
  autoResize: {
    type: Boolean,
    default: true,
  },
});

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute("autofocus")) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value.focus() });

const emit = defineEmits(["update:modelValue"]);

function onInputChange($event) {
  emit("update:modelValue", $event.target.value);

  if (props.autoResize) {
    input.value.style.height = "auto";
    input.value.style.height = input.value.scrollHeight + "px";
  }
}

function adjustHeight() {
  input.value.style.height = "auto";
  input.value.style.height = input.value.scrollHeight + "px";
}

onMounted(() => {
  adjustHeight();
});
</script>

<template>
  <textarea
    class="textarea textarea-info w-full py-2 px-2 text-white border-2 bg-gray-700 border-blue-400 rounded-lg"
    :value="modelValue"
    ref="input"
    @input="onInputChange"
    rows="1"
  />
</template>
