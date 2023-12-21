import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from '../views/RegisterView.vue'
import LoginView from '../views/LoginView.vue'
import RestaurantView from '../views/RestaurantList.vue'
import MenuView from '../views/MenuList.vue'
import DishView from '../views/DishList.vue'
import UserView from '../views/UserList.vue'
import NotFoundView from '../views/NotFoundView.vue'

import { jwtDecode } from 'jwt-decode'
import { POSITION, useToast } from 'vue-toastification'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta: {
      checkAuth: false
    }
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterView,
    meta: {
      checkAuth: true,
      auth: false
    }
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView,
    meta: {
      checkAuth: true,
      auth: false
    }
  },
  {
    path: '/restaurants',
    name: 'restaurants',
    component: RestaurantView,
    meta: {
      checkAuth: true,
      auth: true
    }
  },
  {
    path: '/users',
    name: 'users',
    component: UserView,
    props: true,
    meta: {
      checkAuth: true,
      auth: true
    }
  },
  {
    path: '/restaurant/:rid/menu',
    name: 'menus',
    component: MenuView,
    props: true,
    meta: {
      checkAuth: true,
      auth: true
    }
  },
  {
    path: '/restaurant/:rid/menu/:mid/dish',
    name: 'dishes',
    component: DishView,
    props: true,
    meta: {
      checkAuth: true,
      auth: true
    }
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'notFound',
    component: NotFoundView
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from) => {
  if(to.meta.checkAuth){
    const token = localStorage.getItem('token')
    if(to.meta.auth && !token) return  { name: 'login' }
    else if(!to.meta.auth && token) return { name: 'home' }
  }
})

export default router
