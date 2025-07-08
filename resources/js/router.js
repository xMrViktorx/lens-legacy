import { createRouter, createWebHistory } from 'vue-router';
import Home from '../../Modules/Frontend/resources/assets/js/Home.vue';
import Category from '../../Modules/Frontend/resources/assets/js/Category.vue';
import Contact from '../../Modules/Frontend/resources/assets/js/Contact.vue';
import Album from '../../Modules/Frontend/resources/assets/js/Album.vue';
import Video from '../../Modules/Frontend/resources/assets/js/Video.vue';
import AllCategories from '../../Modules/Frontend/resources/assets/js/AllCategories.vue';

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
    path: '/portfolio',
    name: 'categories',
    component: AllCategories
  },
  {
    path: '/contact',
    name: 'contact',
    component: Contact,
  },
  {
    path: '/album/:slug',
    name: 'album',
    component: Album
  },
  {
    path: '/videography',
    name: 'video',
    component: Video
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
  routes,
  scrollBehavior(to, from, savedPosition) {
    // Always scroll to top when navigating to a new route
    return { top: 0 }
  }
});