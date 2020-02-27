<template>
    <div>
        <v-toolbar flat color="white" class="mb-4">
            <v-toolbar-title class="text-uppercase title">view event</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn color="#078282FF" tile @click="map_dialog = !map_dialog" class="mr-3 custom_button">
                <v-icon left>mdi-map</v-icon>
               view map
            </v-btn>
            <v-btn color="teal" tile @click="open_modal" class="mr-3 custom_button">
                <v-icon left>mdi-eye</v-icon>
               view images used
            </v-btn>
            <v-btn color="primary" tile @click="$router.go(-1)">
                <v-icon left>mdi-arrow-left-circle</v-icon>
               back
            </v-btn>
        </v-toolbar>
        <v-divider></v-divider>
        <template v-if="!data_loaded"> 
            <v-flex xs12 >
                    <v-skeleton-loader
                        type="table-tbody"
                    ></v-skeleton-loader>
                </v-flex>
                </template>
        <v-container grid-list-md v-else>
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
                                                <td class="font-weight-bold">{{event.from ? fulldate(event.from) : 'not set'}}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold text-capitalize">End Date</td>
                                                <td class="font-weight-bold">{{event.to ? fulldate(event.to) : 'not set'}}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold text-capitalize">Place</td>
                                                <td class="font-weight-bold">{{event.place ? event.place : 'not set'}}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold text-capitalize">Description</td>
                                                <td class="font-weight-bold">{{event.description  | striphtml}}</td>
                                            </tr>
                                        </tbody>
                                </v-simple-table>
                                </v-flex>
                            </v-row>
                        </v-card-text>
                        <v-card-title>Price Information</v-card-title>
                       
                        <v-simple-table v-if="event.prices.length ">
                            <tbody >
                                <tr v-for="(item,index) in event.prices" :key="index" >
                                    <td class="font-weight-bold text-capitalize"> {{item.name}}</td>
                                    <td class="font-weight-bold">{{item.price}}</td>
                                </tr>
                            </tbody>
                        </v-simple-table>
                         <div class="mb-4" v-else  >
                        <v-alert type="info" outlined dense class="ma-3 mb-5" >
                            No fixed prices yet.
                        </v-alert>
                        </div>
                        <v-divider></v-divider>
                    </v-card>
                </v-flex>
                <v-flex xs12 sm9 >
                    <v-container>
                        <v-layout row wrap >
                            <v-flex sm12 xs12>
                                <v-toolbar flat :color="event.notify == 1 ? 'success' : 'error'" dense>
                                    <v-toolbar-title class="text-uppercase title custom_button" > {{event.notify == 1 ? 'notification sent' : 'notification not sent' }}</v-toolbar-title>
                                    <v-spacer></v-spacer>
                                    
                                     <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" :color="event.notify == 1 ? 'teal' : 'success'" @click="send_notification" small tile :loading="loading"  class="custom_button">
                                        <v-icon left>mdi-map</v-icon>
                                        {{event.notify == 1 ? 'resend notification' : 'send notification' }}
                                    </v-btn>
                                    </template>
                                    <span>  {{event.notify == 1 ? 'Resend notification to all user ' : 'Send notification to all user ' }}</span>
                                    </v-tooltip>
                                </v-toolbar>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-toolbar flat color="#E0E0E0" dense>
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
                                        <tbody class="font-weight-bold">
                                            <template v-if="event.programs.length">
                                                <tr v-for="(item,index) in event.programs" :key="index" >
                                                    <td class="width:30% ">{{ item.name }}</td>
                                                    <td >{{ item.time }}</td>
                                                    <td >{{item.details | striphtml}}</td>
                                                </tr>
                                            </template>
                                            <tr v-else>
                                                <td colspan="3" class="font-weight-bold text-center">
                                                        No data saved.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </v-flex>
                            <v-flex xs12 sm6 class="pl-4" >
                                <v-toolbar flat color="#E0E0E0" dense>
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
                                        <tbody class="font-weight-bold">
                                            <template v-if="event.companies.length">
                                                <tr v-for="(item,index) in event.companies" :key="index" >
                                                <td>{{ item.name }}</td>
                                                <td>{{ item.paidprice ? item.paidprice : 'not set'  }}</td>
                                                <td >{{item.is_restaurant == 0 ? 'No' :'Yes'}}</td>
                                                </tr>
                                            </template>
                                            <tr v-else>
                                                <td colspan="3" class="font-weight-bold text-center">
                                                        No data saved.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </v-flex>
                            <v-flex xs12 class="my-4">
                            <v-divider></v-divider>
                                <v-toolbar flat color="#E0E0E0" dense>
                                    <v-toolbar-title class="text-uppercase title">Highlights of this EVent</v-toolbar-title>
                                </v-toolbar>
                                <v-layout row wrap>
                                    
                                <v-flex xs12 sm6>
                                <p class="font-weight-bold">Programs</p>
                                <v-simple-table class="elevation-1">
                                    <template v-slot:default fixed-header>
                                        <thead>
                                            <tr>
                                            <th class="text-left">Title</th>
                                            <th class="text-left">Time Scheduled</th>
                                            <th class="text-left">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody  class="font-weight-bold">
                                            <template v-if="program_highlight.length">
                                                <tr v-for="(item,index) in program_highlight" :key="index" >
                                                    <td class="width:30%">{{ item.name }}</td>
                                                    <td>{{ item.time }}</td>
                                                    <td>{{item.details | striphtml}}</td>
                                                </tr>
                                            </template>
                                            <tr v-else>
                                               <td colspan="3" class="font-weight-bold text-center">
                                                        No data saved.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <p class="font-weight-bold">Companies</p>
                                <v-simple-table class="elevation-1">
                                    <template v-slot:default fixed-header>
                                        <thead>
                                            <tr>
                                            <th class="text-left">Name</th>
                                            <th class="text-left">Paid Price</th>
                                            <th class="text-left">is Restaurant</th>
                                            </tr>
                                        </thead>
                                        <tbody  class="font-weight-bold">
                                            <template v-if="company_highlight.length">
                                            <tr v-for="(item,index) in company_highlight" :key="index" >
                                            <td>{{ item.name }}</td>
                                            <td>{{ item.pivot.paidprice ? item.pivot.paidprice : 'not set'  }}</td>
                                            <td >{{item.pivot.is_restaurant == 0 ? 'No' :'Yes'}}</td>
                                            </tr>
                                            </template>
                                            <tr v-else>
                                               <td colspan="3" class="font-weight-bold text-center">
                                                        No data saved.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </v-flex>
                            </v-layout>
                            </v-flex>
                        </v-layout>
                        <v-dialog v-model="dialog2" width="50%" min-height="80%" >
                            <v-card>
                                <v-container grid-list-md>
                                    <v-layout row wrap>
                                        <v-flex xs12>
                                            <v-toolbar flat color="#E0E0E0" dense >
                                                <v-toolbar-title class="text-uppercase title">images used</v-toolbar-title>
                                                <v-spacer></v-spacer>
                                                <v-btn color="teal" tile @click="dialog2 = !dialog2" icon class="mr-3 custom_button" depressed>
                                                    <v-icon left>mdi-close-circle</v-icon>
                                                </v-btn>
                                            </v-toolbar>
                                            <v-divider></v-divider>
                                            <v-card>
                                                <v-layout row wrap>
                                                    <v-flex xs4>
                                                        <v-img :src="event.logo" contain class="white--text align-end" gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                                        height="200px"
                                                        >
                                                            <v-card-title>Event Logo</v-card-title>
                                                        </v-img>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-img :src="event.presentation_image" contain class="white--text align-end" gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                                        height="200px"
                                                        >
                                                            <v-card-title>Presentation Image</v-card-title>
                                                        </v-img>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-img :src="event.programme_image" contain class="white--text align-end" gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                                        height="200px"
                                                        >
                                                            <v-card-title>Programme Image</v-card-title>
                                                        </v-img>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-img :src="event.tarifs_image" contain class="white--text align-end" gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                                        height="200px"
                                                        >
                                                            <v-card-title>Tarifs Image</v-card-title>
                                                        </v-img>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-img :src="event.plan_image" contain class="white--text align-end" gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                                        height="200px"
                                                        >
                                                            <v-card-title>Plan Image</v-card-title>
                                                        </v-img>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-img :src="event.restaurant_image" contain class="white--text align-end" gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                                        height="200px"
                                                        >
                                                            <v-card-title>Restauration Image</v-card-title>
                                                        </v-img>
                                                    </v-flex>
                                                    <v-flex xs4>
                                                        <v-img :src="event.highlights_image" contain class="white--text align-end" gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                                                        height="200px"
                                                        >
                                                            <v-card-title>Highlight Image</v-card-title>
                                                        </v-img>
                                                    </v-flex>
                                                </v-layout>
                                            </v-card>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card>
                        </v-dialog>
                        <v-dialog v-model="map_dialog" width="50%" min-height="80%" >
                            <v-card>
                                <v-container grid-list-md>
                                    <v-layout row wrap>
                                        <v-flex xs12>
                                            <v-toolbar flat color="#E0E0E0" dense >
                                                <v-toolbar-title class="text-uppercase title">Map of this event</v-toolbar-title>
                                                <v-spacer></v-spacer>
                                                <v-btn color="teal" tile @click="map_dialog = !map_dialog" icon class="mr-3 custom_button" depressed>
                                                    <v-icon left>mdi-close-circle</v-icon>
                                                </v-btn>
                                            </v-toolbar>
                                            <v-divider></v-divider>
                                            <v-card>
                                                <v-layout row wrap>
                                                    <v-flex xs12>
                                                        <v-img cover :src="event.fullmap ? event.fullmap : event.map" aspect-ratio="1.2" lazy-src="https://i.pinimg.com/originals/2a/6b/65/2a6b651433f3c6ece42ba25439f76c0d.gif" >
                                                        </v-img>
                                                    </v-flex>
                                                </v-layout>
                                            </v-card>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card>
                        </v-dialog> 
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
        loading:false,
        dialog2:false,
        map_dialog:false,
        show: false ,
        data_loaded: true ,
        event: {
            prices:[],
            companies:[],
            programs:[],
        },
        program_highlight:[],
        company_highlight:[],
    }),
    methods: {
        send_notification(){
            this.loading = true ;
            axios.post('/notify', this.event )
            .then((response) =>  {
                console.log(response.data)
                this.$store.commit('setSnack', 'Notification Sent !')
                this.loading = false ;
                this.get_events_info()
            })
        },
        open_modal(){
           this.dialog2 = true ;
        },
        to_event(e) {
           this.$router.push({name: 'view_event', params: { id: e },})
        },
         get_events_info() {
            this.data_loaded = false ;
            axios.get('/events/'+this.$route.params.id+'/edit', {})
            .then(response => {
                // console.log(response.data.finalevent ,'event')
                this.data_loaded = true ;
                this.event = response.data.finalevent ;
                this.program_highlight =response.data.program_highlight ;
                this.company_highlight = response.data.company_highlight;
            });
        },
    },
    watch : {
        $route(){
            this.get_events_info();  
        }
    },
    created() {
        this.get_events_info();
    }
}
</script>
<style scoped>
  .gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(20rem, 1fr));
    grid-gap: 1rem;
    max-width: 80rem;
    margin: 5rem auto;
    padding: 0 5rem;
  }
 
</style>