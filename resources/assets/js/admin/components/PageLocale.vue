<template>
  <div class="pageLocale">
    <toggle-button
      color="#038e38"
      :value="pageLocale.status"
      :sync="true"
      :labels="true"
      @change="onSwitchChangeEventHandler"
      class="pull-right"
    />
    <form autocomplete="off" @submit.prevent="validateBeforeSubmit" class="form-horizontal" v-if="pageLocale.status">
      <div class="col-sm-12">
        <h3>{{ $t('Information')}}</h3>
      </div>
      
      <div class="row">
        <!-- Title Locale -->
        <div class="form-group col-md-6 col-sm-12">
          <label class="control-label col-lg-2 col-md-4 col-sm-6" for="title">{{ $t('Title', { locale: locale }) }}*</label>
          <div class="col-lg-10 col-md-8 col-sm-6" :class="{ 'has-error' : errors.has('title')}">
            <input
              type="text"
              name="title"
              v-model="item.title"
              v-validate
              data-vv-rules="required|max:100"
              class="form-control"
              :class="{ 'has-error' : errors.has('title')}" />
            <span v-show="errors.has('title')" class="text-danger">{{ errors.first('title') }}</span>
          </div>
        </div>

        <!-- Slug Locale -->
        <div class="form-group col-md-6 col-sm-12">
          <label class="control-label col-lg-2 col-md-4 col-sm-6" for="slug">{{ $t('Slug', { locale: locale }) }}*</label>
          <div class="col-lg-10 col-md-8 col-sm-6 col-md-8" :class="{ 'has-error' : errors.has('slug')}">
            <input
              type="text"
              name="slug"
              v-model="item.slug"
              v-validate
              data-vv-rules="required"
              class="form-control"
              @keyup="filterSlug"
              :class="{ 'has-error' : errors.has('slug')}" />
            <span v-show="errors.has('slug')" class="text-danger">{{ errors.first('slug') }}</span>
          </div>
        </div>
      </div>
      
      <div class="row">
        <!-- Layout Locale -->
        <div class="form-group col-md-6 col-sm-12">
          <label class="control-label col-lg-2 col-md-4 col-sm-6" for="layout">{{ $t('Layout', { locale: locale }) }}*</label>
          <div class="col-lg-10 col-md-8 col-sm-6 col-md-8" :class="{ 'has-error' : errors.has('layout')}">
            <select
              name="layout"
              v-model="item.layout"
              v-validate
              data-vv-rules="required"
              class="form-control"
              :class="{ 'has-error' : errors.has('page_locale_id')}">

              <option v-for="(layout, index) in this.layoutsArray" :value="layout.code">
                {{ layout.name }}
              </option>

            </select>
            <span v-show="errors.has('layout')" class="text-danger">{{ errors.first('layout') }}</span>
          </div>
        </div>

        <!-- Description Locale -->
        <div class="form-group col-md-6 col-sm-12">
          <label class="control-label col-lg-2 col-md-4 col-sm-6" for="description">{{ $t('Description', { locale: locale }) }}*</label>
          <div class="col-lg-10 col-md-8 col-sm-6 col-md-8" :class="{ 'has-error' : errors.has('description')}">
            <textarea
              name="description"
              v-model="item.description"
              v-validate
              data-vv-rules="required|max:500"
              class="form-control"
              :class="{ 'has-error' : errors.has('description')}"></textarea>
            <span v-show="errors.has('description')" class="text-danger">{{ errors.first('description') }}</span>
          </div>
        </div>
      </div>

      <div class="col-md-12 colFormBottomSeparator"></div>
      
      <div class="col-sm-12" v-if="!isNewPage">
        <h3>{{ $t('Contents')}}</h3>
      </div>
      
      <div class="col-sm-12" v-if="!isNewPage">
        <draggable v-if="showList" :list="this.item.contents" @end="eventMove">
          <transition-group name="list-complete">
            <div v-for="(content, index) in this.item.contents" :key="index" class="list-complete-item well well-sm">
              <content-item
                :item="content"
                @removeContentEvent="removeContent"
                @showEditContentEvent="showEditContent"
                />
            </div>
          </transition-group>
        </draggable>
        <content-form v-else
          @cancelEditContentEvent="cancelEditContent"
          @saveEditContentEvent="saveEditContent"
          :locale="locale"
          :content="contentEdit.content"
          />
        <button v-if="buttonNewContent" type="button" class="btn btn-primary btn-block" @click="showEditContent(null)"><i class="fa fa-plus"></i> {{ $t('New content', { locale: locale }) }}</button>
      </div>

      <div class="col-md-12 colFormBottomSeparator"></div>
      
      <div class="col-sm-12">
        <h3>{{ $t('SEO')}}</h3>
      </div>
      
      <div id="sectionFormSEO" class="row">
        <!-- SEO Title Locale -->
        <div class="form-group col-md-6 col-sm-12">
          <label class="control-label col-lg-2 col-md-4 col-sm-6" for="seo_title">{{ $t('SEO Title', { locale: locale }) }}*</label>
          <div class="col-lg-10 col-md-8 col-sm-6" :class="{ 'has-error' : errors.has('seo_title')}">
            <input
              type="text"
              name="seo_title"
              v-model="item.seo_title"
              v-validate
              data-vv-rules="required|max:100"
              class="form-control"
              :class="{ 'has-error' : errors.has('seo_title')}" />
            <span v-show="errors.has('seo_title')" class="text-danger">{{ errors.first('seo_title') }}</span>
          </div>
        </div>
        
        <!-- SEO Keywords Locale -->
        <div class="form-group col-md-6 col-sm-12">
          <label class="control-label col-lg-2 col-md-4 col-sm-6" for="seo_title">{{ $t('SEO Keywords', { locale: locale }) }}</label>
          <div class="col-lg-10 col-md-8 col-sm-6">
            <input-tag class="form-control" :tags="item.seo_keywords"></input-tag>
          </div>
        </div>
      </div>
       
      <div class="row">
        <!-- SEO Description Locale -->
        <div class="form-group col-md-6 col-sm-12">
          <label class="control-label col-lg-2 col-md-4 col-sm-6" for="description">{{ $t('SEO Description', { locale: locale }) }}</label>
          <div class="col-lg-10 col-md-8 col-sm-6 col-md-8" :class="{ 'has-error' : errors.has('seo_description')}">
            <textarea
              name="seo_description"
              v-model="item.seo_description"
              v-validate
              data-vv-rules="max:500"
              class="form-control"
              :class="{ 'has-error' : errors.has('seo_description')}"></textarea>
            <span v-show="errors.has('seo_description')" class="text-danger">{{ errors.first('seo_description') }}</span>
          </div>
        </div>
      </div>
      
      <p class="clearfix"></p>

      <div class="col-sm-12">
        <div class="col-sm-12">
          <button class="btn btn-success btn-xl pull-right" type="submit" v-if="!isLoading">
            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ $t('Save language', { locale: locale }) }}
          </button>
          <div v-else class="alert alert-warning">
            {{ $t('Loading', { locale: locale }) }}...
          </div>
        </div>
      </div>
      <p class="clearfix"></p>
      
    </form>
    
    <div class="col-sm-12" v-if="isTrash">
      <p class="clearfix"></p>
      <div class="callout callout-warning">
        <h4>{{ $t('This change require your confirm', { locale: locale }) }}</h4>
        <p>{{ $t('If there is no translation of the page it will be deleted to avoid trash', { locale: locale }) }}</p>
        <button class="btn btn-danger btn-xl" @click="$emit('removePageLocaleEvent', item.lang_iso)">
          <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> {{ $t('Confirm disable', { locale: locale }) }}
        </button>
      </div>
    </div>
    
    <p class="clearfix"></p>
  </div>
