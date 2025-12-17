<template>
  <div class="p-4">
    <h2 class="text-xl font-semibold mb-4">{{ isEdit ? 'Edit User' : 'Create User' }}</h2>

    <form @submit.prevent="submitForm" class="flex flex-col gap-4 max-w-md">
      <input v-model="form.name" type="text" placeholder="Name" class="p-2 border rounded" required />
      <input v-model="form.email" type="email" placeholder="Email" class="p-2 border rounded" required />
      <input v-model="form.password" type="password" placeholder="Password" class="p-2 border rounded" :required="!isEdit" />

      <select v-model="form.role_id" class="p-2 border rounded" required>
        <option value="">Select Role</option>
        <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
      </select>

      <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
        {{ isEdit ? 'Update' : 'Create' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getUser, createUser, updateUser, getRoles } from './UserService'

const route = useRoute()
const router = useRouter()
const isEdit = ref(!!route.params.id)

const form = ref({
  name: '',
  email: '',
  password: '',
  role_id: ''
})

const roles = ref([])

const fetchRoles = async () => {
  try {
    const res = await getRoles()
    roles.value = res.data
  } catch (err) {
    console.error(err)
  }
}

const fetchUserData = async (id) => {
  try {
    const res = await getUser(id)
    form.value = {
      name: res.data.name,
      email: res.data.email,
      password: '',
      role_id: res.data.role.id
    }
  } catch (err) {
    console.error(err)
  }
}

const submitForm = async () => {
  try {
    if (isEdit.value) {
      await updateUser(route.params.id, form.value)
    } else {
      await createUser(form.value)
    }
    router.push('/users')
  } catch (err) {
    console.error(err)
  }
}

onMounted(() => {
  fetchRoles()
  if (isEdit.value) fetchUserData(route.params.id)
})
</script>
