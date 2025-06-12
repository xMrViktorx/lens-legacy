<template>
  <div>
    <router-link :to="{ name: 'home' }" class="nav-icon-div">
      <img src="/public/build-frontend/images/logo/logo_white.png" class="nav-icon">
    </router-link>

    <nav :class="{ 'nav-open': isMenuOpen }">
      <div class="menu-btn">
        <div class="mt-1 menu-btn-icon p-1" @click="toggleMenu">
          <div :class="['line', 'line--1', { 'line-cross': isMenuOpen }]"></div>
          <div :class="['line', 'line--2', { 'line-fade-out': isMenuOpen }]"></div>
          <div :class="['line', 'line--3', { 'line-cross': isMenuOpen }]"></div>
        </div>
        <button
          @click.stop="toggleLanguageSwitcher"
          class="flex justify-center items-center px-2 language-switcher"
          :class="languageSwitcherClass"
        >
          <i class="ri-earth-line text-3xl text-white"></i>
        </button>
      </div>

      <div class="nav-links" :class="{ 'fade-in': isMenuOpen }">
        <div class="flex flex-col lg:w-[40%] sm:w-[405px] w-full justify-center open-menu-bg items-center h-full">
          <router-link
            v-for="(category, index) in categories"
            :key="category.id"
            :to="{ name: 'category', params: { slug: category.slug } }"
            class="link"
            @mouseover="activeMenu = category.id"
            @mouseleave="activeMenu = null"
            @click="closeMenu"
          >
            {{ category.name[currentLocale] }}
          </router-link>
        </div>
        <div class="w-full h-full relative lg:block hidden">
          <div
            v-for="(category, index) in categories"
            :key="category.id"
            :class="['menu-image', { 'hover-div-show': activeMenu === category.id }]"
            :style="{ zIndex: index + 100, backgroundImage: `url(/storage/${category.image})` }"
          ></div>
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
      isMenuOpen: false,
      showLanguageSwitcher: false,
      currentLocale: this.$i18n.locale,
      activeMenu: null,
      categories: [],
    };
  },

  computed: {
    languageSwitcherClass() {
      return this.isMenuOpen ? 'hide-switch' : 'show-switch';
    },
  },

  watch: {
    isMenuOpen(val) {
      document.body.classList.toggle('no-scroll', val);
    },
  },

  async created() {
    await axios.get('/api/categories')
    .then(response => {
      this.categories = response.data;
    })
    .catch(error => {
      console.error('Failed to fetch categories:', error);
    });
  },

  beforeDestroy() {
    document.body.classList.remove('no-scroll');
  },

  methods: {
    toggleMenu(event) {
      // Prevent double toggling if menu is closed via router-link click
      this.isMenuOpen = !this.isMenuOpen;
      if (!this.isMenuOpen) this.showLanguageSwitcher = false;
    },

    closeMenu() {
      this.isMenuOpen = false;
      this.showLanguageSwitcher = true;
    },

    toggleLanguageSwitcher(event) {
      event && event.stopPropagation();
      this.showLanguageSwitcher = !this.showLanguageSwitcher;
      if (this.showLanguageSwitcher) this.showLanguageDialog();
    },

    showLanguageDialog() {
      Swal.fire({
        showConfirmButton: false,
        showCloseButton: true,
        html: `
          <div class="flex flex-col pb-5">
            <span class="language-title-swall md:text-4xl text-3xl">${this.$t("showAlertButton")}</span><br>
            <span class="py-3 text-2xl font-semibold text-black cursor-pointer ${this.currentLocale === 'en' ? 'bg-black text-white rounded-xl' : ''}" id="btn-en">${this.$t("language.english")}</span>
            <span class="py-3 text-2xl font-semibold text-black cursor-pointer ${this.currentLocale === 'hu' ? 'bg-black text-white rounded-xl' : ''}" id="btn-hu">${this.$t("language.hungarian")}</span>
            <span class="py-3 text-2xl font-semibold text-black cursor-pointer ${this.currentLocale === 'sr' ? 'bg-black text-white' : ''}" id="btn-rs">${this.$t("language.serbian")}</span>
          </div>
        `,
        focusConfirm: false,
        didOpen: () => {
          document.getElementById("btn-en").addEventListener("click", () => this.changeLanguage("en"));
          document.getElementById("btn-hu").addEventListener("click", () => this.changeLanguage("hu"));
          document.getElementById("btn-rs").addEventListener("click", () => this.changeLanguage("sr"));
        },
        willClose: () => {
          this.showLanguageSwitcher = false;
        }
      });
    },

    changeLanguage(locale) {
      this.$i18n.locale = locale;
      this.currentLocale = locale;
      Swal.close();
      this.showLanguageSwitcher = false;
    },
  },
};
</script>