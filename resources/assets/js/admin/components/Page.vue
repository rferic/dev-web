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
            />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import PageLocale from './PageLocale'
  
  export default {
    name: 'PageForm',
    props: [
      'supported_locales_json',
      'page_locales_json',
      'contents_json',
      'locale'
    ],
    components: {
      PageLocale
    },
    
    data () {
      return {
        loading: false,
        pagesLocales: [],
        pageLocaleCurrent: null,
        pageLocaleStructure : {
          exist: false,
          status: false,
          current: false,
          id: null,
          slug: '',
          title: '',
          description: '',
          layout: '',
          options: {},
          seo_title: '',
          seo_description: '',
          seo_keywords: [],
          contents: []
        }
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
      }
    },
    
    mounted () {
      let pagesOrigin = JSON.parse(this.page_locales_json)
      let contents = JSON.parse(this.contents_json)
      let page = null
      let context = this
      
      for (var key in context.supportedLocales) {
        let supportedLocale = context.supportedLocales[key]
        let contentsPage = []
        
        pagesOrigin.forEach((pageOrigin) => {
          if (pageOrigin.lang === key) {
            page = pageOrigin
            
            contents.forEach((content) => {
              if (page.id === content.page_id) {
                contentsPage.push({
                  id: content.id,
                  key: content.key,
                  value: JSON.parse(content.text)
                })
              }
            })
            
            return false
          }
        })
        
        context.pagesLocales.push({
          exist: (page === null) ? this.pageLocaleStructure.exist: true,
          status: (page === null) ? this.pageLocaleStructure.status : true,
          current: this.pageLocaleCurrent === null ? true : false,
          lang: supportedLocale,
          lang_iso: key,
          id: (page === null) ? this.pageLocaleStructure.id : page.id,
          slug: (page === null) ? this.pageLocaleStructure.slug : page.slug,
          title: (page === null) ? this.pageLocaleStructure.title : page.title,
          description: (page === null) ? this.pageLocaleStructure.description : page.description,
          layout: (page === null) ? this.pageLocaleStructure.layout : page.layout,
          options: (page === null) ? this.pageLocaleStructure.options : JSON.parse(page.options),
          seo_title: (page === null) ? this.pageLocaleStructure.seo_title : page.seo_title,
          seo_description: (page === null) ? this.pageLocaleStructure.seo_description : page.seo_description,
          seo_keywords: (page === null) ? this.pageLocaleStructure.seo_keywords : JSON.parse(page.seo_keywords),
          contents: contentsPage 
        })
        
        if (this.pageLocaleCurrent === null) {
          this.pageLocaleCurrent = key
        }
      }
    }
  }
</script>
