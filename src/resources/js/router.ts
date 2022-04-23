import { createRouter, createWebHistory, RouteLocationNormalizedLoaded as Route } from 'vue-router';
import Home from '@/pages/Home.vue'
import UserSignUp from '@/pages/user/SignUp.vue'
import UserLogin from '@/pages/user/Login.vue'

import Temp1 from '@/pages/user/Temp1.vue'
import Search from '@/pages/user/Search.vue'
import CompanyProfile from '@/pages/company/Profile.vue'


const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/user-sign-up',
    name: 'UserSignUp',
    component: UserSignUp,
  },
  {
    path: '/user-login',
    name: 'UserLogin',
    component: UserLogin,
  },
  {
    path: '/temp1',
    name: 'Temp1',
    component: Temp1,
  },
  // {
  //   path: '/search-detail/:prefectureId',
  //   name: 'search-detail',
  //   component: SearchDetail,
  //   props: (route: Route) => ({
  //     prefectureId: Number(route.params.prefectureId)
  //   }),
  // },
  {
    path: '/search',
    name: 'search',
    component: Search,
    // props: (route: Route) => ({
    //   prefectureId: Number(route.params.prefectureId)
    // }),
  },
  {
    path: '/company-profile',
    name: 'CompanyProfile',
    component: CompanyProfile,
  },
]

export const router = createRouter({
  history: createWebHistory(),
  routes,
})
