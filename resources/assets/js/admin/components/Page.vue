<template>
  
  <div v-if="localesExists" class="nav-tabs-custom">
    <div id="confirmSave" v-show="confirmSave" class="alert alert-success alert-dismissible">
      <button type="button" class="close" aria-hidden="true" @click="hideConfirmSave">×</button>
      <p><i class="icon fa fa-check"></i> {{ $t('This page has been saved', { locale: this.locale }) }}</p>
    </div>

    <p class="clearfix"></p>
  
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li v-for="(pageLocale, index) in pagesLocales" :key="index" :class="{ active: pageLocale.current }">
          <a :href="`#locale-${pageLocale.lang_iso}`" data-toggle="tab" aria-expanded="true" @click="setPageLocaleCurrent(pageLocale.lang_iso)">{{ pageLocale.lang.name }}</a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane" id="`#locale-${pageLocale.lang_iso}`" v-for="(pageLocale, index) in pagesLocales" :key="index" :class="{ active: pageLocale.current }">
            <page-locale
              :pageLocale="pageLocale"
              :isNewPage="isNew"
              :isLoading="isLoading"
              @savePageLocaleEvent="savePageLocale"
              @removePageLocaleEvent="removePageLocale"
            />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import PageLocale from './PageLocale'
  import pageLocaleVoidStructure from '../structures/pageLocaleVoidStructure'
  import VueScrollTo from 'vue-scrollto'
  import { mapState } from 'vuex'
  
  export default {
    name: 'PageForm',
    props: [
      'page',
      'page_locales_json',
      'contents_json'
    ],
    components: {
      PageLocale
    },
    
    data () {
      return {
        loading: false,
        pagesLocales: [],
        pageLocaleCurrent: null,
        confirmSave: false,
        isLoading: false
      }
    },
    
    computed: {
      ...mapState([ 'locale', 'supportedLocales', 'routes' ]),
      localesExists () {
        return this.pagesLocales.length > 0
      },
      page_locales () {
        return JSON.parse(this.page_locales_json)
      },
      isNew () {
        return this.page === ''
      }
    },
    
    methods: {
      setPageLocaleCurrent (pageLocaleISOCurrent) {
        this.pagesLocales.forEach((pageLocale) => {
          pageLocale.current = pageLocale.lang_iso === pageLocaleISOCurrent ? true : false
        })
      },
      
      savePageLocale (pageLocale) {
        let context = this
        context.isLoading = true
        
        axios.post(`${this.routes.routepageupdate}`, {
          pageLocale: pageLocale,
          locale: pageLocale.lang_iso
        }).then(function (response) {
          context.isLoading = false
  
          if (context.isNew)
            window.location.href = response.data
          else
            context.showConfirmSave()
        
        }).catch(function (error) {
          console.log(error)
        })
      },
      
      removePageLocale (locale) {
        let context = this
          
        axios.post(`${this.routes.routepagelocaledestroy}`, {
          locale: this.locale
        }).then(function (response) {
          if (response.data) {
            window.location.href = context.routes.routepages
          } else {
            location.reload()
          }          
        }).catch(function (error) {
          console.log(error)
        })
      },
      
      showConfirmSave () {
        this.confirmSave = true
        
        VueScrollTo.scrollTo('#confirmSave', 500, {
          container: "body",
          duration: 500,
          easing: "ease",
          offset: 0,
          cancelable: true,
          onDone: false,
          onCancel: false,
          x: false,
          y: true
        })
      },
      
      hideConfirmSave () {
        this.confirmSave = false
      },
      
      mountPageLocaleVoid (item) {
        item.exist = pageLocaleVoidStructure.exist;
        item.status = pageLocaleVoidStructure.status;
        item.id = pageLocaleVoidStructure.id;
        item.slug = pageLocaleVoidStructure.slug;
        item.title = pageLocaleVoidStructure.title;
        item.description = pageLocaleVoidStructure.description;
        item.layout = pageLocaleVoidStructure.layout;
        item.options = pageLocaleVoidStructure.options;
        item.seo_title = pageLocaleVoidStructure.seo_title;
        item.seo_description = pageLocaleVoidStructure.seo_description;
        item.seo_keywords = pageLocaleVoidStructure.seo_keywords;
        item.contents = [];
        
        return item
      }
    },
    
    mounted () {
      let pagesOrigin = JSON.parse(this.page_locales_json)
      let contentsOrigin = JSON.parse(this.contents_json)
      let page 
      let context = this
      let contentsPage
      
      for (var key in context.supportedLocales) {
        let supportedLocale = context.supportedLocales[key]
        
        page = null
        contentsPage = []
        
        pagesOrigin.forEach((pageOrigin) => {
          if (pageOrigin.lang === key) {
            page = pageOrigin
            page.contents = [];
            
            contentsOrigin.forEach((contentOrigin) => {
              
              if (page.id === contentOrigin.page_id) {
                contentsPage.push(contentOrigin)
              }
            })
          
            return false
          }
        })
        
        context.pagesLocales.push({
          lang: supportedLocale,
          lang_iso: key,
          exist: (page === null) ? pageLocaleVoidStructure.exist: true,
          status: (page === null) ? pageLocaleVoidStructure.status : true,
          current: this.pageLocaleCurrent === null ? true : false,
          id: (page === null) ? pageLocaleVoidStructure.id : page.id,
          slug: (page === null) ? pageLocaleVoidStructure.slug : page.slug,
          title: (page === null) ? pageLocaleVoidStructure.title : page.title,
          description: (page === null) ? pageLocaleVoidStructure.description : page.description,
          layout: (page === null) ? pageLocaleVoidStructure.layout : page.layout,
          options: (page === null) ? pageLocaleVoidStructure.options : JSON.parse(page.options),
          seo_title: (page === null) ? pageLocaleVoidStructure.seo_title : page.seo_title,
          seo_description: (page === null) ? pageLocaleVoidStructure.seo_description : page.seo_description,
          seo_keywords: (page === null) ? pageLocaleVoidStructure.seo_keywords : JSON.parse(page.seo_keywords),
          contents: contentsPage 
        })
        
        if (this.pageLocaleCurrent === null) {
          this.pageLocaleCurrent = key
        }
      }
    }
  }
</script>
