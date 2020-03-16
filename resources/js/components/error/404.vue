<template>
    <v-container class="fill-height">
        <v-row align="center" justify="center">
          <v-col class="text-center">
            <v-icon size="140">mdi-send-lock</v-icon>
            <div class="display-4 text-uppercase font-weight-bold" color="blue-grey lighten-4">Erreur 404</div>
            <div class="title">La page demandé est introuvable.</div>
            <v-btn color="teal mt-10" class="custom_button" rounded to="dashboard">Retour au tableau de bord!</v-btn>
          </v-col>
        </v-row>
    </v-container>
</template>
<script>
  export default {
    data () {
      return {
        justify: 'center',
      }
    },
  }
</script>