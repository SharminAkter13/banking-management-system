<template>
  <admin-layout>
    <h1 class="mb-6 text-2xl font-bold">Customer Dashboard</h1>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
      <StatCard label="Accounts" :value="data.accounts?.length" />
      <StatCard label="Loans" :value="data.loans?.length" />
      <StatCard label="KYC Status" :value="data.kyc_status" />
    </div>

    <div class="mt-8 rounded-xl bg-white p-6 shadow">
      <h2 class="mb-4 text-lg font-semibold">Recent Transactions</h2>
      <SimpleTable :rows="data.recent_transactions" />
    </div>
  </admin-layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '@/services/api'

import AdminLayout from '@/components/layout/AdminLayout.vue'
import StatCard from '@/components/dashboard/StatCard.vue'
import SimpleTable from '@/components/dashboard/SimpleTable.vue'

const data = ref({})

onMounted(async () => {
  const res = await axios.get('/customer/dashboard')
  data.value = res.data
})
</script>
