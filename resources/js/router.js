import { createRouter, createWebHistory } from 'vue-router';
import Home from '../../Modules/Frontend/resources/assets/js/Home.vue';
import Category from '../../Modules/Frontend/resources/assets/js/Category.vue';
import Contact from '../../Modules/Frontend/resources/assets/js/Contact.vue';

const routes = [
    {
    path: '/',
    name: 'home',
    component: Home,
    props: true
  },
  {
    path: '/category/:slug',
    name: 'category',
    component: Category,
    props: true
  },

    {
    path: '/contact',
    name: 'contact',
    component: Contact,
    props: true
  },
];

export default createRouter({
  history: createWebHistory(),
  routes
});