<template>
    <div>
        <v-container>
            <v-layout row wrap>
                <v-flex xs12 class="px-3">
                    <v-toolbar flat >
                        <v-toolbar-title class="font-weight-bold">Liste des événements</v-toolbar-title>
                            <v-icon depressed class="float-left mx-2"  @click="expand = !expand" >mdi-magnify</v-icon>
                        <v-expand-x-transition >
                            <v-text-field width="30" class="pt-5 pl-3" v-show="expand" dense filled color="teal" v-model="search" placeholder="Rechercher un événement..." ></v-text-field>
                        </v-expand-x-transition>
                     <v-flex sm2 xs6 flex >
                    </v-flex>
                    <v-spacer></v-spacer>
                    <v-btn color="teal" tile class="custom_button" to="event/create" >
                       <v-icon left >mdi-plus</v-icon> ajouter 
                    </v-btn>
                    </v-toolbar>
                </v-flex>
                 <v-flex xs12 v-if="!data_loaded">
                    <v-skeleton-loader
                        type="table-tbody"
                    ></v-skeleton-loader>
                </v-flex>
                <v-flex xs12 class="px-4 pb-4" v-else>
                    <v-data-table
                        :headers="headers"
                        fixed-header
                        :items="events"
                        :items-per-page="10"
                        :search="search"
                        :hide-default-footer="events.length < 10"
                        color="primary"
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
                                    class="ma-2"
                                    label
                                    text-color="white"
                                    >
                                    {{item.from}}
                                </v-chip>
                                    <v-chip v-else
                                    color="grey"
                                    class="ma-2"
                                    label
                                    text-color="white"
                                    >
                                    {{item.from}}
                                </v-chip>
                        </template>
                        <template v-slot:item.to="{ item }">
                                <v-chip v-if="item.to != 'no given date'"
                                    color="error"
                                    class="ma-2"
                                    label
                                    text-color="white"
                                    >
                                    {{item.to}}
                                </v-chip>
                                    <v-chip v-else
                                    color="grey"
                                    class="ma-2"
                                    label
                                    text-color="white"
                                    >
                                    {{item.to}}
                                </v-chip>
                        </template>
                    </v-data-table>
                    <!-- <div class="text-center mt-5">
                        <v-pagination
                            v-model="page"
                            :length="15"
                            :total-visible="7"
                        >
                        </v-pagination>
                    </div> -->
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>
<script>
import Repository from "@/js/repositories/RepositoryFactory";
const EventRepository = Repository.get("events");
export default {
    data: () => ({
        data_loaded : true ,
        events:[],
        page:1,
        expand:'',
        search:'',
        headers: [
            {
                text: "nom de l'événement",
                align: 'left',
                value: 'name',
            },
            { text: 'date de début', value: 'from' ,sortable: false },
            { text: 'date de fin', value: 'to' ,sortable: false },
            { text: 'entreprise participant', value: 'company_count' ,sortable: false },
            { text: 'visiteurs attendus', value: 'visitor_count' ,sortable: false },
            { text: 'actions', value: 'action' },
        ],
    }),

    methods : {
        get_events() {
            this.data_loaded = false ;
            EventRepository.getEvents()
            .then(({data}) => {
                this.events = data;
                console.log(data , 'events')
                this.data_loaded = true;
            });
        },
        getrow(e) {
            this.$router.push({name: 'view_event', params: { id: e.id },})
        },
        destroy(id) {
            this.$root.$confirm('Are you sure you want to delete ?').then((result) => {
                if(result) {
                    EventRepository.delete(id)
                     .then(({data}) =>  {
                         console.log(data)
                         this.events = data;
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