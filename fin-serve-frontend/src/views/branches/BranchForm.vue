<template>
  <AdminLayout>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-semibold">{{ isEdit ? 'Edit Branch' : 'Create Branch' }}</h2>
      <router-link to="/admin/branches" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Back to List</router-link>
    </div>

    <div class="bg-white dark:bg-gray-900 border rounded-lg p-6 max-w-lg">
      <form @submit.prevent="submitForm" class="space-y-4">
        <div>
          <label class="block text-sm font-medium">Branch Name</label>
          <input v-model="form.branch_name" type="text" required class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:border-gray-700"/>
        </div>

        <div>
          <label class="block text-sm font-medium">Location</label>
          <input v-model="form.location" type="text" required class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:border-gray-700"/>
        </div>

        <div>
          <label class="block text-sm font-medium">Manager ID</label>
          <input v-model="form.manager_id" type="number" placeholder="Optional" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:border-gray-700"/>
        </div>

        <div>
          <label class="block text-sm font-medium">Contact</label>
          <input v-model="form.contact" type="text" placeholder="Optional" class="w-full border rounded px-3 py-2 dark:bg-gray-800 dark:border-gray-700"/>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          {{ isEdit ? 'Update Branch' : 'Save Branch' }}
        </button>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import { getBranch, createBranch, updateBranch } from './BranchService'

const route = useRoute()
const router = useRouter()
const isEdit = ref(!!route.params.id)

const form = ref({
  branch_name: '',
  location: '',
  manager_id: '',
  contact: ''
})

const fetchBranch = async (id) => {
  try {
    const res = await getBranch(id)
    form.value = res.data
  } catch (err) {
    console.error('Error fetching branch:', err)
  }
}

const submitForm = async () => {
  try {
    if (isEdit.value) {
      await updateBranch(route.params.id, form.value)
    } else {
      await createBranch(form.value)
    }
    router.push('/admin/branches')
  } catch (err) {
    console.error('Error submitting branch:', err)
  }
}

onMounted(() => {
  if (isEdit.value) fetchBranch(route.params.id)
})
</script>
