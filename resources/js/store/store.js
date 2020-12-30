import Vue from "vue";
import Vuex from "vuex";

import Axios from "axios";
Vue.use(Vuex);
Axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
Axios.defaults.withCredentials = true;

export default new Vuex.Store({
    state: {
        user: null, // user who logged in
        isBottomSheetOpen: false,
        jurusan: null,
        ujian: null,
        periode: null,

        isLoading: false,
    },
    mutations: {
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
        toggleBottomSheet(state, data) {
            state.isBottomSheetOpen = data;
        }
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
    },
    modules: {},
})
