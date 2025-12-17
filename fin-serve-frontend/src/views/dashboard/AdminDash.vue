<template>
  <admin-layout>
    <h1 class="mb-6 text-2xl font-bold">Admin Dashboard</h1>

    <!-- STAT CARDS -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
      <StatCard label="Total Users" :value="data.total_users" />
      <StatCard label="Total Customers" :value="data.total_customers" />
      <StatCard label="Active Loans" :value="data.active_loans" />
      <StatCard label="Bank Balance" :value="data.bank_balance" />
    </div>

    <!-- RECENT TRANSACTIONS -->
    <div class="mt-8 rounded-xl bg-white p-6 shadow">
      <h2 class="mb-4 text-lg font-semibold">Recent Transactions</h2>
      <SimpleTable :rows="data.recent_transactions" />
    </div>
  </admin-layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '@/services/axios'

import AdminLayout from '@/components/layout/AdminLayout.vue'
import StatCard from '@/components/dashboard/StatCard.vue'
import SimpleTable from '@/components/dashboard/SimpleTable.vue'

const data = ref({})

onMounted(async () => {
  const res = await axios.get('/admin/dashboard')
  data.value = res.data
})
</script>
