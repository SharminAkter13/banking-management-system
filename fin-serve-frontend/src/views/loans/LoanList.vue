<template>
  <AdminLayout>
    <div class="flex items-center justify-between mb-7">
      <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        Loan Management
      </h2>
      <router-link
        to="/admin/loans/create"
        class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700"
      >
        Create Loan
      </router-link>
    </div>

    <div
      class="overflow-hidden rounded-xl border border-gray-200 bg-white
             dark:border-gray-800 dark:bg-white/[0.03]"
    >
      <div class="max-w-full overflow-x-auto">
        <table class="min-w-full">
          <thead>
            <tr class="border-b border-gray-200 dark:border-gray-700 text-sm">
              <th class="px-5 py-3 text-left">Customer</th>
              <th class="px-5 py-3 text-left">Loan Type</th>
              <th class="px-5 py-3 text-right">Principal</th>
              <th class="px-5 py-3 text-right">EMI</th>
              <th class="px-5 py-3 text-right">Remaining</th>
              <th class="px-5 py-3 text-left">Status</th>
              <th class="px-5 py-3 text-right">Actions</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <tr v-for="loan in loans" :key="loan.id">
              <td class="px-5 py-4">
                {{ loan.customer.first_name }} {{ loan.customer.last_name }}
              </td>

              <td class="px-5 py-4">{{ loan.loan_type?.name || loan.loanType?.name }}</td>

              <td class="px-5 py-4 text-right">
                ৳{{ Number(loan.principal_amount).toLocaleString() }}
              </td>

              <td class="px-5 py-4 text-right">
                ৳{{ Number(loan.emi_amount).toLocaleString() }}
              </td>

              <td class="px-5 py-4 text-right font-semibold"
                  :class="loan.remaining_balance == 0 ? 'text-green-600' : 'text-red-600'">
                ৳{{ Number(loan.remaining_balance).toLocaleString() }}
              </td>

              <td class="px-5 py-4">
                <span
                  class="px-2 py-1 rounded text-xs"
                  :class="statusClass(loan.status)"
                >
                  {{ loan.status }}
                </span>
              </td>

              <td class="px-5 py-4 text-right">
                <div class="flex justify-end gap-3">
                  <router-link
                    :to="`/admin/loans/${loan.id}`"
                    class="text-indigo-600 hover:underline"
                  >
                    View
                  </router-link>

                  <router-link
                    :to="`/admin/loans/${loan.id}/edit`"
                    class="text-blue-600 hover:underline"
                  >
                    Edit
                  </router-link>

                  <button
                    @click="deleteLoan(loan.id)"
                    class="text-red-600 hover:underline"
                  >
                    Delete
                  </button>
                </div>
              </td>
            </tr>

            <tr v-if="loans.length === 0">
              <td colspan="7" class="text-center py-6 text-gray-500">
                No loans found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/components/layout/AdminLayout.vue'
import { ref, onMounted } from 'vue'
import { getLoans, deleteLoan as deleteLoanAPI } from './LoanService'

const loans = ref([])

const fetchLoans = async () => {
  try {
    const res = await getLoans()
    loans.value = res.data.data || res.data
  } catch (err) {
    console.error('Error fetching loans:', err)
  }
}

const deleteLoan = async (id) => {
  if (!confirm('Are you sure you want to delete this loan?')) return
  try {
    await deleteLoanAPI(id)
    loans.value = loans.value.filter(l => l.id !== id)
  } catch (err) {
    console.error('Error deleting loan:', err)
  }
}

const statusClass = (status) => {
  return {
    'bg-yellow-100 text-yellow-800': status === 'Pending',
    'bg-blue-100 text-blue-800': status === 'Active',
    'bg-green-100 text-green-800': status === 'Closed',
    'bg-red-100 text-red-800': status === 'Defaulted',
    'bg-gray-200 text-gray-700': status === 'Rejected'
  }
}

onMounted(fetchLoans)
</script>
