<template>
    <v-container >
        <v-layout row wrap>
            <v-flex xs3>
                    <v-card
                        class="mx-auto purple "
                        max-width="344"
                        outlined color="primary"
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
                            <v-icon size="60" color="primary">mdi-office</v-icon>
                            </v-list-item-avatar>
                        </v-list-item>
                        <v-card-actions >
                            <v-btn @click="$router.push({name: 'company'})"  text>view</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            <v-flex xs3>
                <v-card
                    class="mx-auto purple "
                    max-width="344"
                    outlined 
                    color="info"
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
                        <v-icon size="60" color="primary">mdi-account-group</v-icon>
                        </v-list-item-avatar>
                    </v-list-item>
                    <v-card-actions >
                        <v-btn @click="$router.push({name: 'visitor'})"  text>view</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
            <v-flex xs3>
                <v-card
                    class="mx-auto purple "
                    max-width="344"
                    outlined 
                    color="primary"
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
                        <v-icon size="60" color="primary">mdi-history</v-icon>
                        </v-list-item-avatar>
                    </v-list-item>
                    <v-card-actions >
                        <v-btn @click="$router.push({name: 'event'})" text>view</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
export default {
    data: () => ({
        companies: [],
        visitors: [],
        events: [],
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
            axios.get('/events', {})
            .then(response => {
                this.events = response.data;
                console.log(this.events)
            });
        }
    },
    created: function () {
        this.get_companies()
        this.get_visitors()
        this.get_events()
    },
}
</script>