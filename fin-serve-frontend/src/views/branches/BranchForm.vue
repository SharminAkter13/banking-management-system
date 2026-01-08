<template>
  <AdminLayout>
    <div class="mb-6 flex justify-between items-center">
      <h2 class="text-xl font-semibold">{{ isEdit ? 'Edit Branch' : 'Create Branch' }}</h2>
      <router-link to="/admin/branches" class="px-4 py-2 border rounded hover:bg-gray-100">Back</router-link>
    </div>

    <div class="bg-white dark:bg-gray-900 border rounded p-6 max-w-lg">
      <form @submit.prevent="submitForm" class="space-y-4">
        <div>
          <label class="block text-sm font-medium">Branch Name</label>
          <input v-model="form.branch_name" type="text" required class="w-full px-3 py-2 border rounded dark:bg-gray-800 dark:border-gray-700" />
        </div>

        <div>
          <label class="block text-sm font-medium">Location</label>
          <input v-model="form.location" type="text" required class="w-full px-3 py-2 border rounded dark:bg-gray-800 dark:border-gray-700" />
        </div>

        <div>
          <label class="block text-sm font-medium">Contact</label>
          <input v-model="form.contact" type="text" placeholder="Optional" class="w-full px-3 py-2 border rounded dark:bg-gray-800 dark:border-gray-700" />
        </div>

        <div v-if="isEdit">
          <label class="block text-sm font-medium">Manager</label>
          <input type="text" :value="form.manager_name || auth.user.name" disabled class="w-full px-3 py-2 border rounded dark:bg-gray-800 dark:border-gray-700" />
        </div>

        <div class="flex justify-end">
          <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            {{ isEdit ? 'Update Branch' : 'Create Branch' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { getBranch, createBranch, updateBranch } from './BranchService'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const auth = useAuthStore()
const route = useRoute()
const router = useRouter()
const isEdit = ref(!!route.params.id)
const form = ref({ branch_name: '', location: '', contact: '' })

const fetchBranch = async (id) => {
  try {
    const res = await getBranch(id)
    form.value = { ...res.data, manager_name: res.data.manager?.name }
  } catch (err) {
    console.error(err)
  }
}
const submitForm = async () => {
  try {
    if (isEdit.value) {
      await updateBranch(route.params.id, form.value)
    } else {
      await createBranch(form.value) // manager_id handled by backend
    }
    router.push('/admin/branches')
  } catch (err) {
    console.error(err)
  }
}


onMounted(() => {
  if (isEdit.value) fetchBranch(route.params.id)
})
</script>
