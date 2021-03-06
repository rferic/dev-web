<template>
  <div>
    <button class="btn btn-primary pull-right" @click="$emit('showListEvent')">
      <span class="glyphicon glyphicon-back" aria-hidden="true"></span> {{ $t('Return to list', { locale: this.locale }) }}
    </button>
    <p class="clearfix"></p>
    <hr />
    <form autocomplete="off" @submit.prevent="validateBeforeSubmit" class="form-horizontal">
      <div class="form-group">
        <label class="control-label col-md-4" for="label">{{ $t('Title', { locale: this.locale }) }}</label>
        <div class="col-md-8" :class="{ 'has-error' : errors.has('label')}">
          <input
            type="text"
            name="label"
            v-model="item.label"
            v-validate
            data-vv-rules="required|max:100"
            class="form-control"
            :class="{ 'has-error' : errors.has('label')}" />
          <span v-show="errors.has('label')" class="text-danger">{{ errors.first('label') }}</span>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-4" for="type">{{ $t('Type link', { locale: this.locale }) }}</label>
        <div class="col-md-8" :class="{ 'has-error' : errors.has('type')}">
          <select
            name="type"
            v-model="item.type"
            vee-validate
            data-vv-rules="required"
            class="form-control"
            :class="{ 'has-error' : errors.has('type')}"
          >
            <option value="internal">
              {{ $t('Internal', { locale: this.locale }) }}
            </option>
            <option value="external">
              {{ $t('External', { locale: this.locale }) }}
            </option>
          </select>
          <span v-show="errors.has('type')" class="text-danger">{{ errors.first('type') }}</span>
        </div>
      </div>

      <div v-if="isInternal" class="form-group">
        <label class="control-label col-md-4" for="page_locale_id">{{ $t('Page', { locale: this.locale }) }}</label>
        <div class="col-md-8" :class="{ 'has-error' : errors.has('page_locale_id')}">
          <select
            name="page_locale_id"
            v-model="item.page_locale_id"
            v-validate
            data-vv-rules="required"
            class="form-control"
            :class="{ 'has-error' : errors.has('page_locale_id')}">

            <option v-for="(page_locale, index) in pages_locales" :value="page_locale.id">
              {{ page_locale.title }}
            </option>

          </select>
          <span v-show="errors.has('page_locale_id')" class="text-danger">{{ errors.first('page_locale_id') }}</span>
        </div>
      </div>

      <div v-if="isExternal" class="form-group">
        <label class="control-label col-md-4" for="url_external">{{ $t('URL', { locale: this.locale }) }}</label>
        <div class="col-md-8" :class="{ 'has-error' : errors.has('url_external')}">
          <input
            type="text"
            name="url_external"
            v-model="item.url_external"
            v-validate
            data-vv-rules="required|url"
            class="form-control"
            :class="{ 'has-error' : errors.has('url_external')}"
            @keyup="filterSlug"
          />
          <span v-show="errors.has('url_external')" class="text-danger">{{ errors.first('url_external') }}</span>
        </div>
      </div>

      <button v-if="showSubmit" class="btn btn-success btn-xl pull-right" type="submit">
        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ textSubmit }}
      </button>
      <p class="clearfix"></p>
    </form>
  </div>
</template>

<script>
  import slugMixin from '../includes/mixins/slugMixin'
  import { mapState } from 'vuex'

  export default {
    name: 'MenuItemForm',
    props: [
      'itemEdit',
      'pages'
    ],
    data () {
      return {
        pages_locales: [],
        item: {
          id: null,
          label: null,
          type: null,
          page_locale_id: null,
          url_external: null,
          priority: 0,
          edit: true
        }
      }
    },
    computed: {
      ...mapState([ 'locale' ]),
      textSubmit () {
        if (this.item.id === null) {
          return this.$t('Add', { locale: this.locale })
        } else {
          return this.$t('Update', { locale: this.locale })
        }
      },

      action () {
        return this.item.id === null ? 'addItemEvent' : 'updateItemEvent'
      },

      isInternal () {
        return this.item.type === 'internal'
      },

      isExternal () {
        return this.item.type === 'external'
      },

      showSubmit () {
        return this.isInternal || this.isExternal
      }
    },
    methods: {
      filterSlug () {
        this.item.url_external = this.sluglify(this.item.url_external)
      },

      validateBeforeSubmit () {
        this.$validator.validateAll().then(result => {
          if (!result) {
            console.log('Form not validate')
          } else {
            if (this.item.page_locale_id !== null) {
              this.pages_locales.forEach((page_locale, key) => {
                if (page_locale.id === this.item.page_locale_id) {
                  this.item.page_locale = page_locale
                }
              })
            }

            this.$emit(this.action, this.item)
          }
        }).catch(error => {
          console.error(error)
        })
      }
    },
    mounted () {
      let context = this

      if (context.itemEdit !== null) {
        context.item.id = context.itemEdit.id
        context.item.label = context.itemEdit.label
        context.item.type = context.itemEdit.type
        context.item.page_locale_id = context.itemEdit.page_locale_id
        context.item.url_external = context.itemEdit.url_external
        context.item.priority = context.itemEdit.priority
      }

      if ( context.pages.length > 0 ) {
        context.pages.forEach((page, key) => {
          if ( page.locales.length > 0 ) {
            page.locales.forEach((locale, key) => {
              if ( locale.lang === context.locale ) {
                context.pages_locales.push(locale)
              }
            })
          }
        });
      }
    }
  }
</script>
