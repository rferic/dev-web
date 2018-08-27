<template>
    <div>
        <div class="row">
            <div class="col-md-6 col-sm-12 apps-list">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $t('Private Apps List', { locale: this.locale }) }}</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#{{ $t('ID', { locale: this.locale }) }}</th>
                                    <th>{{ $t('Title', { locale: this.locale }) }}</th>
                                    <th>{{ $t('Version', { locale: this.locale }) }}</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(app, index) in apps"
                                    :key="index"
                                    class="app-item"
                                >
                                    <td>{{ app.id }}</td>
                                    <td>{{ getTitleCurrentLocale(app) }}</td>
                                    <td>{{ app.version}}</td>
                                    <td>
                                        <button class="btn btn-default" @click="showAppUsers(app)">
                                            <i class="fa fa-users"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div v-if="showUsersList" id="users-list-container" class="col-md-6 col-sm-12 app-users-list">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $t('Users attach to', { locale: this.locale }) }} <b>{{ getTitleCurrentLocale(appSelected) }}</b></h3>
                    </div>
                    <div class="box-body">
                        <div>
                            <vue-instant
                                suggestion-attribute="name"
                                v-model="inputUserSelected"
                                type="twitter"
                                class="input-suggestion"
                                @enter="selectSuggestion"
                                @selected="selectSuggestion"
                                @clear="removeSuggestion"
                                @escape="removeSuggestion"
                                :show-autocomplete="true"
                                :autofocus="false"
                                :suggestions="userSuggestions"
                                :placeholder="$t('Search user...', { locale: this.locale })"
                            ></vue-instant>
                            <button class="btn btn-success btn-xs" :class="{ disabled: !btnAddSuggestionIsEnable }" @click="addUserSuggestion">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>

                        <table class="table users-list">
                            <tbody>
                                <tr
                                    v-for="(user, index) in appSelected.users"
                                    :key="index"
                                    class="list-user"
                                >
                                    <td>{{ user.id }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>
                                        <toggle-button :value="getIsActive(user)" @change="toggleUserStatus(user)" :sync="true" :labels="true" />
                                    </td>
                                    <td>
                                        <button class="btn btn-default btn-danger btn-xs" @click="revokeUserApp(user)">
                                            <i class="fa fa-remove"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import VueScrollTo from 'vue-scrollto'
    import { mapState, mapActions, mapMutations } from 'vuex'

    export default {
        name: 'AppPrivateUsersList',
        props: [ 'apps_json', 'users_json' ],

        data () {
            return {
                appSelected: null,
                inputUserSelected: '',
                userSelected: null
            }
        },

        computed: {
            ...mapState([ 'locale', 'supportedLocales', 'routes' ]),
            ...mapState('appModule', { apps: 'apps'}),

            showUsersList () {
                return this.appSelected !== null
            },

            userSuggestions () {
                let list = []
                let usersAll = JSON.parse(this.users_json)

                if ( this.appSelected !== null ) {
                    usersAll.forEach((user, key) => {
                        let find = false

                        this.appSelected.users.forEach((user2, key2)  => {
                            if ( user.id === user2.id ) {
                                find = true
                            }
                        })

                        if ( !find ) {
                            list.push(user)
                        }
                    })
                }

                return list
            },

            btnAddSuggestionIsEnable () {
                return this.userSelected !== null
            }
        },

        methods: {
            ...mapActions('appModule', {
                setList: 'setList',
                addUser: 'addUser',
                refreshUserStatus: 'refreshUserStatus',
                revokeUser: 'revokeUser'
            }),

            getIsActive ( user ) {
                return user.pivot.active === 1
            },

            getTitleCurrentLocale ( app ) {
                let title = ''

                app.locales.forEach(( locale, key ) => {
                    if ( locale.lang === this.locale ) {
                        title = locale.title
                    }
                })

                return title
            },

            showAppUsers ( app ) {
                this.appSelected = app

                VueScrollTo.scrollTo('#users-list-container', 500, {
                    container: "body",
                    duration: 500,
                    easing: "ease",
                    offset: 0,
                    cancelable: true,
                    onDone: false,
                    onCancel: false,
                    x: false,
                    y: true
                })
            },

            selectSuggestion ( user ) {
                this.userSelected = user
            },

            removeSuggestion () {
                this.userSelected = null
            },

            freshUserStatus ( params, resolve, reject ) {
                axios.post(this.routes.routesAppUserSync[params.app.id], {
                    locale: this.locale,
                    user: params.user,
                    active: params.status
                }).then(function (response) {
                    resolve(response)
                }).catch(function (error) {
                    reject(error)
                })
            },

            addUserSuggestion () {
                let context = this

                new Promise((resolve, reject) =>{
                    context.freshUserStatus({
                        app: context.appSelected,
                        user: context.userSelected,
                        status: true
                    }, resolve, reject)
                }).then((response) => {
                    let userUpdated = null

                    response.data.users.forEach((user, key) => {
                        if ( user.id === context.userSelected.id ) {
                            userUpdated = user
                        }
                    })

                    if ( userUpdated !== null ) {
                        context.addUser({
                            app: context.appSelected,
                            user: userUpdated
                        })
                    }

                    context.removeSuggestion()
                }).catch((error) => {
                    console.error(error)
                })
            },

            toggleUserStatus ( user ) {
                let context = this
                let status = user.pivot.active !== 1

                new Promise((resolve, reject) =>{
                    context.freshUserStatus({
                        app: context.appSelected,
                        user: user,
                        status: status
                    }, resolve, reject)
                }).then((response) => {
                    let userUpdated = null

                    response.data.users.forEach((userResponse, key) => {
                        if ( userResponse.id === user.id ) {
                            userUpdated = user
                        }
                    })

                    if ( userUpdated !== null ) {
                        context.refreshUserStatus({
                            app: context.appSelected,
                            user: userUpdated,
                            active: status
                        })
                    }
                }).catch((error) => {
                    console.error(error)
                })
            },

            revokeUserApp ( user ) {
                let context = this

                axios.post(context.routes.routesAppUserRevoke[context.appSelected.id], {
                    locale: context.locale,
                    user: user
                }).then(function (response) {
                    context.revokeUser({
                        app: context.appSelected,
                        user: user
                    })
                }).catch(function (error) {
                    console.error(error)
                })
            }
        },

        mounted () {
            this.setList({ apps: JSON.parse(this.apps_json) })
        }
    }
</script>

<style scoped>
    .users-list {
        margin-top: 10px;
    }

    .input-suggestion {
        display: inline-block;
    }
</style>
