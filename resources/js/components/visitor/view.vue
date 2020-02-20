<template>
    <div>
        <v-toolbar flat color="white" class="mb-3">
            <v-toolbar-title class="text-uppercase title">Visitors information</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn color="primary" tile @click="$router.go(-1)">
                <v-icon left>mdi-arrow-left-circle</v-icon> back
            </v-btn>
        </v-toolbar>
        <v-divider></v-divider>
        <v-container grid-list-md >
            <v-layout row wrap v-if="!data_loaded">
                <v-flex xs12 >
                    <v-skeleton-loader
                        type="card"
                    ></v-skeleton-loader>
                </v-flex>
            </v-layout>
            <v-layout row wrap class="" v-else>
                <v-flex xs12 sm3 >
                   <v-card>
                        <v-avatar
                            class="mx-auto d-block"
                            size="130"
                            round
                        >
                            <img
                                :src=" visitor.image ? visitor.image : 'https://demos.creative-tim.com/vue-material-dashboard/img/marc.aba54d65.jpg'"
                            >
                        </v-avatar>
                        <v-list disabled>
                            <v-list-item-group color="primary" disabled>
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon >mdi-account-tie</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title >{{visitor.name}}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-divider></v-divider>
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon >mdi-email</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title >{{visitor.email}}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-divider></v-divider>
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon >mdi-phone</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title >{{visitor.phone}}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-divider></v-divider>
                                <v-list-item>
                                    <v-list-item-icon>
                                        <v-icon >mdi-alpha-a-box</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title >{{visitor.username}}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            
                            </v-list-item-group>
                        </v-list>

                   </v-card>
                </v-flex>
               <v-flex xs12 sm9>
                    <v-container>
                        <v-layout row wrap >
                            <v-flex xs12 sm6>
                                <v-toolbar flat color="#E0E0E0">
                                    <v-toolbar-title class="text-uppercase title">upcoming events</v-toolbar-title>
                                </v-toolbar>
                                <v-simple-table class="elevation-1">
                                    <template v-slot:default fixed-header>
                                        <thead>
                                            <tr>
                                            <th class="text-left">Name</th>
                                            <th class="text-left">Date Scheduled</th>
                                            <th class="text-left">End Date</th>
                                            </tr>
                                        </thead>
                                        <tbody  class="font-weight-bold"> 
                                            <template v-if="upcoming_event.length">
                                            <tr v-for="item in upcoming_event" :key="item.name" @click="to_event(item.id)">
                                            <td>{{ item.name }}</td>
                                            <td>{{ fulldate(item.fromdate) }}</td>
                                            <td>{{ item.todate ? fulldate(item.todate): 'not set' }}</td>
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
                                <v-toolbar flat color="#E0E0E0">
                                    <v-toolbar-title class="text-uppercase title">past events</v-toolbar-title>
                                </v-toolbar>
                                <v-simple-table class="elevation-1">
                                    <template v-slot:default fixed-header>
                                        <thead>
                                            <tr>
                                            <th class="text-left">Name</th>
                                            <th class="text-left">Date Scheduled</th>
                                            <th class="text-left">End Date</th>
                                            </tr>
                                        </thead>
                                        <tbody  class="font-weight-bold">
                                            <template v-if="past_event.length">
                                            <tr v-for="item in past_event" :key="item.name" @click="to_event(item.id)" >
                                            <td>{{ item.name }}</td>
                                            <td>{{ fulldate(item.fromdate) }}</td>
                                            <td>{{ item.todate ? fulldate(item.todate): 'not set' }}</td>
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
        visitor:[] ,
        upcoming_event:[] ,
        past_event:[] ,
        headers: [
            {
                text: 'Event Name',
                align: 'left',
                value: 'name',
            },
            { text: 'Duration', value: 'contact' },
            { text: 'Price', value: 'email' },
            { text: 'Stands', value: 'created_at' },
            { text: 'actions', value: 'action' },
        ],
    }),
    methods : {
        to_event(e) {
           this.$router.push({name: 'view_event', params: { id: e },})
        },
        get_visitors_info() {
            this.data_loaded = false ;
            axios.get('/visitors/'+this.$route.params.id+'/edit', {})
            .then(response => {
                console.log(response.data)
                this.visitor = response.data ;
                response.data.events.forEach(element => {
                    if(element.fromdate != null) {
                        if(this.fulldate(new Date()) < this.fulldate(element.fromdate)) {
                            this.upcoming_event.push(element)
                        }
                        if(this.fulldate(new Date()) > this.fulldate(element.fromdate)) {
                            this.past_event.push(element)
                        }
                    }
                });
                this.data_loaded = true ;

            });
        },
        getrow(e) {
            console.log(e)
        }
    },
    created () {
        this.get_visitors_info();
    }
}
</script>
<style scoped>
</style>