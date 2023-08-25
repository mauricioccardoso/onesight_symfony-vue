<template>
  <form class="space-y-6" @submit.prevent="submitForm">

    <slot></slot>

    <div>
      <button type="submit"
              class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        {{ buttonLabel }}
      </button>
    </div>
  </form>
</template>

<script setup lang="ts">
const props = defineProps<{
  buttonLabel: string;
  submitPath: string
}>();

const submitForm = () => {
  console.log(props.submitPath);
}
</script>