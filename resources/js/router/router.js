import Vue from "vue";
import VueRouter from "vue-router";
import store from '../store/store'

import HomeClnMhs from "../views/ClnMhs/Home.vue";
import ClnMhsLayout from "../views/ClnMhs/ClnMhsLayout.vue";
import PendaftaranBaru from "../views/ClnMhs/PendaftaranBaru.vue";
import PanduanPendaftaran from "../views/ClnMhs/PanduanPendaftaran.vue";
import Pendaftaran from "../views/ClnMhs/Pendaftaran.vue";
import Soal from "../views/ClnMhs/Soal.vue";

import PetugasLayout from "../views/petugas/PetugasLayout.vue";
import DashboardPetugas from "../views/petugas/Dashboard.vue";
import KelolaSoal from "../views/petugas/Admin/KelolaSoal.vue";
import KelolaPeriode from "../views/petugas/Admin/KelolaPeriode.vue";
import KelolaJurusan from "../views/petugas/Admin/KelolaJurusan.vue";
import Pendaftar from "../views/petugas/Admin/Pendaftar.vue";
import KelolaPendaftaran from "../views/petugas/Admin/KelolaPendaftaran.vue";
import KelolaTemuRamah from "../views/petugas/Admin/KelolaTemuRamah.vue";
import KelolaKategori from "../views/petugas/Admin/KelolaKategori.vue";
import LaporanUjian from "../views/petugas/Admin/LaporanUjian.vue";
import Setting from "../views/petugas/Admin/Setting.vue";

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
                path: "daftar/:id",
                name: "Pendaftaran",
                component: Pendaftaran
            },
            {
                path: "ujian/:type/:ujian_id/:soal_id",
                name: "Soal",
                component: Soal
            },
            {
                path: "daftar",
                name: "Pendaftaran Baru",
                component: PendaftaranBaru,
                beforeEnter(to, from, next) {
                    if (from.name == null) { next({ name: "Home Calon Mahasiswa" }); } else next();
                },

            },
            {
                path: "panduan",
                name: "Panduan Pendaftaran",
                component: PanduanPendaftaran
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
            {
                path: "kelola-temu-ramah",
                name: "Kelola Temu Ramah",
                component: KelolaTemuRamah
            },
            {
                path: "kelola-soal",
                name: "Kelola Soal",
                component: KelolaSoal
            },
            {
                path: "kelola-kategori",
                name: "Kelola Kategori",
                component: KelolaKategori
            },
            {
                path: "laporan-ujian",
                name: "Laporan Ujian",
                component: LaporanUjian},{
                path: "setting-ujian",
                name: "Setting Ujian",
                component: Setting
            },
        ]
    },

];

const router = new VueRouter({
    mode: "history",
    base: "/user/",
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    },
    routes
});
router.beforeEach((to, from, next) => {
    if (from.name == null) {
        console.log('from null')
    }
    next();
})

export default router;
