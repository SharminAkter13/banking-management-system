<template>
<AdminLayout>
  <div class="p-6 space-y-6">
    <h1 class="text-2xl font-semibold">Loan Payment (with Invoice)</h1>

    <!-- ================= Select Loan ================= -->
    <div class="bg-white p-4 rounded-xl shadow">
      <h2 class="font-medium mb-3">Select Loan</h2>
      <select v-model="selectedLoanId" @change="onLoanChange" class="w-full border rounded-lg px-3 py-2">
        <option value="">Select Loan</option>
        <option v-for="l in loans" :key="l.id" :value="l.id">
          Loan #{{ l.id }} — {{ l.customer?.name || 'Customer' }} — Amount: {{ l.amount }}
        </option>
      </select>
    </div>

    <!-- ================= Loan Summary ================= -->
    <div v-if="loan" class="bg-white p-4 rounded-xl shadow grid md:grid-cols-3 gap-4">
      <div>
        <p class="text-sm text-gray-500">Loan Amount</p>
        <p class="font-semibold">{{ loan.amount }}</p>
      </div>
      <div>
        <p class="text-sm text-gray-500">Interest Rate</p>
        <p class="font-semibold">{{ loan.interest_rate }}%</p>
      </div>
      <div>
        <p class="text-sm text-gray-500">Total Payable</p>
        <p class="font-semibold">{{ totalPayable }}</p>
      </div>
      <div>
        <p class="text-sm text-gray-500">Total Paid</p>
        <p class="font-semibold text-green-600">{{ totalPaid }}</p>
      </div>
      <div>
        <p class="text-sm text-gray-500">Remaining Balance</p>
        <p class="font-semibold text-red-600">{{ remainingBalance }}</p>
      </div>
    </div>

    <!-- ================= Payment Form ================= -->
    <div v-if="loan" class="bg-white p-4 rounded-xl shadow">
      <h2 class="font-medium mb-3">Make Payment</h2>

      <form @submit.prevent="submitPayment" class="grid md:grid-cols-4 gap-4">
        <div>
          <label class="text-sm">Amount</label>
          <input
            v-model.number="form.amount"
            type="number"
            :max="remainingBalance"
            class="w-full border rounded-lg px-3 py-2"
            required
          />
        </div>

        <div>
          <label class="text-sm">Payment Date</label>
          <input
            v-model="form.payment_date"
            type="date"
            class="w-full border rounded-lg px-3 py-2"
            required
          />
        </div>

        <div>
          <label class="text-sm">Transaction Ref</label>
          <input
            v-model="form.reference_no"
            class="w-full border rounded-lg px-3 py-2"
            placeholder="Auto or manual"
          />
        </div>

        <div class="flex items-end">
          <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full"
            type="submit"
          >
            Pay Now
          </button>
        </div>
      </form>
    </div>

    <!-- ================= Invoice ================= -->
    <div v-if="invoice" class="bg-white p-6 rounded-xl shadow border">
      <h2 class="text-lg font-semibold mb-4">Payment Receipt</h2>

      <div class="grid md:grid-cols-2 gap-4 text-sm">
        <p><b>Loan ID:</b> {{ loan.id }}</p>
        <p><b>Date:</b> {{ invoice.payment_date }}</p>
        <p><b>Paid Amount:</b> {{ invoice.amount }}</p>
        <p><b>Total Paid:</b> {{ invoice.totalPaid }}</p>
        <p><b>Remaining:</b> {{ invoice.remaining }}</p>
        <p><b>Transaction Ref:</b> {{ invoice.reference_no }}</p>
      </div>

      <div class="mt-4 p-4 bg-gray-50 rounded">
        <p><b>Loan Amount:</b> {{ loan.amount }}</p>
        <p><b>Total Payable (with interest):</b> {{ totalPayable }}</p>
      </div>

      <div class="mt-4">
        <button class="bg-gray-800 text-white px-4 py-2 rounded" @click="printInvoice">
          Print Receipt
        </button>
      </div>
    </div>

    <!-- ================= Payment History ================= -->
    <div v-if="payments.length" class="bg-white p-4 rounded-xl shadow">
      <h2 class="font-medium mb-3">Payment History</h2>

      <table class="w-full border">
        <thead class="bg-gray-100">
          <tr>
            <th class="border px-3 py-2">#</th>
            <th class="border px-3 py-2">Date</th>
            <th class="border px-3 py-2">Amount</th>
            <th class="border px-3 py-2">Balance After</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(p, i) in payments" :key="p.id">
            <td class="border px-3 py-2">{{ i + 1 }}</td>
            <td class="border px-3 py-2">{{ p.payment_date }}</td>
            <td class="border px-3 py-2">{{ p.amount }}</td>
            <td class="border px-3 py-2">{{ p.balance_after }}</td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/services/api'
import AdminLayout from '@/components/layout/AdminLayout.vue'


const loans = ref([])
const payments = ref([])
const loan = ref(null)
const selectedLoanId = ref('')

const form = ref({
  amount: '',
  payment_date: new Date().toISOString().substring(0, 10),
  reference_no: ''
})

const invoice = ref(null)

// ================= Calculations =================
const totalPayable = computed(() => {
  if (!loan.value) return 0
  return Number(loan.value.amount) + (Number(loan.value.amount) * Number(loan.value.interest_rate)) / 100
})

const totalPaid = computed(() => payments.value.reduce((s, p) => s + Number(p.amount), 0))

const remainingBalance = computed(() => Math.max(totalPayable.value - totalPaid.value, 0))

// ================= Loaders =================
const fetchLoans = async () => {
  const res = await api.get('/loans')
  loans.value = res.data
}

const fetchPayments = async (loanId) => {
  const res = await api.get('/loan-payments')
  payments.value = res.data
    .filter(p => p.loan_id === loanId)
    .map(p => ({
      ...p,
      balance_after: 0
    }))

  // calculate running balance
  let balance = totalPayable.value
  payments.value = payments.value.map(p => {
    balance -= Number(p.amount)
    return { ...p, balance_after: balance }
  })
}

const onLoanChange = async () => {
  loan.value = loans.value.find(l => l.id === selectedLoanId.value)
  await fetchPayments(selectedLoanId.value)
}

// ================= Payment =================
const submitPayment = async () => {
  if (form.value.amount > remainingBalance.value) {
    alert('Amount exceeds remaining balance')
    return
  }

  // 1. create transaction
  const trx = await api.post('/transactions', {
    amount: form.value.amount,
    type: 'loan_payment',
    reference_no: form.value.reference_no
  })

  // 2. create loan payment
  await api.post('/loan-payments', {
    loan_id: loan.value.id,
    transaction_id: trx.data.id,
    amount: form.value.amount,
    payment_date: form.value.payment_date
  })

  await fetchPayments(loan.value.id)

  invoice.value = {
    amount: form.value.amount,
    payment_date: form.value.payment_date,
    totalPaid: totalPaid.value,
    remaining: remainingBalance.value,
    reference_no: form.value.reference_no || ('TX-' + trx.data.id)
  }

  form.value.amount = ''
}

const printInvoice = () => window.print()

onMounted(fetchLoans)
</script>
