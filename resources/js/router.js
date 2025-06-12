import { createRouter, createWebHistory } from 'vue-router';
import Category from '../../Modules/Frontend/resources/assets/js/Category.vue';
import Home from '../../Modules/Frontend/resources/assets/js/Home.vue';

const routes = [
  {
    path: '/category/:slug',
    name: 'category',
    component: Category,
    props: true
  },
  {
    path: '/',
    name: 'home',
    component: Home,
    props: true
  },
];

export default createRouter({
  history: createWebHistory(),
  routes
});