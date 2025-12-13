<template>
<nav id="header" class="fixed w-full z-40 top-0 text-white bg-gray-900 shadow-lg">
  <div class="container mx-auto flex flex-wrap items-center justify-between py-4">

      <a class="text-white font-bold text-2xl" href="/">
        FinServe Bank
      </a>

      <button id="nav-toggle" class="lg:hidden text-white focus:outline-none">
        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 6a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75a.75.75 0 01-.75-.75zm0 6a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75a.75.75 0 01-.75-.75z" clip-rule="evenodd"/>
        </svg>
      </button>

      <div id="nav-content"
           class="hidden lg:flex lg:items-center lg:space-x-6 bg-white lg:bg-transparent text-black lg:text-white p-4 lg:p-0 rounded">

          <template v-if="isLoggedIn">
              <router-link to="/dashboard" class="py-2 px-3 hover:underline">
                  Dashboard
              </router-link>
              <router-link to="/accounts" class="py-2 px-3 hover:underline">
                  Accounts
              </router-link>
              <router-link to="/transactions" class="py-2 px-3 hover:underline">
                  Transactions
              </router-link>
              <router-link to="/customers" class="py-2 px-3 hover:underline">
                  Customers
              </router-link>

              <button @click="logout"
                      class="mt-4 lg:mt-0 bg-white text-red-600 font-semibold px-6 py-2 rounded-full shadow hover:scale-105 transition">
                Sign Out
              </button>
          </template>

          <template v-else>
              <router-link to="/" class="py-2 px-3 hover:underline">
                  Home
              </router-link>
              <router-link to="/about" class="py-2 px-3 hover:underline">
                  About Us
              </router-link>
              <router-link to="/contact" class="py-2 px-3 hover:underline">
                  Contact
              </router-link>

              <router-link to="/login"
                      class="mt-4 lg:mt-0 bg-white text-gray-800 font-semibold px-6 py-2 rounded-full shadow hover:scale-105 transition">
                Login
              </router-link>
          </template>

      </div>

  </div>
</nav>
</template>

<script setup>
import { onMounted, ref } from "vue";
// NOTE: Ensure this path is correct in your project
import setupMobileMenu from "../../assets/js/menu.js";

// --- State Management and Toggles ---
// In a real application, this 'isLoggedIn' state would come from a global store (Pinia/Vuex)
const isLoggedIn = ref(false); 

const login = () => {
    isLoggedIn.value = true;
    console.log("Logged In: Displaying Portal Menu");
};

const logout = () => {
    isLoggedIn.value = false;
    console.log("Logged Out: Displaying Public Menu");
};
// ------------------------------------

onMounted(() => {
  setupMobileMenu();
});
</script>