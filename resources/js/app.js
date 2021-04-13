/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue'
import Vuex from 'vuex'
import Vuetify from 'vuetify';
import store from './store/store'
import router from './router/routerMHS'
import VueWindowSize from 'vue-window-size';
import vuescroll from 'vuescroll';
import moment from './moment.js';
import Vue2Filters from 'vue2-filters'
import VueCountdownTimer from 'vuejs-countdown-timer';
import VueMask from 'v-mask';
import VueHtml2Canvas from 'vue-html2canvas';

Vue.use(VueHtml2Canvas);
Vue.use(VueMask);
Vue.use(VueCountdownTimer);
Vue.use(Vue2Filters)
Vue.use(moment);
Vue.use(vuescroll);
Vue.use(VueWindowSize);
Vue.use(Vuex)
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
import App from './views/App';
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.mixin({
  methods: {
    checkFileType(file, extensions) {
      // check if the file extension from vuetify input file is the same as expected
      console.log(file.name);
      var fileExt = "";
      var fileSplitted = file.name.split(".");
      try {
        fileExt = fileSplitted[fileSplitted.length - 1];
      } catch (error) {
        console.log(extensions[0], fileExt);
        return false;
      }
      // console.log(extensions[0], fileExt);
      while (extensions.length > 0) {
        console.log(extensions[0], fileExt);
        if (extensions[0] == fileExt) {
          return true;
        }
        extensions.shift();
      }
      return false;
    }
  }
})
const app = new Vue({
  el: '#app',
  components: { App },
  store,
  router,
  vuetify
});
