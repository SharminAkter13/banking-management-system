<template>
  <AdminLayout>
    <div class="flex items-center justify-between mb-7">
      <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        Branch Management
      </h2>
      <router-link
        to="/admin/branches/create"
        class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700"
      >
        Create Branch
      </router-link>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
      <div class="max-w-full overflow-x-auto">
        <table class="min-w-full">
          <thead>
            <tr class="border-b border-gray-200 dark:border-gray-700">
              <th class="px-5 py-3 text-left">Branch Name</th>
              <th class="px-5 py-3 text-left">Branch Code</th>
              <th class="px-5 py-3 text-left">Location</th>
              <th class="px-5 py-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="branch in branches" :key="branch.id">
              <td class="px-5 py-4">{{ branch.name }}</td>
              <td class="px-5 py-4">{{ branch.code }}</td>
              <td class="px-5 py-4">{{ branch.location }}</td>
              <td class="px-5 py-4 text-right">
                <div class="flex justify-end gap-3">
                  <router-link
                    :to="`/admin/branches/${branch.id}/edit`"
                    class="text-blue-600 hover:underline"
                  >
                    Edit
                  </router-link>
                  <button @click="deleteBranch(branch.id)" class="text-red-600 hover:underline">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="branches.length === 0">
              <td colspan="4" class="text-center py-4 text-gray-500">No branches found.</td>
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
import { getBranches, deleteBranch as deleteBranchAPI } from './BranchService'
import { useRouter } from 'vue-router'

const branches = ref([])
const router = useRouter()

const fetchBranches = async () => {
  try {
    const res = await getBranches()
    branches.value = res.data.data || res.data
  } catch (err) {
    console.error('Error fetching branches:', err)
  }
}

const deleteBranch = async (id) => {
  if (!confirm('Are you sure you want to delete this branch?')) return
  try {
    await deleteBranchAPI(id)
    branches.value = branches.value.filter(b => b.id !== id)
  } catch (err) {
    console.error('Error deleting branch:', err)
  }
}

onMounted(fetchBranches)
</script>
