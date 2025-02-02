import Vue from 'vue'
import VueRouter from 'vue-router'
// import ConfigurationRoute from '../modules/designer-service/configuration/routes'
import AuthServiceAuthRoute from '../modules/auth-service/auth/routes'
import MainLayoutPagesRoute from '../modules/main-layout-pages/routes'

Vue.use(VueRouter)

const defaultRoutes = [
  // {
  //   path: '/',
  //   name: 'Home',
  //   component: () => import(/* webpackChunkName: "home" */ '../views/Home.vue')
  // },
  // {
  //   path: '/',
  //   name: 'Login',
  //   component: () => import('../views/ui-elements/Login.vue')
  // },
  {
    path: '/',
    name: 'Login',
    component: () => import('../modules/auth-service/auth/pages/Login.vue')
  },
  {
    path: '/signup',
    name: 'Signup',
    component: () => import('../views/ui-elements/Signup.vue')
  }
]

const routes = [
  ...defaultRoutes,
  ...AuthServiceAuthRoute,
  ...MainLayoutPagesRoute
]

const router = new VueRouter({
  mode: 'hash',
  base: process.env.BASE_URL,
  routes
})

export default router
