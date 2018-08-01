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
						console.log(image)
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
		}
	}
}

export default appModule