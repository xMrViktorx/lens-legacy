<template>
  <div>
    <!-- Navbar -->
    <Navbar />

    <!-- Home Content -->
    <Home />

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script>
  import Navbar from "./Navbar.vue";
  import Home from "./Home.vue";
  import Footer from "./Footer.vue";

  export default {
    name: "App",
    components: {
      Navbar,
      Home,
      Footer,
    },
  };
</script>
