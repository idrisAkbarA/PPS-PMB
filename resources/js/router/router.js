import Vue from "vue";
import VueRouter from "vue-router";
import store from '../store/store'
import axios from 'axios';
import HomeClnMhs from "../views/ClnMhs/Home.vue";
import ClnMhsLayout from "../views/ClnMhs/ClnMhsLayout.vue";
import PendaftaranBaru from "../views/ClnMhs/PendaftaranBaru.vue";
import PanduanPendaftaran from "../views/ClnMhs/PanduanPendaftaran.vue";
import Pendaftaran from "../views/ClnMhs/Pendaftaran.vue";
import Soal from "../views/ClnMhs/Soal.vue";
import SoalWithTimer from "../views/ClnMhs/SoalWithTimer.vue";

import PetugasLayout from "../views/petugas/PetugasLayout.vue";
import DashboardPetugas from "../views/petugas/Dashboard.vue";
import KelolaSoal from "../views/petugas/Admin/KelolaSoal.vue";
import KelolaPetugas from "../views/petugas/Admin/KelolaPetugas.vue";
import KelolaPeriode from "../views/petugas/Admin/KelolaPeriode.vue";
import KelolaJurusan from "../views/petugas/Admin/KelolaJurusan.vue";
import Pendaftar from "../views/petugas/Admin/Pendaftar.vue";
import KelolaPendaftaran from "../views/petugas/Admin/KelolaPendaftaran.vue";
import KelolaTemuRamah from "../views/petugas/Admin/KelolaTemuRamah.vue";
import KelolaKategori from "../views/petugas/Admin/KelolaKategori.vue";
import LaporanUjian from "../views/petugas/Admin/LaporanUjian.vue";
import Setting from "../views/petugas/Admin/Setting.vue";

import HomeTemuRamah from "../views/petugas/TemuRamah/Home.vue";

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
            // {
            //     path: "ujian/:type/:ujian_id/:soal_id",
            //     name: "Soal",
            //     component: Soal
            // },
            {
                path: "ujian/:type/:ujian_id/:soal_id",
                name: "Soal",
                component: SoalWithTimer
            },
            // timer test
            {
                path: "ujian/soal-timer",
                name: "Soal",
                component: SoalWithTimer
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
                path: "kelola-petugas",
                name: "Kelola Petugas",
                component: KelolaPetugas
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
                component: LaporanUjian
            }, {
                path: "setting-ujian",
                name: "Setting Ujian",
                component: Setting
            },
        ]
    },

    {
        path: "/temu-ramah/:petugas",
        component: ClnMhsLayout,
        children: [
            {
                path: "home",
                name: "Home Petugas Temu Ramah",
                component: HomeTemuRamah
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
        console.log('from null');
        console.log('destination', to);
        var rootPath = to.matched[0].path;
        var isLoggedIn = async (role) => {
            var value = null;
            await axios.post('/api/auth-is-login/' + role).then(response => {
                value = response.data.value;
            })
            return value;
        }
        switch (rootPath) {
            case '/cln-mhs':
                console.log('im at mahasiswa');
                console.log('is logged in?', !isLoggedIn('cln_mahasiswa'))
                if (!isLoggedIn('cln_mahasiswa')) {
                    console.log('not logged in as mahasiswa');
                    window.location.replace('/login');
                    next(false)
                }
                break;
            case '/petugas':
                console.log('im at petugas');
                console.log('is logged in?', !isLoggedIn('petugas'))
                if (!isLoggedIn('petugas')) {
                    console.log('not logged in as petugas');
                    window.location.replace('/login');
                    next(false)
                }
                break;
            default:
                break;
        }
    }
    next();
})

export default router;
