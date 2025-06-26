<template>
  <div class="min-h-screen bg-black">
    <!-- Header with background image and glossy overlay -->
    <header class="relative h-80 md:h-96 flex items-center justify-center mb-12">
      <img
        v-if="category.image"
        :src="`/storage/${category.image}`"
        alt="Header background"
        class="absolute inset-0 w-full h-full object-cover object-center"
        loading="eager"
      />
      <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/55 to-black/70 backdrop-blur-sm"></div>
      <div class="relative px-7 py-8 md:py-12 rounded-xl bg-white/10 backdrop-blur-md shadow-xl max-w-2xl mx-auto text-center">
        <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-3 drop-shadow-lg">
          {{ getLocalizedContent(category.name) }}
        </h1>
        <p class="text-lg md:text-xl text-gray-200">
          {{ getLocalizedContent(category.description) }}
        </p>
      </div>
    </header>

    <!-- Loading state -->
    <div v-if="loading" class="flex justify-center items-center py-20">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
    </div>

    <!-- Grid with linear, filled random sizes -->
    <div v-else-if="albums.length > 0" class="mx-20 px-4 pb-16">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 auto-rows-[16rem] md:auto-rows-[20rem]">
        <router-link
          v-for="album in albums"
          :key="album.id"
          :to="{ name: 'album', params: { slug: album.slug }}"
          :class="[
            'relative rounded-2xl overflow-hidden shadow-lg bg-gradient-to-b from-gray-900 to-gray-800 cursor-pointer hover:shadow-2xl duration-200 group',
            // On mobile, always normal size
            'col-span-1 row-span-1',
            // On >=sm, apply random size
            album.size === 'tall' ? 'sm:row-span-2' : '',
            album.size === 'wide' ? 'sm:col-span-2' : ''
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
          <div class="relative flex flex-col items-start justify-end h-full p-6 z-10">
            <h2 class="text-2xl font-bold text-white mb-1">
              {{ getLocalizedContent(album.name) }}
            </h2>
            <p class="text-sm text-gray-200 bg-black/30 px-3 py-1 rounded-full mt-1 backdrop-blur-md">
              {{ album.imgCount }} Photos
            </p>
          </div>
        </router-link>
      </div>
    </div>

    <!-- No albums state -->
    <div v-else class="text-center py-20 text-gray-400">
      <p class="text-xl">No albums found in this category</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: "CategoryPage",
  data() {
    return {
      category: {},
      albums: [],
      loading: true,
    };
  },
  
  computed: {
    currentLocale() {
      return this.$i18n.locale;
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

  methods: {
    // Fetch all data at once to reduce code duplication
    async fetchData() {
      this.loading = true;
      
      try {
        // Use Promise.all to fetch both requests in parallel
        const [categoryResponse, albumsResponse] = await Promise.all([
          axios.get('/api/category/' + this.$route.params.slug),
          axios.get('/api/category/albums/' + this.$route.params.slug)
        ]);
        
        this.category = categoryResponse.data[0] || {};
        this.albums = this.linearRandomSizes(albumsResponse.data, 4);
      } catch (error) {
        console.error('Failed to fetch data:', error);
        this.category = {};
        this.albums = [];
      } finally {
        this.loading = false;
      }
    },
    
    getLocalizedContent(contentObj) {
      if (!contentObj) return '';
      
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

    linearRandomSizes(albums, cols) {
      if (!albums || !albums.length) return [];
      
      // Create a copy to avoid mutating the original
      const shuffled = [...albums];
      
      // Fisher-Yates shuffle algorithm
      for (let i = shuffled.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
      }

      const result = [];
      let i = 0;
      
      // Process albums to assign sizes
      while (i < shuffled.length) {
        let slots = cols;
        const rowItems = Math.min(slots, shuffled.length - i);
        
        // Only consider special sizes if we have enough items
        let wideEligible = slots >= 2 && rowItems >= 2;
        let tallEligible = rowItems >= 1;
        
        // Determine if this row will have a special-sized card
        let specialCard = null;
        
        if (wideEligible && Math.random() < 0.6) {
          // 60% chance for a wide card
          specialCard = { type: 'wide', idx: Math.floor(Math.random() * (rowItems - 1)) };
        } else if (tallEligible && Math.random() < 0.6) {
          // 60% chance for a tall card
          specialCard = { type: 'tall', idx: Math.floor(Math.random() * rowItems) };
        }
        
        // Process each position in the current row
        for (let pos = 0; pos < rowItems && i < shuffled.length; pos++) {
          if (specialCard && specialCard.idx === pos) {
            if (specialCard.type === 'wide' && slots >= 2) {
              // Add a wide card
              result.push({ ...shuffled[i], size: 'wide' });
              i++;
              slots -= 2;
              pos++; // Skip next position
            } else if (specialCard.type === 'tall') {
              // Add a tall card
              result.push({ ...shuffled[i], size: 'tall' });
              i++;
              slots--;
            }
          } else if (i < shuffled.length) {
            // Add a normal card
            result.push({ ...shuffled[i], size: 'normal' });
            i++;
            slots--;
          }
        }
      }

      return result;
    }
  }
};
</script>

<style scoped>
/* Ensure smooth transitions for all elements */
.router-link-active, .router-link-exact-active {
  @apply text-white;
}
</style>