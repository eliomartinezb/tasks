import { createWebHistory, createRouter } from "vue-router";

const routes = [
    {
        path: "/",
        name: "home",
        component: () => import("@/views/home.vue"),
        meta: {
            title: "Home",
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
