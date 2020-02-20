<template>
  <v-data-table
    :headers="headers"
    :items="events"
    :items-per-page="8"
    :hide-default-footer="events.length < 5"
    @click:row = "getrow"
  >
    <template v-slot:item.duration="{ item }">
        <v-chip 
        class="ma-2"
        color="pink"
      label
      text-color="white"
            >
            {{item.duration}} day(s)
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
           events:{}
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
          { text: 'Will Start', value: 'details' },
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