<template>
  <AdminLayout>
    <div class="flex items-center justify-between mb-7">
      <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        {{ isEdit ? 'Edit Branch' : 'Create Branch' }}
      </h2>
      <router-link
        to="/admin/branches"
        class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-50 dark:border-gray-800"
      >
        Back to List
      </router-link>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] max-w-2xl">
      <form @submit.prevent="submitForm" class="space-y-5">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <!-- Branch Name -->
          <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Branch Name</label>
            <input v-model="form.name" type="text" class="p-2.5 border rounded-lg dark:bg-gray-900 dark:border-gray-800" required />
          </div>

          <!-- Branch Code -->
          <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Branch Code</label>
            <input v-model="form.code" type="text" class="p-2.5 border rounded-lg dark:bg-gray-900 dark:border-gray-800" required />
          </div>

          <!-- Location -->
          <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Location</label>
            <input v-model="form.location" type="text" class="p-2.5 border rounded-lg dark:bg-gray-900 dark:border-gray-800" required />
          </div>
        </div>

        <div class="flex justify-end pt-4">
          <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            {{ isEdit ? 'Update Branch' : 'Save Branch' }}
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
import { getBranch, createBranch, updateBranch } from './BranchService'

const route = useRoute()
const router = useRouter()

const isEdit = ref(!!route.params.id)
const form = ref({
  name: '',
  code: '',
  location: ''
})

const fetchBranchData = async (id) => {
  try {
    const res = await getBranch(id)
    const branch = res.data
    form.value = { name: branch.name, code: branch.code, location: branch.location }
  } catch (err) {
    console.error('Error fetching branch data:', err)
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
    console.error('Error submitting form:', err)
  }
}

onMounted(() => {
  if (isEdit.value) {
    fetchBranchData(route.params.id)
  }
})
</script>
