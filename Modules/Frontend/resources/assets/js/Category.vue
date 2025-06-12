<template>
  <div class="min-h-screen bg-black">
    <!-- Header with background image and glossy overlay -->
    <header class="relative h-64 md:h-96 flex items-center justify-center mb-12 h">
      <img
        :src="`/storage/${category.image}`"
        alt="Header background"
        class="absolute inset-0 w-full h-full object-cover object-center"
      />
      <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/55 to-black/70 backdrop-blur-sm"></div>
      <div class="relative px-7 py-8 md:py-12 rounded-xl bg-white/10 backdrop-blur-md shadow-xl max-w-2xl mx-auto text-center">
        <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-3 drop-shadow-lg">
          {{ category.name[currentLocale] }}
        </h1>
        <p class="text-lg md:text-xl text-gray-200">
          {{ category.description[currentLocale] }}
        </p>
      </div>
    </header>

    <!-- Responsive Masonry-like grid with different card sizes (but same size on mobile/1-column) -->
    <div class="max-w-7xl mx-auto px-4">
      <div
        class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-6 space-y-6"
        style="column-gap:1.5rem;"
      >
        <div
          v-for="category in categories"
          :key="category.id"
          :class="[
            'break-inside-avoid cursor-pointer transition-transform hover:scale-[1.025] hover:shadow-2xl duration-150',
            // On small screens (single column), force a uniform height
            'h-64',
            // On larger screens, use size variants
            'sm:h-72',
            category.size === 'tall' ? 'md:h-96' : category.size === 'wide' ? 'md:h-60' : 'md:h-72'
          ]"
          @click="goToCategory(category.id)"
        >
          <div class="relative h-full rounded-2xl overflow-hidden shadow-lg bg-gradient-to-b from-gray-900 to-gray-800">
            <img
              :src="category.cover"
              :alt="category.name"
              class="absolute inset-0 w-full h-full object-cover object-center"
            />
            <div class="absolute inset-0 bg-black/5"></div>
            <div class="relative flex flex-col items-start justify-end h-full p-6">
              <h2 class="text-2xl font-bold text-white mb-1 ">{{ category.name }}</h2>
              <p class="text-sm text-gray-200 bg-black/30 px-3 py-1 rounded-full mt-1 backdrop-blur-md">{{ category.albumCount }} Albums</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: "CategoryPage",
  data() {
    return {
      currentLocale: this.$i18n.locale,
      category: [],
    };
  },

  async created() {
    await axios.get('/api/category/' + this.$route.params.slug)
    .then(response => {
      this.category = response.data[0];
      console.log('Category fetched:', this.category);
    })
    .catch(error => {
      console.error('Failed to fetch category:', error);
    });
  },

  methods: {
    goToCategory(categoryId) {
      // Replace with your routing logic
      this.$router.push({ name: 'AlbumsByCategory', params: { id: categoryId } });
    },
  },
};
</script>

<style scoped>

@media (min-width: 640px) {
  .columns-2 > div {
    margin-bottom: 1.5rem;
  }
}

@media (min-width: 768px) {
  .columns-3 > div {
    margin-bottom: 1.5rem;
  }
}

@media (min-width: 1024px) {
  .columns-4 > div {
    margin-bottom: 1.5rem;
  }
}

</style>