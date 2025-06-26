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
            Megörökítjük a pillanatokat
          </h1>
          <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-2xl relative">
            Professzionális fotózás minden alkalomra, hogy az emlékeid örökké éljenek.
          </p>
          <div class="flex flex-wrap gap-4 relative">
            <a href="#categories" class="hero-button-primary px-8 py-3 bg-white text-black font-medium rounded-full hover:bg-gray-200 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
              Kategóriák
            </a>
            <a href="#portfolio" class="hero-button-secondary px-8 py-3 bg-transparent border border-white/80 text-white font-medium rounded-full hover:bg-white/10 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
              Portfólió
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

    <!-- Categories Section -->
    <section id="categories" class="py-20 bg-black">
      <div class="container mx-auto px-4">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold mb-4">Kategóriák</h2>
          <p class="text-gray-400 max-w-2xl mx-auto">Válassz a különböző fotózási kategóriák közül, mindegyikben professzionális minőséget nyújtunk.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="(category, index) in categories" :key="index"
               class="group relative h-96 rounded-xl overflow-hidden cursor-pointer">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/10 z-10"></div>
            <img :src="category.image" :alt="category.name" class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110" />
            <div class="absolute bottom-0 left-0 right-0 p-6 z-20 transform transition-transform duration-500 group-hover:translate-y-[-10px]">
              <h3 class="text-2xl font-bold mb-2">{{ category.name }}</h3>
              <p class="text-gray-300 mb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">{{ category.description }}</p>
              <a href="#" class="inline-flex items-center text-white font-medium group">
                Galéria megtekintése
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform transition-transform group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </a>
            </div>
          </div>
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
              <div class="absolute -bottom-6 -right-6 bg-white text-black p-4 rounded-lg">
                <p class="text-lg font-bold">10+ év</p>
                <p>tapasztalat</p>
              </div>
            </div>
          </div>
          <div class="lg:w-1/2">
            <h2 class="text-4xl font-bold mb-6">Rólunk</h2>
            <p class="text-gray-300 mb-6">
              Szenvedélyünk a fotózás, amely lehetővé teszi, hogy megörökítsük az élet legszebb és legkülönlegesebb pillanatait. Több mint 10 éve dolgozunk azon, hogy ügyfeleink számára maradandó emlékeket teremtsünk.
            </p>
            <p class="text-gray-300 mb-8">
              Csapatunk képzett fotósokból áll, akik szakértelmükkel és kreativitásukkal minden projektet egyedivé tesznek. Legyen szó esküvőről, portréról vagy vállalati eseményről, mi mindig a legmagasabb minőségre törekszünk.
            </p>
            <div class="grid grid-cols-3 gap-4 mb-8">
              <div class="text-center">
                <p class="text-3xl font-bold text-white">500+</p>
                <p class="text-gray-400">Elégedett ügyfél</p>
              </div>
              <div class="text-center">
                <p class="text-3xl font-bold text-white">1000+</p>
                <p class="text-gray-400">Elkészült projekt</p>
              </div>
              <div class="text-center">
                <p class="text-3xl font-bold text-white">20+</p>
                <p class="text-gray-400">Díj és elismerés</p>
              </div>
            </div>
            <a href="#" class="inline-flex items-center px-6 py-3 bg-white text-black font-medium rounded-full hover:bg-gray-200 transition-colors">
              Ismerd meg csapatunkat
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Contact CTA Section -->
    <section class="py-20 bg-white text-black">
      <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-6">Kezdjük el a következő projektet együtt</h2>
        <p class="text-xl max-w-2xl mx-auto mb-8 text-gray-700">
          Vedd fel velünk a kapcsolatot, és beszéljük meg, hogyan tehetjük egyedivé a te fotózásodat.
        </p>
        <a href="#" class="inline-block px-8 py-4 bg-black text-white font-medium rounded-full hover:bg-gray-800 transition-colors">
          Kapcsolatfelvétel
        </a>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  data() {
    return {
      scrolled: false,
      currentSlide: 0,
      currentTestimonial: 0,
      sliderInterval: null,
      menuItems: [
        { name: 'Főoldal', link: '#' },
        { name: 'Kategóriák', link: '#categories' },
        { name: 'Portfólió', link: '#portfolio' },
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
      categories: [
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
  },
  beforeUnmount() {
    window.removeEventListener('scroll', this.handleScroll);
    clearInterval(this.sliderInterval);
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