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


// import Select2Component
//import Select2 from 'vue-select';
//Vue.component('Select2', Select2);

import 'bootstrap-vue/dist/bootstrap-vue.css' // Importing CSS Style
import 'bootstrap-vue/dist/bootstrap-vue-icons.min.css'

import Multiselect from 'vue-multiselect'
// register globally
Vue.component('multiselect', Multiselect)
//import 'vue-multiselect/dist/vue-multiselect.min.css'
import '../../public/css/vue-multiselect.css'


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

//Components
Vue.component('po-component', require('./components/po/poComponent.vue').default);
Vue.component('order-component', require('./components/order/OrderComponent.vue').default);
Vue.component('rma-component', require('./components/rma/RMAComponent.vue').default);
Vue.component('refurbish-component', require('./components/refurbish/RefurbishComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});
