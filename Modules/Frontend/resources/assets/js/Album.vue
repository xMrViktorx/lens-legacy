<template>
  <div class="min-h-screen bg-gradient-to-b from-gray-900 to-black text-white">
    <!-- Album Header -->
    <header class="relative h-64 md:h-80 overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/80 z-10"></div>
      <img 
        v-if="album.cover_image" 
        :src="`/storage/${album.cover_image}`" 
        alt="Album cover" 
        class="absolute inset-0 w-full h-full object-cover object-center opacity-70"
      />
      <div class="relative z-20 container mx-auto px-4 h-full flex flex-col justify-end pb-8">
        <h1 class="text-3xl md:text-5xl font-bold mb-2">{{ getLocalizedContent(album.name) }}</h1>
        <p class="text-lg text-gray-300 max-w-3xl">{{ getLocalizedContent(album.description) }}</p>
        <div class="flex items-center mt-4 text-sm">
          <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full">{{ album.imgCount }} Photos</span>
          <span class="mx-2">•</span>
          <span>{{ formatDate(album.created_at) }}</span>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
      <!-- Enlarged Image View -->
      <div v-if="selectedImage" class="fixed inset-0 bg-black/95 z-50 flex items-center justify-center">
        <button @click="closeFullscreen" class="absolute top-4 right-4 text-white/70 hover:text-white z-[60]">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        
        <!-- Navigation arrows -->
        <button 
          @click="prevImage" 
          class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 rounded-full p-3 transition-all z-[60]"
          :class="{'opacity-50 cursor-not-allowed': currentIndex === 0 || isTransitioning}"
          :disabled="currentIndex === 0 || isTransitioning"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        
        <button 
          @click="nextImage" 
          class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 rounded-full p-3 transition-all z-[60]"
          :class="{'opacity-50 cursor-not-allowed': currentIndex === images.length - 1 || isTransitioning}"
          :disabled="currentIndex === images.length - 1 || isTransitioning"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
        
        <!-- Image counter -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black/50 px-4 py-2 rounded-full text-sm z-[60]">
          {{ currentIndex + 1 }} / {{ images.length }}
        </div>
        
        <!-- Image container with fixed dimensions -->
        <div class="relative w-[85vw] h-[85vh] flex items-center justify-center">
          <!-- Current image -->
          <img 
            v-if="!isTransitioning || transitionDirection === 0"
            :src="selectedImage" 
            alt="Selected photo" 
            class="max-h-full max-w-full object-contain transition-opacity duration-300"
            :class="{'opacity-0': isTransitioning}"
            @load="imageLoaded"
          />
          
          <!-- Next image (for animation) -->
          <img 
            v-if="isTransitioning && nextImageSrc"
            :src="nextImageSrc" 
            alt="Next photo" 
            class="absolute max-h-full max-w-full object-contain opacity-0 transition-opacity duration-300"
            :class="{'opacity-100': isTransitioning && imageIsLoaded}"
            @load="nextImageLoaded"
          />
        </div>
      </div>

      <!-- Loading state -->
      <div v-if="isLoading" class="flex justify-center items-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
      </div>

      <!-- Thumbnail Grid -->
      <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mt-8">
        <div 
          v-for="(image, index) in images" 
          :key="index" 
          @click="selectImage(index)"
          class="aspect-square overflow-hidden rounded-lg cursor-pointer relative group"
        >
          <img 
            :src="image" 
            :alt="`Photo ${index + 1}`"
            class="w-full h-full object-cover object-center transition-transform duration-300 group-hover:scale-105"
            loading="lazy"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <div class="absolute bottom-2 left-2 text-sm font-medium text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { ref, onMounted, watch, nextTick } from 'vue';
import { useRoute } from 'vue-router';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

