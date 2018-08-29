<template>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $t('Update data', { locale: this.locale }) }}</h3>
        </div>
        <div class="box-body">
          	<form v-if="isMounted" autocomplete="off" @submit.prevent="validateBeforeSubmit" id="profileUpdateForm" class="form-horizontal form-content">
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
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import { Validator } from 'vee-validate'

    export default {
      	name: 'ProfileUpdate',
        props: [ 'profile_json' ],
        data () {
            return {
                profile: null
            }
        },

        computed: {
            ...mapState([ 'csrfToken', 'locale', 'supportedLocales', 'routes' ]),

            isMounted () {
                return this.profile !== null
            }
        },

      	methods: {
            validateBeforeSubmit () {
                this.$validator.validateAll().then(result => {
                    if ( !result ) {
                        this.$root.generateErrorNotify(this.$t('Form not validate', { locale: this.locale }))
                    } else {
                        this.update()
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
                    context.$root.generateSuccessNotify(context.$t('Profile has been updated', { locale: context.locale }) )
                }).catch(function (error) {
                    console.error(error)
                    context.$root.generateErrorNotify(context.$t('Server error: Profile has not been updated', { locale: context.locale }))
                })
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
        }
    }
</script>
