const appModule = {
	namespaced: true,
	state: {
		apps: []
	},
	actions: {
		setList ( { commit }, payload ) {
			commit('SET_LIST', payload)
		},

		store ( { commit }, appData ) {
			commit('STORE', appData)
		},

		update ( { commit }, appData ) {
			commit('UPDATE', appData)
		},

		destroy ( { commit }, app ) {
			commit('DESTROY', app)
		},

		addUser ( { commit }, params) {
			commit('ADD_USER', params)
		},

		refreshUserStatus ( { commit }, params ) {
			commit('REFRESH_USER_STATUS', params)
		},

		revokeUser ( { commit }, params ) {
			commit('REVOKE_USER', params)
		}
	},
	mutations: {
		SET_LIST: ( state, payload ) => {
			state.apps = payload.apps
		},

		STORE: ( state, appData ) => {
			state.apps.push(appData)
		},

		UPDATE: ( state, appData ) => {
			state.apps.forEach((app, key) => {
				if ( app.id === appData.id ) {
					app.type = appData.type
					app.status = appData.status
					app.version = appData.version
					app.vue_component = appData.vue_component
					app.locales = []
					app.images = []

					appData.locales.forEach((locale, key) => {
						app.locales.push({
							id: locale.id,
							app_id: locale.app_id,
							lang: locale.lang,
							slug: locale.slug,
							title: locale.title,
							description: locale.description
						})
					})

					appData.images.forEach((image, key) => {
						app.images.push({
							id: image.id,
							app_id: image.app_id,
							src: image.src,
							title: image.title,
							priority: image.priority
						})
					})
				}
			})
		},

		DESTROY: ( state, appData ) => {
			state.apps.forEach((app, key) => {
				if ( app.id === appData.id ) {
					state.apps.splice(key, 1);
				}
			})
		},

		ADD_USER: ( state, params ) => {
			state.apps.forEach((app, key) => {
				if ( params.app.id === app.id ) {
					params.app.users.push(params.user)
				}
			})
		},

		REFRESH_USER_STATUS: ( state, params ) => {
			state.apps.forEach((app, kapp) => {
				if ( app.id === params.app.id ) {
					app.users.forEach((user, kuser) => {
						if ( user.id === params.user.id ) {
							user.pivot.active = params.active ? 1 : 0
						}
					})
				}
			})
		},

		REVOKE_USER: ( state, params ) => {
			state.apps.forEach((app, kapp) => {
				if ( app.id === params.app.id ) {

					app.users.forEach((user, kuser) => {
						if ( user.id === params.user.id ) {
							state.apps[kapp].users.splice(kuser, 1);
						}
					})

				}
			})
		}
	}
}

export default appModule