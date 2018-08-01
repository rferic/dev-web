<template>
    <div>
        <div class="row">
            <app-item
                appId="isNew"
                :types="types"
                :status="status"
                :style="{ minHeight: topHeightCSS }"
                @refreshTopHeightEvent="refreshTopHeight"
                @callFormEvent="loadForm"
            ></app-item>
            <app-item
                v-for="(app, index) in apps"
                :key="index"
                :appId="app.id"
                :types="types"
                :status="status"
                :style="{ minHeight: topHeightCSS }"
                @refreshTopHeightEvent="refreshTopHeight"
                @callFormEvent="loadForm"
            ></app-item>
        </div>
    </div>

</template>

<script>
import AppItem from './AppItem'
import AppVoidStructure from '../structures/AppVoidStructure'
import ModalConfigVoidStructure from '../structures/ModalConfigVoidStructure'
import { mapState, mapActions, mapMutations } from 'vuex'
import screenMixin from '../includes/mixins/screenMixin'
import cloneMixin from '../includes/mixins/cloneMixin'

export default {
    mixins: [ screenMixin, cloneMixin ],
    name: 'AppsLists',
    props: [ 'apps_json', 'types_json', 'status_json' ],
    components: { AppItem },

    data () {
        return {
            types: JSON.parse(this.types_json),
            status: JSON.parse(this.status_json),
            topHeight: 0,
            AppVoidStructure,
            appForm: AppVoidStructure,
            forceHeight: false
        }
    },

    computed: {
        ...mapState([ 'locale', 'supportedLocales' ]),
        ...mapState('appModule', { apps: 'apps'}),
        topHeightCSS () {
            if ( this.forceHeight ) {
                return this.topHeight > 0 ? this.topHeight + 'px' : 'auto'
            } else return 'auto'
        }
    },

    methods: {
        ...mapActions('appModule', {
            setList: 'setList'
        }),
        ...mapActions('modalModule', {
            showModal: 'show'
        }),
        ...mapMutations('modalModule', {
            editConfigModal: 'EDIT_CONFIG'
        }),
        refreshTopHeight ( height ) {
            if ( height > this.topHeight ) {
                this.topHeight = height
            }
        },
        loadForm ( app ) {
            let title = this.$t('Create a new APP', { locale: this.locale })
            let configModal = this.clone(ModalConfigVoidStructure)
            let context = this

            if ( app.id !== null ) {
                this.appForm = app
                title = this.$t('Update APP', { locale: this.locale })
            }

            configModal.head.isVisible = true
            configModal.head.title = title
            configModal.footer.isVisible = true
            configModal.footer.buttons = [
                {
                    label: this.$t('Remove', { locale: this.locale }),
                    icon: 'fa fa-trash',
                    class: 'btn btn-flat btn-danger',
                    click: 'removeEvent'
                },
                {
                    label: this.$t('Save', { locale: this.locale }),
                    icon: 'fa fa-save',
                    class: 'btn btn-flat btn-success',
                    click: 'saveEvent'
                }
            ]

            this.showModal({
                $modal: this.$modal,
                component: 'app-form',
                config: configModal,
                data: {
                    app: app,
                    types: this.types,
                    status: this.status
                }
            })
        },
        refreshForceHeight () {
            this.forceHeight = !this.isScreen('mobile')
            this.refreshTopHeight()
        }
    },

    mounted () {
        this.refreshForceHeight()

        window.addEventListener('resize', () => {
            this.refreshForceHeight()
        })

        this.setList({ apps: JSON.parse(this.apps_json) })
    }
}
</script>