export default {
  name: 'AlbumView',
  
  setup() {
    const route = useRoute();
    const { locale } = useI18n();
    
    const album = ref({});
    const images = ref([]);
    const isLoading = ref(true);
    const selectedImage = ref(null);
    const currentIndex = ref(0);
    const isTransitioning = ref(false);
    const nextImageSrc = ref(null);
    const transitionDirection = ref(0); // -1: prev, 0: none, 1: next
    const imageIsLoaded = ref(false);
    
    const fetchAlbum = async () => {
      try {
        isLoading.value = true;
        const response = await axios.get(`/api/album/${route.params.slug}`);
        album.value = response.data;
        
        // Now fetch images for this album
        await fetchAlbumImages();
      } catch (error) {
        console.error('Failed to fetch album:', error);
      } finally {
        isLoading.value = false;
      }
    };
    
    const fetchAlbumImages = async () => {
      try {
        // Note: You'll need to create this endpoint in your Laravel backend
        const response = await axios.get(`/api/album/${route.params.slug}/images`);
        images.value = response.data.map(image => `/storage/${image}`);
      } catch (error) {
        console.error('Failed to fetch album images:', error);
        images.value = [];
      }
    };
    
    const selectImage = (index) => {
      if (isTransitioning.value) return;
      
      currentIndex.value = index;
      selectedImage.value = images.value[index];
      // Add keyboard event listeners when an image is selected
      document.addEventListener('keydown', handleKeydown);
    };
    
    const closeFullscreen = () => {
      selectedImage.value = null;
      // Remove keyboard event listeners when fullscreen is closed
      document.removeEventListener('keydown', handleKeydown);
    };
    
    const nextImage = () => {
      if (isTransitioning.value || currentIndex.value >= images.value.length - 1) return;
      
      transitionDirection.value = 1;
      changeImage(currentIndex.value + 1);
    };
    
    const prevImage = () => {
      if (isTransitioning.value || currentIndex.value <= 0) return;
      
      transitionDirection.value = -1;
      changeImage(currentIndex.value - 1);
    };
    
    const changeImage = async (newIndex) => {
      if (isTransitioning.value) return;
      
      isTransitioning.value = true;
      imageIsLoaded.value = false;
      nextImageSrc.value = images.value[newIndex];
      
      // Wait for a short time to ensure transition starts
      await new Promise(resolve => setTimeout(resolve, 50));
      
      // The actual transition will happen when the next image loads
    };
    
    const nextImageLoaded = () => {
      imageIsLoaded.value = true;
      
      // Complete the transition after the fade animation
      setTimeout(() => {
        currentIndex.value = transitionDirection.value > 0 
          ? currentIndex.value + 1 
          : currentIndex.value - 1;
        
        selectedImage.value = images.value[currentIndex.value];
        isTransitioning.value = false;
        nextImageSrc.value = null;
        transitionDirection.value = 0;
      }, 300); // Match this with your CSS transition duration
    };
    
    const imageLoaded = () => {
      // Image loaded successfully
    };
    
    const handleKeydown = (e) => {
      if (e.key === 'ArrowRight') {
        nextImage();
      } else if (e.key === 'ArrowLeft') {
        prevImage();
      } else if (e.key === 'Escape') {
        closeFullscreen();
      }
    };
    
    const getLocalizedContent = (contentObj) => {
      if (!contentObj) return '';
      
      // First try current locale
      if (contentObj[locale.value]) {
        return contentObj[locale.value];
      }
      
      // Fall back to default locale
      const defaultLocale = 'en';
      if (contentObj[defaultLocale]) {
        return contentObj[defaultLocale];
      }
      
      // Last resort: return first available translation
      const firstAvailableLocale = Object.keys(contentObj)[0];
      return firstAvailableLocale ? contentObj[firstAvailableLocale] : '';
    };
    
    const formatDate = (dateString) => {
      if (!dateString) return '';
      const date = new Date(dateString);
      return new Intl.DateTimeFormat(locale.value, {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      }).format(date);
    };
    
    // Watch for route changes to reload data
    watch(() => route.params.slug, () => {
      fetchAlbum();
    });
    
    // Watch for locale changes to update translations
    watch(() => locale.value, () => {
      // No need to refetch data, just re-render with new locale
    });
    
    onMounted(() => {
      fetchAlbum();
    });
    
    return {
      album,
      images,
      isLoading,
      selectedImage,
      currentIndex,
      isTransitioning,
      nextImageSrc,
      transitionDirection,
      imageIsLoaded,
      selectImage,
      closeFullscreen,
      nextImage,
      prevImage,
      getLocalizedContent,
      formatDate,
      imageLoaded,
      nextImageLoaded
    };
  }
};
</script>

<style scoped>
/* Smooth transitions for image changes */
img {
  will-change: opacity;
}

.opacity-0 {
  opacity: 0;
}

.opacity-100 {
  opacity: 1;
}

/* Ensure images fit properly */
img.max-h-full.max-w-full {
  max-height: 85vh;
  max-width: 85vw;
}

/* Add touch swipe for mobile */
@media (max-width: 768px) {
  /* Add custom swipe implementation if needed */
}
</style>