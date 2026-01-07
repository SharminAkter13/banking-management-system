<template>
  <AdminLayout>
    <div class="p-6 space-y-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Interest Rates</h2>
        <router-link
          to="/admin/interest-rates/create"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        >
          Add New Rate
        </router-link>
      </div>

      <div class="overflow-x-auto bg-white rounded-xl shadow">
        <table class="min-w-full border">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 border">#</th>
              <th class="px-4 py-2 border">Account Type</th>
              <th class="px-4 py-2 border">Rate (%)</th>
              <th class="px-4 py-2 border">Effective Date</th>
              <th class="px-4 py-2 border">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(rate, index) in interestRates" :key="rate.id">
              <td class="px-4 py-2 border">{{ index + 1 }}</td>
              <td class="px-4 py-2 border">{{ rate.account_type.name }}</td>
              <td class="px-4 py-2 border">{{ rate.rate }}</td>
              <td class="px-4 py-2 border">{{ rate.effective_date }}</td>
              <td class="px-4 py-2 border flex gap-2">
                <router-link
                  :to="`/admin/interest-rates/${rate.id}/edit`"
                  class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600"
                >
                  Edit
                </router-link>
                <button
                  @click="deleteRate(rate.id)"
                  class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700"
                >
                  Delete
                </button>
              </td>
            </tr>
            <tr v-if="interestRates.length === 0">
              <td colspan="5" class="text-center py-4 text-gray-500">No rates found.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import { getInterestRates, deleteInterestRate } from './InterestRateService'
import { useRouter } from 'vue-router'

const interestRates = ref([])
const router = useRouter()

const fetchRates = async () => {
  try {
    const res = await getInterestRates()
    interestRates.value = res.data
  } catch (err) {
    console.error(err)
  }
}

const deleteRate = async (id) => {
  if (!confirm('Are you sure you want to delete this rate?')) return
  try {
    await deleteInterestRate(id)
    interestRates.value = interestRates.value.filter(r => r.id !== id)
  } catch (err) {
    console.error(err)
  }
}

onMounted(fetchRates)
</script>
