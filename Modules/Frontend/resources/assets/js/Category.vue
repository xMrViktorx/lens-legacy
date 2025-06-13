<template>
  <div class="min-h-screen bg-black">
    <!-- Header with background image and glossy overlay -->
    <header class="relative h-80 md:h-96 flex items-center justify-center mb-12">
      <img
        :src="`/storage/${category.image}`"
        alt="Header background"
        class="absolute inset-0 w-full h-full object-cover object-center"
      />
      <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/55 to-black/70 backdrop-blur-sm"></div>
      <div class="relative px-7 py-8 md:py-12 rounded-xl bg-white/10 backdrop-blur-md shadow-xl max-w-2xl mx-auto text-center">
        <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-3 drop-shadow-lg">
          {{ category.name?.[currentLocale] }}
        </h1>
        <p class="text-lg md:text-xl text-gray-200">
          {{ category.description?.[currentLocale] }}
        </p>
      </div>
    </header>

    <!-- Grid with linear, filled random sizes -->
    <div class="max-w-7xl mx-auto px-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 auto-rows-[16rem] md:auto-rows-[20rem]">
        <div
          v-for="album in albums"
          :key="album.id"
          :class="[
            'relative rounded-2xl overflow-hidden shadow-lg bg-gradient-to-b from-gray-900 to-gray-800 cursor-pointer transition-transform hover:scale-[1.025] hover:shadow-2xl duration-150',
            // On mobile, always normal size
            'col-span-1 row-span-1',
            // On >=sm, apply random size
            album.size === 'tall' ? 'sm:row-span-2' : '',
            album.size === 'wide' ? 'sm:col-span-2' : ''
          ]"
        >
          <img
            :src="album.cover"
            :alt="album.name?.[currentLocale]"
            class="absolute inset-0 w-full h-full object-cover object-center"
          />
          <div class="absolute inset-0 bg-black/5"></div>
          <div class="relative flex flex-col items-start justify-end h-full p-6 z-10">
            <h2 class="text-2xl font-bold text-white mb-1">{{ album.name?.[currentLocale] }}</h2>
            <p class="text-sm text-gray-200 bg-black/30 px-3 py-1 rounded-full mt-1 backdrop-blur-md">{{ album.imgCount }} Photos</p>
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
      category: {},
      albums: [],
    };
  },

  async created() {
    await axios.get('/api/category/' + this.$route.params.slug)
    .then(response => {
      this.category = response.data[0];
    })
    .catch(error => {
      console.error('Failed to fetch category:', error);
    });

    await axios.get('/api/category/albums/' + this.$route.params.slug)
    .then(response => {
      this.albums = this.linearRandomSizes(response.data, 4); // 4 columns for lg screens
    })
    .catch(error => {
      console.error('Failed to fetch albums:', error);
    });
  },

  methods: {
    linearRandomSizes(albums, cols) {
      const shuffled = [...albums];
      for (let i = shuffled.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
      }

      const result = [];
      let i = 0;
      while (i < shuffled.length) {
        let row = [];
        let slots = cols;

        // Pick one index in this row to be "wide" or "tall" (but only if enough albums left)
        let wideEligible = slots >= 2 && shuffled.length - i >= 2;
        let tallEligible = shuffled.length - i >= 1;

        let wideOrTall = null;
        if (wideEligible && Math.random() < 0.6) {
          // 60% chance to have a wide card in this row
          wideOrTall = { type: 'wide', idx: Math.floor(Math.random() * (slots - 1)) };
        } else if (tallEligible && Math.random() < 0.6) {
          // 60% chance to have a tall card in this row (if not wide)
          wideOrTall = { type: 'tall', idx: Math.floor(Math.random() * slots) };
        }

        for (let col = 0; col < cols && i < shuffled.length; col++) {
          let size = 'normal';
          if (
            wideOrTall &&
            wideOrTall.idx === col &&
            wideOrTall.type === 'wide' &&
            slots >= 2
          ) {
            size = 'wide';
            slots -= 2;
            row.push({ ...shuffled[i], size });
            i++;
            col++; // skip next column for wide
            if (col < cols && i < shuffled.length) {
              // Fill skipped column with null for alignment, will be ignored in rendering
              row.push(null);
            }
          } else if (
            wideOrTall &&
            wideOrTall.idx === col &&
            wideOrTall.type === 'tall'
          ) {
            size = 'tall';
            slots--;
            row.push({ ...shuffled[i], size });
            i++;
          } else {
            size = 'normal';
            slots--;
            row.push({ ...shuffled[i], size });
            i++;
          }
        }

        // Remove nulls for rendering
        result.push(...row.filter(Boolean));
      }

      return result;
    }
  }
};
</script>

<style scoped>
/* No additional CSS needed, Tailwind handles grid sizing */
</style>