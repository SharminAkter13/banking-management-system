<template>
  <admin-layout>
    <h1 class="mb-6 text-2xl font-bold">Branch Manager Dashboard</h1>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
      <StatCard label="Employees" :value="data.employees" />
      <StatCard label="Customers" :value="data.customers" />
      <StatCard label="Today Transactions" :value="data.today_transactions" />
      <StatCard label="Active Loans" :value="data.active_loans" />
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
  const res = await axios.get('/manager/dashboard')
  data.value = res.data
})
</script>
