import Vue from "vue";
import VueRouter from "vue-router";
import store from '../store/store'

import HomeClnMhs from "../views/ClnMhs/Home.vue";
import ClnMhsLayout from "../views/ClnMhs/ClnMhsLayout.vue";

import PetugasLayout from "../views/petugas/PetugasLayout.vue";
import DashboardPetugas from "../views/petugas/Dashboard.vue";
import KelolaSoal from "../views/petugas/Admin/KelolaSoal.vue";
import KelolaPeriode from "../views/petugas/Admin/KelolaPeriode.vue";

Vue.use(VueRouter);
const routes = [
    {
        path: "/cln-mhs",
        component: ClnMhsLayout,
        children: [
            {
                path: "home",
                name: "Home Calon Mahasiswa",
                component: HomeClnMhs
            },
        ]
    },

    {
        path: "/admin/:petugas",
        component: PetugasLayout,
        children: [
            {
                path: "dashboard",
                name: "Dashboard Petugas",
                component: DashboardPetugas
            },
            {
                path: "kelola-soal",
                name: "Kelola Soal",
                component: KelolaSoal
            },
            {
                path: "kelola-periode",
                name: "Kelola Periode",
                component: KelolaPeriode
            },
        ]
    },

];

const router = new VueRouter({
    mode: "history",
    base: "/user/",
    routes
});

export default router;
