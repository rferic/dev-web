
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
import VueNoty from 'vuejs-noty'

Vue.use(VueResource)
Vue.use(VeeValidate)
Vue.use(i18n, translations)
Vue.use(ToggleButton)
Vue.use(VModal, { dialog: true, dynamic: true })
Vue.use(VueScrollTo)
Vue.use(VueInstant)
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
import { mapState, mapActions } from 'vuex'

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

    computed: {
        ...mapState([ 'routesGlobal' ]),
        ...mapState( 'messageModule', [ 'messages' ]),
        pendingsMessages () {
            let count = 0

            this.messages.forEach(function (message, i) {
                if ( message.status === 'pending' ) {
                    count++
                }
            })

            return count
        }
    },

    methods: {
        ...mapActions('messageModule', {
            setListMessage: 'setList'
        }),

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

        getListMessages ( action ) {
            axios.post(this.routesGlobal.routeMessageGetter, {}).then(action).catch(function (error) {
                console.error(error)
            })
        },

        notifyPengindsMessages () {
            let text = this.pendingsMessages === 1 ? this.$t('unopened message', { locale: this.locale }) : this.$t('unopened messages', { locale: this.locale })

            if ( this.pendingsMessages > 0 ) {
                this.generateInfoNotify(this.pendingsMessages + ' ' + text)
            }
        },

        notifyNewMessage (messages) {
            let find, text
            let count = 0

            messages.forEach(function (messageNew, i) {
                find = false

                this.messages.forEach(function(message, k) {
                    if ( messageNew.id === message.id) {
                        find = true
                    }
                })

                if ( !find ) {
                    count++
                }
            })

            if ( count > 0 ) {
                text = count === 1 ? this.$t('new message', { locale: this.locale }) : this.$t('new messages', { locale: this.locale })

                this.generateInfoNotify(count + ' ' + text)
            }
        },

        checkNewMessages () {
            let context = this

            setTimeout(function(){
                context.getListMessages(function (response) {
                    context.notifyNewMessage(response.data.messages)
                    context.setListMessage(response.data)
                })
            }, 60000);
        }
    },

    mounted () {
        let context = this

        context.getListMessages(function (response) {
            context.setListMessage(response.data)
            context.notifyPengindsMessages()
        })

        context.checkNewMessages()
    }
});
