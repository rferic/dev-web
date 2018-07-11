<template>
  <div class="app-item col-lg-3 col-md-4 col-sm-12" @click="$emit('callFormEvent', app)">
  	<div v-if="!isVoid" :class="classApp" class="app-container" v-images-loaded:on.progress="reportTopHeight">
	    <div class="app-head">
	    	<h3>{{ app.title }}  <span class="badge app-badge app-version" v-bind:style="{ backgroundColor: versionColor }">{{ app.version }}</span></h3>
	    	<span><b>{{ $t('Users', { locale: locale }) }}: {{ usersCount }}</b></span>
	  	</div>
	  	<img v-if="image !== null" :src="image.src" :title="image.title" class="app-image" />
	  	<div class="app-content">
	  		<div class="description">{{ app.description }}</div>
  		</div>
		<div class="app-data">
			<div v-if="statusCurrent !== null" class="app-data-badge col-lg-6 text-center">
				<span class="app-badge-label"></span>{{ $t('Status', { locale: locale }) }}<br><span class="badge app-badge" v-bind:style="{ backgroundColor: statusCurrent.color }">{{ statusCurrent.key}}</span>
			</div>
			<div v-if="typeCurrent !== null" class="app-data-badge col-lg-6 text-center">
				<span class="app-badge-label"></span>{{ $t('Type', { locale: locale }) }}<br><span class="badge app-badge" v-bind:style="{ backgroundColor: typeCurrent.color }">{{ typeCurrent.key}}</span>
			</div>
			<div class="app-data-component col-lg-12 text-center">{{ $t('Component', { locale: locale }) }}: {{ app.vue_component }}</div>
			<div class="clearfix"></div>
		</div>
  	</div>
  	<div v-else :class="classApp" class="app-container">
		<div class="app-content">{{ $t('Register a new APP', { locale: locale }) }}</div>
		<i class="fa fa-plus"></i>
  	</div>
  </div>
</template>

<script>
import imagesLoaded from 'vue-images-loaded'

export default {
  name: 'AppItem',
  props: [ 'locale', 'app', 'types', 'status' ],

  directives: { imagesLoaded },

  data () {
  	return {
  		statusCurrent: null,
  		typeCurrent: null,
  	}
  },

  computed: {
    image () {
    	if ( this.app.images.length > 0 ){
    		return this.app.images[0]
    	}

    	return null
    },
    versionColor () {
		return this.statusCurrent !== null ? this.statusCurrent.color : ''
    },
    usersCount () {
    	return this.app.users.length
    },
    isVoid () {
    	return this.app.id === null
    },
    classApp () {
    	return this.isVoid ? 'is-void' : 'is-registered'
    }
  },

  methods: {
  	setStatusCurrent () {
    	this.statusCurrent = null
    	
    	if ( this.status.length > 0 ){
    		this.status.forEach( (item) => {
    			if ( item.key === this.app.status ) {
	    			this.statusCurrent = {
	    				key: item.key,
	    				color: item.color
	    			}
    			}
    		})
    	}
    },
    setTypeCurrent () {
    	this.typeCurrent = null
    	
    	if ( this.types.length > 0 ){
    		this.types.forEach( (item) => {
    			if ( item.key === this.app.type ) {
	    			this.typeCurrent = {
	    				key: item.key,
	    				color: item.color
	    			}
    			}
    		})
    	}
    },
    reportTopHeight () {
    	this.$emit('refreshTopHeightEvent', this.$el.clientHeight)
    }
  },

  mounted () {
  	this.setStatusCurrent()
  	this.setTypeCurrent()
  }
}
</script>

<style scoped>
	.app-item {
		transition: padding .2s;
	}

	.app-item:hover {
		cursor: pointer;
		padding: 0;
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

	.app-container:hover {

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
