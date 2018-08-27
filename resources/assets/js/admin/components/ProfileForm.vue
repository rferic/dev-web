<template>
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $t('Update data', { locale: this.locale }) }}</h3>
                </div>
                <div class="box-body">
                  	<form v-if="isMounted" autocomplete="off" @submit.prevent="validateBeforeSubmitUpdate" id="profileUpdateForm" class="form-horizontal form-content">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label" for="email">{{ $t('Email', { locale: $store.locale }) }}*</label>
                                <div :class="{ 'has-error' : errors.has('email')}">
                                    <input
                                        type="text"
                                        name="email"
                                        v-model="profile.email"
                                        v-validate
                                        data-vv-rules="required|email|emailIsFree|max:50"
                                        class="form-control"
                                        :class="{ 'has-error' : errors.has('email')}"
                                    />
                                    <span v-show="errors.has('email')" class="text-danger">{{ errors.first('email') }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="control-label" for="name">{{ $t('Name', { locale: $store.locale }) }}*</label>
                                <div :class="{ 'has-error' : errors.has('name')}">
                                    <input
                                        type="text"
                                        name="name"
                                        v-model="profile.name"
                                        v-validate
                                        data-vv-rules="required|max:150"
                                        class="form-control"
                                        :class="{ 'has-error' : errors.has('name')}"
                                    />
                                    <span v-show="errors.has('name')" class="text-danger">{{ errors.first('name') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success btn-xl pull-right" type="submit">
                                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ $t('Save', { locale: this.locale }) }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $t('Reset password', { locale: this.locale }) }}</h3>
                </div>
                <div class="box-body">
                    <form v-if="isMounted" autocomplete="off" @submit.prevent="validateBeforeSubmitReset" id="profileResetForm" class="form-horizontal form-content">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label" for="password_current">{{ $t('Current password', { locale: $store.locale }) }}*</label>
                                <div :class="{ 'has-error' : errors.has('password_current')}">
                                    <input
                                        type="password"
                                        name="password_current"
                                        v-model="password_current"
                                        v-validate
                                        data-vv-rules="required|min:6|passwordIsStrong"
                                        class="form-control"
                                        :class="{ 'has-error' : errors.has('password_current')}"
                                    />
                                    <span v-show="errors.has('password_current')" class="text-danger">{{ errors.first('password_current') }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="control-label" for="password">{{ $t('New password', { locale: $store.locale }) }}*</label>
                                <div :class="{ 'has-error' : errors.has('password')}">
                                    <input
                                        type="password"
                                        name="password"
                                        v-model="password"
                                        v-validate
                                        data-vv-rules="required|min:6|passwordIsStrong"
                                        class="form-control"
                                        :class="{ 'has-error' : errors.has('password')}"
                                    />
                                    <span v-show="errors.has('password')" class="text-danger">{{ errors.first('password') }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="control-label" for="password_confirmation">{{ $t('New password, again', { locale: $store.locale }) }}*</label>
                                <div :class="{ 'has-error' : errors.has('password_confirmation')}">
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        v-model="password_confirmation"
                                        v-validate
                                        data-vv-rules="required|min:6|passwordIsStrong|confirmed:password"
                                        class="form-control"
                                        :class="{ 'has-error' : errors.has('password_confirmation')}"
                                    />
                                    <span v-show="errors.has('password_confirmation')" class="text-danger">{{ errors.first('password_confirmation') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success btn-xl pull-right" type="submit">
                                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ $t('Save', { locale: this.locale }) }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div v-show="showAlertSuccessed" class="alert alert-success">{{ textAlertSuccessed }}</div>
            <div v-show="showAlertError" class="alert alert-danger">{{ textAlertError }}</div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import { Validator } from 'vee-validate'
    import passwordIsStrongRule from '../includes/validators/passwordIsStrongRule'

    export default {
      	name: 'ProfileForm',
        props: [ 'profile_json' ],
        data () {
            return {
                profile: null,
                showAlertSuccessed: false,
                showAlertError: false,
                textAlertSuccessed: '',
                textAlertError: '',
                password_current: '',
                password: '',
                password_confirmation: ''
            }
        },

        computed: {
            ...mapState([ 'csrfToken', 'locale', 'supportedLocales', 'routes' ]),

            isMounted () {
                return this.profile !== null
            }
        },

      	methods: {
            validateBeforeSubmitUpdate () {
                this.$validator.validateAll().then(result => {
                    if ( !result ) {
                        console.log('Form not validate')
                    } else {
                        this.update()
                    }
                }).catch(error => {
                    console.error(error)
                })
            },

            validateBeforeSubmitReset () {
                this.$validator.validateAll().then(result => {
                    if ( !result ) {
                        console.log('Form not validate')
                    } else {
                        this.reset()
                    }
                }).catch(error => {
                    console.error(error)
                })
            },

            update () {
                let context = this

                axios.post(context.routes.routeProfileUpdate, {
                    profile: context.profile
                }).then(function (response) {
                    context.showAlert( 'success', context.$t('Profile has been updated', { locale: context.locale }) )
                }).catch(function (error) {
                    console.error(error)
                    context.showAlert( 'error', context.$t('Server error: Profile has not been updated', { locale: context.locale }) )
                })
            },

            reset () {
                let context = this

                axios.post(context.routes.routeProfileReset, {
                    passwordCurrent: context.password_current,
                    password: context.password,
                    password_confirmation: context.password_confirmation
                }).then(function (response) {
                    if ( response.data.result ) {
                        context.showAlert( 'success', context.$t('Password has been reseted', { locale: context.locale }) )
                    } else {
                        context.showAlert( 'error', context.$t('Password has not been reseted because current password is wrong', { locale: context.locale }) )
                    }
                }).catch(function (error) {
                    console.error(error)
                    context.showAlert( 'error', context.$t('Server error: Password has not been reseted', { locale: context.locale }) )
                })
            },

            showAlert ( type, text ) {
                let context = this

                if ( type === 'success' ) {
                    context.showAlertSuccessed = true
                    context.textAlertSuccessed = text
                } else if ( type === 'error' ) {
                    context.showAlertError = true
                    context.textAlertError = text
                }

                setTimeout(() => {
                    context.hideAlert( type )
                }, 2000)
            },

            hideAlert ( type ) {
                if ( type === 'success' ) {
                    this.showAlertSuccessed = false
                    this.textAlertSuccessed = ''
                } else if ( type === 'error' ) {
                    this.showAlertError = false
                    this.textAlertError = ''
                }
            }
      	},

        mounted () {
            let context = this

            context.profile = JSON.parse(context.profile_json)

            // validate if email is free
            Validator.extend('emailIsFree', {
                getMessage () {
                    return 'The email is already in the system'
                },
                validate ( value ) {
                    return new Promise ((resolve) => {
                        if ( value ) {
                            axios.post(context.routes.routeValidateEmailIsFree, {
                                value: value
                            }).then(function (response) {
                                resolve(response.data.result)
                            }).catch(function (error) {
                                console.error(error)
                                resolve(false)
                            })
                        } else {
                            return false
                        }
                    })
                }
            })

            // validate if password is strong
            Validator.extend('passwordIsStrong', passwordIsStrongRule)
        }
    }
</script>
