<template>
    <v-app-bar
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      app
      color="teal"
    >
      <v-app-bar-nav-icon class="white--text" @click.stop="$store.state.drawer = !$store.state.drawer" />
      <v-toolbar-title
        style="width: 300px"
        class="ml-0 pl-4"
      >
        <span class="hidden-sm-and-down white--text">Quimper Evenements</span>
      </v-toolbar-title>
      <v-autocomplete
      v-model="model"
      :items="items"
      @update:search-input="searchdata"
      clearable
      hide-details
      chip
      item-text="name"
      item-value="symbol"
      hide-selected
      label="Search anything on app..."
      color="white"
      solo
      flat
      @focus="test"
    >
      <!-- <template v-slot:no-data>
        <v-list-item>
          <v-list-item-title>
            Search for Anything
            <strong>Events, Companies, Visitors!</strong>
          </v-list-item-title>
        </v-list-item>
      </template> -->
      <template v-slot:item="{ item }" >
          <v-list-item @click="$router.push({name: item.route , params: { id: item.id },})">
        <v-list-item-avatar
        >
        <v-img :src="item.logo"></v-img>
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title v-text="item.name" class="font-weight-bold"></v-list-item-title>
          <v-list-item-subtitle v-text="item.subtitle" class=" text-uppercase"></v-list-item-subtitle>
        </v-list-item-content>
        <v-list-item-action>
          <v-icon class="font-weight-bold" >{{item.icon}}</v-icon>
        </v-list-item-action>
        </v-list-item>
      </template>
    </v-autocomplete>
      <v-spacer />
      <v-btn icon class="white--text"  >
        <v-icon>mdi-apps</v-icon>
      </v-btn>
      <v-btn class="white--text" icon @click="logout">
        <v-icon>mdi-logout</v-icon>
      </v-btn>
    </v-app-bar>
</template>
<script>
export default {
    data: () => ({
      items: [],
      model:'',
      search: '',
      userData :''
    }),
    methods : {
        test(){
            axios.get('/search/').then(({data}) => {
                this.items = data
                console.log(this.items)
            })
        },
        logout() {
            let self = this;
            axios.get('/logout', {})
            .then(response => {
                console.log(response.data)
                location.reload();
            });
        },
        searchdata(val) {
            clearTimeout(this.userData)
                this.userData = setTimeout(() => 
                    axios.get('/search/'+val).then(({data}) => {
                        this.items = data
                        // console.log(this.items)
                    })
                , 1000);
            },
    },
    watch: {
        model (val) {
        if (val != null) this.tab = 0
        else this.tab = null
      },
     
        
    }  
}
</script>