<template>
  <div class="min-h-screen bg-black text-white">
    <!-- Hero Section -->
    <div class="relative h-screen flex items-center overflow-hidden">
      <div class="hero-slider absolute inset-0">
        <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 filter-dark"
             v-for="(slide, index) in heroSlides" 
             :key="index"
             :style="{ backgroundImage: `url(${slide.image})`, opacity: currentSlide === index ? 1 : 0 }">
        </div>
      </div>
      
      <!-- Monochrome text container with glass effect -->
      <div class="container mx-auto px-4 relative">
        <div class="hero-text-box max-w-3xl p-10 rounded-2xl backdrop-blur-md bg-black/50 border-l-2 border-white/30 shadow-[0_10px_50px_rgba(0,0,0,0.5)]">
          <div class="hero-text-accent absolute -top-10 -left-10 w-40 h-40 bg-white/10 blur-3xl rounded-full"></div>
          <div class="hero-text-accent absolute -bottom-10 -right-10 w-40 h-40 bg-white/5 blur-3xl rounded-full"></div>
          
          <h1 class="text-5xl md:text-7xl font-bold leading-tight mb-4 relative">
            {{ $t('we_capture_the_moments') }}
          </h1>
          <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-2xl relative">
            {{ $t('we_capture_the_moments_description') }}
          </p>
          <div class="flex flex-wrap gap-4 relative">
            <router-link 
              :to="{ name: 'contact'}"
              class="hero-button-primary px-8 py-3 bg-white text-black font-medium rounded-full hover:bg-gray-200 transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
            >
            {{ $t('getting_in_touch') }}
            </router-link>
            <a href="#portfolio" class="hero-button-secondary px-8 py-3 bg-transparent border border-white/80 text-white font-medium rounded-full hover:bg-white/10 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
              {{ $t('portfolio') }}
            </a>
          </div>
        </div>
      </div>
      
      <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 flex space-x-2">
        <button v-for="(_, index) in heroSlides" :key="index" 
                @click="currentSlide = index"
                class="w-3 h-3 rounded-full transition-all duration-300"
                :class="currentSlide === index ? 'bg-white w-8 shadow-lg' : 'bg-white/50'">
        </button>
      </div>
    </div>

    <!-- Portfolio/Categories Section -->
    <section id="portfolio" class="py-20 bg-black">
      <div class="container mx-auto px-4">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold mb-4">{{ $t('portfolio') }}</h2>
          <p class="text-gray-400 max-w-2xl mx-auto">{{ $t('browse_through_categories') }}</p>
        </div>
        
        <!-- Loading state -->
        <div v-if="loadingCategories" class="flex justify-center items-center py-16">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
        </div>
        
        <!-- Categories grid -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <router-link 
            v-for="(category, index) in categories" 
            :key="index"
            :to="{ name: 'category', params: { slug: category.slug || formatSlug(category.name) }}"
            class="group relative h-96 rounded-xl overflow-hidden cursor-pointer"
          >
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/10 z-10"></div>
            <img :src="`/storage/${category.image}`" :alt="getLocalizedContent(category.name)" class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110" />
            <div class="absolute bottom-0 left-0 right-0 p-6 z-20 transform transition-transform duration-500 group-hover:translate-y-[-10px]">
              <h3 class="text-2xl font-bold mb-2">{{ getLocalizedContent(category.name) }}</h3>
              <p class="text-gray-300 mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                {{ getLocalizedContent(category.description) }}
              </p>
              <span class="inline-flex items-center text-white font-medium group">
                {{ $t('view_albums') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform transition-transform group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </span>
            </div>
          </router-link>
        </div>
        
        <!-- Error state -->
        <div v-if="categoryError" class="text-center py-12">
          <p class="text-red-400 mb-4">{{ categoryError }}</p>
          <button @click="loadCategories" class="px-4 py-2 bg-white text-black rounded-lg hover:bg-gray-200">
            {{ $t('try_again') }}
          </button>
        </div>
      </div>
    </section>
    
    <!-- Videography Section -->
    <section id="videography" class="py-20 bg-black">
      <div class="container mx-auto px-4">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold mb-4">{{ $t('videography') }}</h2>
          <p class="text-gray-400 max-w-2xl mx-auto">{{ $t('videography_description') }}</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Featured Video -->
          <div class="relative h-[500px] rounded-xl overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/10 z-10"></div>
            <img src="https://images.unsplash.com/photo-1536240478700-b869070f9279" alt="Wedding Video" class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-105 grayscale" />
            <div class="absolute inset-0 flex items-center justify-center z-20">
              <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center cursor-pointer group-hover:scale-110 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 p-6 z-20 transform transition-transform duration-500 group-hover:translate-y-[-10px]">
              <h3 class="text-2xl font-bold mb-2">{{ $t('wedding_films') }}</h3>
              <p class="text-gray-300 mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                {{ $t('wedding_films_description') }}
              </p>
            </div>
          </div>
          
          <!-- Video Services -->
          <div class="grid grid-cols-1 gap-8">
            <!-- Corporate Videos -->
            <div class="relative h-[240px] rounded-xl overflow-hidden group cursor-pointer">
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/10 z-10"></div>
              <img src="https://images.unsplash.com/photo-1556761175-b413da4baf72" alt="Corporate Videos" class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110 grayscale" />
              <div class="absolute bottom-0 left-0 right-0 p-6 z-20 transform transition-transform duration-500 group-hover:translate-y-[-10px]">
                <h3 class="text-xl font-bold mb-2">{{ $t('corporate_videos') }}</h3>
                <p class="text-gray-300 text-sm mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                  {{ $t('corporate_videos_description') }}
                </p>
                <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
            </div>
            
            <!-- Event Documentation -->
            <div class="relative h-[240px] rounded-xl overflow-hidden group cursor-pointer">
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/10 z-10"></div>
              <img src="https://images.unsplash.com/photo-1505236858219-8359eb29e329" alt="Event Documentation" class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110 grayscale" />
              <div class="absolute bottom-0 left-0 right-0 p-6 z-20 transform transition-transform duration-500 group-hover:translate-y-[-10px]">
                <h3 class="text-xl font-bold mb-2">{{ $t('event_documentation') }}</h3>
                <p class="text-gray-300 text-sm mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                  {{ $t('event_documentation_description') }}
                </p>
                <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Video Services Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
          <div class="p-6 rounded-xl bg-white/5 backdrop-blur-sm border border-white/10 transition-transform hover:-translate-y-2 duration-300">
            <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">{{ $t('4k_filming') }}</h3>
            <p class="text-gray-400">{{ $t('4k_filming_description') }}</p>
          </div>
          
          <div class="p-6 rounded-xl bg-white/5 backdrop-blur-sm border border-white/10 transition-transform hover:-translate-y-2 duration-300">
            <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">{{ $t('cinematic_editing') }}</h3>
            <p class="text-gray-400">{{ $t('cinematic_editing_description') }}</p>
          </div>
          
          <div class="p-6 rounded-xl bg-white/5 backdrop-blur-sm border border-white/10 transition-transform hover:-translate-y-2 duration-300">
            <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">{{ $t('custom_soundtrack') }}</h3>
            <p class="text-gray-400">{{ $t('custom_soundtrack_description') }}</p>
          </div>
        </div>
        
        <!-- CTA -->
        <div class="text-center mt-16">
          <router-link 
            :to="{ name: 'video'}"
            class="inline-flex items-center px-6 py-3 bg-white text-black font-medium rounded-full hover:bg-gray-200 transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
          >
            {{ $t('our_videography_portfolio') }}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </router-link>
        </div>
      </div>
    </section>
    
    <!-- About Section -->
    <section id="about" class="py-20 bg-black">
      <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center gap-12">
          <div class="lg:w-1/2">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1542038784456-1ea8e935640e" alt="Photographer" class="rounded-xl grayscale" />
            </div>
          </div>
          <div class="lg:w-1/2">
            <h2 class="text-4xl font-bold mb-6">{{ $t('about_us') }}</h2>
            <p class="text-gray-300 mb-6">
              Szenvedélyünk a fotózás, amely lehetővé teszi, hogy megörökítsük az élet legszebb és legkülönlegesebb pillanatait. Több mint 10 éve dolgozunk azon, hogy ügyfeleink számára maradandó emlékeket teremtsünk.
            </p>
            <p class="text-gray-300 mb-8">
              Csapatunk képzett fotósokból áll, akik szakértelmükkel és kreativitásukkal minden projektet egyedivé tesznek. Legyen szó esküvőről, portréról vagy vállalati eseményről, mi mindig a legmagasabb minőségre törekszünk.
            </p>
            
            <!-- <a href="#" class="inline-flex items-center px-6 py-3 bg-white text-black font-medium rounded-full hover:bg-gray-200 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
              Ismerd meg csapatunkat
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </a> -->
          </div>
        </div>
      </div>
    </section>
    
    <!-- Contact CTA Section -->
    <section class="py-20 bg-white text-black">
      <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-6">{{ $t('lets_start_the_next_project') }}</h2>
        <p class="text-xl max-w-2xl mx-auto mb-8 text-gray-700">
          {{ $t('contact_us_and_let_discuss') }}
        </p>
        <router-link 
          :to="{ name: 'contact'}"
          class="inline-flex items-center px-6 py-3 bg-black text-white font-medium rounded-full hover:bg-gray-800 transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
        >
        {{ $t('getting_in_touch') }}
        </router-link>
      </div>
    </section>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      scrolled: false,
      currentSlide: 0,
      currentTestimonial: 0,
      sliderInterval: null,
      loadingCategories: false,
      categoryError: null,
      menuItems: [
        { name: 'Főoldal', link: '#' },
        { name: 'Kategóriák', link: '#categories' },
        { name: 'Portfólió', link: '#portfolio' },
        { name: 'Videók', link: '#videography' },
        { name: 'Rólunk', link: '#about' },
        { name: 'Árak', link: '#' }
      ],
      heroSlides: [
        { 
          image: 'https://iso.500px.com/wp-content/uploads/2020/08/stock-photo-240662573.jpg',
          title: 'Megörökítjük a pillanatokat'
        },
        { 
          image: '/build-frontend/images/categories/party.jpg',
          title: 'Eseményfotózás'
        },
        { 
          image: 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc',
          title: 'Esküvői fotózás'
        }
      ],
      categories: [],
      // Fallback categories in case API fails
      fallbackCategories: [
        {
          name: 'Portré',
          description: 'Egyedi portré fotózás természetes környezetben vagy stúdióban.',
          image: 'https://iso.500px.com/wp-content/uploads/2020/08/stock-photo-240662573.jpg'
        },
        {
          name: 'Esküvő',
          description: 'Az életed legszebb napjának megörökítése a legmagasabb minőségben.',
          image: 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc'
        },
        {
          name: 'Rendezvény',
          description: 'Céges és privát események professzionális megörökítése.',
          image: '/build-frontend/images/categories/party.jpg'
        },
        {
          name: 'Termék',
          description: 'Termékeid legjobb oldalát mutatjuk meg a vásárlóknak.',
          image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30'
        },
        {
          name: 'Családi',
          description: 'Örökítsd meg a család legszebb pillanatait.',
          image: 'https://images.unsplash.com/photo-1511895426328-dc8714191300'
        },
        {
          name: 'Ingatlan',
          description: 'Tökéletes fotók ingatlanodról az eladáshoz vagy bérbeadáshoz.',
          image: 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2'
        }
      ],
    };
  },
  mounted() {
    window.addEventListener('scroll', this.handleScroll);
    this.startSlider();
    this.loadCategories();
  },
  beforeUnmount() {
    window.removeEventListener('scroll', this.handleScroll);
    clearInterval(this.sliderInterval);
  },
  computed: {
    currentLocale() {
      return this.$i18n.locale;
    }
  },
  methods: {
    handleScroll() {
      this.scrolled = window.scrollY > 50;
    },
    startSlider() {
      this.sliderInterval = setInterval(() => {
        this.currentSlide = (this.currentSlide + 1) % this.heroSlides.length;
      }, 5000);
    },
    nextTestimonial() {
      this.currentTestimonial = (this.currentTestimonial + 1) % this.testimonials.length;
    },
    prevTestimonial() {
      this.currentTestimonial = (this.currentTestimonial - 1 + this.testimonials.length) % this.testimonials.length;
    },
    
    // Load categories from API
    async loadCategories() {
      this.loadingCategories = true;
      this.categoryError = null;
      
      try {
        const response = await axios.get('/api/categories');
        
        if (Array.isArray(response.data) && response.data.length > 0) {
          this.categories = response.data;
        } else if (response.data && typeof response.data === 'object') {
          // Handle case where response might be wrapped in an object
          this.categories = response.data.data || response.data.categories || [];
        } else {
          // Fallback to default categories if API returns empty data
          this.categories = this.fallbackCategories;
        }
      } catch (error) {
        console.error('Failed to load categories:', error);
        this.categoryError = this.$t('failed_to_load_categories');
        // Fallback to default categories
        this.categories = this.fallbackCategories;
      } finally {
        this.loadingCategories = false;
      }
    },
    
    // Format a string into a URL-friendly slug
    formatSlug(text) {
      if (!text) return '';
      return text
        .toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
    },
    
    // Get localized content based on current locale
    getLocalizedContent(contentObj) {
      if (!contentObj) return '';
      
      // If contentObj is a string (not a localized object), return it directly
      if (typeof contentObj === 'string') return contentObj;
      
      // First try current locale
      if (contentObj[this.currentLocale]) {
        return contentObj[this.currentLocale];
      }
      
      // Fall back to default locale if needed
      const defaultLocale = this.$i18n.fallbackLocale || 'en';
      if (contentObj[defaultLocale]) {
        return contentObj[defaultLocale];
      }
      
      // Last resort: return first available translation or empty string
      const firstAvailableLocale = Object.keys(contentObj)[0];
      return firstAvailableLocale ? contentObj[firstAvailableLocale] : '';
    }
  }
};
</script>

<style scoped>
/* Base styles */
.filter-dark {
  filter: brightness(0.6) contrast(1.1);
}

.hero-slider img {
  transform: scale(1.05);
  transition: transform 8s ease;
}

/* Hero text box styling */
.hero-text-box {
  position: relative;
  overflow: hidden;
}

.hero-text-accent {
  z-index: -1;
}

/* Enhanced button hover effects */
.hero-button-primary, .hero-button-secondary {
  transition: all 0.3s ease;
}

.hero-button-primary:hover, .hero-button-secondary:hover {
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Page animations */
html {
  scroll-behavior: smooth;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Apply grayscale to all images for consistent monochrome look */
.grayscale {
  filter: grayscale(100%);
}
</style>