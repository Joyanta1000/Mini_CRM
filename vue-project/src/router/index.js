import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/about',
    name: 'About',
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue')
  },
  {
    path: '/add_company',
    name: 'AddCompany',
    component: () => import('../views/AddCompany.vue')
  },
  {
    path: '/company_list',
    name: 'Companies',
    component: () => import('../views/Companies.vue')
  },
  {
    path: '/add_employee',
    name: 'AddEmployee',
    component: () => import('../views/AddEmployee.vue')
  },
  {
    path: '/employee_list',
    name: 'Employees',
    component: () => import('../views/Employees.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
