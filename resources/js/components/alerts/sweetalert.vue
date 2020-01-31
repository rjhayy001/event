<template>
  <v-snackbar multi-line right top v-model="$store.state.show" :timeout='timeout'>
    <v-icon color="white" >mdi-bell</v-icon>
    {{$store.state.snack}}
    <v-btn text color="pink" @click.native="$store.state.show = false">Close</v-btn>
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