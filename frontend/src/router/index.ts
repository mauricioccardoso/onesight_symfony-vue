import { createRouter, createWebHistory, type RouteLocationNormalized, type Router } from 'vue-router'
import { useAuthDataStore } from "@/stores/AuthDataStore";

import MainView from "@/views/MainView.vue";
import AuthView from "@/views/AuthView.vue";
import SignInFormInputs from "@/components/SignInSignUp/SignInFormInputs.vue";
import SignUpFormInputs from "@/components/SignInSignUp/SignUpFormInputs.vue";

const router : Router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            redirect: '/home',
            component: MainView,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '/home',
                    name: 'home',
                    component: MainView,
                }
            ]
        },
        {
            path: '/auth',
            name: 'auth',
            redirect: '/sign-in',
            component: AuthView,
            meta: { isGuest: true },
            children: [
                {
                    path: '/sign-in',
                    name: 'sign-in',
                    component: SignInFormInputs,
                },
                {
                    path: '/sign-up',
                    name: 'sign-up',
                    component: SignUpFormInputs,
                }
            ]
        },
    ]
});

router.beforeEach((to : RouteLocationNormalized, from, next) : void => {
    const authDataStore = useAuthDataStore();

    if(to.meta.requiresAuth && !authDataStore.token) {
        next({ name: "auth" });
    } else if(to.meta.isGuest && authDataStore.token) {
        next({ name: "home" });
    } else {
        next();
    }
});

export default router
