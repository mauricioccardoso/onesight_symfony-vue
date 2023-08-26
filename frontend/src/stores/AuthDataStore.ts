import { defineStore } from 'pinia'
import { type Ref, ref } from "vue";
import { type Router, useRouter } from "vue-router";

export const useAuthDataStore = defineStore('authDataStore', () => {
    const router : Router = useRouter();
    const token : Ref<string | null> = ref('');

    token.value = sessionStorage.getItem("BEARER_TOKEN");

    const setToken = (apiToken): void => {
        sessionStorage.setItem("BEARER_TOKEN", apiToken);
        token.value = apiToken;
    }

    const removeAuth = async () :Promise<void> => {
        sessionStorage.clear();
        await router.push({ name: 'sign-in' })
    }

    return { token, setToken, removeAuth }
})