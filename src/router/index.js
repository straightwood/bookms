import Vue from 'vue'
import Router from 'vue-router'

import Index from '../pages/login'
import Main from '../pages/main'

import Manager from '../pages/manager'
import Book from '../pages/book'
import Reader from '../pages/reader'
import option from '../pages/option'

Vue.use(Router)

const router = new Router({
  routes: [
    {
      path: '/',
      name: 'index-default',
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
          name: 'manager-default',
          component: Manager
        }, {
          path: '/manager',
          name: 'manager',
          component: Manager
        },{
          path: '/reader',
          name: 'reader',
          component: Reader,
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
});

router.beforeEach((to, from, next) => {
  if (to.path === '/index') {
    next();
  } else {
    let token = localStorage.getItem('Authorization');
 
    if (token === null || token === '') {
      next('/index');
    } else {
      next();//#################index意思
    }
  }
});
 

export default router;
