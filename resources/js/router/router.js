import Vue from "vue";
import VueRouter from "vue-router";
import store from '../store/store'
import Home from "../views/Home.vue";

Vue.use(VueRouter);
const routes = [
    {
        path: "/test",
        name: "Landing Page",
        component: Home
    },
];

const router = new VueRouter({
    mode: "history",
    base: "/user/",
    routes
});

export default router;