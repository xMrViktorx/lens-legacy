<template>
    <nav class="flex flex-wrap items-center lg:justify-around justify-between w-full p-4 text-lg bg-white">
      <div>
        <a href="#">
            <img src="../images/logo.png" alt="img" class="w-24" />
        </a>
      </div>
  
      <!-- Hamburger icon for mobile -->
      <svg xmlns="http://www.w3.org/2000/svg" v-on:click="toggleNav()" class="h-6 w-6 cursor-pointer md:hidden block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
  
      <!-- Navigation links -->
      <div :class="{'block': showMobileNav, 'hidden': !showMobileNav}" class="w-full md:flex md:items-center md:w-auto">
        <ul class="pt-4 md:flex md:justify-between md:pt-0">
          <li>
            <a class="md:p-4 py-2 block hover:text-purple-400" href="#">Portré</a>
          </li>
          <li>
            <a class="md:p-4 py-2 block hover:text-purple-400" href="#">Rendezvény</a>
          </li>
          <li>
            <a class="md:p-4 py-2 block hover:text-purple-400" href="#">Születésnap</a>
          </li>
          <li>
            <a class="md:p-4 py-2 block text-2xl hover:text-purple-400" href="#"><i class="ri-facebook-line"></i></a>
          </li>
          <li><a class="md:p-4 py-2 block text-2xl hover:text-purple-400" href="#"><i class="ri-instagram-line"></i></a></li>
        </ul>
      </div>
    </nav>
  </template>
  
  <script>
  export default {
    data() {
      return {
        showMobileNav: false,
      };
    },
    methods: {
      toggleNav() {
        this.showMobileNav = !this.showMobileNav;
      },
    },
  };
  </script>