</template>

<script>
  import draggable from 'vuedraggable'
  import slugMixin from '../includes/slugMixin'
  import layoutsArray from '../includes/layoutsArray'
  import contentVoidStructure from '../structures/contentVoidStructure'
  import ContentItem from './ContentItem'
  import ContentForm from './ContentForm'
  import InputTag from 'vue-input-tag'
  
  export default {
    mixins: [slugMixin],
    name: 'PageLocale',
    props: [
      'locale',
      'pageLocale',
      'isNewPage',
      'isLoading',
      'routecontentstore',
      'routecontentupdate',
      'routecontentdestroy'
    ],
    components: {
      draggable,
      InputTag,
      ContentItem,
      ContentForm
    },
    
    data () {
      return {
        item: this.pageLocale,
        layoutsArray: layoutsArray,
        buttonNewContent: true,
        contentEdit: {
          content: {
            key: ''
          }
        }
      }
    },
    
    computed: {
      showList() {
        return !this.contentEdit.state && this.hasContents
      },
      
      hasContents () {
        return this.item.contents.length > 0
      },
      
      isTrash () {
        return !this.pageLocale.status && this.item.id !== null
      }
    },
    
    methods: {
      filterSlug () {
        this.item.slug = this.sluglify(this.item.slug)
      },
      
      onSwitchChangeEventHandler (ev) {
        this.pageLocale.status = ev.value
      },
      
      eventMove (ev) {
        this.reorder()
      },
      
      reorder () {
        this.pageLocale.contents.forEach((content, key) => {
          content.priority = key
        });
      },
      
      removeContent (item) {
        let context = this
        let contents = []

        axios.post(context.routecontentdestroy, {
          content: item
        }).then(function (response) {
          if (response.data) {
            context.pageLocale.contents.forEach((content, key) => {
              if (item !== content.id) {
                contents.push(content)
              }
            });
            
            context.pageLocale.contents = contents
            context.reorder()      
          }
        }).catch(function (error) {
          console.log(error)
        })
      },
      
      showEditContent (item) {
        contentVoidStructure.page_locale_id = this.item.id
        contentVoidStructure.priority = this.item.contents.length
        
        this.buttonNewContent = false
        this.contentEdit = {
          state: true,
          content: item === null ? Object.assign({}, contentVoidStructure) : item,
        }
      },
      
      showListContents () {
        this.buttonNewContent = true
        this.contentEdit = {
          state: false,
          content: contentVoidStructure
        }
      },
      
      cancelEditContent () {
        this.showListContents();
      },
      
      saveEditContent (content) {
        let context = this
        let route = content.id === null ? this.routecontentstore : this.routecontentupdate

        axios.post(route, {
          content: content
        }).then(function (response) {
          if (!isNaN(response.data)) {
            if (content.id === null) {
              content.id = response.data
              context.item.contents.push(content)
              context.reorder()
            }
            
            context.showListContents()
          }
        }).catch(function (error) {
          console.log(error)
        })
      },
      
      validateBeforeSubmit () {
        this.$validator.validateAll().then(result => {
          if (!result) {
            console.log('Form not validate')
          } else {
            this.$emit('savePageLocaleEvent', this.item)
          }
        }).catch(error => {
          console.log(error)
        })
      }
    },
    
    mounted () {
      this.reorder()
      this.showListContents()
    }
  }
</script>

<style scoped>
  .vue-js-switch {
    font-size: 16px;
  }

  .list-complete-item {
    transition: all 1s;
    cursor: move;
  }

  .list-complete-enter, .list-complete-leave-active {
    opacity: 0;
  }
  
  .vue-input-tag-wrapper {
    padding-bottom: 34px;
  }

  .colFormBottomSeparator {
    margin-top: 20px;
    margin-bottom: 20px;
    border-bottom: 2px solid #ecf0f5;
  }
</style>