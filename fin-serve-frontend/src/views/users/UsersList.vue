<template>
  <AdminLayout>
    <div class="flex items-center justify-between mb-7">
      <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        Users Management
      </h2>
      <router-link
        to="/admin/users/create"
        class="flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-blue-600 hover:bg-blue-700"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Create User
      </router-link>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
      <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="min-w-full">
          <thead>
            <tr class="border-b border-gray-200 dark:border-gray-700">
              <th class="px-5 py-3 text-left sm:px-6">
                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">User</p>
              </th>
              <th class="px-5 py-3 text-left sm:px-6">
                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Email</p>
              </th>
              <th class="px-5 py-3 text-left sm:px-6">
                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p>
              </th>
              <th class="px-5 py-3 text-right sm:px-6">
                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Actions</p>
              </th>
            </tr>
          </thead>
          
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="user in users" :key="user.id" class="border-t border-gray-100 dark:border-gray-800">
              <td class="px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 overflow-hidden rounded-full bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center">
                    <span class="text-sm font-bold text-blue-600">{{ user.name.charAt(0) }}</span>
                  </div>
                  <div>
                    <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">{{ user.name }}</span>
                    <span class="block text-gray-500 text-theme-xs dark:text-gray-400">{{ getRoleName(user.role_id) }}</span>
                  </div>
                </div>
              </td>
              <td class="px-5 py-4 sm:px-6">
                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ user.email }}</p>
              </td>
              <td class="px-5 py-4 sm:px-6">
                <span :class="['rounded-full px-2 py-0.5 text-theme-xs font-medium uppercase', 
                  user.status === 'active' ? 'bg-success-50 text-success-700' : 'bg-error-50 text-error-700']">
                  {{ user.status }}
                </span>
              </td>
              <td class="px-5 py-4 text-right sm:px-6">
                <div class="flex justify-end gap-4">
                  <router-link :to="`/admin/users/${user.id}/edit`" class="text-gray-500 hover:text-blue-600 transition">
                    Edit
                  </router-link>
                  <button @click="removeUser(user.id)" class="text-gray-500 hover:text-red-600 transition">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/components/layout/AdminLayout.vue' // Adjust path if needed
import { ref, onMounted } from 'vue'
import { getUsers, deleteUser, getRoles } from './UserService'

const users = ref([])
const roles = ref([])

const fetchUsers = async () => {
  try {
    const res = await getUsers();
    // Logic: check if the array is at res.data or res.data.data
    if (res.data && Array.isArray(res.data)) {
      users.value = res.data;
    } else if (res.data && Array.isArray(res.data.data)) {
      users.value = res.data.data;
    }
    console.log("Users fetched successfully:", users.value);
  } catch (err) {
    console.error("Fetch Users Error:", err.response || err);
  }
}

const fetchRoles = async () => {
  try {
    const res = await getRoles();
    if (res.data && Array.isArray(res.data)) {
      roles.value = res.data;
    } else if (res.data && Array.isArray(res.data.data)) {
      roles.value = res.data.data;
    }
  } catch (err) {
    console.error("Fetch Roles Error:", err);
  }
}
const getRoleName = (roleId) => {
  const role = roles.value.find(r => r.id === roleId);
  return role ? role.name : 'User';
}
const removeUser = async (id) => {
  if (confirm('Delete this user?')) {
    try { await deleteUser(id); users.value = users.value.filter(u => u.id !== id); } catch (err) { console.error(err); }
  }
}

onMounted(() => { fetchRoles(); fetchUsers(); })
</script>