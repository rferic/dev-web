<template>
  <div v-if="show">
    <draggable  :list="items" @end="eventMove">
      <transition-group name="list-complete">
        <div v-for="item in items" :key="item.id" class="list-complete-item">
          {{item.label}}
        </div>
      </transition-group>
    </draggable>
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

export default {
  name: 'ItemsMenu',
  props: ['locale', 'urlrequest', 'loadingmessage', 'errormessage'],
  components: {
    draggable
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
      console.log(this.items[0].label)
    },

    eventMove (ev) {
      this.reorder()
    }
  },
  mounted () {
    this.getItems();
  }
}
</script>

<style scoped>
.list-complete-item {
  padding: 4px;
  margin-top: 4px;
  border: solid 1px;
  transition: all 1s;
}

.list-complete-enter, .list-complete-leave-active {
  opacity: 0;
}
</style>
