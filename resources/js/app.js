import "./bootstrap";
import { createApp } from 'vue';
import { createI18n } from 'vue-i18n';

// Import language JSON files
import en from '../../Modules/Frontend/resources/lang/en.json';
import hu from '../../Modules/Frontend/resources/lang/hu.json';
import rs from '../../Modules/Frontend/resources/lang/rs.json';

import App from "../../Modules/Frontend/resources/assets/js/App.vue";

// Create I18n instance
const i18n = createI18n({
  locale: 'rs', // Default locale
  fallbackLocale: 'en', // Fallback to English
  messages: {
    en,
    hu,
    rs
  }
});

// Create Vue app and use i18n
const app = createApp(App);
app.use(i18n);
app.mount('#app');

//Nav JS for animation start

var menuBtn = document.querySelector('.menu-btn');
var nav = document.querySelector('nav');
var lineOne = document.querySelector('nav .menu-btn .line--1');
var lineTwo = document.querySelector('nav .menu-btn .line--2');
var lineThree = document.querySelector('nav .menu-btn .line--3');
var link = document.querySelector('nav .nav-links');
var languageSwitcher = document.querySelector('nav .menu-btn .language-switcher');

menuBtn.addEventListener('click', () => {
  nav.classList.toggle('nav-open');
  lineOne.classList.toggle('line-cross');
  lineTwo.classList.toggle('line-fade-out');
  lineThree.classList.toggle('line-cross');
  link.classList.toggle('fade-in');

  if (languageSwitcher.classList.contains('hide-switch')) {
    languageSwitcher.classList.remove('hide-switch');
    languageSwitcher.classList.add('show-switch');
  } else {
      languageSwitcher.classList.remove('show-switch');
      languageSwitcher.classList.add('hide-switch');
  }

});

//Nav JS for animation end