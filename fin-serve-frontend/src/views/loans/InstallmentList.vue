<template>
<AdminLayout>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Loan Installments</h1>

    <table class="w-full border text-sm">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2 border">Loan ID</th>
          <th class="p-2 border">Customer</th>
          <th class="p-2 border">Due Date</th>
          <th class="p-2 border">EMI</th>
          <th class="p-2 border">Paid</th>
          <th class="p-2 border">Status</th>
          <th class="p-2 border">Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="row in installments" :key="row.id">
          <td class="p-2 border">{{ row.loan_id }}</td>
          <td class="p-2 border">
            {{ row.loan?.customer?.name || '-' }}
          </td>
          <td class="p-2 border">{{ row.due_date }}</td>
          <td class="p-2 border">{{ row.emi_amount }}</td>
          <td class="p-2 border">{{ row.paid_amount }}</td>
          <td class="p-2 border">
            <span :class="statusClass(row.status)">
              {{ row.status }}
            </span>
          </td>
          <td class="p-2 border">
            <button
              v-if="row.status !== 'Paid'"
              @click="openPay(row)"
              class="px-3 py-1 bg-green-600 text-white rounded"
            >
              Pay
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- PAY MODAL -->
    <div v-if="showPay" class="fixed inset-0 bg-black/50 flex items-center justify-center">
      <div class="bg-white p-6 rounded w-[400px]">
        <h2 class="text-lg font-bold mb-4">Pay Installment</h2>

        <p><b>EMI:</b> {{ selected.emi_amount }}</p>
        <p><b>Already Paid:</b> {{ selected.paid_amount }}</p>

        <input
          v-model="payAmount"
          type="number"
          class="w-full border p-2 mt-3"
          placeholder="Enter amount"
        />

        <div class="flex justify-end gap-2 mt-4">
          <button @click="showPay=false" class="px-4 py-1 border">Cancel</button>
          <button @click="submitPay" class="px-4 py-1 bg-blue-600 text-white">
            Pay Now
          </button>
        </div>
      </div>
    </div>
  </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { getInstallments, payInstallment } from "./InstallmentService";
import AdminLayout from '@/components/layout/AdminLayout.vue'

const installments = ref([]);
const showPay = ref(false);
const selected = ref({});
const payAmount = ref("");

const loadData = async () => {
  const res = await getInstallments();
  installments.value = res.data;
};

onMounted(loadData);

const openPay = (row) => {
  selected.value = row;
  payAmount.value = row.amount_due - (row.amount_paid || 0);
  showPay.value = true;
};

const submitPay = async () => {
  await payInstallment({
    installment_id: selected.value.id,
    amount: payAmount.value
  });

  showPay.value = false;
  loadData();
};
</script>
