import { createRouter, createWebHistory } from 'vue-router';
import Home from './pages/Home.vue';
import SignUp from './pages/SignUp.vue';
import Login from './pages/Login.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/sign-up',
    name: 'SignUp',
    component: SignUp,
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
]

export const router = createRouter({
  history: createWebHistory(),
  routes,
})
