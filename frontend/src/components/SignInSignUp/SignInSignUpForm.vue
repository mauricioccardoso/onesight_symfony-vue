<template>
  <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
        {{ isSignIn ? 'Sign In To Your Account' : 'Create An Account' }}
      </h2>
    </div>
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">

      <!--Form-->
      <BasicForm
          :cbFunction="signInSignUpFormDataStore.submitData"
          :submitPath=" isSignIn ? '/login' : '/registration'"
          :buttonLabel="isSignIn ? 'Sign in' : 'Create Account'"
          :isLoading="signInSignUpFormDataStore.onMakeRequest"
      >

        <slot></slot>

      </BasicForm>
      <!--Form-->

      <p class="mt-10 text-center text-sm text-gray-500">
        {{ isSignIn ? 'Not registered?' : 'Already Registered?' }}

        <RouterLink :to="isSignIn ? 'sign-up' : 'sign-in'"
                    class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500 ml-2">
          {{ isSignIn ? 'Create Account' : 'Sign In' }}
        </RouterLink>
      </p>

    </div>
  </div>
</template>

<script setup lang="ts">
import BasicForm from "@/components/BasicForm.vue";

import { useRoute } from "vue-router";
import { computed } from "vue";

import { useSignInSignUpFormDataStore } from "@/stores/SignInSignUpFormDataStore";

const signInSignUpFormDataStore = useSignInSignUpFormDataStore();
const route = useRoute();

const isSignIn = computed(() => route.path === '/sign-in');
</script>