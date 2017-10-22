<template>
  <div>
    Hello World
  </div>
</template>

<script>
  export default {
    name: 'PageForm',
    props: [
      'supported_locales_json',
      'page_locales_json',
      'locale',
    ],
    data () {
      return {
        loading: false,
        pagesLocales: [],
      }
    },
    computed: {
      supportedLocales () {
        return JSON.parse(this.supported_locales_json)
      },
      page_locales () {
        return JSON.parse(this.page_locales_json)
      },
      pageExist () {
        return this.page.length > 0
      }
    },
    mounted () {
      this.pagesLocales = JSON.parse(this.page_locales_json)
    },
    methods: {}
  }
</script>
