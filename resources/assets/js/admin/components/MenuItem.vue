<template>
  <div>
    <i class="glyphicon" :class="{'glyphicon-resize-small': isInternal, 'glyphicon-globe': !isInternal}"></i><br />
    <b>{{ item.label }}</b>
    <span>( <a v-bind:href="urlPage">/{{ item.label }}</a> )</span>
    <button @click="$emit('removeItemEvent', item.id)" type="button" class="btn btn-danger btn-sm pull-right" aria-label="Left Align">
      <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
    </button>
    <button @click="$emit('showEditEvent', item)" type="button" class="btn btn-primary btn-sm pull-right" aria-label="Left Align">
      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
    </button>
    <p class="clearfix"></p>
  </div>
</template>

<script>
export default {
  name: 'MenuItemDragAndDrop',
  props: ['item'],
  computed: {
    isInternal () {
      return this.item.type === 'internal'
    },
    urlPage () {
      return this.isInternal ? `${this.routepages}/${this.item.page.slug}` : this.item.url_external
    }
  }
}
</script>

<style scoped>
.btn-danger {
  margin-left: 10px;
}
</style>
