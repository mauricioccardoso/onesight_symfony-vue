import { defineStore } from 'pinia'
import { ref } from "vue";

export const useUserDataStore = defineStore('userDataStore', () => {
    const user = ref({
        email: '',
        username: ''
    })

    const setUserData = (data): void => {
        user.value.email = data.email;

        const [userName] = data.email.split('@');
        user.value.username = userName;
    }

    return { user, setUserData }
})