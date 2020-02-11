<template>
   
    <div>
        <v-tabs
      background-color="white"
      color="teal"
    >
      <v-tab>General</v-tab>
      <v-tab>Images</v-tab>

      <v-tab-item>
          <div>
              <v-toolbar flat >
                <v-toolbar-title class="font-weight-bold">Apps General Information</v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>
            <v-divider></v-divider>
            <v-container class="px-8 py-5">
                <v-layout row wrap>
                    <v-flex xs12 sm5 >
                        <v-toolbar flat >
                            <v-toolbar-title class="font-weight-bold small" >Owner Details</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" class="custom_button mr-3" small @click="dialog = !dialog">
                                <v-icon left>mdi-plus</v-icon> Add Information
                            </v-btn>
                            <v-btn color="teal" class="custom_button" small @click="save_details">
                                <v-icon left>mdi-content-save-edit-outline</v-icon> Save Changes
                            </v-btn>
                        </v-toolbar>
                        <template v-if="details.length" >
                            <v-text-field
                            class="mx-4"
                            v-for="(item, index) in details" :key="index"
                                dense
                                append-icon="mdi-delete"
                                color="teal"
                                @click:append='remove_info(index , item)'
                                :label="item.fields"
                                outlined
                                v-model="item.value"
                            ></v-text-field>
                        </template>
                        <v-alert type="info" outlined dense v-else  class="ma-4" >
                            No Information given yet.
                        </v-alert>
                    </v-flex>
                    <v-flex offset-sm1 xs12 sm4>
                        <v-toolbar flat >
                            <v-toolbar-title class="font-weight-bold small" >Apps Access</v-toolbar-title>
                            <v-spacer></v-spacer>
                        <v-switch v-model="private_access.required" label="Private Access" class="mt-4" @change="access_change" color="teal"></v-switch>
                        </v-toolbar>
                        <v-card class="elevation-2 mx-4" :disabled="!private_access.required">
                            <p class="font-weight-bold small px-4 pt-4" >Parameter Required</p>
                            <v-divider></v-divider>
                            <v-card-text>
                                    <v-simple-table>
                                        <template v-slot:default>
                                        <thead>
                                            <tr>
                                            <th class="text-left">Fields</th>
                                            <th class="text-left">Required</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item,index) in rules" :key="index">
                                                <td>{{ item.fields }}</td>
                                                <td v-if="item.fields != 'Username' && item.fields != 'Password'"> <v-switch v-model="item.required"  @change="required_change(item)" color="teal"></v-switch></td>
                                                <td v-else>strictly required</td>
                                            </tr>
                                        </tbody>
                                        </template>
                                    </v-simple-table>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-dialog v-model="dialog" max-width="350">
                <v-card>
                    <v-card-title class="headline">Add Information</v-card-title>
                    <v-card-text>
                        <v-text-field
                            dense
                            color="teal"
                            label="Fields"
                            v-model="field"
                            append-icon="mdi-information-outline"
                            outlined
                        ></v-text-field>
                        <v-text-field
                            dense
                            append-icon="mdi-card-text-outline"
                            color="teal"
                            label="Value"
                            v-model="value"
                            outlined
                        ></v-text-field>
                    </v-card-text>

                    <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn
                        color="green darken-1"
                        text
                        :disabled="!field"
                        @click="add_info"
                    >
                        Add
                    </v-btn>

                    <v-btn
                        color="green darken-1"
                        text
                        @click="dialog = false"
                    >
                        Cancel
                    </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
          </div>
      </v-tab-item>
      <v-tab-item>
        <div>
            <v-toolbar flat >
                <v-toolbar-title class="font-weight-bold">Apps Default Images</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn color="teal" class="custom_button" small @click="submit"  >
                    <v-icon class="pr-2">mdi-content-save-edit-outline</v-icon> Save Changes
                </v-btn>
            </v-toolbar>
            <v-divider></v-divider>
            <v-container>
                <v-layout row wrap>
                    <v-flex xs12 sm3 v-for="(item, index) in generals" :key="index">
                        <v-skeleton-loader v-if="!data_loaded"
                            type="table-tbody"
                        ></v-skeleton-loader>
                        <v-card class="d-inline-block  ma-4" v-else> 
                            <v-container class="">
                                <v-row justify="space-between">
                                    <v-col cols="sm3" class="elevation-1 mb-3">
                                        <v-img height="200" width="200" 
                                            :src="item.value" 
                                            contain
                                            lazy-src="https://media1.tenor.com/images/a6a6686cbddb3e99a5f0b60a829effb3/tenor.gif?itemid=7427055">
                                        </v-img>
                                    </v-col>
                                    <v-col cols="auto" class="text-center pl-0" >
                                        <v-row class="flex-column ma-0 fill-height" justify="center" >
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <upload-btn v-on="on" accept="image/*"  depressed icon color="white" style="color:teal!important;"  
                                                    @file-update="file_changed($event , item)">
                                                        <template slot="icon">
                                                            <v-icon>mdi-image-edit-outline</v-icon>
                                                        </template>
                                                    </upload-btn>
                                                </template>
                                                <span>Tooltip</span>
                                            </v-tooltip>
                                        </v-row>

                                    
                                    </v-col>
                                </v-row>
                                    <p class="font-weight-bold ma-0">Default {{item.field}}</p> 
                            </v-container>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </div>
      </v-tab-item>
    </v-tabs>
    </div>
