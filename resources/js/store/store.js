import Vue from "vue";
import Vuex from "vuex";

import Axios from "axios";
Vue.use(Vuex);
Axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
Axios.defaults.withCredentials = true;

export default new Vuex.Store({
    state: {
        user: null, // user who logged in
        urlPeriode: '/api/periode',
        urlJurusan: '/api/jurusan',
        urlPendaftar: '/api/pendaftar',
        urlPendaftaran: "/api/ujian",
        urlKategori: "/api/kategori",
        urlBankSoal: "/api/bank-soal",
        urlKategoriPeriode: "/api/kategori-periode",
        urlTemuRamah: "/api/temu-ramah",
        isBottomSheetOpen: false,
        currentPeriode: null, // current active periode
        jurusan: null,
        ujian: null,
        periode: null,
        isLoading: false,
        ujianSelected: null,
    },
    mutations: {
        toggleBottomSheet(state, data) {
            state.isBottomSheetOpen = data;
        },
        setUjianSelected(state, data) {
            state.ujianSelected = data;
        },
        setUser(state, data) {
            state.user = data;
        },
        setJurusan(state, data) {
            state.jurusan = data;
        },
        setPeriode(state, data) {
            state.periode = data;
        },
        setUjian(state, data) {
            state.ujian = data;
        },
        setCurrentPeriode(state, data) {
            state.currentPeriode = data.id ? data : null;
        },
    },
    actions: {
        initAllDataClnMhs({ commit, dispatch, state }) {
            state.isLoading = true;
            axios
                .get(`/api/data/init-data-cln-mhs`)
                .then((response) => {
                    console.log(response.data);
                    commit('setUser', response.data.user);
                    commit('setUjian', response.data.ujian);
                    commit('setPeriode', response.data.periode);
                    commit('setJurusan', response.data.jurusan);
                    state.isLoading = false;
                })
                .catch((error) => { console.error(error); state.isLoading = false; });
        },
        updateUser({ commit, dispatch, state }, user) {
            return new Promise((resolve, reject) => {
                axios.put('/api/user/update', user).then(response => {
                    console.log(response.data);
                    commit('setUser', response.data);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                })
            })
        },
        getUser({ commit, dispatch, state }, role) {
            axios
                .get(`/api/user/${role}`)
                .then((response) => {
                    commit('setUser', response.data);
                    console.log(response);
                })
                .catch((error) => console.error(error));
        },
        getUjian({ commit, dispatch, state }, role) {
            axios
                .get(`/api/user/${role}`)
                .then((response) => {
                    commit('setUser', response.data);
                    console.log(response);
                })
                .catch((error) => console.error(error));
        },
        getPeriode({ commit, dispatch, state }, role) {
            axios
                .get(`/api/user/${role}`)
                .then((response) => {
                    commit('setUser', response.data);
                    console.log(response);
                })
                .catch((error) => console.error(error));
        },
        getCurrentPeriode({ commit, dispatch, state }) {
            axios
                .get(`${state.urlPeriode}/current`)
                .then((response) => {
                    commit('setCurrentPeriode', response.data);
                })
                .catch((error) => console.error(error));
        },
    },
    modules: {},
})
