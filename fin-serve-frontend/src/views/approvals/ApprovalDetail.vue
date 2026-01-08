<template>
  <AdminLayout>
    <div class="p-6 space-y-6">

      <!-- ================= Approval Header ================= -->
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold">
          Approval #{{ approval.id }} - {{ approval.entity_type }} #{{ approval.entity_id }}
        </h2>
        <router-link to="/admin/dashboard" class="px-4 py-2 border rounded hover:bg-gray-50">
          Back
        </router-link>
      </div>

      <!-- ================= Approval Info ================= -->
      <div class="bg-white p-4 rounded-xl shadow grid md:grid-cols-2 gap-4">
        <div>
          <p class="text-sm text-gray-500">Entity Type</p>
          <p class="font-semibold">{{ approval.entity_type }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-500">Entity ID</p>
          <p class="font-semibold">{{ approval.entity_id }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-500">Created By</p>
          <p class="font-semibold">{{ approval.creator.name }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-500">Status</p>
          <p>
            <span :class="statusClass(approval.status)" class="px-2 py-1 rounded text-xs">{{ approval.status }}</span>
          </p>
        </div>
      </div>

      <!-- ================= Steps ================= -->
      <div class="bg-white p-4 rounded-xl shadow">
        <h3 class="font-semibold mb-3">Approval Steps</h3>
        <div class="space-y-2">
          <div
            v-for="step in approval.steps"
            :key="step.id"
            class="border rounded p-3 flex justify-between items-center"
            :class="{'bg-yellow-50': step.status==='Pending', 'bg-green-50': step.status==='Approved', 'bg-red-50': step.status==='Rejected'}"
          >
            <div>
              <p class="text-sm text-gray-500">Step #{{ step.step_number }} - Role: {{ step.role.name }}</p>
              <p class="font-medium">Status: {{ step.status }}</p>
              <p v-if="step.approved_at" class="text-xs text-gray-400">Approved at: {{ step.approved_at }}</p>
            </div>

            <!-- ================= Approve/Reject Buttons ================= -->
            <div v-if="isCurrentUserStep(step)" class="flex gap-2">
              <button @click="takeAction(step.id, 'Approved')" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Approve</button>
              <button @click="takeAction(step.id, 'Rejected')" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Reject</button>
            </div>
          </div>
        </div>
      </div>

      <!-- ================= Action History ================= -->
      <div class="bg-white p-4 rounded-xl shadow mt-4">
        <h3 class="font-semibold mb-3">Action History</h3>
        <table class="w-full border text-sm">
          <thead class="bg-gray-100">
            <tr>
              <th class="border px-3 py-2">Step #</th>
              <th class="border px-3 py-2">Role</th>
              <th class="border px-3 py-2">Action</th>
              <th class="border px-3 py-2">By</th>
              <th class="border px-3 py-2">Comments</th>
              <th class="border px-3 py-2">Date</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="action in approvalActions" :key="action.id">
              <td class="border px-3 py-2">{{ action.step.step_number }}</td>
              <td class="border px-3 py-2">{{ action.step.role.name }}</td>
              <td class="border px-3 py-2">{{ action.action }}</td>
              <td class="border px-3 py-2">{{ action.approved_by_user.name }}</td>
              <td class="border px-3 py-2">{{ action.comments || '-' }}</td>
              <td class="border px-3 py-2">{{ action.created_at }}</td>
            </tr>
            <tr v-if="approvalActions.length === 0">
              <td colspan="6" class="text-center py-2 text-gray-500">No actions yet.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/services/api'
import AdminLayout from '@/components/layout/AdminLayout.vue'

const route = useRoute()
const approvalId = route.params.id

const approval = ref({
  steps: [],
  creator: {},
})
const approvalActions = ref([])
const currentUserRole = ref('')

// ================= Computed / Helpers =================
const statusClass = (status) => {
  return {
    'Pending': 'bg-yellow-100 text-yellow-800',
    'Approved': 'bg-green-100 text-green-800',
    'Rejected': 'bg-red-100 text-red-800',
    'In Progress': 'bg-gray-100 text-gray-800'
  }[status] || ''
}

const isCurrentUserStep = (step) => step.role.slug === currentUserRole.value && step.status === 'Pending'

// ================= Fetch Approval Data =================
const fetchApproval = async () => {
  try {
    const userRes = await api.get('/me')
    currentUserRole.value = userRes.data.role.slug

    const res = await api.get(`/approvals/${approvalId}?include=steps.role,creator,steps.actions`)
    approval.value = res.data

    // Flatten all actions from steps
    const actions = []
    approval.value.steps.forEach(step => {
      if(step.actions) {
        step.actions.forEach(a => actions.push({...a, step}))
      }
    })
    approvalActions.value = actions
  } catch (err) {
    console.error('Error fetching approval:', err)
  }
}

// ================= Approve / Reject =================
const takeAction = async (stepId, action) => {
  const comments = prompt(`Add a comment for ${action}:`) || null
  try {
    await api.post('/approval-actions', {
      approval_step_id: stepId,
      approved_by: 1, // Replace with current user id from token
      action,
      comments
    })
    fetchApproval()
  } catch (err) {
    console.error('Error taking action:', err)
  }
}

onMounted(fetchApproval)
</script>
