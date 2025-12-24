<template>
  <AdminLayout>

  <div class="max-w-3xl mx-auto rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <h3 class="text-xl font-bold mb-6 text-gray-800 dark:text-white/90">Add New Employee</h3>
    
    <form @submit.prevent="handleSubmit" class="space-y-4">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
          <input v-model="form.name" type="text" class="w-full rounded-lg border border-gray-300 p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700" required />
        </div>
        <div>
          <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
          <input v-model="form.email" type="email" class="w-full rounded-lg border border-gray-300 p-2.5 dark:bg-gray-700" required />
        </div>
        <div>
          <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
          <input v-model="form.password" type="password" class="w-full rounded-lg border border-gray-300 p-2.5 dark:bg-gray-700" required />
        </div>
<div>
  <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Branch</label>
  <select v-model="form.branch_id" class="w-full rounded-lg border border-gray-300 p-2.5 dark:bg-gray-700 text-black" required>
    <option value="" disabled>Select a branch</option>
    <option v-for="branch in branches" :key="branch.id" :value="branch.id">
      {{ branch.branch_name }}
    </option>
  </select>
</div>

<div>
  <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
  <select v-model="form.role_id" class="w-full rounded-lg border border-gray-300 p-2.5 dark:bg-gray-700 text-black" required>
    <option value="" disabled>Select a role</option>
    <option v-for="role in roles" :key="role.id" :value="role.id">
      {{ role.name }}
    </option>
  </select>
</div>
<div>
          <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Position</label>
          <input v-model="form.position" type="text" placeholder="e.g. Senior Teller" class="w-full rounded-lg border border-gray-300 p-2.5 dark:bg-gray-700" required />
        </div>
      </div>

      <div class="flex justify-end gap-3 mt-6">
        <button type="button" @click="$router.back()" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Save Employee</button>
      </div>
    </form>
  </div>
    </AdminLayout>

</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/services/api';
import AdminLayout from '@/components/layout/AdminLayout.vue'

const router = useRouter();
const branches = ref([]);
const roles = ref([]);

const form = ref({
  name: '',
  email: '',
  password: '',
  branch_id: '',
  role_id: '',
  position: '',
  salary: 0
});

onMounted(async () => {
  try {
    const [bRes, rRes] = await Promise.all([
      api.get('/branches'), 
      api.get('/roles')
    ]);

    // Check if data is nested inside .data.data (common in Laravel Resources) 
    // or just .data
    branches.value = bRes.data.data || bRes.data;
    
    const allRoles = rRes.data.data || rRes.data;
    roles.value = allRoles.filter(r => r.slug !== 'customer');
    
    console.log('Branches loaded:', branches.value); // Debugging
    console.log('Roles loaded:', roles.value);       // Debugging
  } catch (error) {
    console.error("Failed to fetch dropdown data:", error);
  }
});
const handleSubmit = async () => {
  try {
    await api.post('/employees', form.value);
    router.push('/employees');
  } catch (error) {
    alert('Error creating employee');
  }
};
</script>