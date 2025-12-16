<script setup>
import { ref } from 'vue';
import api from '@/services/api';
import { useRouter } from 'vue-router';

const router = useRouter();

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role_id: ''
});

const roles = ref([
  { id: 1, name: 'Admin' },
  { id: 2, name: 'Manager' },
  { id: 3, name: 'Customer' }
]);

const loading = ref(false);
const message = ref('');
const error = ref('');

const register = async () => {
  loading.value = true;
  error.value = '';
  message.value = '';

  try {
    await api.post('/register', form.value);
    message.value = 'Registration successful. Awaiting approval.';
    setTimeout(() => router.push('/login'), 2000);
  } catch (err) {
    error.value = err.response?.data?.message || 'Registration failed';
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow">
      <h2 class="text-2xl font-bold text-center mb-6">Create Account</h2>

      <form @submit.prevent="register" class="space-y-4">
        <input v-model="form.name" type="text" placeholder="Full Name"
          class="w-full px-4 py-2 border rounded-lg" />

        <input v-model="form.email" type="email" placeholder="Email"
          class="w-full px-4 py-2 border rounded-lg" />

        <select v-model="form.role_id"
          class="w-full px-4 py-2 border rounded-lg">
          <option value="">Select Role</option>
          <option v-for="role in roles" :key="role.id" :value="role.id">
            {{ role.name }}
          </option>
        </select>

        <input v-model="form.password" type="password" placeholder="Password"
          class="w-full px-4 py-2 border rounded-lg" />

        <input v-model="form.password_confirmation" type="password"
          placeholder="Confirm Password"
          class="w-full px-4 py-2 border rounded-lg" />

        <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
        <p v-if="message" class="text-green-600 text-sm">{{ message }}</p>

        <button type="submit"
          class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
          {{ loading ? 'Registering...' : 'Register' }}
        </button>
      </form>
    </div>
  </div>
</template>
