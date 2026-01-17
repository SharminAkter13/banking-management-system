<template>
  <AdminLayout>
    <div class="p-6 max-w-5xl mx-auto">
      <h2 class="text-2xl font-bold mb-6">Account Types</h2>

      <!-- Form -->
      <form @submit.prevent="save" class="flex flex-col md:flex-row md:items-center md:gap-4 mb-6">
        <input
          v-model="form.account_name"
          placeholder="Account Type"
          class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-1/3 mb-2 md:mb-0"
          required
        />
        <input
          v-model="form.description"
          placeholder="Description"
          class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-1/2 mb-2 md:mb-0"
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
              <th class="px-6 py-3 text-left text-gray-700 font-semibold">Account Type</th>
              <th class="px-6 py-3 text-left text-gray-700 font-semibold">Description</th>
              <th class="px-6 py-3 text-left text-gray-700 font-semibold">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="type in types" :key="type.id" class="hover:bg-gray-50">
              <td class="px-6 py-4">{{ type.account_name }}</td>
              <td class="px-6 py-4">{{ type.description }}</td>
              <td class="px-6 py-4 space-x-2">
                <button
                  @click="edit(type)"
                  class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 transition"
                >
                  Edit
                </button>
                <button
                  @click="remove(type.id)"
                  class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition"
                >
                  Delete
                </button>
              </td>
            </tr>
            <tr v-if="types.length === 0">
              <td colspan="3" class="text-center py-4 text-gray-500">No account types found.</td>
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
  getAccountTypes,
  createAccountType,
  updateAccountType,
  deleteAccountType
} from "@/services/adminApi";

const types = ref([]);
const form = ref({ account_name: "", description: "" });
const editId = ref(null);

const load = async () => {
  const res = await getAccountTypes();
  types.value = res.data;
};

onMounted(load);

const save = async () => {
  if (editId.value) {
    await updateAccountType(editId.value, form.value);
  } else {
    await createAccountType(form.value);
  }
  reset();
  load();
};

const edit = (type) => {
  editId.value = type.id;
  form.value = { account_name: type.account_name, description: type.description };
};

const remove = async (id) => {
  if (confirm("Delete this type?")) {
    await deleteAccountType(id);
    load();
  }
};

const reset = () => {
  editId.value = null;
  form.value = { account_name: "", description: "" };
};
</script>
