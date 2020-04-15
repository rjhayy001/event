<template>
    <div>
        <v-toolbar flat color="white" class="mb-4">
            <v-toolbar-title class="text-uppercase title">Société </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn color="primary" tile @click="$router.go(-1)">
                <v-icon left>mdi-arrow-left-circle</v-icon>
               Retour
            </v-btn>
        </v-toolbar>
        <v-divider></v-divider>
        <v-container grid-list-md>
            <v-layout row wrap>
                <v-flex xs12 sm3>
                    <v-card>
                        <v-img max-height="250" contain :src="companies.logo" lazy-src="https://cdn.dribbble.com/users/197853/screenshots/5506993/boat-wave.gif" ></v-img>
                        <v-card-title>Informations</v-card-title>
                        <v-card-text>
                            <v-row align="center" class="mx-0">
                                <v-flex xs12>
                                    <v-text-field dense
                                        label="Nom de la société"
                                        outlined
                                        v-model="companies.name" readonly 
                                    ></v-text-field>
                                    <v-text-field dense
                                        label="Address 1"
                                        outlined
                                        v-model="companies.address1" readonly 
                                    ></v-text-field>
                                    <v-text-field dense
                                        label="Address 2"
                                        outlined
                                        v-model="companies.address2" readonly 
                                    ></v-text-field>
                                    <v-text-field dense
                                        label="Code postal"
                                        outlined
                                        v-model="companies.zip_code" readonly 
                                    ></v-text-field>
                                    <v-text-field dense
                                        label="Ville"
                                        outlined
                                        v-model="companies.city" readonly 
                                    ></v-text-field>
                                    <v-text-field dense
                                        label="Pays "
                                        outlined
                                        v-model="companies.country" readonly 
                                    ></v-text-field>
                                </v-flex>
                            </v-row>
                        </v-card-text>
                        <v-divider class="mx-4"></v-divider>
                            <v-card-title>Contact</v-card-title>
                            <v-card-text>
                                <v-row align="center" class="mx-0">
                                    <v-flex xs12>
                                        <v-text-field dense
                                            label="Nom Prénom"
                                            outlined
                                            v-model="companies.contacts.name" readonly 
                                        ></v-text-field>
                                        <v-text-field dense
                                            label="Téléphone "
                                            outlined
                                            v-model="companies.contacts.phone" readonly 
                                        ></v-text-field>
                                        <v-text-field dense
                                            label="Email"
                                            outlined
                                            v-model="companies.contacts.email" readonly 
                                        ></v-text-field>
                                    </v-flex>
                                </v-row>
                            </v-card-text>
                    </v-card>
                    
                </v-flex>
                <v-flex xs12 sm8 class="mx-10">
                    <v-container>
                        <v-layout row wrap >
                            <v-flex xs12 sm6>
                                <v-toolbar flat color="#E0E0E0" dense>
                                    <v-toolbar-title class="text-uppercase title">upcoming events</v-toolbar-title>
                                </v-toolbar>
                                <v-simple-table class="elevation-1">
                                    <template v-slot:default fixed-header>
                                        <thead>
                                            <tr>
                                            <th class="text-left">Nom </th>
                                            <th class="text-left">Date de début </th>
                                            <th class="text-left">Date de fin </th>
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
                                                        Aucun enregistrement 
                                                </td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-toolbar flat color="#E0E0E0" dense>
                                    <v-toolbar-title class="text-uppercase title">past events</v-toolbar-title>
                                </v-toolbar>
                                <v-simple-table class="elevation-1">
                                    <template v-slot:default fixed-header>
                                        <thead>
                                            <tr>
                                            <th class="text-left">Nom </th>
                                            <th class="text-left">Date de début </th>
                                            <th class="text-left">Date de fin </th>
                                            </tr>
                                        </thead>
                                        <tbody  class="font-weight-bold">
                                            <template v-if="past_event.length">
                                                <tr v-for="item in past_event" :key="item.name" @click="to_event(item.id)">
                                                    <td>{{ item.name }}</td>
                                                    <td>{{ fulldate(item.fromdate) }}</td>
                                                    <td>{{ item.todate ? fulldate(item.todate): 'not set' }}</td>
                                                </tr>
                                            </template>
                                            <tr v-else>
                                                <td colspan="3" class="font-weight-bold text-center">
                                                        Aucun enregistrement 
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
import Repository from "@/js/repositories/RepositoryFactory";
const CompanyRepository = Repository.get("companies");
export default {
    mixins:[DateHelperVue],
    data: () => ({
        
        show: false ,
        data_loaded: true ,
        companies:[] ,
        upcoming_event:[] ,
        today_event:[] ,
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
    methods: {
        to_event(e) {
           this.$router.push({name: 'view_event', params: { id: e },})
        },
         get_company_info() {
            this.data_loaded = false ;
            CompanyRepository.getCompany(this.$route.params.id)
            .then(response => {
                this.companies = response.data ;
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
                console.log(this.past_event , 'past')
                console.log(this.upcoming_event , 'up')
            });
        },
    },
    watch : {
        $route(){
            this.get_company_info();  
        }
    },
    created() {
        this.get_company_info();
    }
}
</script>