<template>
  <div v-if="employee" class="max-w-4xl mx-auto space-y-6">
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white/90">Employee Details</h3>
        <span :class="employee.user.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" class="px-4 py-1 rounded-full text-sm font-medium">
          {{ employee.user.status }}
        </span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="space-y-4">
          <p class="text-sm text-gray-500 uppercase font-semibold">Personal Information</p>
          <div>
            <p class="text-xs text-gray-400">Full Name</p>
            <p class="font-medium text-gray-800 dark:text-white/90">{{ employee.user.name }}</p>
          </div>
          <div>
            <p class="text-xs text-gray-400">Email Address</p>
            <p class="font-medium text-gray-800 dark:text-white/90">{{ employee.user.email }}</p>
          </div>
        </div>

        <div class="space-y-4">
          <p class="text-sm text-gray-500 uppercase font-semibold">Employment Details</p>
          <div>
            <p class="text-xs text-gray-400">Position</p>
            <p class="font-medium text-gray-800 dark:text-white/90">{{ employee.position }}</p>
          </div>
          <div>
            <p class="text-xs text-gray-400">Branch</p>
            <p class="font-medium text-gray-800 dark:text-white/90">{{ employee.branch.branch_name }}</p>
          </div>
          <div>
            <p class="text-xs text-gray-400">Hire Date</p>
            <p class="font-medium text-gray-800 dark:text-white/90">{{ employee.hire_date || 'N/A' }}</p>
          </div>
        </div>
      </div>
      
      <div class="mt-8 pt-6 border-t border-gray-100 dark:border-white/[0.05]">
        <button @click="$router.push(`/employees/${employee.id}/edit`)" class="bg-gray-100 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200">
          Edit Profile
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '@/api';

const route = useRoute();
const employee = ref(null);

onMounted(async () => {
  try {
    const res = await api.get(`/employees/${route.params.id}`);
    employee.value = res.data;
  } catch (error) {
    console.error(error);
  }
});
</script>