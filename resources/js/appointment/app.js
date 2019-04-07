
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import '../bootstrap';
import Vue from 'vue';
import Vuetifyjs from 'vuetify';
import axios from 'axios';
import VeeValidate from './../vee-validate.js'
import { store } from '../store'

Vue.use(Vuetifyjs, {
    theme: {
        primary: '#49A2BC',
        secondary: '#7FBDCF',
        accent: '#45AD7E',
        error: '#C6384A',
    },
});
Vue.use(VeeValidate)
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import 'vuetify/dist/vuetify.min.css'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('home-component', require('./components/HomeComponent.vue').default)
import IndexComponent from './components/IndexComponent.vue'
import LoginComponent from './components/LoginComponent.vue'
import ProfileComponent from './components/ProfileComponent.vue'
import ListComponent from './components/ListComponent.vue'
import StepsComponent from './components/StepsComponent.vue'
import TermsComponent from './components/TermsComponent.vue'
import PrivacyPolicyComponent from './components/PrivacyPolicyComponent.vue'
import UpdateFormComponent from './components/UpdateFormComponent.vue'
import NotworkComponent from './components/NotworkComponent.vue'


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    el: '#app',
    store,
    components: {
        'index-component': IndexComponent,
        'login-component': LoginComponent,
        'profile-component': ProfileComponent,
        'list-component': ListComponent,
        'steps-component': StepsComponent,
        'terms-component': TermsComponent,
        'privacypolicy-component': PrivacyPolicyComponent,
        'notwork-component': NotworkComponent,
        'updateform-component':UpdateFormComponent
    }
});
