<template>
  <AdminLayout>
    <div class="flex items-center justify-between mb-7">
      <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        {{ isEdit ? 'Edit Customer' : 'Create Customer' }}
      </h2>
      <router-link
        to="/admin/customers"
        class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-50 dark:border-gray-800"
      >
        Back to List
      </router-link>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] max-w-2xl">
      <form @submit.prevent="submitForm" class="space-y-5">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div class="flex flex-col gap-2">
            <label>First Name</label>
            <input v-model="form.first_name" type="text" class="input" required />
          </div>
          <div class="flex flex-col gap-2">
            <label>Last Name</label>
            <input v-model="form.last_name" type="text" class="input" required />
          </div>
          <div class="flex flex-col gap-2">
            <label>Email</label>
            <input v-model="form.email" type="email" class="input" required />
          </div>
          <div class="flex flex-col gap-2">
            <label>Phone</label>
            <input v-model="form.phone" type="text" class="input" required />
          </div>
          <div class="flex flex-col gap-2">
            <label>Date of Birth</label>
            <input v-model="form.dob" type="date" class="input" />
          </div>
          <div class="flex flex-col gap-2 col-span-2">
            <label>Address</label>
            <textarea v-model="form.address" class="input"></textarea>
          </div>
          <div class="flex flex-col gap-2">
            <label>ID Number</label>
            <input v-model="form.id_number" type="text" class="input" />
          </div>
        </div>

        <div class="flex justify-end pt-4">
          <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            {{ isEdit ? 'Update Customer' : 'Save Customer' }}
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
import { getCustomer, createCustomer, updateCustomer } from './CustomerService'

const route = useRoute()
const router = useRouter()
const isEdit = ref(!!route.params.id)
const form = ref({
  first_name: '',
  last_name: '',
  email: '',
  phone: '',
  dob: '',
  address: '',
  id_number: ''
})

const fetchCustomerData = async (id) => {
  try {
    const res = await getCustomer(id)
    form.value = res.data.data || res.data
  } catch (err) {
    console.error('Error fetching customer:', err)
  }
}

const submitForm = async () => {
  try {
    if (isEdit.value) {
      await updateCustomer(route.params.id, form.value)
    } else {
      await createCustomer(form.value)
    }
    router.push('/admin/customers')
  } catch (err) {
    console.error('Error submitting form:', err)
  }
}

onMounted(() => {
  if (isEdit.value) fetchCustomerData(route.params.id)
})
</script>
