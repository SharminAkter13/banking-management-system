<template>
  <div class="rounded-xl bg-white p-6 shadow">
    <h3 class="mb-4 text-lg font-semibold">Approvals</h3>

    <div class="space-y-2">
      <div v-for="(v, k) in stats" :key="k" class="flex justify-between">
        <span>{{ k }}</span>
        <span class="font-bold">{{ v }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '@/services/axios'

const stats = ref({})

onMounted(async () => {
  const { data } = await axios.get('/approvals/dashboard')
  stats.value = data
})
</script>
