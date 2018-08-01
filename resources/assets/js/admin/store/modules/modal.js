import ModalConfigVoidStructure from '../../structures/ModalConfigVoidStructure'

const modalModule = {
	namespaced: true,
	state: {
		isVisible: false,
		currentComponent: '',
		config: ModalConfigVoidStructure,
		data: {}
	},
	actions: {
		show ( { commit }, payload ) {
			commit('SHOW', payload)
			payload.$modal.show('modalDynamic')
		},

		hide ( { commit }, payload ) {
			payload.$modal.hide('modalDynamic')
			commit('HIDE')
		}
	},
	mutations: {
		SHOW: ( state, payload ) => {
			state.currentComponent = payload.component
			state.data = payload.data
			state.config = payload.config
			state.isVisible = true
		},

		HIDE: ( state ) => {
			state.isVisible = false
			state.currentComponent = ''
			state.config = ModalConfigVoidStructure
			state.data = {}
		}
	}
}

export default modalModule