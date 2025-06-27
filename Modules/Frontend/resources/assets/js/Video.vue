<template>
  <div class="min-h-screen bg-black">
    <!-- Header with background image and glossy overlay -->
    <header class="relative h-64 md:h-96 flex items-center justify-center mb-8 md:mb-12">
      <div class="absolute inset-0 bg-gray-900"></div>
      <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/55 to-black/70 backdrop-blur-sm"></div>
      <div class="relative px-5 md:px-7 py-6 md:py-12 rounded-xl bg-white/10 backdrop-blur-md shadow-xl max-w-2xl mx-auto text-center">
        <h1 class="text-2xl md:text-5xl font-extrabold text-white mb-2 md:mb-3 drop-shadow-lg">
          {{ $t('videography') }}
        </h1>
        <p class="text-base md:text-xl text-gray-200">
          {{ $t('videography_description') }}
        </p>
      </div>
    </header>

    <!-- Loading state -->
    <div v-if="loading" class="flex justify-center items-center py-16">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
    </div>
    
    <!-- Video category filters -->
    <div v-if="!loading" class="container mx-auto px-4 md:px-8 lg:px-20 mb-8">
      <div class="flex flex-wrap justify-center gap-3">
        <button 
          @click="setActiveCategory('all')" 
          class="px-4 py-2 rounded-full text-sm font-medium transition-all"
          :class="activeCategory === 'all' ? 'bg-white text-black' : 'bg-white/10 text-white hover:bg-white/20'"
        >
        {{ $t('all_videos') }}
        </button>
        <button 
          v-for="category in videoCategories" 
          :key="category" 
          @click="setActiveCategory(category)"
          class="px-4 py-2 rounded-full text-sm font-medium transition-all"
          :class="activeCategory === category ? 'bg-white text-black' : 'bg-white/10 text-white hover:bg-white/20'"
        >
          {{ category }}
        </button>
      </div>
    </div>

    <!-- Videos layout grid -->
    <div v-if="!loading && videos.length > 0" class="px-4 md:px-8 lg:px-20 pb-16">
      <!-- Featured video (first item, larger) - Only shown on larger screens -->
      <div v-if="videos.length > 0" class="hidden md:block mb-6">
        <div class="relative rounded-2xl overflow-hidden shadow-lg bg-gradient-to-b from-gray-900 to-gray-800 h-[24rem] w-full">
          <!-- Cover image with play button overlay -->
          <div v-if="!isVideoActive(videos[0].id)" class="absolute inset-0 z-10 cursor-pointer" @click="activateVideo(videos[0].id)">
            <!-- YouTube thumbnail image -->
            <img 
              :src="`https://img.youtube.com/vi/${videos[0].youtubeId}/maxresdefault.jpg`" 
              :alt="videos[0].title"
              class="absolute inset-0 w-full h-full object-cover object-center"
              @error="handleThumbnailError($event, videos[0].youtubeId)"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/10"></div>
            
            <!-- Play button -->
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-300 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white group-hover:scale-110 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
            
            <div class="absolute bottom-0 left-0 right-0 p-8 z-20">
              <h2 class="text-3xl font-bold text-white mb-2">
                {{ videos[0].title }}
              </h2>
              <p class="text-base text-gray-200 mb-2 max-w-xl">
                {{ videos[0].description }}
              </p>
              <p class="text-sm text-gray-200 bg-black/30 px-3 py-1 rounded-full mt-1 backdrop-blur-md inline-block">
                {{ videos[0].category }}
              </p>
            </div>
          </div>
          
          <!-- YouTube iframe (shown only when active) -->
          <div v-if="isVideoActive(videos[0].id)" class="w-full h-full">
            <iframe 
              class="w-full h-full" 
              :src="`https://www.youtube.com/embed/${videos[0].youtubeId}?autoplay=1&modestbranding=1&rel=0`" 
              title="YouTube video player" 
              frameborder="0" 
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
              allowfullscreen>
            </iframe>
          </div>
        </div>
      </div>

      <!-- Mobile view - simple single column -->
      <div v-if="isMobile" class="grid grid-cols-1 gap-4">
        <div
          v-for="video in videos"
          :key="video.id"
          class="relative rounded-2xl overflow-hidden shadow-lg bg-gradient-to-b from-gray-900 to-gray-800 h-[20rem]"
        >
          <!-- Cover image with play button overlay -->
          <div v-if="!isVideoActive(video.id)" class="absolute inset-0 z-10 cursor-pointer" @click="activateVideo(video.id)">
            <!-- YouTube thumbnail image -->
            <img 
              :src="`https://img.youtube.com/vi/${video.youtubeId}/hqdefault.jpg`" 
              :alt="video.title"
              class="absolute inset-0 w-full h-full object-cover object-center"
              @error="handleThumbnailError($event, video.youtubeId)"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/10"></div>
            
            <!-- Play button -->
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-300 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white group-hover:scale-110 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
            
            <div class="absolute bottom-0 left-0 right-0 p-4 z-20">
              <h2 class="text-xl font-bold text-white mb-1">
                {{ $t(video.title) }}
              </h2>
              <p class="text-sm text-gray-200 bg-black/30 px-3 py-1 rounded-full mt-1 backdrop-blur-md inline-block">
                {{ video.category }}
              </p>
            </div>
          </div>
          
          <!-- YouTube iframe (shown only when active) -->
          <div v-if="isVideoActive(video.id)" class="w-full h-full">
            <iframe 
              class="w-full h-full" 
              :src="`https://www.youtube.com/embed/${video.youtubeId}?autoplay=1&modestbranding=1&rel=0`" 
              title="YouTube video player" 
              frameborder="0" 
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
              allowfullscreen>
            </iframe>
          </div>
        </div>
      </div>
      
      <!-- Desktop view with uniform grid -->
      <div v-else class="uniform-video-grid">
        <div
          v-for="(video, index) in videos.slice(1)"
          :key="video.id"
          class="video-item relative rounded-2xl overflow-hidden shadow-lg bg-gradient-to-b from-gray-900 to-gray-800"
        >
          <!-- Cover image with play button overlay -->
          <div v-if="!isVideoActive(video.id)" class="absolute inset-0 z-10 cursor-pointer" @click="activateVideo(video.id)">
            <!-- YouTube thumbnail image -->
            <img 
              :src="`https://img.youtube.com/vi/${video.youtubeId}/hqdefault.jpg`" 
              :alt="video.title"
              class="absolute inset-0 w-full h-full object-cover object-center"
              @error="handleThumbnailError($event, video.youtubeId)"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/10"></div>
            
            <!-- Play button -->
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-300 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white group-hover:scale-110 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
            
            <div class="absolute bottom-0 left-0 right-0 p-6 z-20">
              <h2 class="text-2xl font-bold text-white mb-1">
                {{ (video.title) }}
              </h2>
              <p class="text-sm text-gray-200 bg-black/30 px-3 py-1 rounded-full mt-1 backdrop-blur-md inline-block">
                {{ video.category }}
              </p>
            </div>
          </div>
          
          <!-- YouTube iframe (shown only when active) -->
          <div v-if="isVideoActive(video.id)" class="w-full h-full">
            <iframe 
              class="w-full h-full" 
              :src="`https://www.youtube.com/embed/${video.youtubeId}?autoplay=1&modestbranding=1&rel=0`" 
              title="YouTube video player" 
              frameborder="0" 
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
              allowfullscreen>
            </iframe>
          </div>
        </div>
      </div>
    </div>

    <!-- No videos state -->
    <div v-else-if="!loading && (!videos || videos.length === 0)" class="text-center py-16 text-gray-400">
      <p class="text-xl">{{ $t('no_videos_found') }}</p>
    </div>
    
    <!-- Video playback controls -->
    <div v-if="activeVideoId && !isMobile" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50">
      <div class="flex items-center gap-4 px-6 py-3 bg-black/80 backdrop-blur-md rounded-full shadow-xl">
        <button @click="closeActiveVideo" class="w-10 h-10 flex items-center justify-center rounded-full bg-white text-black hover:bg-gray-200 transition-all">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
        <span class="text-white font-medium">
          {{ getCurrentVideoTitle() }}
        </span>
        <button @click="openFullscreen" class="text-white hover:text-gray-300 ml-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 11-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15 13.586V12a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "VideoPage",
  data() {
    return {
      loading: true,
      windowWidth: window.innerWidth,
      activeVideoId: null,
      activeCategory: 'all',
      allVideos: [
        {
          id: 1,
          title: 'MK.Old Bikers Vojvodina - Open Moto Season Party 2025 | Aftermovie',
          description: '',
          category: 'Aftermovie',
          youtubeId: 'mK1Mq4_x6bw'
        },
        {
          id: 2,
          title: 'Tavaszi VIFÓ bál 2025 | Official Aftermovie',
          description: '.',
          category: 'Aftermovie',
          youtubeId: '_x6ix33An7I'
        },
        {
          id: 3,
          title: 'Őszi VIFÓ bál 2024 | Official Aftermovie',
          description: '',
          category: 'Aftermovie',
          youtubeId: 'wI2V-1Eei6M'
        }
      ]
    };
  },
  
  computed: {
    videos() {
      if (this.activeCategory === 'all') {
        return this.allVideos;
      } else {
        return this.allVideos.filter(video => video.category === this.activeCategory);
      }
    },
    
    videoCategories() {
      return [...new Set(this.allVideos.map(video => video.category))];
    },
    
    // Check if we're on mobile
    isMobile() {
      return this.windowWidth < 640; // sm breakpoint in Tailwind
    }
  },
  
  mounted() {
    // Add window resize listener for responsive layout
    window.addEventListener('resize', this.handleResize);
    this.handleResize();
    
    // Set loading to false after a short delay to simulate API loading
    setTimeout(() => {
      this.loading = false;
    }, 1000);

    // Set up router navigation guard for scroll to top if router exists
    if (this.$router) {
      this.$router.afterEach(() => {
        this.scrollToTop();
      });
    }

  },
  
  beforeUnmount() {
    // Clean up resize listener
    window.removeEventListener('resize', this.handleResize);
  },

  methods: {
    // Handle thumbnail loading errors
    handleThumbnailError(event, videoId) {
      // If maxresdefault fails, try hqdefault
      if (event.target.src.includes('maxresdefault')) {
        event.target.src = `https://img.youtube.com/vi/${videoId}/hqdefault.jpg`;
      }
      // If hqdefault fails, try mqdefault
      else if (event.target.src.includes('hqdefault')) {
        event.target.src = `https://img.youtube.com/vi/${videoId}/mqdefault.jpg`;
      }
      // If all else fails, show a default background
      else {
        event.target.style.display = 'none';
      }
    },

    scrollToTop() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    },
    
    // Track window width for responsive designs
    handleResize() {
      this.windowWidth = window.innerWidth;
    },
    
    // Check if a video is currently active
    isVideoActive(videoId) {
      return this.activeVideoId === videoId;
    },
    
    // Activate a video
    activateVideo(videoId) {
      console.log(`Activating video ID: ${videoId}`);
      this.activeVideoId = videoId;
    },
    
    // Close the active video
    closeActiveVideo() {
      this.activeVideoId = null;
    },
    
    // Get the title of the currently active video
    getCurrentVideoTitle() {
      if (!this.activeVideoId) return '';
      
      const video = this.allVideos.find(v => v.id === this.activeVideoId);
      return video ? video.title : '';
    },
    
    // Open fullscreen for the active video
    openFullscreen() {
      if (!this.activeVideoId) return;
      
      const iframe = document.querySelector(`iframe[title="YouTube video player"]`);
      if (iframe) {
        if (document.fullscreenElement) {
          document.exitFullscreen();
        } else {
          iframe.requestFullscreen().catch(err => {
            console.error(`Error attempting to enable fullscreen: ${err.message}`);
          });
        }
      }
    },
    
    // Set active category for filtering
    setActiveCategory(category) {
      this.activeCategory = category;
      this.closeActiveVideo();
    }
  }
};
</script>

<style scoped>
/* Ensure smooth transitions for all elements */
.router-link-active, .router-link-exact-active {
  @apply text-white;
}

/* Uniform video grid layout */
.uniform-video-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
}

/* All video items have the same fixed height */
.video-item {
  height: 300px; /* Fixed height for all cards */
}

/* Responsive grid adjustments */
@media (max-width: 1200px) {
  .uniform-video-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 767px) {
  .uniform-video-grid {
    grid-template-columns: 1fr;
  }
}

/* Touch optimization for mobile */
@media (max-width: 639px) {
  .grid {
    gap: 12px;
  }
}

/* Grayscale effect on images */
.grayscale {
  filter: grayscale(100%);
}
</style>