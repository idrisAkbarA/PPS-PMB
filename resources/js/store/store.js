import Vue from "vue";
import Vuex from "vuex";

import Axios from "axios";
Vue.use(Vuex);
Axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
Axios.defaults.withCredentials = true;

export default new Vuex.Store({
    state: {
        user: null, // user who logged in
        isTambahSoal: false,
    },
    mutations: {
        mutateUser(state, data) {
            state.user = data;
        },
        toggleTambahSoal(state, data) {
            state.isTambahSoal = data;
            //  console.log(data);
        }
    },
    actions: {},
    modules: {},
})
