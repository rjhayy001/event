<template>
    <div>
        <v-container>
            <v-layout row wrap>
                <v-toolbar flat >
                        <v-toolbar-title class="font-weight-bold">Visitor's List</v-toolbar-title>
                            <v-icon depressed class="float-left mx-2"  @click="expand = !expand" >mdi-magnify</v-icon>
                        <v-expand-x-transition >
                            <v-text-field width="30" class="pt-5 pl-3" v-show="expand" dense filled color="teal" v-model="search" placeholder="Search Visitor..." ></v-text-field>
                        </v-expand-x-transition>
                     <v-flex sm2 xs6 flex >
                    </v-flex>
                    <v-spacer></v-spacer>
                    <v-btn color="teal" tile class="custom_button" to="visitor/create" >
                       <v-icon left >mdi-plus</v-icon> add visitor
                    </v-btn>
                    </v-toolbar>
                 <v-flex xs12 v-if="!data_loaded">
                    <v-skeleton-loader
                        type="table-tbody"
                    ></v-skeleton-loader>
                </v-flex>
                <v-flex xs12 class="pa-4" v-else>
                    <v-data-table
                        :headers="headers"
                        fixed-header
                        :search="search"
                        :items="visitors"
                        :items-per-page="10"
                        :hide-default-footer="visitors.length < 10"
                        color="primary"
                        class="elevation-1"
                        @click:row = "getrow"
                    >
                            <template v-slot:item.created_at="{ item }">
                              {{ fulldate(item.created_at)}}
                            </template>
                            <template v-slot:item.active="{ item }">
                              <v-switch v-model="item.active"   @click.stop="required_change(item)"  color="teal"></v-switch>
                            </template>
                        <template v-slot:item.action="{ item }">
                             <v-menu bottom origin="right center" transition="scale-transition">
                                <template v-slot:activator="{ on }">
                                    <v-btn text color="teal" dark v-on="on" @click.stop>
                                        <v-icon>mdi-dots-vertical</v-icon>
                                    </v-btn>
                                </template>
                                <v-list class="pa-0">
                                     <v-list-item @click="$router.push({name: 'view_visitor', params: { id: item.id },})" >
                                        <v-list-item-icon class="mr-0">
                                            <v-icon size="20" color="primary">mdi-eye</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-title>
                                            View
                                        </v-list-item-title>
                                    </v-list-item>
                                    <v-list-item @click="$router.push({name: 'edit_visitor', params: { id: item.id },})" >
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
                    </v-data-table>
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
        data_loaded : true ,
        expand:'',
        visitors:[],
        search:'',
        headers: [
            {
                text: 'Visitor Name',
                align: 'left',
                value: 'name',
            },
            { text: 'Contact', value: 'contact',sortable: false },
            { text: 'Email Address', value: 'email',sortable: false },
            { text: 'Registered at', value: 'created_at',sortable: false },
            { text: 'Active', value: 'active',sortable: false },
            { text: 'actions', value: 'action',sortable: false },
        ],
    }),

    methods : {
        required_change(item) {
            item.active = item.active == 1 ? 0 : 1 ;
            axios.post('/activate',item)
            .then((response) =>  {
            this.visitors = response.data ;
            })
        },
        get_visitors() {
            this.data_loaded = false ;
            axios.get('/visitors', {})
            .then(response => {
                this.visitors = response.data;
                console.log(this.visitors)
                this.data_loaded = true;
            });
        },
        getrow(e) {
            this.$router.push({name: 'view_visitor', params: { id: e.id },})
        },
        destroy(id) {
            this.$root.$confirm('Are you sure you want to delete ?').then((result) => {
                if(result) {
                    axios.delete('/visitors/'+id, {})
                    .then((response) =>  {
                        this.$store.commit('setSnack', 'Visitor Deleted !')
                        console.log(response.data)
                        this.visitors = response.data;
                    });
                }
            })
        }
    },
    watch : {
        $route(){
            this.get_visitors();  
        }
    },
    created() {
        this.get_visitors()
    }
}
</script>