<template>
    <v-app>
          <span>ss</span>

    </v-app>
</template>

<script>
    export default {
         data () {
          return {
            items: [
              { title: 'Home', icon: 'dashboard' },
              { title: 'About', icon: 'question_answer' }
            ],
            right: null
          }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
