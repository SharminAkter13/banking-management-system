<template>
  <div class="gradient min-h-screen">
    <Navbar />

    <!-- Page Content -->
    <main class="pt-24">
      <slot />
    </main>

    <Footer />
  </div>
</template>

<script setup>
import Navbar from "../components/portals/Navbar.vue";
import Footer from "../components/portals/Footer.vue";
</script>
