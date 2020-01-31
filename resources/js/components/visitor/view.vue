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
                                src="https://demos.creative-tim.com/vue-material-dashboard/img/marc.aba54d65.jpg"
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
                <v-flex xs12 sm8 class="mx-10">
                     <v-toolbar flat color="white" class="">
                        <v-toolbar-title class="text-uppercase title">Upcoming Events</v-toolbar-title>
                    </v-toolbar>
                    <v-card >
                       <v-data-table
                        :headers="headers"
                        fixed-header
                        :items="upcoming_event"
                        hide-default-footer
                        disable-pagination
                        class=" mb-3"
                        @click:row = "getrow"
                        ></v-data-table>
                   </v-card>

                    <v-toolbar flat color="white" class="">
                        <v-toolbar-title class="text-uppercase title">Past Events</v-toolbar-title>
                    </v-toolbar>
                    <v-card >
                       <v-data-table
                            :headers="headers"
                            fixed-header
                            :items="upcoming_event"
                            hide-default-footer
                            disable-pagination
                            class=" mb-3"
                            @click:row = "getrow"
                        ></v-data-table>
                   </v-card>
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
        get_visitors_info() {
            this.data_loaded = false ;
            axios.get('/visitors/'+this.$route.params.id+'/edit', {})
            .then(response => {
                console.log(response.data)
                this.visitor = response.data ;
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