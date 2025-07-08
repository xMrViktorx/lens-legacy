<template>
  <div class="min-h-screen bg-gradient-to-b from-gray-900 to-black text-white">
    <!-- Album Header -->
    <header class="relative h-72 md:h-[450px] 2xl:h-[500px] overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/80"></div>
      <img v-if="album.cover_image" :src="`/storage/${album.cover_image}`" alt="Album cover"
        class="absolute inset-0 w-full h-full object-cover object-center opacity-70" />
      <div class="relative container mx-auto px-4 h-full flex flex-col justify-end pb-8">
        <h1 class="text-3xl md:text-5xl font-bold mb-2 mt-10 md:mt-0">{{ getLocalizedContent(album.name) }}</h1>
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
      <!-- Back Button -->
      <button @click="goBackToCategory"
        class="inline-flex items-center px-6 py-3 bg-white text-black font-medium rounded-full hover:bg-gray-200 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:-translate-x-1 transition" fill="none"
          viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        <span class="font-medium text-base">{{ $t('back_to_category') }}</span>
      </button>

      <!-- Enlarged Image View -->
      <div v-if="selectedImage" class="fixed inset-0 bg-black/95 z-[200] flex items-center justify-center">
        <button @click="closeFullscreen" class="absolute top-4 right-4 text-white/70 hover:text-white z-[60]">
          <svg xmlns="https://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        <!-- Navigation arrows -->
        <button @click="prevImage"
          class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 rounded-full p-3 transition-all z-[60]"
          :class="{ 'opacity-50 cursor-not-allowed': currentIndex === 0 || isTransitioning }"
          :disabled="currentIndex === 0 || isTransitioning">
          <svg xmlns="https://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        <button @click="nextImage"
          class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 rounded-full p-3 transition-all z-[60]"
          :class="{ 'opacity-50 cursor-not-allowed': currentIndex === images.length - 1 || isTransitioning }"
          :disabled="currentIndex === images.length - 1 || isTransitioning">
          <svg xmlns="https://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>

        <!-- Image counter -->
        <div
          class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black/50 px-4 py-2 rounded-full text-sm z-[60]">
          {{ currentIndex + 1 }} / {{ images.length }}
        </div>

        <!-- Image container with fixed dimensions -->
        <div class="relative w-[85vw] h-[85vh] flex items-center justify-center">
          <!-- Current image -->
          <img v-if="!isTransitioning || transitionDirection === 0" :src="selectedImage" alt="Selected photo"
            class="max-h-full max-w-full object-contain transition-opacity duration-300"
            :class="{ 'opacity-0': isTransitioning }" @load="imageLoaded" />

          <!-- Next image (for animation) -->
          <img v-if="isTransitioning && nextImageSrc" :src="nextImageSrc" alt="Next photo"
            class="absolute max-h-full max-w-full object-contain opacity-0 transition-opacity duration-300"
            :class="{ 'opacity-100': isTransitioning && imageIsLoaded }" @load="nextImageLoaded" />
        </div>
      </div>

      <!-- Loading state -->
      <div v-if="isLoading" class="flex justify-center items-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
      </div>

      <!-- Thumbnail Grid -->
      <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 mt-8">
        <div v-for="(image, index) in images" :key="index" @click="selectImage(index)"
          class="aspect-square overflow-hidden rounded-lg cursor-pointer relative group">
          <img :src="image" :alt="`Photo ${index + 1}`"
            class="w-full h-full object-cover object-center transition-transform duration-300 group-hover:scale-105"
            loading="lazy" />
          <div
            class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
          </div>
          <div
            class="absolute bottom-2 left-2 text-sm font-medium text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { ref, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import axios from 'axios';

export default {
  name: 'AlbumView',

  setup() {
    const route = useRoute();
    const router = useRouter();
    const { locale, t } = useI18n();

    const album = ref({});
    const images = ref([]);
    const isLoading = ref(true);
    const selectedImage = ref(null);
    const currentIndex = ref(0);
    const isTransitioning = ref(false);
    const nextImageSrc = ref(null);
    const transitionDirection = ref(0); // -1: prev, 0: none, 1: next
    const imageIsLoaded = ref(false);

    // For back button - try to get the category slug from the album if available
    const goBackToCategory = () => {
      // If album has category info, navigate to its page
      if (album.value && album.value.category && album.value.category.slug) {
        router.push({ name: 'category', params: { slug: album.value.category.slug } });
      } else if (album.value && album.value.category_slug) {
        router.push({ name: 'category', params: { slug: album.value.category_slug } });
      } else {
        router.back();
      }
    };

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
      document.addEventListener('keydown', handleKeydown);
    };

    const closeFullscreen = () => {
      selectedImage.value = null;
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
      await new Promise(resolve => setTimeout(resolve, 50));
      // The actual transition will happen when the next image loads
    };

    const nextImageLoaded = () => {
      imageIsLoaded.value = true;
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

    const imageLoaded = () => { };

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
      if (contentObj[locale.value]) {
        return contentObj[locale.value];
      }
      const defaultLocale = 'en';
      if (contentObj[defaultLocale]) {
        return contentObj[defaultLocale];
      }
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
      nextImageLoaded,
      goBackToCategory,
      t
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

img.max-h-full.max-w-full {
  max-height: 85vh;
  max-width: 85vw;
}

@media (max-width: 768px) {

  /* Add custom swipe implementation if needed */
  .absolute.top-6.left-4 {
    top: 1rem;
    left: 0.5rem;
  }
}

/* Back button */
button[aria-label="Back"],
.back-btn {
  background: rgba(0, 0, 0, 0.35);
  color: white;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 0.5rem 1.2rem;
  border-radius: 9999px;
  display: flex;
  align-items: center;
  font-size: 1rem;
  box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.13);
  transition: background 0.2s, color 0.2s;
}

button[aria-label="Back"]:hover,
.back-btn:hover {
  background: rgba(0, 0, 0, 0.6);
}

/* Fix for header button on mobile */
@media (max-width: 640px) {

  .absolute.top-6.left-4,
  .absolute.top-6.left-8 {
    top: 0.5rem;
    left: 0.5rem;
    padding: 0.25rem 0.7rem;
    font-size: 0.95rem;
  }
}
</style>