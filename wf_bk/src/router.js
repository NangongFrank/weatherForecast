import Vue from 'vue'
import Router from 'vue-router'
import store from "./store"


Vue.use(Router)


import Index from "./views/index"
import Home from "./views/home"
import HomeIndex from "./components/HomeIndex"
import NotFound from "./components/NotFound"

import User from "./components/User"
import Side from "./components/Side"
import HotCity from "./components/HotCity"




let router = new Router({
    //mode: "history",
    routes: [{
        path: "/",
        redirect: "/index",
    },{
        path: "/index",
        component: Index,
    },{
        path: "/home",
        component: Home,
        children: [{
            path: "/home",
            component: HomeIndex
        }, {
            path: "/user",
            component: User
        }, {
            path: "/side",
            component: Side
        }, {
            path: "/hotcity",
            component: HotCity
        }]
    }, {
        path: "*",
        component: NotFound,
    }],

})
router.beforeEach((to, from, next) => {
    store.commit("setProgressStatus", false)
    store.commit("setProgressPage", 0)
    setTimeout(() => {
         next()
    }, 1)
})
router.afterEach((to, from) => {
    store.commit("setProgressStatus", true)
    store.dispatch("forProgressPage", 100)
})
export default router

