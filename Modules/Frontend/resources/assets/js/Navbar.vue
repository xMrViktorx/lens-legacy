<template>
    <nav class="flex flex-wrap items-center fixed lg:justify-around justify-between w-full p-4 text-lg bg-white">
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
          <li><a class="md:p-4 py-2 block text-2xl hover:text-purple-400" href="#"><i class="ri-instagram-line"></i></a>
          </li>

          <!-- Language Switcher Button -->
          <li class="flex justify-center items-center">
            <button @click="showLanguageSwitcher" class="flex justify-center items-center px-2"><i class="ri-earth-line text-2xl"></i></button>
          </li>
        </ul>
      </div>
    </nav>
  </template>
  
  <script>

  import Swal from "sweetalert2";

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
      switchLanguage(locale) {
        this.$i18n.locale = locale; // Switch locale dynamically
      },

      showLanguageSwitcher() {
        // Show SweetAlert with language options
        Swal.fire({
          showConfirmButton: false,
          showCloseButton: true,
          html: `
            <div class="flex flex-col pb-5">
              <span class="language-title-swall md:text-4xl text-3xl">${this.$t("showAlertButton")}</span><br>
              <span class="py-3 text-2xl font-semibold text-black cursor-pointer" id="btn-en">${this.$t("language.english")}</span>
              <span class="py-3 text-2xl font-semibold text-black cursor-pointer" id="btn-hu">${this.$t("language.hungarian")}</span>
              <span class="py-3 text-2xl font-semibold text-black cursor-pointer" id="btn-rs">${this.$t("language.serbian")}</span>
            </div>
          `,
          focusConfirm: false,
          didOpen: () => {
            // Access the buttons and set click event listeners
            document.getElementById("btn-en").addEventListener("click", () => this.changeLanguage("en"));
            document.getElementById("btn-hu").addEventListener("click", () => this.changeLanguage("hu"));
            document.getElementById("btn-rs").addEventListener("click", () => this.changeLanguage("rs"));
          }
        });
      },

      changeLanguage(locale) {
        // Change the locale dynamically and close SweetAlert
        this.$i18n.locale = locale;
        Swal.close();
      },
    },
  };
  </script>