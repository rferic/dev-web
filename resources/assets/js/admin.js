
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueResource from 'vue-resource'
import VeeValidate from 'vee-validate';
import i18n from 'voo-i18n'
import translations from './admin/includes/translations'

Vue.use(VueResource)
Vue.use(VeeValidate);
Vue.use(i18n, translations)

import MenuDragAndDrop from './admin/components/Menu.vue'
import PageForm from './admin/components/Page.vue'

const app = new Vue({
    el: '#app',
    components: {
        MenuDragAndDrop,
        PageForm
    }
});
