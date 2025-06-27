<template>
  <div class="min-h-screen bg-black">
    <!-- Header with background image and glossy overlay -->
    <header class="relative h-64 md:h-96 flex items-center justify-center mb-8 md:mb-12">
      <img
        v-if="category && category.image"
        :src="`/storage/${category.image}`"
        alt="Header background"
        class="absolute inset-0 w-full h-full object-cover object-center"
        loading="eager"
      />
      <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/55 to-black/70 backdrop-blur-sm"></div>
      <div class="relative px-5 md:px-7 py-6 md:py-12 rounded-xl bg-white/10 backdrop-blur-md shadow-xl max-w-2xl mx-auto text-center">
        <h1 class="text-2xl md:text-5xl font-extrabold text-white mb-2 md:mb-3 drop-shadow-lg">
          {{ category ? getLocalizedContent(category.name) : 'Loading...' }}
        </h1>
        <p class="text-base md:text-xl text-gray-200">
          {{ category ? getLocalizedContent(category.description) : '' }}
        </p>
      </div>
    </header>

    <!-- Loading state -->
    <div v-if="loading" class="flex justify-center items-center py-16">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
    </div>

    <!-- Modern card layout grid -->
    <div v-else-if="albums.length > 0" class="px-4 md:px-8 lg:px-20 pb-16">
      <!-- Featured album (first item, larger) - Only shown on larger screens -->
      <div v-if="albums.length > 0" class="hidden md:block mb-6">
        <router-link
          :to="{ name: 'album', params: { slug: albums[0].slug }}"
          class="relative rounded-2xl overflow-hidden shadow-lg bg-gradient-to-b from-gray-900 to-gray-800 cursor-pointer hover:shadow-2xl duration-200 group h-[24rem] w-full block"
        >
          <div class="absolute inset-0 rounded-2xl overflow-hidden transform-gpu">
            <img
              :src="`/storage/${albums[0].cover_image}`"
              :alt="getLocalizedContent(albums[0].name)"
              class="w-full h-full object-cover object-center transition-transform duration-300 group-hover:scale-105"
              loading="eager"
            />
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/10 rounded-2xl"></div>
          <div class="relative flex flex-col items-start justify-end h-full p-8">
            <span class="px-3 py-1 bg-white/20 text-white text-sm font-medium rounded-full mb-3 backdrop-blur-md">{{ $t('featured') }}</span>
            <h2 class="text-3xl font-bold text-white mb-2">
              {{ getLocalizedContent(albums[0].name) }}
            </h2>
            <p class="text-base text-gray-200 mb-2 max-w-xl">
              {{ getLocalizedContent(albums[0].description || {}) }}
            </p>
            <p class="text-sm text-gray-200 bg-black/30 px-3 py-1 rounded-full mt-1 backdrop-blur-md">
              {{ albums[0].imgCount }} {{ $t('photos') }}
            </p>
          </div>
        </router-link>
      </div>

      <!-- Main grid - no gaps pattern layout -->
      <div v-if="isMobile" class="grid grid-cols-1 gap-3">
        <!-- Mobile view - simple single column -->
        <router-link
          v-for="album in albums"
          :key="album.id"
          :to="{ name: 'album', params: { slug: album.slug }}"
          class="relative rounded-2xl overflow-hidden shadow-lg bg-gradient-to-b from-gray-900 to-gray-800 cursor-pointer hover:shadow-2xl duration-200 group h-[20rem]"
        >
          <div class="absolute inset-0 rounded-2xl overflow-hidden transform-gpu">
            <img
              :src="`/storage/${album.cover_image}`"
              :alt="getLocalizedContent(album.name)"
              class="w-full h-full object-cover object-center transition-transform duration-300 group-hover:scale-105"
              loading="lazy"
            />
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-black/10 rounded-2xl"></div>
          <div class="relative flex flex-col items-start justify-end h-full p-4">
            <h2 class="text-xl font-bold text-white mb-1">
              {{ getLocalizedContent(album.name) }}
            </h2>
            <p class="text-sm text-gray-200 bg-black/30 px-3 py-1 rounded-full mt-1 backdrop-blur-md">
              {{ album.imgCount }} {{ $t('photos') }}
            </p>
          </div>
        </router-link>
      </div>
      
      <!-- Desktop view with pattern layout that ensures no gaps -->
      <div v-else class="album-grid">
        <!-- We'll use a CSS Grid layout with a specific pattern -->
        <router-link
          v-for="(album, index) in albums.slice(1)"
          :key="album.id"
          :to="{ name: 'album', params: { slug: album.slug }}"
          :class="[
            'album-item relative rounded-2xl overflow-hidden shadow-lg bg-gradient-to-b from-gray-900 to-gray-800 cursor-pointer hover:shadow-2xl duration-200 group',
            getGridClass(index)
          ]"
        >
          <div class="absolute inset-0 rounded-2xl overflow-hidden transform-gpu">
            <img
              :src="`/storage/${album.cover_image}`"
              :alt="getLocalizedContent(album.name)"
              class="w-full h-full object-cover object-center transition-transform duration-300 group-hover:scale-105"
              loading="lazy"
            />
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-black/10 rounded-2xl"></div>
          <div class="relative flex flex-col items-start justify-end h-full p-6">
            <h2 class="text-2xl font-bold text-white mb-1">
              {{ getLocalizedContent(album.name) }}
            </h2>
            <p class="text-sm text-gray-200 bg-black/30 px-3 py-1 rounded-full mt-1 backdrop-blur-md">
              {{ album.imgCount }} {{ $t('photos') }}
            </p>
          </div>
        </router-link>
      </div>
    </div>

    <!-- No albums state -->
    <div v-else-if="!loading" class="text-center py-16 text-gray-400">
      <p class="text-xl">{{ $t('no_albums_found') }}</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: "CategoryPage",
  data() {
    return {
      category: null,
      albums: [],
      loading: true,
      windowWidth: window.innerWidth
    };
  },
  
  computed: {
    currentLocale() {
      return this.$i18n.locale;
    },
    
    // Check if we're on mobile
    isMobile() {
      return this.windowWidth < 640; // sm breakpoint in Tailwind
    }
  },

  watch: {
    // Re-fetch data when locale changes
    currentLocale() {
      this.fetchData();
    },
    
    // Re-fetch data when route changes
    '$route.params.slug'() {
      this.fetchData();
    }
  },

  created() {
    // Ensure locale is initialized from localStorage if available
    const savedLocale = localStorage.getItem('user_locale');
    if (savedLocale && this.$i18n.availableLocales.includes(savedLocale)) {
      this.$i18n.locale = savedLocale;
    }
    
    this.fetchData();
  },
  
  mounted() {
    // Add window resize listener for responsive layout
    window.addEventListener('resize', this.handleResize);
    this.handleResize();
  },
  
  beforeUnmount() {
    // Clean up resize listener
    window.removeEventListener('resize', this.handleResize);
  },

  methods: {
    // Track window width for responsive designs
    handleResize() {
      this.windowWidth = window.innerWidth;
    },
    
    // Fetch all data at once to reduce code duplication
    async fetchData() {
      this.loading = true;
      
      try {
        const categorySlug = this.$route.params.slug;
        
        // First, get the category data
        const categoryResponse = await axios.get('/api/category/' + categorySlug);
        
        // Handle different response structures
        if (Array.isArray(categoryResponse.data) && categoryResponse.data.length > 0) {
          this.category = categoryResponse.data[0];
        } else if (categoryResponse.data && typeof categoryResponse.data === 'object') {
          this.category = categoryResponse.data;
        } else {
          this.category = null;
        }
        
        // Then, get the albums
        const albumsResponse = await axios.get('/api/category/albums/' + categorySlug);
        
        if (Array.isArray(albumsResponse.data)) {
          this.albums = albumsResponse.data;
        } else {
          this.albums = [];
        }
      } catch (error) {
        console.error('Failed to fetch data:', error);
        this.category = null;
        this.albums = [];
      } finally {
        this.loading = false;
      }
    },
    
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
    },
    
    // Get grid positioning class based on index to create a pattern that fills the grid
    getGridClass(index) {
      // Calculate position in our repeating pattern (modulo 12)
      const position = index % 12;
      
      // Assign grid classes based on position in the pattern
      switch (position) {
        case 0: return 'tall'; // Tall item (spans 2 rows)
        case 3: return 'wide'; // Wide item (spans 2 columns)
        case 4: return 'wide-tall'; // Large item (spans 2x2)
        case 9: return 'tall'; // Another tall item
        default: return 'normal'; // Regular item
      }
    }
  }
};
</script>

<style scoped>
/* Ensure smooth transitions for all elements */
.router-link-active, .router-link-exact-active {
  @apply text-white;
}

/* Album grid layout that ensures no gaps */
.album-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-auto-rows: 320px; /* Increased from 280px to make thin cards taller */
  gap: 16px;
}

/* Item sizing classes */
.album-item.normal {
  grid-column: span 1;
  grid-row: span 1;
}

.album-item.wide {
  grid-column: span 2;
  grid-row: span 1;
}

.album-item.tall {
  grid-column: span 1;
  grid-row: span 2;
}

.album-item.wide-tall {
  grid-column: span 2;
  grid-row: span 2;
}

/* Responsive grid adjustments */
@media (max-width: 1024px) {
  .album-grid {
    grid-template-columns: repeat(2, 1fr);
    grid-auto-rows: 340px; /* Even taller on tablet-sized screens */
  }
}

/* Touch optimization for mobile */
@media (max-width: 639px) {
  .grid {
    gap: 12px;
  }
}
</style>