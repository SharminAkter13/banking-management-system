<template>
  <AdminLayout>
    <div class="flex items-center justify-between mb-7">
      <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        Customers Management
      </h2>
      <router-link
        to="/admin/customers/create"
        class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700"
      >
        Create Customer
      </router-link>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
      <div class="max-w-full overflow-x-auto">
        <table class="min-w-full">
          <thead>
            <tr class="border-b border-gray-200 dark:border-gray-700">
              <th class="px-5 py-3 text-left">Name</th>
              <th class="px-5 py-3 text-left">Email</th>
              <th class="px-5 py-3 text-left">Phone</th>
              <th class="px-5 py-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="customer in customers" :key="customer.id">
              <td class="px-5 py-4">{{ customer.first_name }} {{ customer.last_name }}</td>
              <td class="px-5 py-4">{{ customer.email }}</td>
              <td class="px-5 py-4">{{ customer.phone }}</td>
              <td class="px-5 py-4 text-right">
                <div class="flex justify-end gap-3">
                  <router-link
                    :to="`/admin/customers/${customer.id}/edit`"
                    class="text-blue-600 hover:underline"
                  >
                    Edit
                  </router-link>
                  <button @click="deleteCustomer(customer.id)" class="text-red-600 hover:underline">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="customers.length === 0">
              <td colspan="4" class="text-center py-4 text-gray-500">No customers found.</td>
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
import { getCustomers, deleteCustomer as deleteCustomerAPI } from './CustomerService'
import { useRouter } from 'vue-router'

const customers = ref([])
const router = useRouter()

const fetchCustomers = async () => {
  try {
    const res = await getCustomers()
    customers.value = res.data.data || res.data
  } catch (err) {
    console.error('Error fetching customers:', err)
  }
}

const deleteCustomer = async (id) => {
  if (!confirm('Are you sure you want to delete this customer?')) return
  try {
    await deleteCustomerAPI(id)
    customers.value = customers.value.filter(c => c.id !== id)
  } catch (err) {
    console.error('Error deleting customer:', err)
  }
}

onMounted(fetchCustomers)
</script>
