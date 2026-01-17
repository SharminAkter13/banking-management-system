<template>
  <AdminLayout>
    <div class="p-6 max-w-6xl mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Accounts Management</h2>
        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
          Role: {{ auth.role }}
        </span>
      </div>

      <form
        v-if="auth.role === 'admin'"
        @submit.prevent="save"
        class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 bg-white p-4 rounded-lg shadow-sm border"
      >
        <div class="flex flex-col">
          <label class="text-sm font-medium mb-1">Customer</label>
          <select v-model="form.customer_id" class="border rounded px-3 py-2 w-full" required>
            <option value="" disabled>Select Customer</option>
            <option v-for="customer in customers" :key="customer.id" :value="customer.id">
              {{ customer.first_name }} {{ customer.last_name }}
            </option>
          </select>
        </div>

        <div class="flex flex-col">
          <label class="text-sm font-medium mb-1">Branch</label>
          <select v-model="form.branch_id" class="border rounded px-3 py-2 w-full" required>
            <option value="" disabled>Select Branch</option>
            <option v-for="branch in branches" :key="branch.id" :value="branch.id">
              {{ branch.branch_name }}
            </option>
          </select>
        </div>

        <div class="flex flex-col">
          <label class="text-sm font-medium mb-1">Account Type</label>
          <select v-model="form.account_type_id" class="border rounded px-3 py-2 w-full" required>
            <option value="" disabled>Select Type</option>
            <option v-for="type in accountTypes" :key="type.id" :value="type.id">
              {{ type.account_name }}
            </option>
          </select>
        </div>

        <div class="flex flex-col">
          <label class="text-sm font-medium mb-1">Status</label>
          <select v-model="form.account_status_id" class="border rounded px-3 py-2 w-full" required>
            <option value="" disabled>Select Status</option>
            <option v-for="status in accountStatuses" :key="status.id" :value="status.id">
              {{ status.name }}
            </option>
          </select>
        </div>

        <div class="flex flex-col">
          <label class="text-sm font-medium mb-1">Balance</label>
          <input type="number" v-model="form.balance" placeholder="0.00" class="border rounded px-3 py-2 w-full" step="0.01" />
        </div>

        <div class="flex flex-col">
          <label class="text-sm font-medium mb-1">Opened Date</label>
          <input type="date" v-model="form.opened_date" class="border rounded px-3 py-2 w-full" required />
        </div>

        <div class="flex flex-col">
          <label class="text-sm font-medium mb-1">Closed Date</label>
          <input type="date" v-model="form.closed_date" class="border rounded px-3 py-2 w-full" />
        </div>

        <div class="flex items-end">
          <button
            type="submit"
            class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
          >
            {{ editId ? 'Update Account' : 'Create Account' }}
          </button>
        </div>
      </form>

      <div class="overflow-x-auto border rounded-lg shadow bg-white">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Branch</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Balance</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dates</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="account in filteredAccounts" :key="account.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <div class="font-medium text-gray-900">{{ account.customer?.first_name }} {{ account.customer?.last_name }}</div>
                <div class="text-gray-500 text-xs">{{ account.customer?.email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ account.branch?.branch_name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ account.account_type?.account_name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="account.status?.name === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 rounded-full text-xs font-semibold">
                  {{ account.status?.name }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                ${{ parseFloat(account.balance).toLocaleString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                <div>Open: {{ account.opened_date }}</div>
                <div v-if="account.closed_date">Close: {{ account.closed_date }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                <button
                  @click="viewDetails(account)"
                  class="text-blue-600 hover:text-blue-900 font-medium"
                >
                  View
                </button>
                <button
                  v-if="auth.role === 'admin'"
                  @click="edit(account)"
                  class="text-yellow-600 hover:text-yellow-900 font-medium"
                >
                  Edit
                </button>
                <button
                  v-if="auth.role === 'admin'"
                  @click="remove(account.id)"
                  class="text-red-600 hover:text-red-900 font-medium"
                >
                  Delete
                </button>
                <span v-else class="text-gray-400 italic">View Only</span>
              </td>
            </tr>
            <tr v-if="filteredAccounts.length === 0">
              <td colspan="7" class="text-center py-10 text-gray-500 italic">
                No accounts found matching your role.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div v-if="isViewModalOpen" class="fixed inset-0 z-50 overflow-y-auto">
  <div class="fixed inset-0 bg-black opacity-50" @click="closeViewModal"></div>

  <div class="relative min-h-screen flex items-center justify-center p-4">
    <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full p-6">
      <div class="flex justify-between items-center border-b pb-3 mb-4">
        <h3 class="text-xl font-bold text-gray-900">Account Details</h3>
        <button @click="closeViewModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-2">
          <h4 class="font-bold text-blue-700 uppercase text-xs tracking-wider">Customer Information</h4>
          <p><span class="text-gray-500">Name:</span> {{ selectedAccount.customer?.first_name }} {{ selectedAccount.customer?.last_name }}</p>
          <p><span class="text-gray-500">Email:</span> {{ selectedAccount.customer?.email }}</p>
          <p><span class="text-gray-500">Phone:</span> {{ selectedAccount.customer?.phone }}</p>
          <p><span class="text-gray-500">ID Number:</span> {{ selectedAccount.customer?.id_number }}</p>
          <p><span class="text-gray-500">Address:</span> {{ selectedAccount.customer?.address }}</p>
        </div>

        <div class="space-y-2">
          <h4 class="font-bold text-blue-700 uppercase text-xs tracking-wider">Account Information</h4>
          <p><span class="text-gray-500">Type:</span> {{ selectedAccount.account_type?.account_name }}</p>
          <p><span class="text-gray-500">Status:</span> {{ selectedAccount.status?.name }}</p>
          <p><span class="text-gray-500">Current Balance:</span> ${{ parseFloat(selectedAccount.balance).toLocaleString() }}</p>
          <p><span class="text-gray-500">Opened On:</span> {{ selectedAccount.opened_date }}</p>
          <p v-if="selectedAccount.closed_date"><span class="text-gray-500">Closed On:</span> {{ selectedAccount.closed_date }}</p>
        </div>

        <div class="md:col-span-2 space-y-2 border-t pt-4">
          <h4 class="font-bold text-blue-700 uppercase text-xs tracking-wider">Branch Details</h4>
          <div class="grid grid-cols-2">
            <p><span class="text-gray-500">Branch Name:</span> {{ selectedAccount.branch?.branch_name }}</p>
            <p><span class="text-gray-500">Location:</span> {{ selectedAccount.branch?.location }}</p>
          </div>
        </div>
      </div>

      <div class="mt-8 flex justify-end">
        <button 
          @click="closeViewModal"
          class="bg-gray-200 text-gray-800 px-6 py-2 rounded hover:bg-gray-300 transition"
        >
          Close
        </button>
      </div>
    </div>
  </div>
</div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import AdminLayout from '@/components/layout/AdminLayout.vue';
import { useAuthStore } from "@/stores/auth";
import {
  getAccounts,
  getAccountTypes,
  getAccountStatuses
} from "@/services/adminApi";
import api from "@/services/api";

const auth = useAuthStore();
const accounts = ref([]);
const customers = ref([]);
const branches = ref([]);
const accountTypes = ref([]);
const accountStatuses = ref([]);
const editId = ref(null);

const form = ref({
  customer_id: "",
  branch_id: "",
  account_type_id: "",
  account_status_id: "",
  balance: null,
  opened_date: "",
  closed_date: ""
});

const load = async () => {
  try {
    const [accRes, typeRes, statusRes, custRes, branchRes] = await Promise.all([
      getAccounts(),
      getAccountTypes(),
      getAccountStatuses(),
      api.get("/customers"),
      api.get("/branches")
    ]);

    accounts.value = accRes.data;
    accountTypes.value = typeRes.data;
    accountStatuses.value = statusRes.data;
    customers.value = custRes.data;
    branches.value = branchRes.data;
  } catch (error) {
    console.error("Critical error loading data:", error);
  }
};

onMounted(async () => {
  if (!auth.isAuthenticated) {
    window.location.href = "/login";
    return;
  }

  if (!auth.user) {
    await auth.fetchUser();
  }

  await load();
});

const filteredAccounts = computed(() => {
  if (!accounts.value || accounts.value.length === 0) return [];
  
  const role = auth.role;
  const user = auth.user;

  if (role === "admin" || role === "loan-officer") return accounts.value;
  
  if (role === "customer") {
    return accounts.value.filter(acc => acc.customer_id === user?.id);
  }
  
  // Use hyphenated slugs to match RoleSeeder/Laravel logic
  if (role === "branch-manager" || role === "bank-teller") { 
    return accounts.value.filter(acc => acc.branch_id === user?.branch_id);
  }
  
  return [];
});

const save = async () => {
  try {
    if (editId.value) {
      await api.put(`/accounts/${editId.value}`, form.value);
    } else {
      await api.post("/accounts", form.value);
    }
    reset();
    await load();
  } catch (error) {
    console.error("Save error:", error);
    alert("Failed to save account.");
  }
};

const edit = (account) => {
  editId.value = account.id;
  form.value = {
    customer_id: account.customer_id,
    branch_id: account.branch_id,
    account_type_id: account.account_type_id,
    account_status_id: account.account_status_id,
    balance: account.balance,
    // HTML date inputs require YYYY-MM-DD format
    opened_date: account.opened_date ? account.opened_date.split(' ')[0] : "",
    closed_date: account.closed_date ? account.closed_date.split(' ')[0] : ""
  };
};

const remove = async (id) => {
  if (confirm("Are you sure you want to delete this account?")) {
    try {
      await api.delete(`/accounts/${id}`);
      await load();
    } catch (error) {
      console.error("Delete error:", error);
    }
  }
};

const reset = () => {
  editId.value = null;
  form.value = {
    customer_id: "",
    branch_id: "",
    account_type_id: "",
    account_status_id: "",
    balance: null,
    opened_date: "",
    closed_date: ""
  };
};

const isViewModalOpen = ref(false);
const selectedAccount = ref(null);

const viewDetails = (account) => {
  selectedAccount.value = account;
  isViewModalOpen.value = true;
};

const closeViewModal = () => {
  isViewModalOpen.value = false;
  selectedAccount.value = null;
};
</script>