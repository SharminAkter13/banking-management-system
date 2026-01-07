<template>
  <AdminLayout>
    <h2 class="text-xl font-semibold mb-4">
      {{ isEdit ? 'Edit' : 'Create' }} Transaction
    </h2>

    <form @submit.prevent="submit" class="bg-white p-6 rounded-xl shadow max-w-xl">

      <div class="mb-3">
        <label>Account ID</label>
        <input v-model="form.account_id" class="input" required />
      </div>

      <div class="mb-3">
        <label>Transaction Type ID</label>
        <input v-model="form.transaction_type_id" class="input" required />
      </div>

      <div class="mb-3">
        <label>Status ID</label>
        <input v-model="form.transaction_status_id" class="input" required />
      </div>

      <div class="mb-3">
        <label>Amount</label>
        <input v-model="form.amount" type="number" class="input" required />
      </div>

      <div class="mb-3">
        <label>Date</label>
        <input v-model="form.transaction_date" type="date" class="input" required />
      </div>

      <div class="mb-4">
        <label>Created By (User ID)</label>
        <input v-model="form.created_by" class="input" required />
      </div>

      <button class="btn-primary">Save</button>
    </form>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import { createTransaction, getTransaction, updateTransaction } from '@/TransactionService'

const route = useRoute()
const router = useRouter()

const isEdit = ref(false)

const form = ref({
  account_id: '',
  transaction_type_id: '',
  transaction_status_id: '',
  amount: '',
  transaction_date: '',
  created_by: ''
})

onMounted(async () => {
  if (route.params.id) {
    isEdit.value = true
    const res = await getTransaction(route.params.id)
    form.value = {
      account_id: res.data.account_id,
      transaction_type_id: res.data.transaction_type_id,
      transaction_status_id: res.data.transaction_status_id,
      amount: res.data.amount,
      transaction_date: res.data.transaction_date,
      created_by: res.data.created_by
    }
  }
})

const submit = async () => {
  if (isEdit.value) {
    await updateTransaction(route.params.id, form.value)
  } else {
    await createTransaction(form.value)
  }
  router.push('/admin/transactions')
}
</script>

<style scoped>
.input { width:100%; border:1px solid #ccc; padding:8px; border-radius:6px; }
.btn-primary { background:#2563eb;color:white;padding:10px 16px;border-radius:8px; }
</style>
