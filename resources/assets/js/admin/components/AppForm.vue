<template>
  	<div class="app-form-modal">
  		<form autocomplete="off" @submit.prevent="validateBeforeSubmitApp" id="appForm" class="form-horizontal form-content">
            <div class="container">
      			<div class="row form-group">
                    <!-- Edit locales -->
                    <div class="col-md-9 col-sm-12">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li v-for="(supportedLocale, iso) in supportedLocales" :key="iso" :class="{ active: iso === currentLocaleIso }">
                                    <a :href="`#locale-${iso}`" data-toggle="tab" aria-expanded="true" @click="setAppLocaleISOCurrent(iso)">{{ supportedLocale.name }}</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane" id="`#locale-${iso}`" v-for="(supportedLocale, iso) in supportedLocales" :key="iso" :class="{ active: iso === currentLocaleIso }">
                                    <div v-if="typeof locales[iso] !== typeof undefined" class="row form-group">
                                        <!-- Title App -->
                                        <div class="col-md-12">
                                            <label class="control-label" :for="`title-${iso}`">{{ $t('Title', { locale: this.locale }) }}*</label>
                                            <div :class="{ 'has-error' : errors.has('title-' + iso)}">
                                                <input
                                                    type="text"
                                                    :name="`title-${iso}`"
                                                    v-model="locales[iso].title"
                                                    v-validate
                                                    data-vv-rules="required|max:150"
                                                    class="form-control"
                                                    :class="{ 'has-error' : errors.has('title-' + iso)}"
                                                />
                                                <span v-show="errors.has('title-' + iso)" class="text-danger">{{ errors.first('title-' + iso) }}</span>
                                            </div>
                                        </div>

                                        <!-- Slug App -->
                                        <div class="col-md-12">
                                            <label class="control-label" :for="`slug-${iso}`">{{ $t('Slug', { locale: this.locale }) }}*</label>
                                            <div :class="{ 'has-error' : errors.has('slug-' + iso)}">
                                                <input
                                                    type="text"
                                                    :name="`slug-${iso}`"
                                                    v-model="locales[iso].slug"
                                                    v-validate
                                                    data-vv-rules="required|max:150"
                                                    class="form-control"
                                                    :class="{ 'has-error' : errors.has('slug-' + iso)}"
                                                    @keyup="filterSlug"
                                                />
                                                <span v-show="errors.has('slug-' + iso)" class="text-danger">{{ errors.first('slug-' + iso) }}</span>
                                            </div>
                                        </div>

                                        <!-- Description App -->
                                        <div class="col-md-12">
                                            <label class="control-label" :for="`description-${iso}`">{{ $t('Description', { locale: $store.locale }) }}*</label>
                                            <div :class="{ 'has-error' : errors.has('description-' + iso)}">
                                                <textarea
                                                    :name="`description-${iso}`"
                                                    v-model="locales[iso].description"
                                                    v-validate
                                                    data-vv-rules="required|max:500"
                                                    class="form-control"
                                                    :class="{ 'has-error' : errors.has('description-' + iso)}
                                                "></textarea>
                                                <span v-show="errors.has('description-' + iso)" class="text-danger">{{ errors.first('description-' + iso) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Type App -->    
                    <div class="col-md-3 col-sm-12">
                        <div class="row form-group">
                            <!-- Title App -->
                            <div class="col-md-12">
                                <label class="control-label" for="type">{{ $t('Type', { locale: $store.locale }) }}*</label>
                                <div :class="{ 'has-error' : errors.has('type')}">
                                    <select
                                        name="type"
                                        v-model="app.type"
                                        v-validate
                                        data-vv-rules="required"
                                        class="form-control"
                                        :class="{ 'has-error' : errors.has('type')}"
                                    >
                                        <option v-for="(type, index) in this.types" :value="type.key">{{ type.key }}</option>
                                    </select>
                                    <span v-show="errors.has('type')" class="text-danger">{{ errors.first('type') }}</span>
                                </div>
                            </div>


                            <!-- Status App -->
                            <div class="col-md-12">
                                <label class="control-label" for="status">{{ $t('Status', { locale: $store.locale }) }}*</label>
                                <div :class="{ 'has-error' : errors.has('status')}">
                                    <select
                                        name="status"
                                        v-model="app.status"
                                        v-validate
                                        data-vv-rules="required"
                                        class="form-control"
                                        :class="{ 'has-error' : errors.has('status')}"
                                    >
                                        <option v-for="(status, index) in this.status" :value="status.key">{{ status.key }}</option>
                                    </select>
                                    <span v-show="errors.has('status')" class="text-danger">{{ errors.first('status') }}</span>
                                </div>
                            </div>

                            <!-- VUE Component App -->    
                            <div class="col-md-12">
                                <label class="control-label" for="vue_component">{{ $t('VUE Component', { locale: this.locale }) }}*</label>
                                <div :class="{ 'has-error' : errors.has('vue_component')}">
                                    <input
                                        type="text"
                                        name="vue_component"
                                        v-model="app.vue_component"
                                        v-validate
                                        data-vv-rules="required|max:50"
                                        class="form-control"
                                        :class="{ 'has-error' : errors.has('vue_component')}"
                                    />
                                    <span v-show="errors.has('vue_component')" class="text-danger">{{ errors.first('vue_component') }}</span>
                                </div>
                            </div>

                            <!-- Version App -->    
                            <div class="col-md-12">
                                <label class="control-label" for="version">{{ $t('Version', { locale: this.locale }) }}*</label>
                                <div :class="{ 'has-error' : errors.has('version')}">
                                    <input
                                        type="text"
                                        name="version"
                                        v-model="app.version"
                                        v-validate
                                        data-vv-rules="required|max:10"
                                        class="form-control"
                                        :class="{ 'has-error' : errors.has('version')}"
                                    />
                                    <span v-show="errors.has('version')" class="text-danger">{{ errors.first('version') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Uploaded Images App -->
                    <div class="col-md-6 col-sm-12 app-list-images">
                        <label class="control-label" for="description">{{ $t('Images', { locale: $store.locale }) }}</label>
                        
                        <draggable v-if="hasImages" :list="images" @end="moveEvent">
                            <transition-group name="list-complete">
                                <div
                                    v-for="(image, index) in images"
                                    :key="index"
                                    :id="getImageElementID(index)"
                                    class="row app-list-images-item"
                                >
                                    <div class="col-md-4 col-sm-12">
                                        <img :src="getAbosluteSrc(image.src)" />
                                        <div class="text-center">
                                            <button @click="removeImage(index, image)" type="button" class="btn btn-xs btn-danger app-images-btn-remove">
                                                <i class="fa fa-trash"></i> {{ $t('Remove', { locale: $store.locale }) }}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-8 col-sm-12">
                                        <label class="control-label" :for="index">{{ $t('Title', { locale: this.locale }) }}*</label>
                                        <div :class="{ 'has-error' : errors.has('title-image-' + index)}">
                                            <input
                                                type="text"
                                                :name="`title-image-${index}`"
                                                v-model="image.title"
                                                v-validate
                                                data-vv-rules="required|max:50"
                                                class="form-control"
                                                :class="{ 'has-error' : errors.has('title-image-' + index)}"
                                            />
                                            <span v-show="errors.has('title-image-' + index)" class="text-danger">{{ errors.first('title image-') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </transition-group>
                        </draggable>
                        <div v-else>
                            <div v-if="imagesNotFound" class="alert alert-danger">{{ $t('At least one image is required', { locale: $store.locale }) }}</div>    
                            <div v-else class="alert alert-warning">{{ $t('Images not found', { locale: $store.locale }) }}</div>
                        </div>                        
                    </div>

                    <!-- Uploader Images App -->
                    <div v-if="enableDropzone" class="col-md-6 col-sm-12">
                        <label class="control-label" for="description">{{ $t('Images Uploader', { locale: $store.locale }) }}</label>
                        <div v-show="uploadError" class="alert alert-danger">{{ $t('Error has been detected on upload image', { locale: $store.locale }) }}</div>
                        <vue2-dropzone
                            ref="appImages"
                            id="app-images"
                            name="app-images"
                            :options="dropzoneOptions"
                            :class="{ dropzoneBig: !this.isScreen('mobile') }"
                            v-on:vdropzone-sending="sendingDropzone"
                            v-on:vdropzone-success="successDropzone"
                            v-on:vdropzone-error="errorDropzone"
                            v-on:vdropzone-removed-file="removedDropzone"
                        ></vue2-dropzone>
                    </div>
                </div>
            </div>
  		</form>
  	</div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import ModalConfigVoidStructure from '../structures/ModalConfigVoidStructure'
    import AppLocaleVoidStructure from '../structures/AppLocaleVoidStructure'
    import VueScrollTo from 'vue-scrollto'
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    import draggable from 'vuedraggable'
    import slugMixin from '../includes/mixins/slugMixin'
    import cloneMixin from '../includes/mixins/cloneMixin'
    import screenMixin from '../includes/mixins/screenMixin'
    import srcMixin from '../includes/mixins/srcMixin'

    export default {
        mixins: [ slugMixin, cloneMixin, screenMixin, srcMixin ],
      	name: 'AppForm',
        components: { vue2Dropzone, draggable },
        data () {
            return {
                app: ModalConfigVoidStructure,
                types: [],
                status: [],
                images: [],
                locales: {},
                enableDropzone: false,
                currentLocaleIso: locale,
                uploadError: false,
                imagesNotFound: false,
                dropzoneOptions: {
                    method: 'post',
                    paramName: 'image',
                    acceptedFiles: 'image/*',
                    url: 'https://httpbin.org/post',
                    thumbnailWidth: 150,
                    maxFilesize: 4,
                    addRemoveLinks: true,
                    dictDefaultMessage: '<i class="fa fa-cloud-upload"></i> ' + this.$t('Upload me', { locale: this.locale }),
                    dictRemoveFile: this.$t('Remove image', { locale: this.locale })
                }
            }
        },
        computed: {
            ...mapState([ 'csrfToken', 'locale', 'supportedLocales', 'routes' ]),
            ...mapState( 'modalModule', [ 'isVisible', 'currentComponent', 'config', 'data' ]),

            hasImages () {
                return this.images.length
            },
        },
      	methods: {
            ...mapActions([ 'pushRouteToArray' ]),
            ...mapActions('modalModule', {
                hideModal: 'hide'
            }),

            ...mapActions('appModule', {
                storeApp: 'store',
                updateApp: 'update',
                destroyApp: 'destroy'
            }),

      		callEvent ( event ) {
      			this[event]()
      		},

            filterSlug () {
                this.locales[this.currentLocaleIso].slug = this.sluglify(this.locales[this.currentLocaleIso].slug)
            },
            
            initForm () {
                const app = this.data.app

                for ( let property in app ) {
                    if ( app.hasOwnProperty(property) ) {
                        this.app[property] = app[property]
                    }
                }

                this.pushImages()            
                this.pushLocales()            

                this.dropzoneOptions.url = this.routes.routeAppImagesUpload
                this.enableDropzone = true
            },

            pushImages () {
                this.images = []

                this.app.images.forEach((image, key) => {
                    this.images.push({
                        id: image.id,
                        src: image.src,
                        title: image.title,
                        priority: image.priority
                    })
                });
            },

            pushLocales () {
                this.locales = {}

                for ( let iso in this.supportedLocales ) {
                    let localeSelected = this.clone(AppLocaleVoidStructure)

                    this.app.locales.forEach((locale, key) => {

                        if ( locale.lang === iso ) {
                            for ( let property in locale ) {
                                if ( locale.hasOwnProperty(property) ) {
                                    localeSelected[property] = locale[property]
                                }
                            }
                        }
                    })

                    this.locales[iso] = localeSelected
                }
            },

      		saveEvent () {
                new Promise((resolve, reject) =>{
                    this.validateBeforeSubmitApp(resolve, reject)
                }).then((response) => {
                    if ( this.app.id === null ) {
                        this.storeApp(response.app)

                        response.routes.forEach((route, key) => {
                            this.pushRouteToArray(route)
                        })
                    } else {
                        this.updateApp(response.app)
                    }
                    this.hideModal({ $modal: this.$modal })
                }).catch((error) => {
                    console.error(error)
                })
      		},

      		removeEvent () {
                let context = this

                axios.post(this.routes.routesAppDestroy[this.app.id], {
                    locale: this.locale
                }).then(function (response) {
                    if ( response ) {
                        context.destroyApp( context.app )
                        context.hideModal({ $modal: context.$modal })
                    }
                }).catch(function (error) {
                    console.error(error)
                })
      		},

            moveEvent (ev) {
                this.reorder()
            },

            reorder () {
                this.images.forEach((image, key) => {
                    image.priority = key
                });
            },

            validateBeforeSubmitApp (resolve, reject) {
                this.$validator.validateAll().then(result => {
                    let validateImages = this.images.length > 0

                    if ( !result || !validateImages) {
                        if ( !validateImages ) {
                            this.imagesNotFound = true
                        }

                        this.toogleFirstFormLocaleNotValidate()
                        
                        this.$root.generateErrorNotify(this.$t('Form not validate', { locale: this.locale }))
                        reject('Form not validate')
                    } else {
                        this.submitApp(resolve, reject)
                    }
                }).catch(error => {
                    reject(error)
                })
            },

            toogleFirstFormLocaleNotValidate () {
                let firstFormLocaleNotValidate = null

                this.app.locales.forEach((locale, kL) => {
                    if ( firstFormLocaleNotValidate === null ) {
                        this.errors.items.forEach((item, kI) => {
                            if ( item.field === 'title-' + locale.lang || item.field === 'slug-' + locale.lang || item.field === 'description-' + locale.lang ) {
                                firstFormLocaleNotValidate = locale.lang
                            }
                        })
                    }
                })

                if ( firstFormLocaleNotValidate !== null ) {
                    this.currentLocaleIso = firstFormLocaleNotValidate
                }
            },

            submitApp (resolve, reject) {
                let action = ( this.app.id === null ) ? this.routes.routeAppStore : this.routes.routesAppUpdate[this.app.id]

                axios.post(action, {
                    app: this.app,
                    images: this.images,
                    locales: this.locales
                }).then(function (response) {
                    resolve(response.data)
                }).catch(function (error) {
                    reject(error)
                })
            },

            removeImage ( index, image ) {
                if ( typeof image.dropzone !== typeof undefined ) {
                    this.$refs.appImages.removeFile(image.dropzone)
                }

                this.images.splice(index, 1);
            },

            getImageElementID ( index ) {
                return 'image-' + index
            },

            // Dropzone Events
            sendingDropzone ( file, xhr, formData ) {
                this.uploadError = false
                formData.append('_token', this.csrfToken);
            },

            successDropzone ( file, response ) {
                let context = this

                if ( response.res ) {
                    context.imagesNotFound = false
                    context.images.push({
                        id: null,
                        priority: ( context.images.length > 0) ? context.images[context.images.length - 1].priority + 1 : 0,
                        src: response.data.image,
                        title: '',
                        dropzone: file
                    })

                    setTimeout(function(){
                        VueScrollTo.scrollTo('#' + context.getImageElementID(context.images.length - 1), 500, {
                            container: ".modal-body",
                            duration: 500,
                            easing: "ease",
                            offset: 0,
                            cancelable: true,
                            onDone: false,
                            onCancel: false,
                            x: false,
                            y: true
                        })
                    }, 1000)
                } else {
                    context.errorDropzone( file )
                }
            },

            errorDropzone ( file ) {
                this.$refs.appImages.removeFile(file)
                this.uploadError = true
            },

            removedDropzone ( file, error, xhr ) {
                let images = []
                
                this.images.forEach((image, key) => {
                    if ( typeof image.dropzone === typeof undefined || image.dropzone.upload.uuid !== file.upload.uuid) {
                        images.push(image)
                    }
                })

                this.images = images
            },

            setAppLocaleISOCurrent ( iso ) {
                this.currentLocaleIso = iso
            }
      	},
        
        mounted () {
            this.types = this.data.types
            this.status = this.data.status

            this.initForm()
        }
    }
</script>

<style scoped>
    .app-list-images .app-list-images-item {
        transition: all 1s;
        cursor: move;
        margin: 10px 0;
        padding: 5px 0;
        background: #ccd0d2;
    }

	.app-list-images .app-list-images-item img {
        width: 100%
    }

    .app-list-images .app-list-images-item .app-images-btn-remove {
        margin-top: 5px;
    }

    .dropzoneBig {
        min-height: 400px
    }
</style>
