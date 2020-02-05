<template>
    <v-app-bar
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      app
      color="teal"
      dark
    >
      <v-app-bar-nav-icon @click.stop="$store.state.drawer = !$store.state.drawer" />
      <v-toolbar-title
        style="width: 300px"
        class="ml-0 pl-4"
      >
        <span class="hidden-sm-and-down">Quimper Evenements</span>
      </v-toolbar-title>
      <v-text-field
        flat
        solo-inverted
        hide-details
        prepend-inner-icon="mdi-magnify"
        label="Search"
        class="hidden-sm-and-down"
      />
      <v-spacer />
      <v-btn icon  >
        <v-icon>mdi-apps</v-icon>
      </v-btn>
      <v-btn icon @click="logout">
        <v-icon>mdi-logout</v-icon>
      </v-btn>
    </v-app-bar>
</template>
<script>
export default {
  methods : {
    logout() {
       let self = this;
     axios.get('/logout', {})
      .then(response => {
          console.log(response.data)
          location.reload();
      });
    }
  }
}
</script>