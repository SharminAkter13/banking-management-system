<template>
  <AdminLayout>

  <div class="max-w-3xl mx-auto rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <h3 class="text-xl font-bold mb-6 text-gray-800 dark:text-white/90">Edit Employee</h3>
    
    <form v-if="loaded" @submit.prevent="handleUpdate" class="space-y-4">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
          <input v-model="form.user.name" type="text" class="w-full rounded-lg border border-gray-300 p-2.5 dark:bg-gray-700" required />
        </div>
        <div>
          <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
          <select v-model="form.user.status" class="w-full rounded-lg border border-gray-300 p-2.5 dark:bg-gray-700">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
        <div>
          <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Position</label>
          <input v-model="form.position" type="text" class="w-full rounded-lg border border-gray-300 p-2.5 dark:bg-gray-700" required />
        </div>
        <div>
          <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Salary</label>
          <input v-model="form.salary" type="number" step="0.01" class="w-full rounded-lg border border-gray-300 p-2.5 dark:bg-gray-700" required />
        </div>
      </div>

      <div class="flex justify-end gap-3 mt-6">
        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Update Profile</button>
      </div>
    </form>
  </div>
    </AdminLayout>

</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '@/services/api';
import AdminLayout from '@/components/layout/AdminLayout.vue'

const route = useRoute();
const router = useRouter();
const form = ref({});
const loaded = ref(false);

onMounted(async () => {
  const res = await api.get(`/employees/${route.params.id}`);
  form.value = res.data;
  loaded.value = true;
});

const handleUpdate = async () => {
  try {
    await api.put(`/employees/${route.params.id}`, form.value);
    router.push('/employees');
  } catch (error) {
    alert('Update failed');
  }
};
</script>