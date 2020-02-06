
require('./bootstrap');
window.Vue = require('vue');
import html2canvas from 'html2canvas';
import Vuex from "vuex"
import Timeselector from 'vue-timeselector';
import vuetify from './vuetify';
import * as  VeeValidate from 'vee-validate';
import App from './components/MainComponent' ;
import { VueEditor } from "vue2-editor";
import router from './router';
import {LMap, LTileLayer, LMarker,LImageOverlay,LPopup} from 'vue2-leaflet';
import { Icon } from 'leaflet';
import { CRS } from "leaflet";
import VueDraggableResizable from 'vue-draggable-resizable'
delete Icon.Default.prototype._getIconUrl;

Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png')
});

Vue.use(Vuex);

const store = new Vuex.Store(
    {
        state: {
            drawer:null,
            snack: '',
            show: false,
        },
        mutations: {
            setSnack (state, snack) {
                state.snack = snack
                state.show = true ;
            }
        }
    } 
);


axios.interceptors.response.use( ( response ) => {
    // store.commit('HIDE_LOADER')
    return response;
}, ( error ) => {
    let message = ''

    switch (error.response.status) {
        case 404:
            message = 'we did not find this page'
            break;
        case 500:
                message = 'Internal server error. Please contact the developer to resolve this issue.'
                break;
        case 503:
                message = 'Service unavailable, please check your internet connection to continue..'
                break;
        case 401:
                message = 'Sorry, you\'re not authenticated to do this action.'
                break;
        case 422:
            for (var key in error.response.data.errors) {
                message += '* '+error.response.data.errors[key];
            }
            break;
        default:
            message = 'Opps, something went wrong with the processing of your application.'
            break;
    }

    window.scroll({ top: 0, left: 0, behavior: 'smooth' })

    alert(message)
    // store.commit('HIDE_LOADER')
    // store.commit('SHOW_BANNER', {visible:1, message:message, status:2})
    return Promise.reject( error );
});
Vue.component('vue-draggable-resizable', VueDraggableResizable)
Vue.use(VeeValidate);
Vue.use(require('vue-moment'));
Vue.component('app-confirm', require('./components/alerts/confirm.vue').default);
Vue.component('app-alert', require('./components/alerts/sweetalert.vue').default);
Vue.component('text-editor', VueEditor);
Vue.component('time-selector', Timeselector);
Vue.component('l-map', LMap);
Vue.component('l-tile-layer', LTileLayer);
Vue.component('l-marker', LMarker);
Vue.component('l-image-overlay', LImageOverlay);
Vue.component('l-popup',LPopup)
Vue.filter('striphtml', function (value) {
    var div = document.createElement("div");
    div.innerHTML = value;
    var text = div.textContent || div.innerText || "";
    return text;
  });



new Vue({
    el: '#app',
    router,
    store,
    vuetify,
    components:{
        'App' : App,
    }
});
