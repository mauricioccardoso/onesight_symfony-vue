<template>
  <form class="space-y-6" @submit.prevent="submitForm">

    <slot></slot>

    <div>
      <button
          :disabled="isLoading"
          :class="{ 'cursor-progress': isLoading}"
          type="submit"
          class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
      >

        <span v-if="!isLoading">{{ buttonLabel }}</span>
        <span v-else class="animate-spin">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
            <path fill-rule="evenodd"
                  d="M4.755 10.059a7.5 7.5 0 0112.548-3.364l1.903 1.903h-3.183a.75.75 0 100 1.5h4.992a.75.75 0 00.75-.75V4.356a.75.75 0 00-1.5 0v3.18l-1.9-1.9A9 9 0 003.306 9.67a.75.75 0 101.45.388zm15.408 3.352a.75.75 0 00-.919.53 7.5 7.5 0 01-12.548 3.364l-1.902-1.903h3.183a.75.75 0 000-1.5H2.984a.75.75 0 00-.75.75v4.992a.75.75 0 001.5 0v-3.18l1.9 1.9a9 9 0 0015.059-4.035.75.75 0 00-.53-.918z"
                  clip-rule="evenodd"/>
          </svg>
        </span>

      </button>
    </div>

  </form>
</template>

<script setup lang="ts">
const props = defineProps<{
  buttonLabel: string;
  cbFunction: (data) => void;
  submitPath: string;
  isLoading?: boolean;
}>();

const submitForm = () => {
  props.cbFunction(props.submitPath);
}
</script>