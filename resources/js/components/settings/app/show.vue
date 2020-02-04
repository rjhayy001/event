<template>
    <div>
        <v-toolbar flat >
            <v-toolbar-title class="font-weight-bold">Apps Default Information</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn color="teal" class="custom_button" @click="submit"  >
                <v-icon class="pr-2">mdi-content-save-edit-outline</v-icon> Save Changes
            </v-btn>
        </v-toolbar>
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
</template>
<script>
import UploadButton from 'vuetify-upload-button';
export default {
    components: {
      'upload-btn': UploadButton
    },
    data: () => ({
        fab:'',
        data_loaded : true ,
    selectedFile: null,
    isSelecting: false,
    generals:[],
    names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party', 'test'],
  }),
  computed: {
    buttonText() {
      return this.selectedFile ? this.selectedFile.name : this.defaultButtonText
    }
  },
  created() {
      this.get_data()
  },
  methods: {
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
        console.log(this.companies)
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