<template>
  <div class="app-content">
    <RouterView/>

    <NotificationCard v-show="notificationStore.notification?.message"/>
  </div>
</template>

<script setup lang="ts">
import { RouterView } from 'vue-router'
import NotificationCard from "@/components/NotificationCard.vue";

import { useNotificationStore } from "@/stores/NotificationStore";

const notificationStore = useNotificationStore();
</script>
