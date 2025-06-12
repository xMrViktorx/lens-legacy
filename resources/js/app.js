import "./bootstrap";
import { createApp } from 'vue';
import { createI18n } from 'vue-i18n';
import router from './router';

// Import language JSON files
import en from '../../Modules/Frontend/resources/lang/en.json';
import hu from '../../Modules/Frontend/resources/lang/hu.json';
import sr from '../../Modules/Frontend/resources/lang/sr.json';
import App from "../../Modules/Frontend/resources/assets/js/App.vue";

// Create I18n instance
const i18n = createI18n({
  locale: 'sr', // Default locale
  fallbackLocale: 'en', // Fallback to English
  messages: {
    en,
    hu,
    sr
  }
});

// Create Vue app and use i18n
const app = createApp(App);
app.use(i18n);
app.use(router);
app.mount('#app');