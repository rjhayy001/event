<template>
    <div>
        <v-container>
            <v-layout row wrap>
                <v-flex xs12 class="px-3">
                    <v-toolbar flat >
                    <v-toolbar-title class="font-weight-bold">Visitor's List</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn color="teal" tile class="custom_button" to="visitor/create" >
                       <v-icon class="pr-2">mdi-plus</v-icon> add visitor
                    </v-btn>
                    </v-toolbar>
                </v-flex>
                 <v-flex xs12 v-if="!data_loaded">
                    <v-skeleton-loader
                        type="table-tbody"
                    ></v-skeleton-loader>
                </v-flex>
                <v-flex xs12 class="pa-4" v-else>
                    <v-layout row wrap justify-end class="mr-3">
                        <v-flex xs3 >
                            <v-text-field right class="mb-4" color="teal" v-model="search" append-icon="mdi-magnify" label="Search Visitor ..." single-line hide-details ></v-text-field>
                        </v-flex>
                    </v-layout>
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
export default {
    data: () => ({
        data_loaded : true ,
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
            { text: 'actions', value: 'action',sortable: false },
        ],
    }),

    methods : {
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
    created() {
        this.get_visitors()
    }
}
</script>