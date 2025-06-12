<template>
  <div>
    <!-- Navbar -->
    <Navbar />

    <!-- Main content -->
    <router-view />

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script>
  import Swal from "sweetalert2";
  import axios from "axios";

  import Navbar from "./Navbar.vue";
  import Footer from "./Footer.vue";

  export default {
    name: "App",
    components: {
      Navbar,
      Footer,
    },
  };
</script>
