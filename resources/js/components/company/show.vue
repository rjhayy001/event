<template>
    <div>
        <v-container>
            <v-layout row wrap>
                <v-flex xs12 class="px-3">
                    <v-toolbar flat >
                        <v-toolbar-title class="font-weight-bold">Liste des entreprises</v-toolbar-title>
                            <v-icon depressed class="float-left mx-2"  @click="expand = !expand" >mdi-magnify</v-icon>
                        <v-expand-x-transition >
                            <v-text-field width="30" class="pt-5 pl-3" v-show="expand" dense filled color="teal" v-model="search" placeholder="Rechercher une entreprise..." ></v-text-field>
                        </v-expand-x-transition>
                     <v-flex sm2 xs6 flex >
                    </v-flex>
                    <v-spacer></v-spacer>
                    <v-btn color="teal" tile class="custom_button" to="company/create" >
                       <v-icon left >mdi-plus</v-icon> ajouter
                    </v-btn>
                    </v-toolbar>
                </v-flex>
                 <v-flex xs12 v-if="!data_loaded">
                    <v-skeleton-loader
                        type="table-tbody"
                    ></v-skeleton-loader>
                </v-flex>
                <v-flex xs12 class="pa-4" v-else>
                    <v-data-table
                        :headers="headers"
                        fixed-header
                        :items="companies"
                        :items-per-page="10"
                        :hide-default-footer="companies.length < 10"
                        :search="search"
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
                                     <v-list-item @click="$router.push({name: 'view_company', params: { id: item.id },})" >
                                        <v-list-item-icon class="mr-0">
                                            <v-icon size="20" color="primary">mdi-eye</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-title>
                                            Voir 
                                        </v-list-item-title>
                                    </v-list-item>
                                    <v-list-item @click="$router.push({name: 'edit_company', params: { id: item.id },})" >
                                        <v-list-item-icon class="mr-0">
                                            <v-icon size="20" color="info">mdi-pencil</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-title>
                                            Editer
                                        </v-list-item-title>
                                    </v-list-item>
                                    <v-list-item @click="destroy(item.id)" >
                                        <v-list-item-icon class="mr-0">
                                            <v-icon size="20" color="error">mdi-delete</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-title>
                                            Supprimer 
                                        </v-list-item-title>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </template>
                          <template v-slot:item.created_at="{ item }">
                              {{ fulldate(item.created_at)}}
                          </template>
                    </v-data-table>
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
        data_loaded : true ,
        companies:[],
        expand:'',
        search:'',
        headers: [
            {
                text: 'Nom de la société',
                align: 'left',
                value: 'name',
            },
            { text: 'Personne de contact', value: 'contact person',sortable: false },
            { text: 'Téléphone ', value: 'contact number',sortable: false },
            { text: "Email", value: 'company email' ,sortable: false},
            { text: 'Enregistré le', value: 'created_at',sortable: false},
            { text: 'Actions', value: 'action' ,sortable: false },
        ],
    }),

    methods : {
        get_company() {
            this.data_loaded = false ;
            CompanyRepository.get()
            .then(({data}) => {
                this.companies = data;
                console.log(data , 'companies')
                this.data_loaded = true;
            });
        },
        getrow(e) {
            this.$router.push({name: 'view_company', params: { id: e.id },})
        },
        destroy(id) {
            this.$root.$confirm('Are you sure you want to delete ?').then((result) => {
                if(result) {
                    CompanyRepository.delete(id)
                    .then(({data}) => {
                        console.log(data)
                        this.companies = data;
                        this.$store.commit('setSnack', 'Company Deleted !')
                    });
                }
            })
        }
    },
    created() {
        this.get_company()
    }
}
</script>