
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
import 'vue-instant/dist/vue-instant.css'
import VueInstant   from 'vue-instant'

Vue.use(VueResource)
Vue.use(VeeValidate)
Vue.use(i18n, translations)
Vue.use(ToggleButton)
Vue.use(VModal, { dialog: true, dynamic: true })
Vue.use(VueScrollTo)
Vue.use(VueInstant)

import MenuDragAndDrop from './components/Menu'
import PageForm from './components/Page'
import AppsList from './components/AppsList'
import AppPrivateUsersList from './components/AppPrivateUsersList'
import ModalDynamic from './components/ModalDynamic'
import ProfileForm from './components/ProfileForm'
import store from './store'

const app = new Vue({
    el: '#app',
    store,
    components: {
        MenuDragAndDrop,
        PageForm,
        AppsList,
        AppPrivateUsersList,
        ModalDynamic,
        ProfileForm
    }
});
