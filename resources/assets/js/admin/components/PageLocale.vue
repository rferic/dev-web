<template>
  <div class="pageLocale">
    <toggle-button
      color="#038e38"
      :value="pageLocale.status"
      :sync="true"
      :labels="true"
      @change="onSwitchChangeEventHandler"
    />
    <form autocomplete="off" @submit.prevent="validateBeforeSubmit" class="form-horizontal" v-if="pageLocale.status">
      <!-- Title Locale -->
      <div class="form-group">
        <label class="control-label col-md-4" for="title">{{ $t('Title', { locale: locale }) }}</label>
        <div class="col-md-8" :class="{ 'has-error' : errors.has('title')}">
          <input
            type="text"
            name="title"
            v-model="pageLocale.title"
            v-validate
            data-vv-rules="required|max:100"
            class="form-control"
            :class="{ 'has-error' : errors.has('title')}" />
          <span v-show="errors.has('title')" class="text-danger">{{ errors.first('title') }}</span>
        </div>
      </div>

      <button v-if="showSubmit" class="btn btn-success btn-xl pull-right" type="submit">
        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ $t('Save', { locale: locale }) }}
      </button>
      <p class="clearfix"></p>
      
    </form>
  </div>
</template>

<script>
  export default {
    name: 'PageLocale',
    props: [
      'locale',
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