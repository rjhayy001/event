<template>
    <v-navigation-drawer
      v-model="$store.state.drawer"
      :clipped="$vuetify.breakpoint.lgAndUp"
      app
    >
      <v-list dense>
        <template v-for="item in items">
          <v-row
            v-if="item.heading"
            :key="item.heading"
            align="center"
          >
          </v-row>
          <v-list-group no-action
            v-else-if="item.children"
            :key="item.text"
            v-model="item.model"
            :prepend-icon="item.model ? item.icon : item['icon-alt']"
              color="black whiten-5"
          >
            <template v-slot:activator>
              
                <v-list-item-content>
                  <v-list-item-title >
                    {{ item.text }}
                  </v-list-item-title>
                </v-list-item-content>
            </template>
            <v-list-item
              v-for="(child, i) in item.children"
              :key="i"
              link :to="child.url"
              color="teal"
            >
              <v-list-item-content>
                <v-list-item-title>
                  {{ child.text }}
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-group>
          <v-list-item
            v-else
            :key="item.text"
            link :to="item.url"
            color="teal"
          >
            <v-list-item-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>
                {{ item.text }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </template>
      </v-list>
    </v-navigation-drawer>
</template>
<script>
  export default {
    props: {
      source: String,
    },
    data: () => ({
      dialog: false,
      drawer: null,
      items: [
        { icon: 'mdi-view-dashboard', text: 'Tableau de bord' ,url:'/dashboard' },
        { icon: 'mdi-calendar', text: 'Ev??nements' ,url:'/event' },
        { icon: 'mdi-office-building', text: 'Soci??t??s' ,url:'/company' },
        { icon: 'mdi-account-group', text: 'Visiteurs' ,url:'/visitor' },
        {
          icon: 'mdi-settings',
          'icon-alt': 'mdi-settings',
          text: 'R??glages',
          model: false,
          children: [
            { icon: 'mdi-tab', text: 'Cat??gories age' , url:'/category' },
            { icon: 'mdi-image', text: 'App' , url:'/app'  },
          ],
        },
      ],
    }),
  }
</script>