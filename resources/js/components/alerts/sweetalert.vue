<template>
  <v-snackbar multi-line left bottom v-model="$store.state.show" :timeout='timeout' color="teal">
    <v-icon color="white" >mdi-bell</v-icon>
    {{$store.state.snack}}
    <v-btn text color="white" @click.native="$store.state.show = false">Close</v-btn>
  </v-snackbar>
</template>

<script>
export default {
  data () {
    return {
        timeout:3000,
    }
  }
}
</script>