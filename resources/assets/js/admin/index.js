
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
import VueInstant from 'vue-instant'
import VueMoment from 'vue-moment'
import VueNoty from 'vuejs-noty'

// CSS
import 'vue-instant/dist/vue-instant.css'

Vue.use(VueResource)
Vue.use(VeeValidate)
Vue.use(i18n, translations)
Vue.use(ToggleButton)
Vue.use(VModal, { dialog: true, dynamic: true })
Vue.use(VueScrollTo)
Vue.use(VueInstant)
Vue.use(VueMoment);
Vue.use(VueNoty, {
    layout: 'bottomRight',
    timeout: 4000,
    animation: {
        open: 'noty_effects_open',
    }
})

import MenuDragAndDrop from './components/Menu'
import PageForm from './components/Page'
import AppsList from './components/AppsList'
import AppPrivateUsersList from './components/AppPrivateUsersList'
import ModalDynamic from './components/ModalDynamic'
import ProfileFormUpdate from './components/ProfileFormUpdate'
import ProfileFormReset from './components/ProfileFormReset'
import store from './store'
import { mapState } from 'vuex'

const app = new Vue({
    el: '#app',
    store,
    components: {
        MenuDragAndDrop,
        PageForm,
        AppsList,
        AppPrivateUsersList,
        ModalDynamic,
        ProfileFormUpdate,
        ProfileFormReset
    },

    data () {
        return {
            messagesAlerts: {
                momentLastRequest: Vue.moment().subtract(1, 'month')
            }
        }
    },

    computed: {
        ...mapState([ 'routesGlobal' ])
    },

    methods: {
        generateInfoNotify ( text ) {
            this.$noty.info('<i class="fa fa-info"></i> ' + text)
        },

        generateWarningNotify ( text ) {
            this.$noty.warning('<i class="fa fa-warning"></i> ' + text)
        },

        generateErrorNotify ( text ) {
            this.$noty.error('<i class="fa fa-thumbs-o-down"></i> ' + text)
        },

        generateSuccessNotify ( text ) {
            this.$noty.success('<i class="fa fa-thumbs-o-up"></i> ' + text)
        },

        getCountPendings () {
            let context = this

            axios.post(this.routesGlobal.routeMessagesGetCountPendings, {}).then(function (response) {
                context.notifyPengindsMessages(response.data.count)
            }).catch(function (error) {
                console.error(error)
            })
        },

        getCountLastMessages () {
            let context = this

            axios.post(this.routesGlobal.routeMessagesGetCountLast, {
                timeSince: context.messagesAlerts.momentLastRequest
            }).then(function (response) {
                context.messagesAlerts.momentLastRequest = Vue.moment()
                context.notifyNewMessages(response.data.count)
                context.checkLastMessages()
            }).catch(function (error) {
                console.error(error)
            })
        },

        notifyPengindsMessages (count) {
            let text

            if ( count > 0 ) {
                text = count === 1 ? this.$t('unopened message', { locale: this.locale }) : this.$t('unopened messages', { locale: this.locale })
                this.generateInfoNotify(count + ' ' + text)
            }
        },

        notifyNewMessages (count) {
            let text

            if ( count > 0 ) {
                text = count === 1 ? this.$t('new message since', { locale: context.locale }) : this.$t('new messages since', { locale: this.locale })
                this.generateInfoNotify(count + ' ' + text + ' ' + this.messagesAlerts.momentLastRequest.format('LT'))
            }
        },

        checkLastMessages () {
            let context = this

            setTimeout(function(){
                context.getCountLastMessages()
            }, 60000);
        }
    },

    mounted () {
        let context = this

        context.getCountPendings()
        context.checkLastMessages()
    }
});
