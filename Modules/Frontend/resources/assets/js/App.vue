<template>
    <Navbar />
    <Home />
    <Footer />
</template>

<script>
import Navbar from "./Navbar.vue";
import Home from "./Home.vue";
import Footer from "./Footer.vue";

export default {
  data() {
    return {
    };
  },
  components: {
    Navbar,
    Home,
    Footer
  },
};
</script>