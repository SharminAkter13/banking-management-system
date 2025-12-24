<template>
  <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Employees</h3>
      <router-link to="/employees/create" class="flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
        Add Employee
      </router-link>
    </div>

    <div class="max-w-full overflow-x-auto">
      <table class="w-full table-auto">
        <thead>
          <tr class="text-left bg-gray-50 dark:bg-white/[0.02]">
            <th class="px-4 py-4 font-medium text-gray-800 dark:text-white/90">Name</th>
            <th class="px-4 py-4 font-medium text-gray-800 dark:text-white/90">Branch</th>
            <th class="px-4 py-4 font-medium text-gray-800 dark:text-white/90">Position</th>
            <th class="px-4 py-4 font-medium text-gray-800 dark:text-white/90">Status</th>
            <th class="px-4 py-4 font-medium text-gray-800 dark:text-white/90 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-white/[0.05]">
          <tr v-for="employee in employees" :key="employee.id">
            <td class="px-4 py-4">
              <div class="flex flex-col">
                <span class="font-medium text-gray-800 dark:text-white/90">{{ employee.user.name }}</span>
                <span class="text-xs text-gray-500">{{ employee.user.email }}</span>
              </div>
            </td>
            <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ employee.branch.branch_name }}</td>
            <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">{{ employee.position }}</td>
            <td class="px-4 py-4">
              <span :class="employee.user.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" class="rounded-full px-3 py-1 text-xs font-medium">
                {{ employee.user.status }}
              </span>
            </td>
            <td class="px-4 py-4 text-right">
              <div class="flex justify-end gap-2">
                <router-link :to="`/employees/${employee.id}`" class="text-blue-600 hover:underline text-sm">View</router-link>
                <router-link :to="`/employees/${employee.id}/edit`" class="text-gray-600 hover:underline text-sm">Edit</router-link>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/api';

const employees = ref([]);

const fetchEmployees = async () => {
  try {
    const response = await api.get('/employees');
    employees.value = response.data;
  } catch (error) {
    console.error("Failed to fetch employees", error);
  }
};

onMounted(fetchEmployees);
</script>