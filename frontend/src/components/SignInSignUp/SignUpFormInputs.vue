<template>
  <div>
    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
    <div class="mt-2">
      <input
          v-model="signInSignUpFormData.email"
          ref="emailInputFocus"
          id="email" name="email" type="email" autocomplete="email" required
          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-1.5"/>
    </div>
  </div>

  <div>
    <div class="flex items-center justify-between">
      <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
    </div>
    <div class="mt-2">
      <input
          v-model="signInSignUpFormData.password"
          id="password" name="password" type="password" required="" minlength="3"
          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-1.5"/>
    </div>
  </div>

  <div>
    <div class="flex items-center justify-between">
      <label for="password-confirmation" class="block text-sm font-medium leading-6 text-gray-900">Confirm Your
        Password</label>
    </div>
    <div class="mt-2">
      <input
          v-model="signInSignUpFormData.passwordConfirmation"
          id="password-confirmation" name="password-confirmation" type="password" required="" minlength="3"
          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-1.5"/>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useSignInSignUpFormDataStore } from "@/stores/SignInSignUpFormDataStore";
import { onMounted, onUnmounted, ref, Ref } from "vue";

const { signInSignUpFormData, clearData } = useSignInSignUpFormDataStore();
const emailInputFocus: Ref<HTMLElement | null> = ref(null);

onUnmounted(() => {
  clearData();
});

onMounted(() => {
  emailInputFocus.value?.focus();
});
</script>