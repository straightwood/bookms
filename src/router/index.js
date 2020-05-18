import Vue from 'vue'
import Router from 'vue-router'
import Index from '../pages/login'
import Main from '../pages/main'
import Manager from '../pages/manager'
import Book from '../pages/book'
import option from '../pages/option'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'index-login',
      component: Index
    },{
      path: '/index',
      name: 'index',
      component: Index
    },{
      path: '/main',
      name: 'main',
      component: Main,
      children:[
        {
          path: '/',
          name: 'manager',
          component: Manager
        },{
          path: '/manager',
          name: 'manager',
          component: Manager
        },{
          path: '/book',
          name: 'book',
          component: Book
        },{
          path: '/option',
          name: 'option',
          component: option
        }
      ]
    },
  ]
})
