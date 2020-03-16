<template>
  <v-data-table
    :headers="headers"
    :items="ongoing"
    :items-per-page="5"
    :hide-default-footer="ongoing.length < 5"
    :no-data-text="loadingText"
    @click:row = "getrow"
  >
   
    <template v-slot:item.duration="{ item }">
        <v-chip 
         class="ma-2"
        color="pink"
      label
      text-color="white"
            >
            {{item.duration}} jour(s)
        </v-chip>
    </template>
    <template v-slot:item.details="{ item }">
        <v-chip
             class="ma-2"
        color="primary"
      label
      text-color="white"
            >
            {{item.details}}
        </v-chip>
    </template>
    </v-data-table>
</template>
<script>
  export default {
       props:{
           ongoing:{}
    },
    data () {
      return {
        loadingText:'Aucune donnée disponible',
        headers: [
          {
            text: 'Nom',
            align: 'left',
            sortable: false,
            value: 'name',
          },
          { text: 'Date de début', value: 'from' },
          { text: 'Date de fin', value: 'to' },
          { text: 'Durée', value: 'duration' },
          { text: 'Démarré depuis', value: 'details' },
        ],
      }
    },
    methods : {
       getrow(e) {
            this.$router.push({name: 'view_event', params: { id: e.id },})
        },
    }
  }
</script>