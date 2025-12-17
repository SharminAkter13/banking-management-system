<template>
  <admin-layout>
    <h1 class="mb-6 text-2xl font-bold">Teller Dashboard</h1>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
      <StatCard label="Today Transactions" :value="data.today_transactions" />
      <StatCard label="Cash Processed" :value="data.cash_processed" />
      <StatCard label="Pending Verifications" :value="data.pending_verifications" />
    </div>
  </admin-layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '@/services/api'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import StatCard from '@/components/dashboard/StatCard.vue'

const data = ref({})

onMounted(async () => {
  const res = await axios.get('/teller/dashboard')
  data.value = res.data
})
</script>
