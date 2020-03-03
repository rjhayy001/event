<template>
    <div>
        <v-container grid-list-xs>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-toolbar flat >
                        <v-toolbar-title class="font-weight-bold">Age Categories List</v-toolbar-title>
                    </v-toolbar>
                </v-flex>
                <v-flex xs12 sm8 >
                    <v-form ref="form">
                        <v-container  grid-list-md>
                            <v-layout row wrap>
                                <v-flex xs12 >
                                    <v-card class="pa-5" >
                                        <v-layout row wrap mb-3 class="text-capitalize">
                                            <v-flex xs12>
                                                <p class="subheading pa-0 font-weight-bold">Category Information</p>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-text-field 
                                                    type="text"
                                                    v-model="category.name"
                                                    v-validate="'required'" 
                                                    :error-messages="errors.collect('Category Name')" 
                                                    data-vv-name="Category Name" 
                                                    label="Category Name" required>
                                                </v-text-field>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout row wrap mb-3 justify-end>
                                            <v-flex xs12 class="text-right">
                                                <v-btn color="teal" class="custom_button" tile @click="submit"  >
                                                    <v-icon left>mdi-content-save-edit-outline</v-icon>
                                                    save category
                                                </v-btn>
                                                <v-btn color="primary" tile @click="clear"  >
                                                    <v-icon left>mdi-lock-reset</v-icon>
                                                    reload form
                                                </v-btn>
                                            </v-flex>
                                        </v-layout>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-form>
                </v-flex>
                <v-flex xs12 sm4 class="pa-4">
                    <v-data-table
                        :headers="headers"
                        fixed-header
                        :items="categories"
                        height="430"
                        hide-default-footer
                        disable-pagination
                        class="elevation-1"
                    >
                        <template v-slot:item.action="{ item }">
                             <v-menu bottom origin="right center" transition="scale-transition">
                                <template v-slot:activator="{ on }">
                                    <v-btn text color="teal" dark v-on="on" @click.stop>
                                        <v-icon>mdi-dots-vertical</v-icon>
                                    </v-btn>
                                </template>
                                <v-list class="pa-0">
                                    <v-list-item @click="get_category_edit(item.id)" >
                                        <v-list-item-icon class="mr-0">
                                            <v-icon size="20" color="primary">mdi-pencil</v-icon>
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
import Repository from "@/js/repositories/RepositoryFactory";
const CategoryRepository = Repository.get("categories");
    export default {
        $_veeValidate: {
            validator: 'new'
        },
        data: () => ({
            default_footer:true,
            no_items:true,
            categories: [],
            max_height: '100px',
            category : {
                name: '',
                id :''
            },
            headers: [
          {
            text: 'Category Name',
            align: 'left',
            sortable: false,
            value: 'person',
          },
          ,
          { text: 'created at', value: 'created_at' },
          { text: 'actions', value: 'action' },
        ],
        }),
        methods: {
            submit(){
                let self = this;
                this.$validator.validateAll().then(result => {
                    if (result){
                        this.$store.commit('setOverlay' , true);
                        this.$root.$confirm('Are you sure you want to save ?').then((result) => {
                            if(result) {
                                let dis = this ;
                                let id = this.category.id ;
                                if(id != '' ) {
                                    CategoryRepository.update(this.category ,id)
                                    .then(({data}) => {
                                        this.$store.commit('setOverlay' , false);
                                        this.$store.commit('setSnack', 'Category Updated !')
                                        console.log(data)
                                        dis.get_categories()
                                        dis.clear()
                                        dis.category.id= ''
                                    })
                                } else {
                                CategoryRepository.create(this.category)
                                .then(({data}) => {
                                    this.$store.commit('setOverlay' , false);
                                    this.$store.commit('setSnack', 'Category Saved !')
                                    dis.get_categories()
                                    console.log(data , ' saved')
                                    dis.clear()
                                })
                                }
                            }
                        })
                    }
                });
            },
            destroy(id){
                this.$root.$confirm('Are you sure you want to delete ?').then((result) => {
                    if(result) {
                        CategoryRepository.delete(id)
			            .then(({data}) => {
                            this.$store.commit('setSnack', 'Category Deleted !')
                            console.log(data)
                            this.get_categories()
                            this.clear()
			            });
                    }
                })
            },
            get_category_edit(id) {
                CategoryRepository.getCategory(id)
			    .then(({data}) => {
                    console.log(data)
                    this.category.name = data.person ;
                    this.category.id = data.id ;
			    });
            },
            clear () {
                this.$validator.reset()
                this.$refs.form.reset()
            },
            get_categories() {
                // axios.get('/categories', {})
                CategoryRepository.get()
			    .then(({data}) => {
                    this.categories = data;
                    console.log(this.categories)
                    this.data_loaded = true;
                });
            }
        },
        created() {
            this.get_categories()
	    },
    }
</script>
<style scoped>
    .my_table {
        max-height: 100px !important ;
    }
</style>