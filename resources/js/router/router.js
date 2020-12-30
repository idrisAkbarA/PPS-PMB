import Vue from "vue";
import VueRouter from "vue-router";
import store from '../store/store'

import HomeClnMhs from "../views/ClnMhs/Home.vue";
import ClnMhsLayout from "../views/ClnMhs/ClnMhsLayout.vue";
import Pendaftaran from "../views/ClnMhs/Pendaftaran.vue";

import PetugasLayout from "../views/petugas/PetugasLayout.vue";
import DashboardPetugas from "../views/petugas/Dashboard.vue";
import KelolaSoal from "../views/petugas/Admin/KelolaSoal.vue";
import KelolaPeriode from "../views/petugas/Admin/KelolaPeriode.vue";
import KelolaJurusan from "../views/petugas/Admin/KelolaJurusan.vue";

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
            {
                path: "daftar",
                name: "Pendaftaran",
                component: Pendaftaran
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
            {
                path: "kelola-jurusan",
                name: "Kelola Jurusan",
                component: KelolaJurusan
            },
        ]
    },

];

const router = new VueRouter({
    mode: "history",
    base: "/user/",
    routes
});
router.beforeEach((to, from, next) => {
    if (from.name == null) {
        console.log('from null')
    }
    next();
})

export default router;
