<template>
	<modal
		class="modal-dynamic"
		name="modalDynamic"
		:adaptive="true"
		height="90%"
		width="90%"
		transition="fade"
		transition-mode="out-in"
		@before-open="beforeOpen"
		@before-close="beforeClose"
		@closed="afterClose"
		>

		<div class="modal-container" :class="{ 'headIsVisible': config.head.isVisible, 'footerIsVisible': config.footer.isVisible }">
			<div v-show="config.head.isVisible" class="modal-head">
				<i @click="$modal.hide('modalDynamic')" class="pull-right fa fa-close"></i>
				<h2>{{ config.head.title }}</h2>
	    	</div>
	    	<div class="modal-body">
	    		<div class="modal-content">
					<component :is="currentComponent" ref="content"></component>
				</div>
			</div>
			<div v-show="config.footer.isVisible" class="modal-footer">
				<button v-for="button in config.footer.buttons" @click="callEvent(button)" class="modal-button" :class="button.class">
					<i v-if="button.icon !== false" :class="button.icon"></i>
					{{ button.label }}
				</button>
			</div>
		</div>
	</modal>
</template>

<script>
	import { mapState, mapActions } from 'vuex'
	import AppForm from './AppForm'

	export default {
	    name: 'ModalDynamic',
	    components: { AppForm },
	    data () {
    		return {}
  		},
	    computed: {
	    	...mapState([ 'locale' ]),
	    	...mapState( 'modalModule', [ 'isVisible', 'currentComponent', 'config', 'data' ]),
	    	footerIsVisible () {
	    		return this.config.footer.isVisible && this.config.footer.buttons.length > 0
	    	}
	    },
	  	methods: {
	  		...mapActions('modalModule', {
	            hideModal: 'hide'
	        }),
		    beforeOpen (event) {
		    	document.getElementsByTagName('body')[0].classList.add('fixed')
		    },
		    beforeClose (event) {
		    	document.getElementsByTagName('body')[0].classList.remove('fixed')
		    },
		    afterClose (event) {},
		    dialogEvent (eventName) {},
		    callEvent (button) {
		    	this.$refs.content.callEvent(button.click)
		    }
		}
	}
</script>

<style scoped>
	.modal-dynamic {
		z-index: 9999;
	}
</style>