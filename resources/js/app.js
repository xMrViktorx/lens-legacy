import "./bootstrap";
import { createApp, watch } from 'vue';
import { createI18n } from 'vue-i18n';
import router from './router';

// Import language JSON files
import en from '../../Modules/Frontend/resources/lang/en.json';
import hu from '../../Modules/Frontend/resources/lang/hu.json';
import sr from '../../Modules/Frontend/resources/lang/sr.json';
import App from "../../Modules/Frontend/resources/assets/js/App.vue";

// Get saved locale from localStorage or use default
const savedLocale = localStorage.getItem('user_locale') || 'sr';

// Create I18n instance
const i18n = createI18n({
  legacy: false, // Use Composition API
  globalInjection: true, // Make i18n available in template
  locale: savedLocale, // Use saved locale or default to 'sr'
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

// Set initial lang attribute
document.querySelector('html').setAttribute('lang', savedLocale);

// Mount the app
app.mount('#app');

// Set up locale watcher after app is mounted
watch(
  () => i18n.global.locale.value,
  (newLocale) => {
    localStorage.setItem('user_locale', newLocale);
    document.querySelector('html').setAttribute('lang', newLocale);
  }
);