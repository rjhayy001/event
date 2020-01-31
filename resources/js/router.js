import Vue from 'vue'
import VueRouter from 'vue-router'
import sidebarroutes from './components/routes/sidebarroutes'
import errorroutes from './components/routes/error'
import visitorroutes from './components/routes/visitorroutes'
import companyroutes from './components/routes/companyroutes'
import eventroutes from './components/routes/eventroutes'
// import sellerroutes from './components/routes/sellerroutes'
// import routeSectionB from './routeSectionB.js'

Vue.use(VueRouter)



export default new VueRouter({
    routes: [
        ...eventroutes,
        ...companyroutes,
        ...visitorroutes,
        ...sidebarroutes,
        ...errorroutes // always at last
    ],
    mode: 'history'
})