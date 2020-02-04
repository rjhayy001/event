import Vue from 'vue';
import Vuetify from 'vuetify';
import { Ripple } from 'vuetify/lib/directives'

Vue.use(Vuetify)

export default new Vuetify({
    directives: {
        Ripple
    }
})