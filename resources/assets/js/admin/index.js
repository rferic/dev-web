
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./../bootstrap');

window.Vue = require('vue');

import VueResource from 'vue-resource'
import VeeValidate from 'vee-validate'
import i18n from 'voo-i18n'
import translations from './includes/translations'
import ToggleButton from 'vue-js-toggle-button'
import VModal from 'vue-js-modal'
import VueScrollTo from 'vue-scrollto'

Vue.use(VueResource)
Vue.use(VeeValidate)
Vue.use(i18n, translations)
Vue.use(ToggleButton)
Vue.use(VModal, { dialog: true, dynamic: true })
Vue.use(VueScrollTo)

import MenuDragAndDrop from './components/Menu.vue'
import PageForm from './components/Page.vue'
import AppsList from './components/AppsList.vue'
import ModalDynamic from './components/ModalDynamic.vue'
import store from './store'

const app = new Vue({
    el: '#app',
    store,
    components: {
        MenuDragAndDrop,
        PageForm,
        AppsList,
        ModalDynamic
    }
});
