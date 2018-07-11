<template>
  <div class="row">
    <app-item
        :app="appVoidStructure"
        :types="types"
        :status="status"
        :locale="locale"
        v-bind:style="{ minHeight: topHeightCSS }"
        @refreshTopHeightEvent="refreshTopHeight"
        @callFormEvent="callForm"
    ></app-item>
    <app-item
        v-for="(app, index) in apps"
        :key="index"
        :app="app"
        :types="types"
        :status="status"
        :locale="locale"
        v-bind:style="{ minHeight: topHeightCSS }"
        @refreshTopHeightEvent="refreshTopHeight"
        @callFormEvent="callForm"
    ></app-item>
  </div>
</template>

<script>
import AppItem from './AppItem'
import appVoidStructure from '../structures/appVoidStructure'

export default {
  name: 'AppsLists',
  props: [ 'locale', 'apps_json', 'types_json', 'status_json' ],
  components: {
    AppItem
  },

  data () {
    return {
      apps: JSON.parse(this.apps_json),
      types: JSON.parse(this.types_json),
      status: JSON.parse(this.status_json),
      topHeight: 0,
      appVoidStructure
    }
  },

  computed: {
    topHeightCSS () {
        return this.topHeight > 0 ? this.topHeight + 'px' : ''
    }
  },

  methods: {
    refreshTopHeight ( height ) {
        if ( height > this.topHeight ) {
            this.topHeight = height
        }
    },
    callForm ( app ) {
        console.log(app)
    }
  }
}
</script>
