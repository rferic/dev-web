<template>
  <div>{{ item.priority }} - {{ item.key }}</div>
</template>

<script>  
  export default {
    name: 'ContentItem',
    props: [ 'item' ],
    data () {
      return {}
    },
    
    methods: {}
  }
</script>
