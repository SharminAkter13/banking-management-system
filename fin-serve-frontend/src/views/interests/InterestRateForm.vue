<template>
  <AdminLayout>
    <div class="p-6 max-w-2xl">
      <h2 class="text-xl font-semibold mb-4">{{ isEdit ? 'Edit' : 'Add' }} Interest Rate</h2>

      <form @submit.prevent="submitForm" class="space-y-4 bg-white p-6 rounded-xl shadow">
        <div class="flex flex-col gap-2">
          <label>Account Type</label>
          <select v-model="form.account_type_id" class="border p-2 rounded" required>
            <option value="">Select Account Type</option>
            <option v-for="type in accountTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
          </select>
        </div>

        <div class="flex flex-col gap-2">
          <label>Rate (%)</label>
          <input v-model="form.rate" type="number" step="0.01" class="border p-2 rounded" required />
        </div>

        <div class="flex flex-col gap-2">
          <label>Effective Date</label>
          <input v-model="form.effective_date" type="date" class="border p-2 rounded" required />
        </div>

        <div class="flex justify-end pt-4">
          <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            {{ isEdit ? 'Update' : 'Save' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import { useRoute, useRouter } from 'vue-router'
import { getInterestRates, getInterestRate, createInterestRate, updateInterestRate } from './InterestRateService'
import api from '@/services/api'

const route = useRoute()
const router = useRouter()
const isEdit = ref(!!route.params.id)

const form = ref({
  account_type_id: '',
  rate: '',
  effective_date: ''
})

const accountTypes = ref([])

const fetchAccountTypes = async () => {
  const res = await api.get('/account-types')
  accountTypes.value = res.data
}

const fetchRate = async (id) => {
  const res = await getInterestRate(id)
  form.value = { ...res.data }
}

const submitForm = async () => {
  try {
    if (isEdit.value) {
      await updateInterestRate(route.params.id, form.value)
    } else {
      await createInterestRate(form.value)
    }
    router.push('/admin/interest-rates')
  } catch (err) {
    console.error(err)
  }
}

onMounted(() => {
  fetchAccountTypes()
  if (isEdit.value) fetchRate(route.params.id)
})
</script>
