<template>
  <AdminLayout>
    <div class="flex items-center justify-between mb-7">
      <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        {{ isEdit ? 'Edit User' : 'Create User' }}
      </h2>
      <router-link to="/admin/users" class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-50 dark:border-gray-800">
        Back to List
      </router-link>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] max-w-2xl">
      <form @submit.prevent="submitForm" class="space-y-5">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <!-- Name -->
          <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Full Name</label>
            <input v-model="form.name" type="text" class="p-2.5 border rounded-lg dark:bg-gray-900 dark:border-gray-800" required />
          </div>

          <!-- Email -->
          <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Email Address</label>
            <input v-model="form.email" type="email" class="p-2.5 border rounded-lg dark:bg-gray-900 dark:border-gray-800" required />
          </div>

          <!-- Password -->
          <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Password {{ isEdit ? '(Optional)' : '' }}</label>
            <input v-model="form.password" type="password" class="p-2.5 border rounded-lg dark:bg-gray-900 dark:border-gray-800" :required="!isEdit" />
          </div>

          <!-- Role -->
          <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Role</label>
            <select v-model="form.role_id" class="p-2.5 border rounded-lg dark:bg-gray-900 dark:border-gray-800" required>
              <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
            </select>
          </div>

          <!-- Status -->
          <div class="flex flex-col gap-2">
            <label class="text-sm font-medium">Status</label>
            <select v-model="form.status" class="p-2.5 border rounded-lg dark:bg-gray-900 dark:border-gray-800" required>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>

        <!-- Submit button -->
        <div class="flex justify-end pt-4">
          <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            {{ isEdit ? 'Update User' : 'Save User' }}
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
import { getUser, createUser, updateUser, getRoles } from './UserService'

const route = useRoute()
const router = useRouter()
const isEdit = ref(!!route.params.id)

const roles = ref([])
const form = ref({
  name: '',
  email: '',
  password: '',
  role_id: '',
  status: 'active'
})

// Fetch all roles
const fetchRoles = async () => {
  try {
    const res = await getRoles()
    roles.value = res.data.data || res.data
  } catch (err) {
    console.error("Fetch Roles Error:", err)
  }
}

// Fetch user data if editing
const fetchUserData = async (id) => {
  try {
    const res = await getUser(id)
    const user = res.data.data || res.data
    form.value = {
      name: user.name,
      email: user.email,
      role_id: user.role_id,
      status: user.status || 'active',
      password: '' // leave blank for editing
    }
  } catch (err) {
    console.error("Fetch User Error:", err)
  }
}

// Submit form (create or update)
const submitForm = async () => {
  try {
    if (isEdit.value) {
      await updateUser(route.params.id, form.value)
    } else {
      await createUser(form.value)
    }
    router.push('/admin/users')
  } catch (err) {
    console.error("Submit Error:", err)
  }
}

onMounted(() => {
  fetchRoles()
  if (isEdit.value) fetchUserData(route.params.id)
})
</script>
