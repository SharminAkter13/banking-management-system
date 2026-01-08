<template>
  <AdminLayout>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-semibold">Branches</h2>
      <router-link
        to="/admin/branches/create"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
      >
        Create Branch
      </router-link>
    </div>

    <div class="overflow-x-auto border rounded-lg bg-white dark:bg-gray-900 dark:border-gray-800">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-100 dark:bg-gray-800">
          <tr>
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Location</th>
            <th class="px-4 py-2">Manager</th>
            <th class="px-4 py-2">Contact</th>
            <th class="px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="branch in branches" :key="branch.id" class="border-b dark:border-gray-700">
            <td class="px-4 py-2">{{ branch.branch_name }}</td>
            <td class="px-4 py-2">{{ branch.location }}</td>
            <td class="px-4 py-2">{{ branch.manager?.name || '-' }}</td>
            <td class="px-4 py-2">{{ branch.contact || '-' }}</td>
            <td class="px-4 py-2 flex gap-2">
              <router-link :to="`/admin/branches/${branch.id}/edit`" class="text-blue-600 hover:underline">Edit</router-link>
              <button @click="deleteBranch(branch.id)" class="text-red-600 hover:underline">Delete</button>
            </td>
          </tr>
          <tr v-if="branches.length === 0">
            <td colspan="5" class="text-center py-4 text-gray-500">No branches found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { getBranches, deleteBranch as deleteBranchAPI } from './BranchService'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const branches = ref([])
const auth = useAuthStore()

const fetchBranches = async () => {
  try {
    const res = await getBranches()
    branches.value = res.data
  } catch (err) {
    console.error(err)
  }
}

const deleteBranch = async (id) => {
  if (!confirm('Are you sure you want to delete this branch?')) return
  try {
    await deleteBranchAPI(id)
    branches.value = branches.value.filter(b => b.id !== id)
  } catch (err) {
    console.error(err)
  }
}

onMounted(fetchBranches)
</script>
