import Vue from "vue";
import VueRouter from "vue-router";
import store from '../store/store'

import HomeClnMhs from "../views/ClnMhs/Home.vue";
import ClnMhsLayout from "../views/ClnMhs/ClnMhsLayout.vue";

import PetugasLayout from "../views/petugas/PetugasLayout.vue";
import DashboardPetugas from "../views/petugas/Dashboard.vue";
import KelolaSoal from "../views/petugas/Admin/KelolaSoal.vue";
import KelolaPeriode from "../views/petugas/Admin/KelolaPeriode.vue";
import KelolaJurusan from "../views/petugas/Admin/KelolaJurusan.vue";
import Pendaftar from "../views/petugas/Admin/Pendaftar.vue";
import KelolaPendaftaran from "../views/petugas/Admin/KelolaPendaftaran.vue";

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
            {
                path: "kelola-jurusan",
                name: "Kelola Jurusan",
                component: KelolaJurusan
            },
            {
                path: "pendaftar",
                name: "Pendaftar",
                component: Pendaftar
            },
            {
                path: "kelola-pendaftaran",
                name: "Kelola Pendaftaran",
                component: KelolaPendaftaran
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
