/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue'
import Vuetify from 'vuetify';
import VueWindowSize from 'vue-window-size';
// import vuescroll from 'vuescroll';
// import moment from './moment.js';
// import Vue2Filters from 'vue2-filters'

// Vue.use(Vue2Filters)
// Vue.use(moment);
// Vue.use(vuescroll);
Vue.use(VueWindowSize);
Vue.use(Vuetify);

const vuetify = new Vuetify(
    {
        theme: {
            dark: false,
        },
    }
);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
import Login from './views/Auth/LoginComponent';
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#login-vue-component',
    components: { Login },
    vuetify
});
