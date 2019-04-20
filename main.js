import Vue from 'vue'
import App from './App'

import Req from "./request"
import myreq from './myRequest'

import "./static/font/iconfont.css"


Vue.prototype.$req = Req
Vue.prototype.$myreq = myreq

Vue.config.productionTip = false

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()
