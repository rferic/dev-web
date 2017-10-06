<template>
  <div v-if="show">
    <draggable v-if="hasItems" :list="items" @end="eventMove">
      <transition-group name="list-complete">
        <div v-for="item in items" :key="item.id" class="list-complete-item well well-sm">
          <menu-item-drag-and-drop v-bind:item="item" v-bind:routepages="routepages" />
        </div>
      </transition-group>
    </draggable>
    <div v-else class="alert alert-warning">
      {{ voidmessage }}
    </div>
  </div>
  <div v-else>
    <div v-if="!error" class="alert alert-warning">
      {{ loadingmessage }}
    </div>
    <div v-if="error" class="alert alert-danger">
      {{ errormessage }}
    </div>
  </div>
</template>

<script>
import draggable from 'vuedraggable'
import MenuItemDragAndDrop from './MenuItem'

export default {
  name: 'MenuDragAndDrop',
  props: ['locale', 'urlrequest', 'loadingmessage', 'errormessage', 'voidmessage', 'routepages'],
  components: {
    draggable,
    MenuItemDragAndDrop
  },
  data () {
    return {
      loading: true,
      error: false,
      items: []
    }
  },
  computed: {
    show () {
      return !this.loading && !this.error
    },
    hasItems () {
      return this.items.length
    }
  },
  methods: {
    getItems () {
      let context = this
      this.loading = true

      axios.post(this.urlrequest, {
        locale: this.locale
      }).then(function (response) {
        context.loading = false
        context.error = false

        context.setItems(response.data)
      }).catch(function (error) {
        context.loading = false
        context.error = true
      })
    },

    setItems (items) {
      this.items = items
      this.reorder()
    },

    reorder () {
      this.items.forEach((item, key) => {
        item.priority = key
      });
    },

    eventMove (ev) {
      this.reorder()
    },

    getUrlPage (item) {
      console.log(item)
      return 'asd'
    }
  },
  mounted () {
    this.getItems();
  }
}
</script>

<style scoped>
.list-complete-item {
  transition: all 1s;
  cursor: move;
}

.list-complete-enter, .list-complete-leave-active {
  opacity: 0;
}
</style>
