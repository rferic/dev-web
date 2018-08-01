<template>
  	<div v-if="isMounted" class="app-item col-lg-3 col-md-4 col-sm-12" @click="$emit('callFormEvent', app)">
  		<div v-if="!isNewApp" :class="classApp" class="app-container" v-images-loaded:on.progress="reportTopHeight">
	    	<div class="app-head">
	    		<h3>{{ appLocale.title }}  <span class="badge app-badge app-version" v-bind:style="{ backgroundColor: versionColor }">{{ app.version }}</span></h3>
	    		<span><b>{{ $t('Users', { locale: this.locale }) }}: {{ usersCount }}</b></span>
	  		</div>
	  		<img v-if="image !== null" :src="getAbosluteSrc(image.src)" :title="image.title" class="app-image" />
	  		<div class="app-content">
	  			<div class="description">{{ appLocale.description }}</div>
  			</div>
			<div class="app-data">
				<div v-if="statusCurrent !== null" class="app-data-badge col-lg-6 text-center">
					<span class="app-badge-label"></span>{{ $t('Status', { locale: this.locale }) }}<br><span class="badge app-badge" v-bind:style="{ backgroundColor: statusCurrent.color }">{{ statusCurrent.key}}</span>
				</div>
				<div v-if="typeCurrent !== null" class="app-data-badge col-lg-6 text-center">
					<span class="app-badge-label"></span>{{ $t('Type', { locale: this.locale }) }}<br><span class="badge app-badge" v-bind:style="{ backgroundColor: typeCurrent.color }">{{ typeCurrent.key}}</span>
				</div>
				<div class="app-data-component col-lg-12 text-center">{{ $t('Component', { locale: this.locale }) }}: {{ app.vue_component }}</div>
				<div class="clearfix"></div>
			</div>
  		</div>
  		<div v-else :class="classApp" class="app-container">
			<div class="app-content">{{ $t('Register a new APP', { locale: this.locale }) }}</div>
			<i class="fa fa-plus"></i>
  		</div>
  	</div>
</template>

<script>
	import imagesLoaded from 'vue-images-loaded'
	import { mapState } from 'vuex'
	import screenMixin from '../includes/mixins/screenMixin'
	import cloneMixin from '../includes/mixins/cloneMixin'
	import srcMixin from '../includes/mixins/srcMixin'
	import AppVoidStructure from '../structures/AppVoidStructure'

	export default {
		mixins: [ screenMixin, cloneMixin, srcMixin ],
	  	name: 'AppItem',
	  	props: [ 'appId', 'types', 'status' ],

	  	directives: { imagesLoaded },

	  	data () {
	  		return {
	  			isMounted: false
	  		}
	  	},

	  	computed: {
			...mapState([ 'locale' ]),
	        ...mapState('appModule', { apps: 'apps'}),
			app () {
				let appFind = AppVoidStructure

				this.apps.forEach((app, key) => {
					if ( app.id === this.appId) {
						appFind = app
					}
				})

				return appFind
			},
			appLocale () {
				let appLocaleFind = {
	    			title: '',
	    			description: '',
	    			slug: ''
	    		}

				this.app.locales.forEach((appLocale, key) => {
					if ( appLocale.lang === this.locale ) {
						appLocaleFind = appLocale
					}
				})

				return appLocaleFind
			},
			statusCurrent () {
				let statusCurrent = null

				if ( this.status.length > 0 ){
	    			this.status.forEach( (item) => {
	    				if ( item.key === this.app.status ) {
		    				statusCurrent = {
		    					key: item.key,
		    					color: item.color
		    				}
	    				}
	    			})
	    		}

	    		return statusCurrent
			},
			typeCurrent () {
				let typeCurrent = null
				if ( this.types.length > 0 ){
	    			this.types.forEach( (item) => {
	    				if ( item.key === this.app.type ) {
		    				typeCurrent = {
		    					key: item.key,
		    					color: item.color
		    				}
	    				}
	    			})
	    		}

	    		return typeCurrent
			},
	    	image () {
	    		let image = null
	    		if ( this.app.images.length > 0 ){
		    		image = this.app.images[0]
	    		}

	    		return image
	    	},
	    	versionColor () {
				return this.statusCurrent !== null ? this.statusCurrent.color : ''
	    	},
	    	usersCount () {
	    		if ( this.typeCurrent.key === 'private' ) {
	    			return this.app.users.length
    			} else if ( this.typeCurrent.key === 'protected' ) {
    				return 'All registered users'
	    		} else {
	    			return "All users"
	    		}
	    		
	    	},
	    	classApp () {
		    	return this.isNewApp ? 'is-void' : 'is-registered'
	    	},
	        isNewApp () {
	        	return this.appId === "isNew"
	        }
	  	},

	  	methods: {
	    	reportTopHeight () {
	    		if ( !this.isScreen('mobile') ) {
	    			this.$emit('refreshTopHeightEvent', this.$el.clientHeight)
	    		}
	    	}
	  	},

	  	mounted () {
	  		this.isMounted = true
	  		
			window.addEventListener('resize', () => {
				this.reportTopHeight()
			})
	  	}
	}
</script>

<style scoped>
	.app-item {
		transition: opacity .2s;
	}

	.app-item:hover {
		cursor: pointer;
		opacity: 0.8;
	}

	.app-container {
		border: 1px solid #796b6f;
		border-radius: 10px;
	    margin: 10px auto;
	    overflow: hidden;
	    background: white;
	    -webkit-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.5);
		-moz-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.5);
		box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.5);
	}

	.is-void.app-container {
		min-height: 200px;
		opacity: .6;
	}

	.is-void.app-container i.fa {
		width: 100%;
	    font-size: 100pt;
	    text-align: center;
	}

	.app-head,
	.app-content {
		padding: 0 4%;
		text-align: center;
	}

	.app-content {
		font-size: 12px;
		margin: 4%;
	}

	.app-data {
		color: white;
		font-size: 10px;
		background: #0c5494;
    	padding: 10px 0;
	}

	h3 {
		font-size: 20px;
	}

	.app-image {
		max-width: 100%;
	}

	.app-badge,
	.app-version {
		font-size: 12px;
	}

	.app-data-component {
		font-size: 14px;
		margin-top: 10px;
		font-style: italic;
	}
</style>
