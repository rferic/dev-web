<template>
  <div v-if="viewList">
    <div v-if="show">
      <div v-if="hasItems" class="text-right">
        <button class="btn btn-primary" @click="showForm">
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ $t('Add', { locale: this.locale }) }}
        </button>
        <button class="btn btn-success" @click="save">
          <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ $t('Save', { locale: this.locale }) }}
        </button>
        <hr v-show="requireSave" />
        <div v-show="requireSave" class="alert alert-warning text-left">
          {{ $t('Remember, Your changes only apply when you save.', { locale: this.locale }) }}
        </div>
      </div>
      <hr />
      <draggable v-if="hasItems" :list="items" @end="eventMove">
        <transition-group name="list-complete">
          <div v-for="(item, index) in items" :key="index" class="list-complete-item well well-sm">
            <menu-item-drag-and-drop
              :item="item"
              @serverLoadingEvent="serverLoading"
              @serverErrorEvent="serverError"
              @serverOkEvent="serverOk"
              @removeItemEvent="removeItem"
              @showEditEvent="showEdit"
            />
          </div>
        </transition-group>
      </draggable>
      <div v-else class="alert alert-warning">
        {{ $t('Items not found', { locale: this.locale }) }}
      </div>
    </div>
    <div v-else>
      <div v-if="!error" class="alert alert-warning">
        {{ $t('Loading', { locale: this.locale }) }}...
      </div>
    </div>
  </div>
  <div v-else>
    <menu-item-form
      :itemEdit="itemEdit"
      :pages="pages"
      @showListEvent="showList"
      @addItemEvent="addItem"
      @updateItemEvent="updateItem"
    />
  </div>
</template>

<script>

import draggable from 'vuedraggable'
import MenuItemDragAndDrop from './MenuItem'
import MenuItemForm from './MenuItemForm'
import { mapState } from 'vuex'

export default {
  name: 'MenuDragAndDrop',
  props: [
    'menu'
  ],
  components: {
    draggable,
    MenuItemDragAndDrop,
    MenuItemForm
  },
  data () {
    return {
      loading: true,
      error: false,
      requireSave: false,
      viewList: true,
      items: [],
      itemsForRemove: [],
      itemEdit: null,
      pages: []
    }
  },
  computed: {
    ...mapState([ 'locale', 'routes' ]),
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
      this.serverLoading()

      axios.post(`${this.routes.routemenuget}`, {
        locale: this.locale
      }).then(function (response) {
        context.serverOk();
        context.items = response.data
      }).catch(function (error) {
        console.error(error)
        context.serverError();
      })
    },

    getPages () {
      let context = this
      this.serverLoading()

      axios.post(`${this.routes.routepagesgetter}`, {}).then(function (response) {
        context.serverOk();
        context.pages = response.data
      }).catch(function (error) {
        console.error(error)
        context.serverError();
      })
    },

    reorder () {
      this.requireSave = true

      this.items.forEach((item, key) => {
        item.priority = key
      });
    },

    eventMove (ev) {
      this.reorder()
    },

    getUrlPage (item) {
      return `${this.routes.routepage}/${item.page_locale.slug}`
    },

    removeItem (item_id) {
      let newList = []
      this.requireSave = true

      this.items.forEach((item, key) => {
        if (item.id !== item_id) {
          newList.push(item)
        } else {
          if (item.id !== null) {
            this.itemsForRemove.push(item)
          }
        }
      })

      this.items = newList
    },

    addItem (item) {
      item.priority = this.items.length
      this.items.push(item)
      this.requireSave = true
      this.showList()
    },

    updateItem (itemEdit) {
      this.items.forEach((item, key) => {
        if (item.id === itemEdit.id) {
          this.items[key].edit = true
          this.items[key].label = itemEdit.label
          this.items[key].type = itemEdit.type
          this.items[key].page_locale_id = itemEdit.page_locale_id
          this.items[key].page_locale = itemEdit.page_locale
          this.items[key].url_external = itemEdit.url_external
        }
      })

      this.requireSave = true
      this.showList()
    },

    save () {
      let context = this
      this.serverLoading()
      this.reorder()

      axios.post(`${this.routes.routemenusave}`, {
        items: this.items,
        itemsForRemove: this.itemsForRemove,
        locale: this.this.locale
      }).then(function (response) {
        context.requireSave = false
        context.serverOk();
      }).catch(function (error) {
        console.error(error)
        context.serverError()
      })
    },

    showForm () {
      this.viewList = false
    },

    showList () {
      this.viewList = true
      this.itemEdit = null
    },

    showEdit (item) {
      this.itemEdit = item
      this.showForm()
    },

    serverLoading () {
      this.loading = true
      this.error = false
    },

    serverError () {
      this.loading = false
      this.error = true

      this.$root.generateErrorNotify(this.$t('Error on request. Please, reload page', { locale: context.locale }) )
    },

    serverOk () {
      this.loading = false
      this.error = false
    }
  },
  mounted () {
    this.getItems()
    this.getPages()
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
