import { defineStore } from 'pinia'
import { type Ref, ref } from "vue";

interface INotification {
    message : string;
    type : string;
}

export const useNotificationStore = defineStore('notificationStore', () => {
    const notification : Ref<INotification> = ref({});

    let timeoutId : number | null = null;

    const showNotification = (message : string, type : string) : void => {

        clearNotificationTimeOut();

        notification.value.message = message;
        notification.value.type = type;

        timeoutId = setTimeout(() : void => {
            clearNotificationData();
        }, 3000);
    }

    const clearNotificationTimeOut = (): void => {
        if(timeoutId) {
            clearTimeout(timeoutId);
            clearNotificationData();
        }
    }

    const clearNotificationData = () : void => {
        if(timeoutId){
            clearTimeout(timeoutId);
        }
        delete notification.value.message
        delete notification.value.type
    }

    return { notification, showNotification, clearNotificationData }
})