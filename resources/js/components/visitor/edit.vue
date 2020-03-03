<template>
    <div>
        <v-toolbar flat color="white" class="mb-3">
            <v-toolbar-title class="text-uppercase title">edit visitors</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn color="primary" tile @click="$router.go(-1)">
                <v-icon left>mdi-arrow-left-circle</v-icon>
               back
            </v-btn>
        </v-toolbar>
        <v-divider></v-divider>
         <v-form ref="seller_form">
            <v-container  grid-list-md>
                <v-layout row wrap>
                    <v-flex xs12 v-if="!data_loaded">
                    <v-skeleton-loader
                        type="card"
                    ></v-skeleton-loader>
                    </v-flex>
                    <v-flex xs12 md8 v-else>
                        <v-card class="pa-5" >
                            <v-layout row wrap mb-3 class="text-capitalize">
                                <v-flex xs12>
                                    
                                    <p class="subheading pa-0 font-weight-bold">visitor Information</p>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field 
                                        type="text"
                                        v-model="visitor.name"
                                        v-validate="'required|min:3'" 
                                        :error-messages="errors.collect(' Name')" 
                                        data-vv-name=" Name" 
                                        label=" Name" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field 
                                        type="number"
                                        v-model="visitor.phone"
                                        v-validate="'required|min:3'" 
                                        :error-messages="errors.collect('Phone')" 
                                        data-vv-name="Phone" 
                                        label="Phone" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field 
                                        type="email"
                                        v-model="visitor.email"
                                        v-validate="'required|min:3|email'" 
                                        :error-messages="errors.collect('Email')" 
                                        data-vv-name="Email" 
                                        label="Email" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-text-field 
                                        type="text"
                                        v-model="visitor.username"
                                        v-validate="'required|min:3'" 
                                        :error-messages="errors.collect('Username')" 
                                        data-vv-name="Username" 
                                        label="Username" required>
                                    </v-text-field>
                                </v-flex>
                                <v-btn text  color="primary" @click="show = !show" dense><v-icon class="mr-2">mdi-key</v-icon>  change password</v-btn>
                                <v-flex xs12 >
                                    <transition name="fade">
                                        <div v-if="show">
                                            <v-layout row wrap>
                                                <v-flex xs12 sm6>
                                                    <v-text-field 
                                                        ref="password"
                                                        type="password"
                                                        v-model="visitor.new_password"
                                                        v-validate="'required|min:3'" 
                                                        :error-messages="errors.collect('New Password')" 
                                                        data-vv-name="New Password" 
                                                        label="New Password" required>
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6>
                                                    <v-text-field 
                                                        type="password"
                                                        v-model="visitor.confirm_password"
                                                        v-validate="'required|confirmed:password'" 
                                                        :error-messages="errors.collect(' Password')" 
                                                        data-vv-name=" Password" 
                                                        label="Confirm Password" required>
                                                    </v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </div>
                                    </transition>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap mb-3 justify-end>
                                 <v-flex xs12 class="text-right">
                                    <v-btn color="teal" class="custom_button" tile @click="submit" >
                                        <v-icon left>mdi-content-save-edit-outline</v-icon>
                                        save visitor
                                    </v-btn>
                                    <v-btn color="primary" tile @click="clear" >
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
    </div>
</template>
<script>
import Repository from "@/js/repositories/RepositoryFactory";
const VisitorRepository = Repository.get("visitors");
  export default {
      $_veeValidate: {
            validator: 'new'
        },
        mounted () {
            this.$validator.localize('en', this.dictionary)
        },
        data: () => ({
            show: false ,
            data_loaded: true ,
            visitor: {
                id:'',
                name: '',
                phone: '',
                email: '',
                username: '',
                new_password: '',
                confirm_password: '',
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
                                VisitorRepository.update(this.visitor,this.$route.params.id)
                                // axios.put('/visitors/'+this.$route.params.id, this.visitor )
                                 .then(({data}) => {
                                this.$store.commit('setOverlay' , false);
                                this.$store.commit('setSnack', 'Visitor Updated !')
                                    console.log(data)
                                    self.$router.push('/visitor');
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
            get_visitors_info() {
                this.data_loaded = false ;
                 VisitorRepository.getVisitor(this.$route.params.id)
                // axios.get('/visitors/'+this.$route.params.id+'/edit', {})
			   .then(({data}) => {
                    console.log(data ,'visitors_info')
                    this.visitor = data ;
                this.data_loaded = true ;

			    });
            }
        },
        created(){
            this.get_visitors_info()
        }
  }
</script>