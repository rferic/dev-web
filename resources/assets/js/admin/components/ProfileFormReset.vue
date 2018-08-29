<template>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $t('Reset password', { locale: this.locale }) }}</h3>
        </div>
        <div class="box-body">
            <form autocomplete="off" @submit.prevent="validateBeforeSubmit" id="profileResetForm" class="form-horizontal form-content">
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
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import { Validator } from 'vee-validate'
    import passwordIsStrongRule from '../includes/validators/passwordIsStrongRule'

    export default {
      	name: 'ProfileFormReset',
        data () {
            return {
                password_current: '',
                password: '',
                password_confirmation: ''
            }
        },

        computed: {
            ...mapState([ 'csrfToken', 'locale', 'supportedLocales', 'routes' ])
        },

      	methods: {
            validateBeforeSubmit () {
                this.$validator.validateAll().then(result => {
                    if ( !result ) {
                        this.$root.generateErrorNotify(this.$t('Form not validate', { locale: this.locale }))
                    } else {
                        this.reset()
                    }
                }).catch(error => {
                    console.error(error)
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
                        context.$root.generateSuccessNotify(context.$t('Password has been reseted', { locale: context.locale }) )
                    } else {
                        context.$root.generateErrorNotify(context.$t('Password has not been reseted because current password is wrong', { locale: context.locale }) )
                    }
                }).catch(function (error) {
                    console.error(error)
                    context.$root.generateErrorNotify(context.$t('Server error: Password has not been reseted', { locale: context.locale }) )
                })
            }
      	},

        mounted () {
            // validate if password is strong
            Validator.extend('passwordIsStrong', passwordIsStrongRule)
        }
    }
</script>