</template>
<script>
import UploadButton from 'vuetify-upload-button';
export default {
    components: {
      'upload-btn': UploadButton
    },
    data: () => ({
        fab:'',
        private_access:[],
        field:'',
        value:'',
        dialog:false,
        data_loaded : true ,
        selectedFile: null,
        isSelecting: false,
        generals:[],
        details:[],
        rules:[],
    }),
  computed: {
    buttonText() {
      return this.selectedFile ? this.selectedFile.name : this.defaultButtonText
    }
  },
  created() {
      this.get_data()
      this.get_rules()
      this.get_details()
      this.get_access()
  },
    methods: {
        access_change(){
            axios.post('/rules', this.private_access)
                .then((response) =>  {
                console.log(response.data);
            })
        },
        required_change(item) {
            axios.post('/rules', item)
                .then((response) =>  {
                console.log(response.data);
            })
        },
        remove_info(index , item){
             this.$root.$confirm('Are you sure you want to Delete ?').then((result) => {
                if(result) {
                    if(item.id){
                        axios.delete('/details/'+item.id, {})
                        .then((response) =>  {
                        console.log(response.data)
                        this.$store.commit('setSnack', 'Information Deleted !')
                    });
                        this.details.splice(index,1)
                        return
                    }
                    this.details.splice(index,1)
                    this.$store.commit('setSnack', 'Information Deleted !')
                }
             });
            
        },
        add_info(){
            this.details.push({ 'fields' : this.field , 'value' :this.value , 'id':''})
            this.dialog = !this.dialog
            this.field = ''
            this.value = ''
        },
        save_details(){
            this.$root.$confirm('Are you sure you want to save ?').then((result) => {
                if(result) {
                    axios.post('/details', this.details )
                    .then((response) =>  {
                    this.$store.commit('setSnack', 'Information Saved !')
                    this.details =response.data ;
                    })
                }
            })
        },
        submit(){
           this.$root.$confirm('Are you sure you want to save ?').then((result) => {
                if(result) {
                    this.$store.commit('setOverlay' , true);
                    axios.post('/generals', this.generals )
                    .then((response) =>  {
                     this.$store.commit('setOverlay' , false);
                    this.$store.commit('setSnack', 'Configuration Saved !')
                    this.generals=response.data ;
                    console.log(response.data);
                    })
                }
            })
        },
        file_changed(file ,item){
            console.log(item)
            if (file) {
                var reader = new FileReader();
                reader.onload = (e) => {
                    item.value = e.target.result ;
                }
                reader.readAsDataURL(file);
                }else {
                    return
                }
        },
        get_data() {
            this.data_loaded = false ;
            axios.get('/generals', {})
            .then(response => {
                let i =1;
                response.data.forEach(element => {
                    this.generals.push({'id' : element.id  ,'field' : element.field , 'value' : element.value , 'is_selected':false , 'model' :i++ })
                });
                this.data_loaded = true ;
            });
        },
        get_details() {
            this.data_loaded = false ;
            axios.get('/details', {})
            .then(response => {
                console.log(response.data);
                this.details = response.data;
            });
        },
        get_access() {
            this.data_loaded = false ;
            axios.get('/access', {})
            .then(response => {
                console.log(response.data ,' access');
                this.private_access = response.data ;
            });
        },
        get_rules() {
            this.data_loaded = false ;
            axios.get('/rules', {})
            .then(response => {
               this.rules = response.data ;
               console.log(this.rules)
            });
        },
    onButtonClick(is_selected , uploader) {
      is_selected = true
      window.addEventListener('focus', () => {
        is_selected = false
      }, { once: true })
      console.log(uploader)
    //   this.$refs.uploader.click()
    },
    onFileChanged(e) {
      this.selectedFile = e.target.files[0]
      
      // do something
    },
    rnd (a, b) {
        return Math.floor((b - a + 1) * Math.random()) + a
    }
  }
}
</script>