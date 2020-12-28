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
    },
    mutations: {
        mutateUser(state, data) {
            state.user = data;
        },
        toggleBottomSheet(state, data) {
            state.isBottomSheetOpen = data;
        }
    },
    actions: {},
    modules: {},
})
