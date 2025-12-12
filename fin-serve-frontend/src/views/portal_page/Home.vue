<script setup>
import PortalLayout from "../../layouts/PortalLayout.vue";
</script>

<template>
  <PortalLayout>
    <!-- HERO SECTION -->
    <div class="pt-24">
      <div class="container px-3 mx-auto flex flex-wrap flex-col md:flex-row items-center">
        
        <!-- Left Text -->
        <div class="flex flex-col w-full md:w-2/5 justify-center items-start text-center md:text-left">
          <p class="uppercase tracking-loose w-full">What business are you?</p>

          <h1 class="my-4 text-5xl font-bold leading-tight">
            Main Hero Message to sell yourself!
          </h1>

          <p class="leading-normal text-2xl mb-8">
            Sub-hero message, not too long and not too short. Make it just right!
          </p>

          <button
            class="mx-auto lg:mx-0 hover:underline bg-white text-gray-800 font-bold rounded-full my-6 py-4 px-8 shadow-lg transform transition hover:scale-105 duration-300 ease-in-out">
            Subscribe
          </button>
        </div>

        <!-- Right Image -->
        <div class="w-full md:w-3/5 py-6 text-center">
          <img class="w-full md:w-4/5 z-50" :src="'/hero.png'" alt="Hero Image" />
        </div>
      </div>
    </div>

    <!-- SVG WAVE -->
    <div class="relative -mt-12 lg:-mt-24">
      <svg viewBox="0 0 1428 174" xmlns="http://www.w3.org/2000/svg">
        <g fill="none" fill-rule="evenodd">
          <g transform="translate(-2 44)" fill="#FFFFFF" fill-rule="nonzero">
            <path
              d="M0,0 C90.7283404,0.927527913 147.912752,27.187927 291.910178,59.9119003 C387.908462,81.7278826 543.605069,89.334785 759,82.7326078 C469.336065,156.254352 216.336065,153.6679 0,74.9732496"
              opacity="0.1"
            ></path>
          </g>

          <g transform="translate(-4 76)" fill="#FFFFFF" fill-rule="nonzero">
            <path
              d="M0.457,34.035 C57.086,53.198 98.208,65.809 123.822,71.865 C181.454,85.495 234.295,90.29 272.033,93.459 C311.355,96.759 396.635,95.801 461.025,91.663 C486.76,90.01 518.727,86.372 556.926,80.752 C595.747,74.596 622.372,70.008 636.799,66.991 C663.913,61.324 712.501,49.503 727.605,46.128 C780.47,34.317 818.839,22.532 856.324,15.904 C922.689,4.169 955.676,2.522 1011.185,0.432 C1060.705,1.477 1097.39,3.129 1121.236,5.387 C1161.703,9.219 1208.621,17.821 1235.4,22.304 C1285.855,30.748 1354.351,47.432 1440.886,72.354 L1441.191,104.352 L1.121,104.031 L0.457,34.035 Z"
            ></path>
          </g>
        </g>
      </svg>
    </div>

    <!-- CONTENT SECTIONS -->
    <section class="bg-white border-b py-8">
      <div class="container max-w-5xl mx-auto m-8">

        <h2 class="text-5xl font-bold text-center text-gray-800">Title</h2>
        <div class="h-1 mx-auto gradient w-64 opacity-25 rounded-t my-4"></div>

        <div class="flex flex-wrap">
          <div class="w-5/6 sm:w-1/2 p-6">
            <h3 class="text-3xl text-gray-800 font-bold mb-3">
              Lorem ipsum dolor sit amet
            </h3>
            <p class="text-gray-600 mb-8">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit.
              <br><br>
              Images from:
              <a class="text-pink-500 underline" href="https://undraw.co/">undraw.co</a>
            </p>
          </div>

          <div class="w-full sm:w-1/2 p-6">
            <svg class="w-full sm:h-64 mx-auto" viewBox="0 0 1177 598.5">
              <ellipse cx="588.5" cy="577.5" rx="588.5" ry="21" fill="#3f3d56" />
            </svg>
          </div>
        </div>

      </div>
    </section>

    <!-- PRICING -->
    <section class="bg-gray-100 py-8">
      <div class="container mx-auto px-2 pb-12">
        <h2 class="text-5xl font-bold text-center text-gray-800">Pricing</h2>
        <div class="h-1 mx-auto gradient w-64 opacity-25 my-4 rounded-t"></div>

        <div class="flex flex-col sm:flex-row justify-center pt-12">

          <!-- Free -->
          <div class="flex flex-col w-5/6 lg:w-1/4 bg-white rounded-lg shadow mx-2 p-6">
            <div class="text-3xl font-bold text-center border-b pb-4">Free</div>
            <ul class="text-center text-sm my-4">
              <li class="border-b py-4">Thing</li>
              <li class="border-b py-4">Thing</li>
              <li class="border-b py-4">Thing</li>
            </ul>
            <div class="text-3xl text-gray-600 font-bold text-center pt-6">
              £0 <span class="text-base">for one user</span>
            </div>
            <button class="gradient text-white rounded-full px-8 py-4 font-bold mx-auto mt-6">
              Sign Up
            </button>
          </div>

          <!-- Basic -->
          <div class="flex flex-col w-5/6 lg:w-1/3 bg-white rounded-lg shadow-xl mx-2 p-6 -mt-6 z-10">
            <div class="text-3xl font-bold text-center">Basic</div>
            <div class="gradient h-1 w-full rounded-t my-4"></div>
            <ul class="text-center font-bold">
              <li class="border-b py-4">Thing</li>
              <li class="border-b py-4">Thing</li>
              <li class="border-b py-4">Thing</li>
              <li class="border-b py-4">Thing</li>
            </ul>
            <div class="text-3xl text-gray-600 font-bold text-center pt-6">
              £x.99 <span class="text-base">/ month</span>
            </div>
            <button class="gradient text-white rounded-full px-8 py-4 font-bold mx-auto mt-6">
              Sign Up
            </button>
          </div>

          <!-- Pro -->
          <div class="flex flex-col w-5/6 lg:w-1/4 bg-white rounded-lg shadow mx-2 p-6">
            <div class="text-3xl font-bold text-center border-b pb-4">Pro</div>
            <ul class="text-center text-sm my-4">
              <li class="border-b py-4">Thing</li>
              <li class="border-b py-4">Thing</li>
              <li class="border-b py-4">Thing</li>
            </ul>
            <div class="text-3xl text-gray-600 font-bold text-center pt-6">
              £x.99 <span class="text-base">/ month</span>
            </div>
            <button class="gradient text-white rounded-full px-8 py-4 font-bold mx-auto mt-6">
              Sign Up
            </button>
          </div>

        </div>
      </div>
    </section>

  </PortalLayout>
</template>

<style scoped>
.gradient {
  background: linear-gradient(90deg, #d53369 0%, #daae51 100%);
}
</style>
