<template>
  <admin-layout>
    <h1 class="mb-6 text-2xl font-bold">Loan Officer Dashboard</h1>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
      <StatCard label="Assigned Loans" :value="data.assigned_loans" />
      <StatCard label="Pending Loans" :value="data.pending_loans" />
      <StatCard label="Approved Today" :value="data.approved_today" />
    </div>
  </admin-layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '@/services/axios'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import StatCard from '@/components/dashboard/StatCard.vue'

const data = ref({})

onMounted(async () => {
  const res = await axios.get('/officer/dashboard')
  data.value = res.data
})
</script>
