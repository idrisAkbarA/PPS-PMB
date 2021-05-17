import Vue from "vue";
import VueRouter from "vue-router";
import store from '../store/store'


import PetinggiLayout from "../views/Petugas/PetinggiLayout.vue";
import DashboardPetugas from "../views/Petugas/Dashboard.vue";
import Pendaftar from "../views/Petugas/Admin/Pendaftar.vue";
import KelolaPendaftaran from "../views/Petugas/Admin/KelolaPendaftaran.vue";
import GantiPassword from "../views/Petugas/Admin/GantiPassword.vue";
import KelolaTemuRamah from "../views/Petugas/Admin/KelolaTemuRamah.vue";
import KelolaPetugas from "../views/Petugas/Admin/KelolaPetugas.vue";
import LaporanUjian from "../views/Petugas/Admin/LaporanUjian.vue";
Vue.use(VueRouter);
const routes = [
    {
        path: "/:petugas",
        component: PetinggiLayout,
        children: [
            {
                path: "dashboard",
                name: "Dashboard Petugas",
                component: DashboardPetugas
            },
            {
                path: "ganti-password",
                name: "Ganti Password",
                component: GantiPassword
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
                path: "kelola-petugas",
                name: "Kelola Petugas",
                component: KelolaPetugas
            },
            {
                path: "laporan-ujian",
                name: "Laporan Ujian",
                component: LaporanUjian
            },
        ]
    },
];

const router = new VueRouter({
    mode: "history",
    base: "/petinggi",
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    },
    routes
});
router.beforeEach(async (to, from, next) => {
    if (from.name == null) {
        // console.log('from null');
        // console.log('destination', to);
        var rootPath = to.matched[0].path;
        var isLoggedIn = async (role) => {
            var value = null;
            await axios.post('/api/auth-is-login/' + role).then(response => {
                value = response.data.value;
                console.log('check');
            })
            return value;
        }
        switch (rootPath) {
            case '/cln-mhs':
                console.log('im at mahasiswa');
                // console.log('is logged in?', !isLoggedIn('cln_mahasiswa'))
                var isLogin = await isLoggedIn('cln_mahasiswa')
                if (!isLogin) {
                    console.log('not logged in as mahasiswa');
                    next(false)
                    window.location.replace('/login');
                }
                break;
            case '/petinggi/:petugas':
                console.log('im at petinggi');
                // console.log('is logged in?', !isLoggedIn('petugas'))
                var isLogin = await isLoggedIn('petugas')
                console.log(isLogin);
                if (!isLogin) {
                    console.log('not logged in as petugas');
                    next(false)
                    window.location.replace('/login');
                }
                break;
            default:
                break;
        }
    }
    next();
})

export default router;
