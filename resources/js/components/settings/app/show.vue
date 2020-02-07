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
                    <v-flex xs12 sm5>
                        <v-toolbar flat >
                            <v-spacer></v-spacer>
                            <v-btn color="primary" class="custom_button mr-3" small @click="dialog = !dialog">
                                <v-icon left>mdi-plus</v-icon> Add Information
                            </v-btn>
                            <v-btn color="teal" class="custom_button" small @click="save_details">
                                <v-icon left>mdi-content-save-edit-outline</v-icon> Save Changes
                            </v-btn>
                        </v-toolbar>
                        <v-text-field
                        v-for="(item, index) in details" :key="index"
                            dense
                            append-icon="mdi-delete"
                            color="teal"
                            @click:append='remove_info(index , item)'
                            :label="item.fields"
                            outlined
                            v-model="item.value"
                        ></v-text-field>
                    </v-flex>
                    <v-flex offset-sm1 xs12 sm6>
                        asdas
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
        field:'',
        value:'',
        dialog:false,
        data_loaded : true ,
        selectedFile: null,
        isSelecting: false,
        generals:[],
        details:[]
    }),
  computed: {
    buttonText() {
      return this.selectedFile ? this.selectedFile.name : this.defaultButtonText
    }
  },
  created() {
      this.get_data()
      this.get_details()
  },
    methods: {
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
                    axios.post('/generals', this.generals )
                    .then((response) =>  {
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