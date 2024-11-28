<template>
  <div>
    <nav>
      <div class="menu-btn">
        <div class="mt-1 menu-btn-icon">
          <div class="line line--1"></div>
          <div class="line line--2"></div>
          <div class="line line--3"></div>
        </div>

        <button @click="showLanguageSwitcher" class="flex justify-center items-center px-2 language-switcher">
          <i class="ri-earth-line text-3xl"></i>
        </button>
      </div>

      <div class="nav-links">
        <div class="flex flex-col lg:w-[520px] sm:w-[405px] w-full justify-center bg-white items-center h-full">
          <a href="#" 
            class="link" 
            @mouseover="activeMenu = 'home'" 
            @mouseleave="activeMenu = null">{{ $t('menu.home') }}</a>
          <a href="#" 
            class="link" 
            @mouseover="activeMenu = 'event'" 
            @mouseleave="activeMenu = null">{{ $t('menu.event') }}</a>
          <a href="#" 
            class="link" 
            @mouseover="activeMenu = 'portrait'" 
            @mouseleave="activeMenu = null">{{ $t('menu.portrait') }}</a>
          <a href="#" 
            class="link" 
            @mouseover="activeMenu = 'birthday'" 
            @mouseleave="activeMenu = null">{{ $t('menu.birthday') }}</a>
          <span class="link cursor-pointer" @click="showLanguageSwitcher">{{ $t('language.name') }}</span>
        </div>
        <div class="w-full h-full relative lg:block hidden">
          <div 
            v-for="(image, index) in menuImages" 
            :key="index" 
            :class="['menu-image', { 'hover-div-show': activeMenu === image.key }]" 
            :style="{ zIndex: index + 1, backgroundImage: `url(${image.url})` }">
          </div>
        </div>
      </div>
    </nav>
  </div>
</template> 

<script>

  import Swal from "sweetalert2";

  export default {
    data() {
      return {
        showMobileNav: false,
        isMenuOpen: false,
        currentLocale: this.$i18n.locale,
        activeMenu: null,
        menuImages: [
          { key: 'home', url: '/build-frontend/images/categories/party.jpg' },
          { key: 'event', url: '/build-frontend/images/categories/event.jpg' },
          { key: 'portrait', url: '/build-frontend/images/categories/portrait.jpg' },
          { key: 'birthday', url: '/build-frontend/images/categories/birthday.jpg' },
        ],
      };
    },
    methods: {
      toggleNav() {
        this.showMobileNav = !this.showMobileNav;
      },

      toggleMenu() {
        this.isMenuOpen = !this.isMenuOpen;
      },

      switchLanguage(locale) {
        this.$i18n.locale = locale;
        this.currentLocale = locale;
      },

      showLanguageSwitcher() {
        Swal.fire({
          showConfirmButton: false,
          showCloseButton: true,
          html: `
            <div class="flex flex-col pb-5">
              <span class="language-title-swall md:text-4xl text-3xl">${this.$t("showAlertButton")}</span><br>
              <span class="py-3 text-2xl font-semibold text-black cursor-pointer ${this.currentLocale === 'en' ? 'bg-black text-white rounded-xl' : ''}" id="btn-en">${this.$t("language.english")}</span>
              <span class="py-3 text-2xl font-semibold text-black cursor-pointer ${this.currentLocale === 'hu' ? 'bg-black text-white rounded-xl' : ''}" id="btn-hu">${this.$t("language.hungarian")}</span>
              <span class="py-3 text-2xl font-semibold text-black cursor-pointer ${this.currentLocale === 'rs' ? 'bg-black text-white ' : ''}" id="btn-rs">${this.$t("language.serbian")}</span>
            </div>
          `,
          focusConfirm: false,
          didOpen: () => {
            document.getElementById("btn-en").addEventListener("click", () => this.changeLanguage("en"));
            document.getElementById("btn-hu").addEventListener("click", () => this.changeLanguage("hu"));
            document.getElementById("btn-rs").addEventListener("click", () => this.changeLanguage("rs"));
          },
        });
      },
      
      changeLanguage(locale) {
        this.$i18n.locale = locale;
        this.currentLocale = locale;
        Swal.close();
      },
    },
  };
</script>