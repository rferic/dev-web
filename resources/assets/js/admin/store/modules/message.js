const appModule = {
	namespaced: true,
	state: {
		messages: []
	},
	actions: {
		setList ( { commit }, list ) {
			commit('SET_LIST', list)
		}
	},
	mutations: {
		SET_LIST: ( state, list ) => {
			state.messages = list.messages
		}
	}
}

export default appModule