<template>
  <div v-if="show">
    <div v-if="hasItems" class="text-right">
      <button class="btn btn-primary">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ $t('Add') }}
      </button>
      <button class="btn btn-success" @click="saveReorder">
        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ $t('Save') }}
      </button>
    </div>
    <hr />
    <draggable v-if="hasItems" :list="items" @end="eventMove">
      <transition-group name="list-complete">
        <div v-for="item in items" :key="item.id" class="list-complete-item well well-sm">
          <menu-item-drag-and-drop
            v-bind:item="item"
            :routemenuitem="routemenuitem"
            @serverLoadingEvent="serverLoading"
            @serverErrorEvent="serverError"
            @serverOkEvent="serverOk"
            @removeItemEvent="removeItem($event.target.value)"
          />
        </div>
      </transition-group>
    </draggable>
    <div v-else class="alert alert-warning">
      {{ $t('Items not found') }}
    </div>
  </div>
  <div v-else>
    <div v-if="!error" class="alert alert-warning">
      {{ $t('Loading') }}...
    </div>
    <div v-if="error" class="alert alert-danger">
      {{ $t('Please, reload page: Error on request. Please, reload page') }}
    </div>
  </div>
</template>

<script>

import draggable from 'vuedraggable'
import MenuItemDragAndDrop from './MenuItem'

export default {
  name: 'MenuDragAndDrop',
  props: [
    'menu',
    'locale',
    'routemenu',
    'routemenuitem',
    'routepage'
  ],
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
      this.serverLoading();

      axios.post(`${this.routemenu}/${this.menu}/getItemsLocale`, {
        locale: this.locale
      }).then(function (response) {
        context.serverOk();
        context.setItems(response.data)
      }).catch(function (error) {
        context.serverError();
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
      return `${this.routepage}/${item.page.slug}`
    },

    saveReorder () {
      let context = this
      this.serverLoading();

      axios.post(`${this.routemenu}/${this.menu}/reorder`, {
        items: this.items
      }).then(function (response) {
        context.serverOk();
      }).catch(function (error) {
        context.serverError();
      })
    },

    removeItem (item_id) {
      console.log(item_id)
    },

    serverLoading () {
      this.loading = true
      this.error = false
    },

    serverError () {
      this.loading = false
      this.error = true
    },

    serverOk () {
      this.loading = false
      this.error = false
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
