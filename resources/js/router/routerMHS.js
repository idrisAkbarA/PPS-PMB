import Vue from "vue";
import VueRouter from "vue-router";
import store from '../store/store'
import axios from 'axios';
import HomeClnMhs from "../views/ClnMhs/Home.vue";
import ClnMhsLayout from "../views/ClnMhs/ClnMhsLayout.vue";
import PanduanPendaftaran from "../views/ClnMhs/PanduanPendaftaran.vue";
import PendaftaranBaru from "../views/ClnMhs/PendaftaranBaru.vue";
import Pendaftaran from "../views/ClnMhs/Pendaftaran.vue";
import Cumlaude from "../views/ClnMhs/Cumlaude.vue";
import Soal from "../views/ClnMhs/Soal.vue";
import SoalWithTimer from "../views/ClnMhs/SoalWithTimer.vue";

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
                path: "cumlaude/:id",
                name: "Daftar Cumlaude",
                component: Cumlaude
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
            case '/admin/:petugas':
                console.log('im at petugas');
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
