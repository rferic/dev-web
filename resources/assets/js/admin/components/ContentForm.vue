<template>
  <div class="bg-aqua-active color-palette">
    <form autocomplete="off" @submit.prevent="validateBeforeSubmit" class="form-horizontal">
      <div class="col-sm-12">
        <h4>{{ $t('Edit content')}}</h4>
      </div>
      
      <div class="row">
        <!-- Key Content -->
        <div class="form-group col-md-6 col-sm-12">
          <label class="control-label col-lg-2 col-md-4 col-sm-6" for="key">{{ $t('Key', { locale: locale }) }}*</label>
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
          <label class="control-label col-lg-2 col-md-4 col-sm-6" for="key">{{ $t('ID', { locale: locale }) }}</label>
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
          <label class="control-label col-lg-2 col-md-4 col-sm-6" for="class_html">{{ $t('Class', { locale: locale }) }}</label>
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
          <label class="control-label col-lg-1 col-md-2 col-sm-6">{{ $t('HTML', { locale: locale }) }}</label>
          <div class="col-lg-11 col-md-10 col-sm-6">
            <editor height="300px" :content="content.text" theme="chrome" lang="html" :sync="true" @change="changeContentText"></editor>
          </div>
        </div>
        
        <!-- Header Inject Content -->
        <div class="form-group col-md-6 col-sm-12">
          <label class="control-label col-lg-2 col-md-4 col-sm-6">{{ $t('CSS', { locale: locale }) }}</label>
          <div class="col-lg-10 col-md-8 col-sm-6">
            <editor height="300px" :content="content.header_inject" theme="chrome" lang="css" :sync="true" @change="changeContentHeaderInject"></editor>
          </div>
        </div>
        
        <!-- Footer Inject Content -->
        <div class="form-group col-md-6 col-sm-12">
          <label class="control-label col-lg-2 col-md-4 col-sm-6">{{ $t('JS', { locale: locale }) }}</label>
          <div class="col-lg-10 col-md-8 col-sm-6">
            <editor height="300px" :content="content.footer_inject" theme="chrome" lang="javascript" :sync="true" @change="changeContentFooterInject"></editor>
          </div>
        </div>
        
        <p class="clearfix"></p>
        
        <div class="col-md-12 col-sm-12">
          <div class="col-md-6 col-sm-12">
            <button class="btn btn-danger btn-xl">
              {{ $t('Cancel', { locale: locale }) }}
            </button>
          </div>
          <div class="col-md-6 col-sm-12">
            <button class="btn btn-success btn-xl pull-right" type="submit">
              <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ $t('Save', { locale: locale }) }}
            </button>
          </div>
        </div>
        <p class="clearfix"></p>
      </div>
    </form>
  </div>
</template>

<script>
  import contentVoidStructure from '../structures/contentVoidStructure'
  import editor from 'ace-vue2'
  import 'brace/mode/html'
  import 'brace/mode/javascript'
  import 'brace/mode/css'
  import 'brace/theme/chrome'
  
  export default {
    name: 'ContentForm',
    props: [ 'locale', 'item' ],
    components: {
      editor
    },
    
    data () {
      return {
        content: contentVoidStructure
      }
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
      
      validateBeforeSubmit () {}
    },
    
    mounted () {
      if (this.item !== null) {
        this.content = this.item
      }
    }
  }
</script>

<style>
  form {
    background-color: #4984933b;
  }
</style>
