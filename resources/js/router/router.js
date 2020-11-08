import Vue from "vue";
import VueRouter from "vue-router";
import store from '../store/store'
import Home from "../views/Home.vue";

Vue.use(VueRouter);
const routes = [
    {
        path: "/",
        name: "Landing Page",
        component: Home
    },
];

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    routes
});

export default router;