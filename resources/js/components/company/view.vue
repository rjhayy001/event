<template>
    <div>
        <v-toolbar flat color="white" class="mb-4">
            <v-toolbar-title class="text-uppercase title">view company</v-toolbar-title>
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
                        <v-img max-height="250" contain :src="companies.logo" lazy-src="https://cdn.dribbble.com/users/197853/screenshots/5506993/boat-wave.gif" ></v-img>
                        <v-card-title>Company Information</v-card-title>
                        <v-card-text>
                            <v-row align="center" class="mx-0">
                                <v-flex xs12>
                                    <v-text-field dense
                                        label="Company Name"
                                        outlined
                                        v-model="companies.name" readonly 
                                    ></v-text-field>
                                    <v-text-field dense
                                        label="Address1"
                                        outlined
                                        v-model="companies.address1" readonly 
                                    ></v-text-field>
                                    <v-text-field dense
                                        label="Address2"
                                        outlined
                                        v-model="companies.address2" readonly 
                                    ></v-text-field>
                                    <v-text-field dense
                                        label="Zip Code"
                                        outlined
                                        v-model="companies.zip_code" readonly 
                                    ></v-text-field>
                                    <v-text-field dense
                                        label="City"
                                        outlined
                                        v-model="companies.city" readonly 
                                    ></v-text-field>
                                    <v-text-field dense
                                        label="Country"
                                        outlined
                                        v-model="companies.country" readonly 
                                    ></v-text-field>
                                    <v-btn color="primary" text  @click="show = !show"> <v-icon class="mr-2">mdi-chevron-down</v-icon> company contact</v-btn>
                                </v-flex>
                            </v-row>
                        </v-card-text>
                        <v-divider class="mx-4"></v-divider>
                         <transition name="fade">
                            <div v-if="show">
                                <v-card-title>Contact Information</v-card-title>
                                <v-card-text>
                                    <v-row align="center" class="mx-0">
                                        <v-flex xs12>
                                            <v-text-field dense
                                                label="Contact Person"
                                                outlined
                                                v-model="companies.contacts.name" readonly 
                                            ></v-text-field>
                                            <v-text-field dense
                                                label="Contact Number"
                                                outlined
                                                v-model="companies.contacts.phone" readonly 
                                            ></v-text-field>
                                            <v-text-field dense
                                                label="Company Email"
                                                outlined
                                                v-model="companies.contacts.email" readonly 
                                            ></v-text-field>
                                        </v-flex>
                                    </v-row>
                                </v-card-text>
                            </div>
                         </transition>
                    </v-card>
                </v-flex>
                <v-flex xs12 sm8 class="mx-10">
                    past events and upcoming events
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>
<script>
export default {
    data: () => ({
        show: false ,
        data_loaded: true ,
        companies:[] ,
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
    methods: {
         get_company_info() {
            this.data_loaded = false ;
            axios.get('/companies/'+this.$route.params.id+'/edit', {})
            .then(response => {
                console.log(response.data)
                this.companies = response.data ;
            this.data_loaded = true ;

            });
        },
    },
    created() {
        this.get_company_info();
    }
}
</script>