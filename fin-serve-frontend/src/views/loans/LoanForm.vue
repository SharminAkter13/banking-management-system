<template>
  <AdminLayout>
    <div class="flex items-center justify-between mb-7">
      <h2 class="text-xl font-semibold">
        {{ isEdit ? 'Edit Loan' : 'Create Loan' }}
      </h2>

      <router-link to="/admin/loans" class="px-4 py-2 border rounded-lg">
        Back
      </router-link>
    </div>

    <div class="bg-white p-6 rounded-xl border max-w-2xl">
      <form @submit.prevent="submitForm" class="space-y-5">

        <div class="grid sm:grid-cols-2 gap-4">
          <div>
            <label>Customer ID</label>
            <input v-model="form.customer_id" type="number" class="input" required />
          </div>

          <div>
            <label>Branch ID</label>
            <input v-model="form.branch_id" type="number" class="input" required />
          </div>

          <div>
            <label>Loan Type ID</label>
            <input v-model="form.loan_type_id" type="number" class="input" required />
          </div>

          <div>
            <label>Principal Amount</label>
            <input v-model.number="form.principal_amount" type="number" class="input" required />
          </div>

          <div>
            <label>Interest Rate (%)</label>
            <input v-model.number="form.interest_rate" type="number" step="0.01" class="input" required />
          </div>

          <div>
            <label>Duration (Months)</label>
            <input v-model.number="form.duration_months" type="number" class="input" required />
          </div>

          <div>
            <label>EMI Amount</label>
            <input :value="emi" disabled class="input bg-gray-100" />
          </div>

          <div>
            <label>Total Payable</label>
            <input :value="totalPayable" disabled class="input bg-gray-100" />
          </div>

          <div>
            <label>Status</label>
            <select v-model="form.status" class="input">
              <option>Pending</option>
              <option>Active</option>
              <option>Closed</option>
              <option>Rejected</option>
              <option>Defaulted</option>
            </select>
          </div>
        </div>

        <button class="btn-primary">
          {{ isEdit ? 'Update Loan' : 'Create Loan' }}
        </button>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getLoan, createLoan, updateLoan } from './LoanService'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const route = useRoute()
const router = useRouter()

const isEdit = ref(!!route.params.id)

const form = ref({
  customer_id: '',
  branch_id: '',
  loan_type_id: '',
  principal_amount: 0,
  interest_rate: 0,
  duration_months: 1,
  emi_amount: 0,
  total_payable: 0,
  remaining_balance: 0,
  status: 'Pending'
})

/* ===== Banking Calculations ===== */

const totalPayable = computed(() => {
  const interest = (form.value.principal_amount * form.value.interest_rate) / 100
  return Number(form.value.principal_amount + interest).toFixed(2)
})

const emi = computed(() => {
  if (!form.value.duration_months) return 0
  return (totalPayable.value / form.value.duration_months).toFixed(2)
})

watch([totalPayable, emi], () => {
  form.value.total_payable = totalPayable.value
  form.value.emi_amount = emi.value
  if (!isEdit.value) form.value.remaining_balance = totalPayable.value
})

/* ===== Load ===== */

const fetchLoan = async () => {
  const res = await getLoan(route.params.id)
  form.value = res.data
}

/* ===== Submit ===== */

const submitForm = async () => {
  if (isEdit.value) {
    await updateLoan(route.params.id, form.value)
  } else {
    await createLoan(form.value)
  }
  router.push('/admin/loans')
}

onMounted(() => {
  if (isEdit.value) fetchLoan()
})
</script>

<style scoped>
.input { @apply w-full border rounded-lg px-3 py-2 }
.btn-primary { @apply bg-blue-600 text-white px-6 py-2 rounded-lg }
</style>
