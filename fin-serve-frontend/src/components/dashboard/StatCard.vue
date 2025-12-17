<template>
  <div class="rounded-xl bg-white p-6 shadow">
    <p class="text-sm text-gray-500">{{ label }}</p>
    <p class="mt-2 text-3xl font-bold text-gray-900">
      {{ value }}
    </p>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  value: [String, Number]
})
</script>
