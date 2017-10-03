<template>
  <div v-if="show">
    <div v-for="item in items" :id="id">
      {{ item.label }} ({{ item.priority }})
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
  //import draggable from 'vuedraggable'

  export default {
    name: 'ItemsMenu',
    props: ['locale', 'urlrequest', 'loadingmessage', 'errormessage'],
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
      }
    },
    mounted () {
      this.getItems();
    }
  }
</script>
