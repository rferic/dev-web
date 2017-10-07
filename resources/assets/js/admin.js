
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueResource from 'vue-resource'
import i18n from 'voo-i18n'
import translations from './admin/translations'

Vue.use(VueResource)
Vue.use(i18n, translations)

import MenuDragAndDrop from './admin/components/Menu.vue'

const app = new Vue({
    el: '#app',
    components: {
        MenuDragAndDrop
    }
});
