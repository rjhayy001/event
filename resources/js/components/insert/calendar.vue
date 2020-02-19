<template>
  <div>
    <v-sheet
      tile
      height="54"
      color="grey lighten-3"
      class="d-flex"
    >
      <v-btn
        icon
        class="ma-2"
        @click="$refs.calendar.prev()"
      >
        <v-icon>mdi-chevron-left</v-icon>
      </v-btn>
      <v-btn
        icon
        class="ma-2"
        @click="$refs.calendar.next()"
      >
        <v-icon>mdi-chevron-right</v-icon>
      </v-btn>
    </v-sheet>
    <v-sheet height="600">
      <v-calendar
        ref="calendar"
        color="teal"
        text-color="white"
        v-model="value"
        :weekdays="weekday"
        :type="type"
        :events="events_range"
        :event-color="getEventColor"
        @change="getEvents"
        @click:event="showEvent"
      ></v-calendar>
    </v-sheet>
  </div>
</template>
<script>
  export default {
     
    data: () => ({
      type: 'month',
      types: ['month', 'week', 'day', '4day'],
      mode: 'stack',
      modes: ['stack', 'column'],
      weekday: [0, 1, 2, 3, 4, 5, 6],
      weekdays: [
        { text: 'Sun - Sat', value: [0, 1, 2, 3, 4, 5, 6] },
        { text: 'Mon - Sun', value: [1, 2, 3, 4, 5, 6, 0] },
        { text: 'Mon - Fri', value: [1, 2, 3, 4, 5] },
        { text: 'Mon, Wed, Fri', value: [1, 3, 5] },
      ],
      value: '',
      events_range: [],
      colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
      names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
    }),
    methods: {
        getEvents () {
            this.events_range = [];
            axios.get('/events', {})
            .then(response => {
                this.events = response.data;
                response.data.forEach(element => {
                    if(element.start != null) {
                        let range = {'name' : element.name , 'start' : element.start , color: this.colors[this.rnd(0, this.colors.length - 1)] , 'id': element.id};
                        if(element.end != null) {
                            range.end = element.end;
                        }
                        this.events_range.push(range)
                    }
                });
                console.log(this.events_range , 'calendar')
            });
        },
        showEvent(event){
          console.log(event)
          this.$router.push({name: 'view_event', params: { id: event.event.id },})
        },
      getEventColor (event) {
        return event.color
      },
      rnd (a, b) {
        return Math.floor((b - a + 1) * Math.random()) + a
      },
    },
  }
</script>