<template>
  <div class="pageLocale">
    <toggle-button
      color="#038e38"
      :value="pageLocale.status"
      :sync="true"
      :labels="true"
      @change="onSwitchChangeEventHandler"
    />
  </div>
</template>

<script>
  export default {
    name: 'PageLocale',
    props: [
      'pageLocale'
    ],
    
    methods: {
      onSwitchChangeEventHandler (ev) {
        this.pageLocale.status = ev.value
      }
    }
  }
</script>

<style scoped>
  .vue-js-switch {
    font-size: 16px;
  }
</style>