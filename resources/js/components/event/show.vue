<template>
    <div>
        <v-container>
            <v-layout row wrap>
                <v-flex xs12 class="px-3">
                    <v-toolbar flat >
                    <v-toolbar-title class="font-weight-bold">Event's List</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn color="teal" tile class="custom_button" to="event/create" >
                       <v-icon class="pr-2">mdi-plus</v-icon> add event
                    </v-btn>
                    </v-toolbar>
                </v-flex>
                 <v-flex xs12 v-if="!data_loaded">
                    <v-skeleton-loader
                        type="table-tbody"
                    ></v-skeleton-loader>
                </v-flex>
                <v-flex xs12 class="px-4 pb-4" v-else>
                    <v-layout row wrap justify-end class="mr-3">
                        <v-flex xs3 >
                            <v-text-field right class="mb-4 mr-3" color="teal" v-model="search" append-icon="mdi-magnify" label="Search Event" single-line hide-details ></v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-data-table
                        :headers="headers"
                        fixed-header
                        :items="events"
                        hide-default-footer
                        :search="search"
                        color="primary"
                        disable-pagination
                        class="elevation-1"
                        @click:row = "getrow"
                    >
                        <template v-slot:item.action="{ item }">
                             <v-menu bottom origin="right center" transition="scale-transition">
                                <template v-slot:activator="{ on }">
                                    <v-btn text color="teal" dark v-on="on" @click.stop>
                                        <v-icon>mdi-dots-vertical</v-icon>
                                    </v-btn>
                                </template>
                                <v-list class="pa-0">
                                     <v-list-item @click="$router.push({name: 'view_event', params: { id: item.id },})" >
                                        <v-list-item-icon class="mr-0">
                                            <v-icon size="20" color="primary">mdi-eye</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-title>
                                            View
                                        </v-list-item-title>
                                    </v-list-item>
                                    <v-list-item  @click="$router.push({name: 'edit_event', params: { id: item.id },})">
                                        <v-list-item-icon class="mr-0">
                                            <v-icon size="20" color="info">mdi-pencil</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-title>
                                            Edit
                                        </v-list-item-title>
                                    </v-list-item>
                                    <v-list-item @click="destroy(item.id)" >
                                        <v-list-item-icon class="mr-0">
                                            <v-icon size="20" color="error">mdi-delete</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-title>
                                            Delete
                                        </v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </template>
                        <template v-slot:item.from="{ item }">
                                <v-chip v-if="item.from != 'no given date'"
                                    color="primary"
                                    >
                                    {{item.from}}
                                </v-chip>
                                <p v-else>{{item.from}}</p>
                        </template>
                        <template v-slot:item.to="{ item }">
                                <v-chip v-if="item.to != 'no given date'"
                                    color="error"
                                    >
                                    {{item.to}}
                                </v-chip>
                                <p v-else>{{item.to}}</p>
                        </template>
                    </v-data-table>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>
<script>
export default {
    data: () => ({
        data_loaded : true ,
        events:[],
        search:'',
        headers: [
            {
                text: 'Event Name',
                align: 'left',
                value: 'name',
            },
            { text: 'Start Date', value: 'from' ,sortable: false },
            { text: 'End Date', value: 'to' ,sortable: false },
            { text: 'company attending', value: 'company_count' ,sortable: false },
            { text: 'expected visitors', value: 'visitor_count' ,sortable: false },
            { text: 'actions', value: 'action' },
        ],
    }),

    methods : {
        get_events() {
            this.data_loaded = false ;
            axios.get('/events', {})
            .then(response => {
                this.events = response.data;
                console.log(response.data)
                this.data_loaded = true;
            });
        },
        getrow(e) {
            this.$router.push({name: 'view_event', params: { id: e.id },})
        },
        destroy(id) {
            this.$root.$confirm('Are you sure you want to delete ?').then((result) => {
                if(result) {
                    axios.delete('/events/'+id, {})
                     .then((response) =>  {
                         console.log(response.data)
                         this.events = response.data;
                        this.$store.commit('setSnack', 'Event Deleted !')
                    });
                }
            })
        }
    },
    created() {
        this.get_events()
    }
}
</script>