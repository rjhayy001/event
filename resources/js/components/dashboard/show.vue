<template>
    <v-container >
        <v-layout row wrap>
            <v-flex xs6 sm3 class="pa-4">
                <v-card
                    class="mx-auto purple "
                    outlined 
                    color="teal"
                    dark
                >
                    <v-list-item three-line>
                        <v-list-item-content>
                            <div class="overline mb-4">events</div>
                            <v-list-item-title class="headline mb-1">{{events.length}}</v-list-item-title>
                            <v-list-item-subtitle>Events Registered</v-list-item-subtitle>
                        </v-list-item-content>
                        <v-list-item-avatar
                            tile
                            size="80"
                                color="white"
                        >
                        <v-icon size="60" color="teal">mdi-calendar</v-icon>
                        </v-list-item-avatar>
                    </v-list-item>
                    <v-card-actions >
                        <v-btn @click="$router.push({name: 'event'})" text>view</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
            <v-flex xs6 sm3 class="pa-4">
                    <v-card
                        class="mx-auto purple "
                        outlined color="teal"
                        dark
                    >
                        <v-list-item three-line>
                            <v-list-item-content>
                                <div class="overline mb-4">companies</div>
                                <v-list-item-title class="headline mb-1">{{companies.length}}</v-list-item-title>
                                <v-list-item-subtitle>Companies</v-list-item-subtitle>
                            </v-list-item-content>
                            <v-list-item-avatar
                                tile
                                size="80"
                                 color="white"
                            >
                            <v-icon size="60" color="teal">mdi-office-building</v-icon>
                            </v-list-item-avatar>
                        </v-list-item>
                        <v-card-actions >
                            <v-btn @click="$router.push({name: 'company'})"  text>view</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            <v-flex xs6 sm3 class="pa-4">
                <v-card
                    class="mx-auto purple "
                    outlined 
                    color="teal "
                    dark
                >
                    <v-list-item three-line>
                        <v-list-item-content>
                            <div class="overline mb-4">visitors</div>
                            <v-list-item-title class="headline mb-1">{{visitors.length}}</v-list-item-title>
                            <v-list-item-subtitle>Visitors</v-list-item-subtitle>
                        </v-list-item-content>
                        <v-list-item-avatar
                            tile
                            size="80"
                                color="white"
                        >
                        <v-icon size="60" color="teal">mdi-account-group</v-icon>
                        </v-list-item-avatar>
                    </v-list-item>
                    <v-card-actions >
                        <v-btn @click="$router.push({name: 'visitor'})"  text>view</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
            
             <v-flex sm6 xs12 class="px-4 pt-8">
                <v-card class="mb-4">
                    <v-toolbar flat color="#E0E0E0" class="mb-2" dense>
                        <v-toolbar-title class="text-uppercase title">ongoing events</v-toolbar-title>
                    </v-toolbar>
                    <Ongoing :ongoing="ongoing"/>
                </v-card>
                <v-card>
                    <v-toolbar flat color="#E0E0E0" class="my-2" dense>
                        <v-toolbar-title class="text-uppercase title">upcoming events</v-toolbar-title>
                    </v-toolbar>
                    <Upcoming :events="upcoming"/>
                </v-card>
            </v-flex>
            <v-flex sm6 xs12 class="px-4 pt-8">
                <vue-calendar></vue-calendar>
            </v-flex>
            <v-flex sm12 class="px-4 pt-8" > 
                <v-card>
                    <!-- <BarChart/> -->
                </v-card>
            </v-flex>
           
        </v-layout>
    </v-container>

</template>
<script>
import fullCalendar from 'vue-fullcalendar';
import VueCalendar from '../insert/calendar';
import Upcoming from '../insert/Upcoming';
import Ongoing from '../insert/Ongoing';
import BarChart from "../insert/BarChart.vue";
import DateHelperVue from '../mixins/DateHelper.vue';
import moment from 'moment';

export default {
    components:{
         'full-calendar' : fullCalendar,
         'vue-calendar' : VueCalendar,
         BarChart,
         Upcoming, Ongoing

    },
    mixins:[DateHelperVue],
    data: () => ({
        companies: [],
        visitors: [],
        events: [],
        upcoming:[],
        ongoing:[],
        
    }),
    methods: {
        get_companies(){
            axios.get('/companies', {})
            .then(response => {
                this.companies = response.data;
                console.log(this.companies)
            });
        },
        get_visitors(){
            axios.get('/visitors', {})
            .then(response => {
                this.visitors = response.data;
                console.log(this.visitors)
            });
        },
        get_events(){
            let today = this.fulldate(new Date())
            axios.get('/upcoming', {})
            .then(response => {
                this.events = response.data;
                response.data.forEach(element => {
                    if(element.from != 'no given date') {
                        if( moment() < moment(element.from)) {
                            this.upcoming.push({
                                'id':element.id,
                                'name':element.name ,
                                'from': this.fulldate(element.from),
                                'to': this.fulldate(element.to),
                                'duration' : (moment(element.to).diff(moment(element.from), 'day', true)+1) ,
                                'details' : this.daysRemaining(element.from),
                            })
                        }
                    }
                    // if(moment().isBetween(moment(element.start),moment(element.end)) || moment().isSame(moment(element.start)) || moment().isSame(moment(element.end)) ){
                    if(moment().isBetween(moment(element.start),moment(element.end),null,'[]') || moment(element.from).isSame(moment(), 'day')  || moment(element.to).isSame(moment(), 'day')){
                        this.ongoing.push({
                            'id':element.id,
                            'name':element.name ,
                            'from': this.fulldate(element.from),
                            'to': this.fulldate(element.to),
                            'duration' : (moment(element.to).diff(moment(element.from), 'day', true)+1) ,
                            'details' : this.daysRemaining(element.from),
                        })
                    }
                })
            }); 
        },
        daysRemaining(date) {
            // var eventdate = moment(date);
            // var todaysdate = moment();
            // return eventdate.diff(todaysdate, 'days' , true)+1;
            return new moment().to(moment(date));
        }
       
    },
    created: function () {
        this.get_companies()
        this.get_visitors()
        this.get_events()
    },
}
</script>