<template>
  <v-data-table
    :headers="headers"
    :items="ongoing"
    :items-per-page="5"
    :hide-default-footer="ongoing.length < 5"
    @click:row = "getrow"
  >
    <template v-slot:item.duration="{ item }">
        <v-chip 
            >
            {{item.duration}} day(s)
        </v-chip>
    </template>
    <template v-slot:item.details="{ item }">
        <v-chip
            color="error"
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
        headers: [
          {
            text: 'Event Name',
            align: 'left',
            sortable: false,
            value: 'name',
          },
          { text: 'Start Date', value: 'from' },
          { text: 'End Date', value: 'to' },
          { text: 'Duration', value: 'duration' },
          { text: 'Started', value: 'details' },
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