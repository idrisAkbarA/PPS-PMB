import Vue from "vue";
import Vuex from "vuex";

import Axios from "axios";
Vue.use(Vuex);
Axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
Axios.defaults.withCredentials = true;

export default new Vuex.Store({
    state: {
        user: null, // user who login
        isTambahSoal: false,
    },
    mutations: {
        mutateUser(val) {
            this.user = val;
        }
    },
    mutations: {
        toggleTambahSoal(state, data) {
            state.isTambahSoal = data;
            //  console.log(data);
        }
    },
    actions: {},
    modules: {},
})
