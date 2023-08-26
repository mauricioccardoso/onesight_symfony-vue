import { ref, type Ref } from 'vue'
import { defineStore } from 'pinia'

import type { signInSignUpDataInterface } from "@/DTO/SignInSignUpDTO";
import type { AxiosError, AxiosResponse } from "axios";

import httpClient from "@/http";

import { useAuthDataStore } from "@/stores/AuthDataStore";
import { type Router, useRouter } from "vue-router";

export const useSignInSignUpFormDataStore = defineStore('signInSignUpFormDataStore', () => {
    const router : Router = useRouter();
    const authDataStore = useAuthDataStore();

    const signInSignUpFormData : Ref<signInSignUpDataInterface> = ref({});

    const clearData = () : void => {
        signInSignUpFormData.value.email = "";
        signInSignUpFormData.value.password = "";
        delete signInSignUpFormData.value.passwordConfirmation;
    }

    const submitData = async (path) : Promise<void> => {
        if (path === '/registration' && signInSignUpFormData.value.password !== signInSignUpFormData.value.passwordConfirmation) {
            console.log('Show Password Error Notification')
            return;
        }

        const resp = await makeRequest(path, signInSignUpFormData.value);

        if (resp?.code) {
            console.log('Show Error Notification', resp)
            return;
        }

        if (path === '/login') {
            authDataStore.setToken(resp.token);
            await router.push({ name: 'home' })
            return;
        }

        if (path === '/registration') {
            console.log('Show Registration Success Notification', resp)
            await router.push({ name: 'sign-in' })
            return;
        }
    }

    const makeRequest = async (path, data) => {
        return await httpClient.post(`${ path }`, data)
            .then(({ data } : AxiosResponse) => {
                return data;
            })
            .catch((error : AxiosError) => {
                if (error.response?.data) {
                    return error.response.data
                }
                return error
            })
    }

    return { signInSignUpFormData, submitData, clearData }
})