<template>
  <div v-if="localesExists" class="nav-tabs-custom">
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
              :locale="locale"
              @savePageLocaleEvent="savePageLocale"
            />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import PageLocale from './PageLocale'
  import pageLocaleVoidStructure from '../structures/pageLocaleVoidStructure'
  
  export default {
    name: 'PageForm',
    props: [
      'page',
      'supported_locales_json',
      'page_locales_json',
      'contents_json',
      'locale',
      'routepageupdate'
    ],
    components: {
      PageLocale
    },
    
    data () {
      return {
        loading: false,
        pagesLocales: [],
        pageLocaleCurrent: null
      }
    },
    
    computed: {
      localesExists () {
        return this.pagesLocales.length > 0
      },
      supportedLocales () {
        return JSON.parse(this.supported_locales_json)
      },
      page_locales () {
        return JSON.parse(this.page_locales_json)
      }
    },
    
    methods: {
      setPageLocaleCurrent (pageLocaleISOCurrent) {
        this.pagesLocales.forEach((pageLocale) => {
          pageLocale.current = pageLocale.lang_iso === pageLocaleISOCurrent ? true : false
        })
      },
      
      savePageLocale (pageLocale) {
        axios.post(`${this.routepageupdate}`, {
          pageLocale: pageLocale,
          locale: pageLocale.lang_iso
        }).then(function (response) {
          console.log(response)
        }).catch(function (error) {
          console.log(error)
          context.serverError();
        })
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
