import { ref, type Ref } from 'vue'
import { defineStore } from 'pinia'

import type { signInSignUpDataInterface } from "@/DTO/SignInSignUpDTO";
import type { AxiosError, AxiosResponse } from "axios";

import httpClient from "@/http";

import { useAuthDataStore } from "@/stores/AuthDataStore";
import { type Router, useRouter } from "vue-router";
import { useNotificationStore } from "@/stores/NotificationStore";

export const useSignInSignUpFormDataStore = defineStore('signInSignUpFormDataStore', () => {
    const router : Router = useRouter();
    const authDataStore = useAuthDataStore();
    const notificationStore = useNotificationStore();

    const signInSignUpFormData : Ref<signInSignUpDataInterface> = ref({});
    const onMakeRequest : Ref<boolean> = ref(false);

    const clearData = () : void => {
        signInSignUpFormData.value.email = "";
        clearPassword();
    }

    const clearPassword = () : void => {
        signInSignUpFormData.value.password = "";
        delete signInSignUpFormData.value.passwordConfirmation;
    }

    const submitData = async (path) : Promise<void> => {
        if(path === '/registration' && signInSignUpFormData.value.password !== signInSignUpFormData.value.passwordConfirmation) {
            notificationStore.showNotification('Passwords do not match!', 'error');
            clearPassword();
            return;
        }

        const resp = await makeRequest(path, signInSignUpFormData.value);

        if(resp?.code) {
            notificationStore.showNotification(resp.message, 'error');
            clearPassword();
            return;
        }

        if(path === '/login') {
            authDataStore.setToken(resp.token);
            await router.push({ name: 'home' });
            return;
        }

        if(path === '/registration') {
            notificationStore.showNotification(resp.message, 'success');
            await router.push({ name: 'sign-in' });
            return;
        }
    }

    const makeRequest = async (path, data) => {
        onMakeRequest.value = true;

        httpClient.interceptors.request.clear();

        return await httpClient.post(`${ path }`, data)
            .then(({ data } : AxiosResponse) => {
                return data;
            })
            .catch((error : AxiosError) => {
                if(error.response?.data) {
                    return error.response.data;
                }
                return error
            })
            .finally(() : void => {
                    onMakeRequest.value = false;
                }
            );
    }

    return { signInSignUpFormData, onMakeRequest, submitData, clearData }
})