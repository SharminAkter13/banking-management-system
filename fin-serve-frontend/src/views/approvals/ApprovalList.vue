<template>
  <AdminLayout>
    <div class="p-6 space-y-4">
      <h1 class="text-2xl font-semibold">Approvals</h1>

      <router-link
        to="/admin/approvals/create"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
      >
        Create Approval
      </router-link>

      <table class="w-full border mt-4">
        <thead class="bg-gray-100">
          <tr>
            <th class="border px-3 py-2">#</th>
            <th class="border px-3 py-2">Entity Type</th>
            <th class="border px-3 py-2">Entity ID</th>
            <th class="border px-3 py-2">Status</th>
            <th class="border px-3 py-2">Creator</th>
            <th class="border px-3 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(approval, i) in approvals" :key="approval.id">
            <td class="border px-3 py-2">{{ i + 1 }}</td>
            <td class="border px-3 py-2">{{ approval.entity_type }}</td>
            <td class="border px-3 py-2">{{ approval.entity_id }}</td>
            <td class="border px-3 py-2">{{ approval.status }}</td>
            <td class="border px-3 py-2">{{ approval.creator.name }}</td>
            <td class="border px-3 py-2 text-right flex gap-2 justify-end">
              <router-link :to="`/admin/approvals/${approval.id}`" class="text-blue-600 hover:underline">View</router-link>
              <button @click="deleteApproval(approval.id)" class="text-red-600 hover:underline">Delete</button>
            </td>
          </tr>
          <tr v-if="approvals.length === 0">
            <td colspan="6" class="text-center py-4 text-gray-500">No approvals found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import api from '@/services/api'
import { useRouter } from 'vue-router'

const approvals = ref([])
const router = useRouter()

const fetchApprovals = async () => {
  const res = await api.get('/approvals')
  approvals.value = res.data
}

const deleteApproval = async (id) => {
  if (!confirm('Are you sure you want to delete this approval?')) return
  await api.delete(`/approvals/${id}`)
  approvals.value = approvals.value.filter(a => a.id !== id)
}

onMounted(fetchApprovals)
</script>
