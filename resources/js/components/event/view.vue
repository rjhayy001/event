<template>
    <div>
        <v-toolbar flat color="white" class="mb-4">
            <v-toolbar-title class="text-uppercase title">view event</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn color="primary" tile @click="$router.go(-1)">
                <v-icon left>mdi-arrow-left-circle</v-icon>
               back
            </v-btn>
        </v-toolbar>
        <v-divider></v-divider>
        <v-container grid-list-md>
            <v-layout row wrap>
                <v-flex xs12 sm3>
                    <v-card>
                        <v-img max-height="250" contain :src="event.image" lazy-src="https://cdn.dribbble.com/users/197853/screenshots/5506993/boat-wave.gif" ></v-img>
                        <v-card-title>Event Information</v-card-title>

                        <v-card-text>
                            <v-row >
                                <v-flex xs12>
                                            <v-divider></v-divider>
                                    <v-simple-table>
                                        <tbody>
                                            <tr>
                                                <td class="font-weight-bold text-capitalize"> name</td>
                                                <td class="font-weight-bold">{{event.name}}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold text-capitalize">Start Date</td>
                                                <td class="font-weight-bold">{{event.fromdate ? fulldate(event.fromdate) : 'not set'}}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold text-capitalize">End Date</td>
                                                <td class="font-weight-bold">{{event.todate ? fulldate(event.todate) : 'not set'}}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold text-capitalize">Place</td>
                                                <td class="font-weight-bold">{{event.place ? event.place : 'not set'}}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold text-capitalize">Description</td>
                                                <td class="font-weight-bold">{{event.description | striphtml}}</td>
                                            </tr>
                                        </tbody>
                                </v-simple-table>
                                </v-flex>
                            </v-row>
                        </v-card-text>
                    </v-card>
                    
                </v-flex>
                <v-flex xs12 sm8 class="mx-10">
                    <v-container>
                        <v-layout row wrap >
                            <v-flex xs12 sm6>
                                <v-toolbar flat color="white">
                                    <v-toolbar-title class="text-uppercase title">Programs saved</v-toolbar-title>
                                </v-toolbar>
                                <v-simple-table class="elevation-1">
                                    <template v-slot:default fixed-header>
                                        <thead>
                                            <tr>
                                            <th class="text-left">Title</th>
                                            <th class="text-left">Time Scheduled</th>
                                            <th class="text-left">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item,index) in event.programs" :key="index" >
                                            <td class="width:30%">{{ item.name }}</td>
                                            <td>{{ item.time }}</td>
                                            <td>{{item.details | striphtml}}</td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </v-flex>
                            <v-flex xs12 sm6 class="pl-4">
                                <v-toolbar flat color="white">
                                    <v-toolbar-title class="text-uppercase title">Companies attached</v-toolbar-title>
                                </v-toolbar>
                                <v-simple-table class="elevation-1">
                                    <template v-slot:default fixed-header>
                                        <thead>
                                            <tr>
                                            <th class="text-left">Name</th>
                                            <th class="text-left">Paid Price</th>
                                            <th class="text-left">is Restaurant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item,index) in event.companies" :key="index" >
                                            <td>{{ item.name }}</td>
                                            <td>{{ item.pivot.paidprice }}</td>
                                            <td >{{item.pivot.is_restaurant == 0 ? 'No' :'Yes'}}</td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
import DateHelperVue from '../mixins/DateHelper.vue';
export default {
    mixins:[DateHelperVue],
    data: () => ({
        show: false ,
        data_loaded: true ,
        event:[] ,
    }),
    methods: {
        to_event(e) {
           this.$router.push({name: 'view_event', params: { id: e },})
        },
         get_events_info() {
            this.data_loaded = false ;
            axios.get('/events/'+this.$route.params.id+'/edit', {})
            .then(response => {
                console.log(response.data)
                this.data_loaded = true ;
                this.event = response.data ;
            });
        },
    },
    created() {
        this.get_events_info();
    }
}
</script>