<template>
  <div class="bg-aqua-active color-palette">
    <form autocomplete="off" @submit.prevent="validateBeforeSubmitContent" class="form-horizontal form-content">
      <div class="col-sm-12">
        <h4>{{ $t('Edit content')}}</h4>
      </div>
      
      <div class="row">
        <!-- Key Content -->
        <div class="form-group col-md-6 col-sm-12">
          <label class="control-label col-lg-2 col-md-4 col-sm-6" for="key">{{ $t('Key', { locale: this.locale }) }}*</label>
          <div class="col-lg-10 col-md-8 col-sm-6" :class="{ 'has-error' : errors.has('key')}">
            <input
              type="text"
              name="key"
              v-model="content.key"
              v-validate
              data-vv-rules="required|max:50"
              class="form-control"
              :class="{ 'has-error' : errors.has('key')}" />
            <span v-show="errors.has('key')" class="text-danger">{{ errors.first('key') }}</span>
          </div>
        </div>
      </div>
      
      <div class="row">
        <!-- ID Content -->
        <div class="form-group col-md-6 col-sm-12">
          <label class="control-label col-lg-2 col-md-4 col-sm-6" for="id_html">{{ $t('ID', { locale: this.locale }) }}</label>
          <div class="col-lg-10 col-md-8 col-sm-6" :class="{ 'has-error' : errors.has('id_html')}">
            <input
              type="text"
              name="id_html"
              v-model="content.id_html"
              v-validate
              data-vv-rules="max:50"
              class="form-control"
              :class="{ 'has-error' : errors.has('id_html')}" />
            <span v-show="errors.has('id_html')" class="text-danger">{{ errors.first('id_html') }}</span>
          </div>
        </div>
        <!-- Class Content -->
        <div class="form-group col-md-6 col-sm-12">
          <label class="control-label col-lg-2 col-md-4 col-sm-6" for="class_html">{{ $t('Class', { locale: this.locale }) }}</label>
          <div class="col-lg-10 col-md-8 col-sm-6" :class="{ 'has-error' : errors.has('class_html')}">
            <input
              type="text"
              name="class_html"
              v-model="content.class_html"
              v-validate
              data-vv-rules="max:150"
              class="form-control"
              :class="{ 'has-error' : errors.has('class_html')}" />
            <span v-show="errors.has('class_html')" class="text-danger">{{ errors.first('class_html') }}</span>
          </div>
        </div>
        
        <!-- HTML Content -->
        <div class="form-group col-md-12 col-sm-12">
          <label class="control-label col-lg-1 col-md-2 col-sm-6">{{ $t('HTML', { locale: this.locale }) }}</label>
          <div class="col-lg-11 col-md-10 col-sm-6">
            <editor height="300px" :content="content.text" theme="chrome" lang="html" :sync="true" @change="changeContentText"></editor>
          </div>
        </div>
        
        <!-- Header Inject Content -->
        <div class="form-group col-md-6 col-sm-12">
          <label class="control-label col-lg-2 col-md-4 col-sm-6">{{ $t('CSS', { locale: this.locale }) }}</label>
          <div class="col-lg-10 col-md-8 col-sm-6">
            <editor height="300px" :content="content.header_inject" theme="chrome" lang="css" :sync="true" @change="changeContentHeaderInject"></editor>
          </div>
        </div>
        
        <!-- Footer Inject Content -->
        <div class="form-group col-md-6 col-sm-12">
          <label class="control-label col-lg-2 col-md-4 col-sm-6">{{ $t('JS', { locale: this.locale }) }}</label>
          <div class="col-lg-10 col-md-8 col-sm-6">
            <editor height="300px" :content="content.footer_inject" theme="chrome" lang="javascript" :sync="true" @change="changeContentFooterInject"></editor>
          </div>
        </div>
        
        <p class="clearfix"></p>
        
        <div class="col-md-12 col-sm-12">
          <div class="col-md-6 col-sm-12">
            <button class="btn btn-danger btn-xl" @click="cancelEdit" type="button">
              {{ $t('Cancel', { locale: this.locale }) }}
            </button>
          </div>
          <div class="col-md-6 col-sm-12">
            <button class="btn btn-success btn-xl pull-right" type="submit">
              <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ $t('Save', { locale: this.locale }) }}
            </button>
          </div>
        </div>
        <p class="clearfix"></p>
      </div>
    </form>
  </div>
</template>

<script>
  import editor from 'ace-vue2'
  import 'brace/mode/html'
  import 'brace/mode/javascript'
  import 'brace/mode/css'
  import 'brace/theme/chrome'
  import { mapState } from 'vuex'
  
  export default {
    name: 'ContentForm',
    props: [ 'content' ],
    components: {
      editor
    },
    
    data () {
      return {
        dataOrigin: {}
      }
    },
    
    computed : {
      ...mapState([ 'locale' ])
    },

    methods: {
      changeContentText (value) {
        this.content.text = value
      },
      
      changeContentHeaderInject (value) {
        this.content.header_inject = value
      },
      
      changeContentFooterInject (value) {
        this.content.footer_inject = value
      },
      
      cancelEdit () {
        this.content.key = this.dataOrigin.key
        this.content.id_html = this.dataOrigin.id_html
        this.content.class_html = this.dataOrigin.class_html
        this.content.text = this.dataOrigin.text
        this.content.header_inject = this.dataOrigin.header_inject
        this.content.footer_inject = this.dataOrigin.footer_inject
        
        this.$emit('cancelEditContentEvent', this.content)
      },
      
      validateBeforeSubmitContent () {
        this.$validator.validateAll().then(result => {
          if ( !result ) {
            this.$root.generateErrorNotify(this.$t('Form not validate', { locale: this.locale }))
          } else {
            this.$emit('saveEditContentEvent', this.content)
          }
        }).catch(error => {
          console.error(error)
        })
      }
    },
    
    mounted () {
      this.dataOrigin = {
        page_locale_id: this.content.page_locale_id,
        key: this.content.key,
        id_html: this.content.id_html,
        class_html: this.content.class_html,
        text: this.content.text,
        header_inject: this.content.header_inject,
        footer_inject: this.content.footer_inject
      }
    }
  }
</script>

<style>
  form.form-content {
    background-color: #4984933b;
  }
</style>
