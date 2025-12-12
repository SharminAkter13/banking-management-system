<template>
<nav id="header" class="fixed w-full z-30 top-0 text-white">
  <div class="container mx-auto flex flex-wrap items-center justify-between py-4">

      <!-- Logo -->
      <a class="text-white font-bold text-2xl" href="#">
        LANDING
      </a>

      <!-- Mobile Toggle -->
      <button id="nav-toggle" class="lg:hidden text-white focus:outline-none">
        <svg class="h-6 w-6" fill="currentColor">
          <path d="M0 3h20v2H0zm0 6h20v2H0zm0 6h20v2H0"/>
        </svg>
      </button>

      <!-- Menu -->
      <div id="nav-content"
           class="hidden lg:flex lg:items-center lg:space-x-6 bg-white lg:bg-transparent text-black lg:text-white p-4 lg:p-0 rounded">

          <router-link to="/" class="py-2 px-3 hover:underline">
              Home
          </router-link>

          <a href="#" class="py-2 px-3 hover:underline">Link 1</a>
          <a href="#" class="py-2 px-3 hover:underline">Link 2</a>

          <!-- CTA Button -->
          <button class="mt-4 lg:mt-0 bg-white text-gray-800 font-semibold px-6 py-2 rounded-full shadow hover:scale-105 transition">
            Login
          </button>
      </div>

  </div>
</nav>
</template>

<script setup>
import { onMounted } from "vue";
import setupMobileMenu from "../../assets/js/menu.js";

onMounted(() => {
  setupMobileMenu();
});
</script>
