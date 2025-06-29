<template>
    <div class="min-h-screen bg-black text-white">
        <!-- Page Header -->
        <div class="relative pt-32 pb-16 bg-black">
            <div class="absolute inset-0 opacity-30">
                <div class="absolute inset-0 bg-gradient-to-b from-black via-transparent to-black"></div>
            </div>
            <div class="container mx-auto px-4 relative">
                <h1 class="text-5xl font-bold mb-4 text-center">{{ $t("all_categories") }}</h1>
                <p class="text-xl text-gray-400 max-w-3xl mx-auto text-center">
                    {{ $t("browse_all_photography_categories") }}
                </p>
            </div>
        </div>

        <!-- Categories Grid -->
        <section class="py-16 bg-black">
            <div class="container mx-auto px-4">
                <!-- Loading state -->
                <div
                    v-if="loadingCategories"
                    class="flex justify-center items-center py-16"
                >
                    <div
                        class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"
                    ></div>
                </div>

                <!-- Categories grid -->
                <div
                    v-else
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <router-link
                        v-for="(category, index) in categories"
                        :key="index"
                        :to="{
                            name: 'category',
                            params: {
                                slug:
                                    category.slug || formatSlug(category.name),
                            },
                        }"
                        class="group relative h-96 rounded-xl overflow-hidden cursor-pointer"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/10 z-10"
                        ></div>
                        <img
                            :src="`/storage/${category.image}`"
                            :alt="getLocalizedContent(category.name)"
                            class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110"
                        />
                        <div
                            class="absolute bottom-0 left-0 right-0 p-6 z-20 transform transition-transform duration-500 group-hover:translate-y-[-10px]"
                        >
                            <h3 class="text-2xl font-bold mb-2">
                                {{ getLocalizedContent(category.name) }}
                            </h3>
                            <p class="text-gray-300 mb-4">
                                {{ getLocalizedContent(category.description) }}
                            </p>
                            <span
                                class="inline-flex items-center text-white font-medium group"
                            >
                                {{ $t("view_albums") }}
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 ml-2 transform transition-transform group-hover:translate-x-1"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </span>
                        </div>
                    </router-link>
                </div>

                <!-- Error state -->
                <div v-if="categoryError" class="text-center py-12">
                    <p class="text-red-400 mb-4">{{ categoryError }}</p>
                    <button
                        @click="loadCategories"
                        class="px-4 py-2 bg-white text-black rounded-lg hover:bg-gray-200"
                    >
                        {{ $t("try_again") }}
                    </button>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            loadingCategories: false,
            categoryError: null,
            categories: [],
        };
    },
    computed: {
        currentLocale() {
            return this.$i18n.locale;
        },
    },
    mounted() {
        // Ensure the page scrolls to top when the component is mounted
        this.$nextTick(() => {
            window.scrollTo({
                top: 0,
                left: 0,
                behavior: 'auto'
            });
        });
        
        this.loadCategories();
    },
    methods: {
        // Load categories from API
        async loadCategories() {
            this.loadingCategories = true;
            this.categoryError = null;

            try {
                const response = await axios.get("/api/categories");

                if (Array.isArray(response.data) && response.data.length > 0) {
                    this.categories = response.data;
                } else {
                    // Handle case where response might be wrapped in an object
                    this.categories =
                        response.data.data || response.data.categories || [];
                }
            } catch (error) {
                console.error("Failed to load categories:", error);
                this.categoryError = this.$t("failed_to_load_categories");
            } finally {
                this.loadingCategories = false;
            }
        },

        // Format a string into a URL-friendly slug
        formatSlug(text) {
            if (!text) return "";
            return text
                .toLowerCase()
                .replace(/[^\w\s-]/g, "")
                .replace(/[\s_-]+/g, "-")
                .replace(/^-+|-+$/g, "");
        },

        // Get localized content based on current locale
        getLocalizedContent(contentObj) {
            if (!contentObj) return "";

            // If contentObj is a string (not a localized object), return it directly
            if (typeof contentObj === "string") return contentObj;

            // First try current locale
            if (contentObj[this.currentLocale]) {
                return contentObj[this.currentLocale];
            }

            // Fall back to default locale if needed
            const defaultLocale = this.$i18n.fallbackLocale || "en";
            if (contentObj[defaultLocale]) {
                return contentObj[defaultLocale];
            }

            // Last resort: return first available translation or empty string
            const firstAvailableLocale = Object.keys(contentObj)[0];
            return firstAvailableLocale ? contentObj[firstAvailableLocale] : "";
        },
    },
};
</script>

<style scoped>
/* Base styles */
.filter-dark {
    filter: brightness(0.6) contrast(1.1);
}

/* Page animations */
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