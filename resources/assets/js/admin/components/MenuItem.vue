<template>
  <div>
    <i class="glyphicon" :class="{'glyphicon-resize-small': isInternal, 'glyphicon-globe': !isInternal}"></i><br />
    <b>{{ item.label }}</b>
    <span>( <a v-bind:href="urlPage">/{{ item.label }}</a> )</span>
    <button @click="destroy" type="button" class="btn btn-danger btn-sm pull-right" aria-label="Left Align">
      <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
    </button>
    <a v-bind:href="urlEdit" type="button" class="btn btn-primary btn-sm pull-right" aria-label="Left Align">
      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
    </a>
    <p class="clearfix"></p>
  </div>
</template>

<script>
export default {
  name: 'MenuItemDragAndDrop',
  props: [
    'item',
    'routemenuitem'
  ],
  computed: {
    isInternal () {
      return this.item.type === 'internal'
    },
    urlPage () {
      return this.isInternal ? `${this.routepages}/${this.item.page.slug}` : this.item.url_external
    },
    urlEdit () {
      return `${this.routemenuitem}/${this.item.id}/detail`
    },
    urlRemove () {
      return `${this.routemenuitem}/${this.item.id}/destroy`
    }
  },
  methods: {
    destroy () {
      let context = this

      axios.delete(`${this.routemenuitem}/${this.item.id}/destroy`, {
        items: context.item
      }).then(function (response) {
        console.log(context.item.id);
        context.$emit('serverOkEvent', context.item.id)
      }).catch(function (error) {
        context.$emit('serverErrorEvent', context.item.id)
      })
    },
  }
}
</script>

<style scoped>
.btn-danger {
  margin-left: 10px;
}
</style>
