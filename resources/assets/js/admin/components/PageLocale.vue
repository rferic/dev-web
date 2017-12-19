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
      
      <div class="col-sm-12">
        <h3>{{ $t('Content')}}</h3>
      </div>
      
      <div class="col-sm-12">
        <draggable v-if="hasContents" :list="this.item.contents" @end="eventMove">
          <transition-group name="list-complete">
            <div v-for="(content, index) in this.item.contents" :key="index" class="list-complete-item well well-sm">
              <content-item
                :item="content"
                />
            </div>
          </transition-group>
        </draggable>
      </div>
      
      <div class="col-sm-12">
        <h3>{{ $t('SEO')}}</h3>
      </div>
      
      <div class="col-sm-12">
        <h3>{{ $t('Inject CSS & JS')}}</h3>
      </div>
      
      <p class="clearfix"></p>

      <div class="col-sm-12">
        <div class="col-sm-12">
          <button class="btn btn-success btn-xl pull-right" type="submit">
            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ $t('Save', { locale: locale }) }}
          </button>
        </div>
      </div>
      <p class="clearfix"></p>
      
    </form>
  </div>
</template>

<script>
  import draggable from 'vuedraggable'
  import slugMixin from '../includes/slugMixin'
  import layoutsArray from '../includes/layoutsArray'
  import ContentItem from './ContentItem'
  
  export default {
    mixins: [slugMixin],
    name: 'PageLocale',
    props: [
      'locale',
      'pageLocale'
    ],
    components: {
      draggable,
      ContentItem
    },
    
    computed: {
      hasContents () {
        return this.item.contents.length > 0
      }
    },
    
    data () {
      return {
        item: this.pageLocale,
        layoutsArray: layoutsArray
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
          console.log(key)
          content.priority = key
        });
      }
    },
    
    mounted () {
      this.reorder()
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
</style>