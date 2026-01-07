<template>
  <AdminLayout>

  <div class="p-6">
    <h1 class="text-2xl font-semibold mb-4">Loan Types</h1>

    <!-- ===== Form ===== -->
    <div class="bg-white p-4 rounded-xl shadow mb-6">
      <h2 class="text-lg font-medium mb-3">
        {{ isEdit ? 'Edit' : 'Add' }} Loan Type
      </h2>

      <form
        @submit.prevent="isEdit ? updateLoanType() : createLoanType()"
        class="grid grid-cols-1 md:grid-cols-2 gap-4"
      >
        <!-- Name -->
        <div>
          <label class="block text-sm mb-1">Name</label>
          <input
            v-model="form.name"
            class="w-full border rounded-lg px-3 py-2 outline-none focus:ring"
            required
          />
        </div>

        <!-- Interest -->
        <div>
          <label class="block text-sm mb-1">Interest Rate (%)</label>
          <input
            v-model="form.interest_rate"
            type="number"
            step="0.01"
            class="w-full border rounded-lg px-3 py-2 outline-none focus:ring"
            required
          />
        </div>

        <!-- Max Amount -->
        <div>
          <label class="block text-sm mb-1">Max Amount</label>
          <input
            v-model="form.max_amount"
            type="number"
            class="w-full border rounded-lg px-3 py-2 outline-none focus:ring"
          />
        </div>

        <!-- Description -->
        <div class="md:col-span-2">
          <label class="block text-sm mb-1">Description</label>
          <textarea
            v-model="form.description"
            class="w-full border rounded-lg px-3 py-2 outline-none focus:ring"
          ></textarea>
        </div>

        <!-- Buttons -->
        <div class="md:col-span-2 flex gap-2">
          <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
            type="submit"
          >
            {{ isEdit ? 'Update' : 'Save' }}
          </button>

          <button
            v-if="isEdit"
            class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg"
            type="button"
            @click="resetForm"
          >
            Cancel
          </button>
        </div>
      </form>
    </div>

    <!-- ===== Table ===== -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="w-full border">
        <thead class="bg-gray-100">
          <tr>
            <th class="border px-3 py-2 text-left">#</th>
            <th class="border px-3 py-2 text-left">Name</th>
            <th class="border px-3 py-2 text-left">Interest</th>
            <th class="border px-3 py-2 text-left">Max Amount</th>
            <th class="border px-3 py-2 text-left">Actions</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="(item, index) in loanTypes"
            :key="item.id"
            class="hover:bg-gray-50"
          >
            <td class="border px-3 py-2">{{ index + 1 }}</td>
            <td class="border px-3 py-2">{{ item.name }}</td>
            <td class="border px-3 py-2">{{ item.interest_rate }}%</td>
            <td class="border px-3 py-2">
              {{ item.max_amount ?? '-' }}
            </td>
            <td class="border px-3 py-2 flex gap-2">
              <button
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded"
                @click="editLoanType(item)"
              >
                Edit
              </button>
              <button
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded"
                @click="deleteLoanType(item.id)"
              >
                Delete
              </button>
            </td>
          </tr>

          <tr v-if="loanTypes.length === 0">
            <td colspan="5" class="text-center py-4 text-gray-500">
              No loan types found
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
    </AdminLayout>

</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api' // axios with token
import AdminLayout from '@/components/layout/AdminLayout.vue'

const loanTypes = ref([])
const isEdit = ref(false)
const editId = ref(null)

const form = ref({
  name: '',
  description: '',
  interest_rate: '',
  max_amount: ''
})

// ===== API Calls =====
const fetchLoanTypes = async () => {
  const res = await api.get('/loan-types')
  loanTypes.value = res.data
}

const createLoanType = async () => {
  await api.post('/loan-types', form.value)
  await fetchLoanTypes()
  resetForm()
}

const editLoanType = (item) => {
  isEdit.value = true
  editId.value = item.id
  form.value = {
    name: item.name,
    description: item.description,
    interest_rate: item.interest_rate,
    max_amount: item.max_amount
  }
}

const updateLoanType = async () => {
  await api.put(`/loan-types/${editId.value}`, form.value)
  await fetchLoanTypes()
  resetForm()
}

const deleteLoanType = async (id) => {
  if (!confirm('Delete this loan type?')) return
  await api.delete(`/loan-types/${id}`)
  await fetchLoanTypes()
}

// ===== Helpers =====
const resetForm = () => {
  isEdit.value = false
  editId.value = null
  form.value = {
    name: '',
    description: '',
    interest_rate: '',
    max_amount: ''
  }
}

onMounted(fetchLoanTypes)
</script>
