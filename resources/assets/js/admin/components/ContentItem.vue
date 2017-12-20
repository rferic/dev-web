<template>
  <div>
    <div class="col-sm-6 col-xs-12">
      {{ item.priority }} - {{ item.key }}
    </div>
    <div class="col-sm-4 col-xs-12 text-right">
      <i>id: {{ item.id_html}}<br>class: {{ item.class_html }}</i>
    </div>
    <div class="col-sm-2 col-xs-12 text-right">
      <button @click="$emit('removeContentEvent', item.id)" type="button" class="btn btn-danger btn-sm pull-right" aria-label="Left Align">
        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
      </button>
      <button @click="$emit('showEditContentEvent', item)" type="button" class="btn btn-primary btn-sm pull-right" aria-label="Left Align">
        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
      </button>
    </div>
    <p class="clearfix"></p>
  </div>
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

<style scoped>
.btn-danger {
  margin-left: 10px;
}
</style>
