<template>
  <AdminLayout>
    <div class="p-6 max-w-4xl mx-auto">
      <h2 class="text-2xl font-bold mb-6">Account Status</h2>

      <!-- Form -->
      <form @submit.prevent="save" class="flex flex-col md:flex-row md:items-center md:gap-4 mb-6">
        <input
          v-model="form.name"
          placeholder="Status Name"
          class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-1/2 mb-2 md:mb-0"
          required
        />
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition w-full md:w-auto"
        >
          {{ editId ? 'Update' : 'Create' }}
        </button>
      </form>

      <!-- Table -->
      <div class="overflow-x-auto border rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-6 py-3 text-left text-gray-700 font-semibold">Status Name</th>
              <th class="px-6 py-3 text-left text-gray-700 font-semibold">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="status in statuses" :key="status.id" class="hover:bg-gray-50">
              <td class="px-6 py-4">{{ status.name }}</td>
              <td class="px-6 py-4 space-x-2">
                <button
                  @click="edit(status)"
                  class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 transition"
                >
                  Edit
                </button>
                <button
                  @click="remove(status.id)"
                  class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition"
                >
                  Delete
                </button>
              </td>
            </tr>
            <tr v-if="statuses.length === 0">
              <td colspan="2" class="text-center py-4 text-gray-500">No statuses found.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import AdminLayout from '@/components/layout/AdminLayout.vue'
import {
  getAccountStatuses,
  createAccountStatus,
  updateAccountStatus,
  deleteAccountStatus
} from "@/services/adminApi";

const statuses = ref([]);
const form = ref({ name: "" });
const editId = ref(null);

const load = async () => {
  const res = await getAccountStatuses();
  statuses.value = res.data;
};

onMounted(load);

const save = async () => {
  if (editId.value) {
    await updateAccountStatus(editId.value, form.value);
  } else {
    await createAccountStatus(form.value);
  }
  reset();
  load();
};

const edit = (status) => {
  editId.value = status.id;
  form.value = { name: status.name };
};

const remove = async (id) => {
  if (confirm("Delete this status?")) {
    await deleteAccountStatus(id);
    load();
  }
};

const reset = () => {
  editId.value = null;
  form.value = { name: "" };
};
</script>
