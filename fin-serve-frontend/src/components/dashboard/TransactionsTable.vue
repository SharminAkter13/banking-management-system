<template>
  <div class="rounded-xl bg-white p-6 shadow">
    <h3 class="mb-4 text-lg font-semibold">Live Transactions</h3>

    <table class="w-full text-sm">
      <thead>
        <tr class="border-b">
          <th class="text-left py-2">Amount</th>
          <th class="text-left py-2">Date</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="t in transactions" :key="t.id">
          <td>{{ t.amount }}</td>
          <td>{{ t.transaction_date }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Echo from '@/services/echo'
import axios from '@/services/axios'

const transactions = ref([])

onMounted(async () => {
  const { data } = await axios.get('/transactions/latest')
  transactions.value = data

  Echo.channel('transactions')
    .listen('TransactionCreated', e => {
      transactions.value.unshift(e.transaction)
    })
})
</script>
