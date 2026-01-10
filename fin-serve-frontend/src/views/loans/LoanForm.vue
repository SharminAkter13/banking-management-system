<template>
  <AdminLayout>
    <div class="flex items-center justify-between mb-7">
      <h2 class="text-xl font-semibold">
        {{ isEdit ? 'Edit Loan' : 'Create Loan' }}
      </h2>

      <router-link
        to="/admin/loans"
        class="px-4 py-2 border rounded-lg hover:bg-gray-100"
      >
        Back
      </router-link>
    </div>

    <div class="bg-white p-6 rounded-xl border max-w-2xl">
      <form @submit.prevent="submitForm" class="space-y-5">

        <div class="grid sm:grid-cols-2 gap-4">

          <!-- Customer Name (auto from FK) -->
          <div>
            <label class="block mb-1 font-medium">Customer Name</label>
            <input
              :value="customerDisplayName"
              type="text"
              class="w-full border rounded-lg px-3 py-2 bg-gray-100 cursor-not-allowed"
              disabled
            />
            <input type="hidden" v-model="form.customer_id" />
          </div>

          <!-- Branch -->
          <div>
            <label class="block mb-1 font-medium">Branch</label>
            <select v-model="form.branch_id" class="w-full border rounded-lg px-3 py-2" required>
              <option value="">Select Branch</option>
              <option v-for="b in branches" :key="b.id" :value="b.id">
                {{ b.branch_name }}
              </option>
            </select>
          </div>

          <!-- Loan Type -->
          <div>
            <label class="block mb-1 font-medium">Loan Type</label>
            <select v-model="form.loan_type_id" class="w-full border rounded-lg px-3 py-2" required>
              <option value="">Select Loan Type</option>
              <option v-for="lt in loanTypes" :key="lt.id" :value="lt.id">
                {{ lt.name }}
              </option>
            </select>
          </div>

          <!-- Principal -->
          <div>
            <label class="block mb-1 font-medium">Principal Amount</label>
            <input v-model.number="form.principal_amount" type="number" class="w-full border rounded-lg px-3 py-2" required />
          </div>

          <!-- Interest -->
          <div>
            <label class="block mb-1 font-medium">Interest Rate (%)</label>
            <input v-model.number="form.interest_rate" type="number" step="0.01" class="w-full border rounded-lg px-3 py-2" required />
          </div>

          <!-- Duration -->
          <div>
            <label class="block mb-1 font-medium">Duration (Months)</label>
            <input v-model.number="form.duration_months" type="number" class="w-full border rounded-lg px-3 py-2" required />
          </div>

          <!-- EMI -->
          <div>
            <label class="block mb-1 font-medium">EMI Amount</label>
            <input :value="emi" disabled class="w-full border rounded-lg px-3 py-2 bg-gray-100" />
          </div>

          <!-- Total -->
          <div>
            <label class="block mb-1 font-medium">Total Payable</label>
            <input :value="totalPayable" disabled class="w-full border rounded-lg px-3 py-2 bg-gray-100" />
          </div>

          <!-- Status -->
          <div>
            <label class="block mb-1 font-medium">Status</label>
            <select v-model="form.status" class="w-full border rounded-lg px-3 py-2">
              <option>Pending</option>
              <option>Active</option>
              <option>Closed</option>
              <option>Rejected</option>
              <option>Defaulted</option>
            </select>
          </div>

        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
          {{ isEdit ? 'Update Loan' : 'Create Loan' }}
        </button>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import {
  getLoan,
  createLoan,
  updateLoan,
  getBranches,
  getLoanTypes,
  getCustomers
} from './LoanService'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()

const isEdit = ref(!!route.params.id)

const branches = ref([])
const loanTypes = ref([])
const customers = ref([])

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

/* ================= CUSTOMER NAME BY FK ================= */
const customerDisplayName = computed(() => {
  if (!form.value.customer_id) return 'Loading...'
  if (!Array.isArray(customers.value)) return 'Loading...'

  const found = customers.value.find(
    c => Number(c.id) === Number(form.value.customer_id)
  )

  return found
    ? `${found.first_name} ${found.last_name}`
    : 'Not Assigned'
})

/* ================= CALCULATIONS ================= */
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

/* ================= FETCH DROPDOWNS ================= */
const fetchDropdowns = async () => {
  try {
    const [branchRes, loanTypeRes, customerRes] = await Promise.all([
      getBranches(),
      getLoanTypes(),
      getCustomers()
    ])

    branches.value = branchRes.data
    loanTypes.value = loanTypeRes.data

    // ✅ support laravel pagination
    customers.value = Array.isArray(customerRes.data)
      ? customerRes.data
      : customerRes.data.data

  } catch (err) {
    console.error('Dropdown load failed', err)
  }
}

/* ================= FETCH LOAN (EDIT) ================= */
const fetchLoan = async () => {
  const res = await getLoan(route.params.id)
  form.value = res.data
}

/* ================= SUBMIT ================= */
const submitForm = async () => {
  try {
    if (isEdit.value) {
      await updateLoan(route.params.id, form.value)
    } else {
      await createLoan(form.value)
    }
    router.push('/admin/loans')
  } catch (err) {
    console.error('Submit failed', err)
  }
}

/* ================= MOUNT ================= */
onMounted(async () => {
  await fetchDropdowns()

  // Auto-assign customer by logged-in user
  if (!isEdit.value) {
    if (!auth.user && auth.token) {
      await auth.fetchUser()
    }

    if (auth.user && customers.value.length) {
      const customer = customers.value.find(c => c.user_id === auth.user.id)
      if (customer) {
        form.value.customer_id = customer.id
      }
    }
  }

  if (isEdit.value) {
    await fetchLoan()
  }
})
</script>
