import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/pages/Home.vue';
import UserSignUp from '@/pages/user/SignUp.vue';
import UserLogin from '@/pages/user/Login.vue';

import CompanyProfile from '@/pages/company/Profile.vue';
import CompanyProfileEx from '@/pages/company/ProfileEx.vue';

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
    path: '/company-profile',
    name: 'CompanyProfile',
    component: CompanyProfile,
  },

  {
    path: '/company-profile-ex',
    name: 'CompanyProfile-ex',
    component: CompanyProfileEx,
  },



]

export const router = createRouter({
  history: createWebHistory(),
  routes,
})
