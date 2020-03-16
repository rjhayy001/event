<template>
    <div>
        <v-toolbar flat color="white" class="mb-3">
            <v-toolbar-title class="text-uppercase title">ajouter une entreprise</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn color="primary" tile @click="$router.go(-1)">
                <v-icon left>mdi-arrow-left-circle</v-icon>
               Retour
            </v-btn>
        </v-toolbar>
        <v-divider></v-divider>
         <v-form ref="seller_form">
            <v-container  grid-list-md>
                <v-layout row wrap>
                    <v-flex xs12 md8>
                        <v-card class="pa-5" >
                            <v-layout row wrap mb-3 class="text-capitalize">
                                <v-flex xs12>
                                    <p class="subheading pa-0 font-weight-bold">Information de la société</p>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field 
                                        type="text"
                                        v-model="company.name"
                                        v-validate="'required|min:3'" 
                                        :error-messages="errors.collect('Company Name')" 
                                        data-vv-name="Company Name" 
                                        label="Nom de la société" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field 
                                        type="text"
                                        v-model="company.contacts.name"
                                        v-validate="'required|min:3'" 
                                        :error-messages="errors.collect('Contact Person')" 
                                        data-vv-name="Contact Person" 
                                        label="Contact Person" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field 
                                        type="text"
                                        v-model="company.address1"
                                        v-validate="'min:3'" 
                                        :error-messages="errors.collect('Address1')" 
                                        data-vv-name="Address1" 
                                        label="Adresse 1" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field 
                                        type="number"
                                        v-model="company.contacts.phone"
                                        v-validate="'required|min:3'" 
                                        :error-messages="errors.collect('Contact Number')" 
                                        data-vv-name="Contact Number" 
                                        label="Téléphone " required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field 
                                        type="text"
                                        v-model="company.address2"
                                        v-validate="'min:3'" 
                                        :error-messages="errors.collect('Address2')" 
                                        data-vv-name="Address2" 
                                        label="Adresse 2" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field 
                                        type="text"
                                        v-model="company.contacts.email"
                                        v-validate="'required|min:3|email'" 
                                        :error-messages="errors.collect('Company Email')" 
                                        data-vv-name="Company Email" 
                                        label="Email de la société" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field 
                                        type="text"
                                        v-model="company.zip_code"
                                        v-validate="'min:3'" 
                                        :error-messages="errors.collect('Zip Code')" 
                                        data-vv-name="Zip Code" 
                                        label="Code Postal" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field 
                                        type="text"
                                        v-model="company.city"
                                        v-validate="'min:3'" 
                                        :error-messages="errors.collect('City')" 
                                        data-vv-name="City" 
                                        label="Ville " required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field 
                                        type="text"
                                        v-model="company.country"
                                        v-validate="'min:3'" 
                                        :error-messages="errors.collect('Country')" 
                                        data-vv-name="Country" 
                                        label="Pays " required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-file-input small-chips 
                                        accept="image/*"  
                                        label="logo de la société" 
                                        @change="onFileChange"
                                        v-validate="'image'" 
                                        :error-messages="errors.collect('Company Logo')" 
                                        data-vv-name="Company Logo">
                                    </v-file-input>
                                </v-flex>
                                <v-flex xs12 sm12>
                                    <div class="subheading pa-0 mb-2 font-weight-bold">Description</div>
                                    <text-editor v-model="company.description"></text-editor>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap mb-3 justify-end>
                                <v-flex xs12 class="text-right">
                                    <v-btn color="teal" class="custom_button" tile @click="submit" >
                                        <v-icon left>mdi-content-save-edit-outline</v-icon>
                                        Sauvegarder 
                                    </v-btn>
                                    <v-btn color="primary" tile @click="clear" >
                                        <v-icon left>mdi-lock-reset</v-icon>
                                        Recharger le formulaire
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-card>
                    </v-flex>
                    <v-flex xs12 md4>
                        <v-card class="pa-3">
                             <p class="subheading pa-0 font-weight-bold">Logo de la société</p>
                             <v-divider></v-divider>
                            <div id="preview"  v-if="company.logo" class="pa-5" >
                                <v-img  
                                :src="company.logo"
                                max-height="200"
                                class="mx-auto"
                                max-width="200"
                                contain=""
                                lazy-src="https://media1.tenor.com/images/a6a6686cbddb3e99a5f0b60a829effb3/tenor.gif?itemid=7427055"
                                >
                                </v-img>
                            </div>
                            <div v-else>
                                <v-alert
                                    dense
                                    outlined
                                    type="info"
                                    >
                                    No logo chosen yet .
                                </v-alert>
                            </div>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-form>
    </div>
</template>
<script>
import Repository from "@/js/repositories/RepositoryFactory";
const CompanyRepository = Repository.get("companies");
  export default {
        $_veeValidate: {
            validator: 'new'
        },
        mounted () {
            this.$validator.localize('en', this.dictionary)
        },
        data: () => ({
            company: {
                name: '',
                contacts:
                {
                    name: '',
                    phone: '',
                    email: '',  
                },
                address1: '',
                address2: '',
                zip_code: '',
                city: '',
                country: 'france',
                logo: '' ,
                description: '' ,
            },
        }),
        methods: {
            submit (e) {
                let self = this;
                this.$validator.validateAll().then(result => {
                    if (result){
                        this.$root.$confirm('Are you sure you want to save ?').then((result) => {
                            if(result) {
                                this.$store.commit('setOverlay' , true);
                                CompanyRepository.create(this.company)
                                .then(({data}) =>  {
                                this.$store.commit('setSnack', 'Company Saved !')
                                this.$store.commit('setOverlay' , false);
                                self.$router.push('/company');
                                console.log(data);
                                })
                            }
                        })
                    }
                });
            },
            clear () {
                this.$validator.reset()
                this.$refs.seller_form.reset()
            },
            onFileChange(files) {
                // var files = e.target.files || e.dataTransfer.files;
               
                if (files == "") {
                    return
                }
                
                this.createImage(files);
            },
            createImage(file) {
                var reader = new FileReader();
                reader.onload = (e) => {
                    this.company.logo = e.target.result ;
                };
                reader.readAsDataURL(file);
            },
        }
  }
</script>