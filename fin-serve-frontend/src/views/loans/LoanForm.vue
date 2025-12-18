<template>
  <AdminLayout>
    <div class="flex items-center justify-between mb-7">
      <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        {{ isEdit ? 'Edit Loan' : 'Create Loan' }}
      </h2>
      <router-link to="/admin/loans" class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-50 dark:border-gray-800">
        Back to List
      </router-link>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] max-w-2xl">
      <form @submit.prevent="submitForm" class="space-y-5">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Customer</label>
            <input v-model="form.customer_id" type="number" class="p-2.5 border rounded-lg dark:bg-gray-900 dark:border-gray-800" required />
          </div>
          <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Branch</label>
            <input v-model="form.branch_id" type="number" class="p-2.5 border rounded-lg dark:bg-gray-900 dark:border-gray-800" required />
          </div>
          <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Loan Type</label>
            <input v-model="form.loan_type_id" type="number" class="p-2.5 border rounded-lg dark:bg-gray-900 dark:border-gray-800" required />
          </div>
          <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Principal Amount</label>
            <input v-model="form.principal_amount" type="number" class="p-2.5 border rounded-lg dark:bg-gray-900 dark:border-gray-800" required />
          </div>
          <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Status</label>
            <input v-model="form.status" type="text" class="p-2.5 border rounded-lg dark:bg-gray-900 dark:border-gray-800" required />
          </div>
        </div>
        <div class="flex justify-end pt-4">
          <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            {{ isEdit ? 'Update Loan' : 'Save Loan' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/components/layout/AdminLayout.vue'
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getLoan, createLoan, updateLoan } from './LoanService'

const route = useRoute()
const router = useRouter()
const isEdit = ref(!!route.params.id)
const form = ref({ customer_id: '', branch_id: '', loan_type_id: '', principal_amount: '', status: '' })

const fetchLoanData = async (id) => {
  try {
    const res = await getLoan(id)
    form.value = { ...res.data }
  } catch (err) {
    console.error('Error fetching loan:', err)
  }
}

const submitForm = async () => {
  try {
    if (isEdit.value) {
      await updateLoan(route.params.id, form.value)
    } else {
      await createLoan(form.value)
    }
    router.push('/admin/loans')
  } catch (err) {
    console.error('Error submitting form:', err)
  }
}

onMounted(() => {
  if (isEdit.value) fetchLoanData(route.params.id)
})
</script>
