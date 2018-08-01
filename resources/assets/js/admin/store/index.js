import Vue from 'vue'
import Vuex from 'vuex'

import modalModule from './modules/modal'
import appModule from './modules/app'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
	state: {
		csrfToken: csrfToken,
		locale: locale,
		supportedLocales: supportedLocales,
		routes: typeof routes !== typeof undefined ? routes : {}
	},
	actions: {
		pushRouteToArray ( { commit }, params ) {
			commit('PUSH_ROUTE_TO_ARRAY', params)
		}
	},
	mutations: {
		PUSH_ROUTE_TO_ARRAY: ( state, params ) => {
			if ( typeof state.routes[params.type] !== typeof undefined ) {
				state.routes[params.type][params.id] = params.route
			}
		}
	},
	modules: {
    	modalModule,
    	appModule
  	},
  	strict: debug
})

