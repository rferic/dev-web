
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueResource from 'vue-resource'
import VeeValidate from 'vee-validate'
import i18n from 'voo-i18n'
import translations from './admin/includes/translations'
import ToggleButton from 'vue-js-toggle-button'

Vue.use(VueResource)
Vue.use(VeeValidate)
Vue.use(i18n, translations)
Vue.use(ToggleButton)

import MenuDragAndDrop from './admin/components/Menu.vue'
import PageForm from './admin/components/Page.vue'
import AppsList from './admin/components/AppsList.vue'

const app = new Vue({
    el: '#app',
    components: {
        MenuDragAndDrop,
        PageForm,
        AppsList
    }
});
