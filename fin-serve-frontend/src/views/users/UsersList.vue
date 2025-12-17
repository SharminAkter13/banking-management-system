<template>
  <div class="p-4">
    <div class="flex justify-between mb-4">
      <h2 class="text-xl font-semibold">Users Management</h2>
      <router-link
        to="/users/create"
        class="px-4 py-2 bg-blue-600 text-white rounded"
      >Create User</router-link>
    </div>

    <table class="w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Name</th>
          <th class="p-2 border">Email</th>
          <th class="p-2 border">Role</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td class="p-2 border">{{ user.id }}</td>
          <td class="p-2 border">{{ user.name }}</td>
          <td class="p-2 border">{{ user.email }}</td>
          <td class="p-2 border">{{ getRoleName(user.role_id) }}</td>
          <td class="p-2 border flex gap-2">
            <router-link
              :to="`/users/edit/${user.id}`"
              class="px-2 py-1 bg-yellow-400 rounded"
            >Edit</router-link>
            <button
              @click="removeUser(user.id)"
              class="px-2 py-1 bg-red-500 text-white rounded"
            >Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getUsers, deleteUser, getRoles } from './UserService'
import { useRouter } from 'vue-router'

const users = ref([])
const roles = ref([])
const router = useRouter()

const fetchUsers = async () => {
  try {
    const res = await getUsers()
    users.value = res.data
  } catch (err) {
    console.error(err)
  }
}

const fetchRoles = async () => {
  try {
    const res = await getRoles()
    roles.value = res.data
  } catch (err) {
    console.error(err)
  }
}

const getRoleName = (roleId) => {
  const role = roles.value.find(r => r.id === roleId)
  return role ? role.name : 'N/A'
}

const removeUser = async (id) => {
  if (confirm('Are you sure you want to delete this user?')) {
    try {
      await deleteUser(id)
      users.value = users.value.filter(u => u.id !== id)
    } catch (err) {
      console.error(err)
    }
  }
}

onMounted(async () => {
  await fetchRoles()
  await fetchUsers()
})
</script>
