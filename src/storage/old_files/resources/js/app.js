/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


import Vue from 'vue'
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'

Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)


//import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'
//import BootstrapVue from 'bootstrap-vue' //Importing
//Vue.use(BootstrapVue) // Telling Vue to use this in whole application
//Vue.use(require('bootstrap-vue'));


//import { BootstrapVueIcons } from 'bootstrap-vue'


import 'bootstrap-vue/dist/bootstrap-vue.css' // Importing CSS Style
import 'bootstrap-vue/dist/bootstrap-vue-icons.min.css'


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
Vue.component('createpo-component', require('./components/po/createPOComponent.vue').default);
Vue.component('editpo-component', require('./components/po/editPOComponent.vue').default);

Vue.component('createorder-component', require('./components/order/createOrderComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});
