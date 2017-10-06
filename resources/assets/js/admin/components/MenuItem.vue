<template>
  <div>
    <i class="glyphicon" :class="{'glyphicon-resize-small': isInternal, 'glyphicon-globe': !isInternal}"></i>
    <b>{{ item.label }}</b>
    <span>( <a v-bind:href="url">/{{ item.label }}</a> )</span>
  </div>
</template>

<script>
export default {
  name: 'MenuItemDragAndDrop',
  props: ['item', 'routepages'],
  computed: {
    isInternal () {
      return this.item.type === 'internal'
    },
    url () {
      return this.isInternal ? `${this.routepages}/${this.item.page_id}` : this.item.url_external
    }
  }
}
</script>
