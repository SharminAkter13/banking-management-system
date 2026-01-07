<template>
  <AdminLayout>
    <div class="flex justify-between mb-6">
      <h2 class="text-xl font-semibold">Transactions</h2>
      <router-link to="/admin/transactions/create" class="btn-primary">
        New Transaction
      </router-link>
    </div>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-100">
          <tr>
            <th class="th">#</th>
            <th class="th">Account</th>
            <th class="th">Type</th>
            <th class="th">Amount</th>
            <th class="th">Status</th>
            <th class="th">Date</th>
            <th class="th text-right">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(t, i) in transactions" :key="t.id">
            <td class="td">{{ i + 1 }}</td>
            <td class="td">{{ t.account?.account_number }}</td>
            <td class="td">{{ t.type?.name }}</td>
            <td class="td">{{ t.amount }}</td>
            <td class="td">{{ t.status?.name }}</td>
            <td class="td">{{ t.transaction_date }}</td>
            <td class="td text-right">
              <router-link
                :to="`/admin/transactions/${t.id}/edit`"
                class="text-blue-600 mr-3"
              >
                Edit
              </router-link>
              <button @click="remove(t.id)" class="text-red-600">Delete</button>
            </td>
          </tr>

          <tr v-if="transactions.length === 0">
            <td colspan="7" class="text-center py-4">No data</td>
          </tr>
        </tbody>
      </table>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import { getTransactions, deleteTransaction } from './TransactionService'

const transactions = ref([])

const loadData = async () => {
  const res = await getTransactions()
  transactions.value = res.data
}

const remove = async (id) => {
  if (!confirm('Delete transaction?')) return
  await deleteTransaction(id)
  transactions.value = transactions.value.filter(t => t.id !== id)
}

onMounted(loadData)
</script>

<style scoped>
.th { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
.td { padding: 10px; border-bottom: 1px solid #eee; }
.btn-primary { background:#2563eb;color:white;padding:8px 14px;border-radius:8px; }
</style>
