<script setup>
import { ref } from 'vue';
import api from '@/services/api';
import { useRouter } from 'vue-router';

const router = useRouter();

const form = ref({
  email: '',
  password: ''
});

const loading = ref(false);
const error = ref('');

const login = async () => {
  error.value = '';
  loading.value = true;

  try {
    const res = await api.post('/login', form.value);

    localStorage.setItem('token', res.data.token);
    localStorage.setItem('user', JSON.stringify(res.data.user));

    // 🔐 Role-based redirect
    const role = res.data.user.role.slug;

    if (role === 'admin') router.push('/admin/dashboard');
    else if (role === 'manager') router.push('/manager/dashboard');
    else router.push('/dashboard');

  } catch (err) {
    error.value = err.response?.data?.message || 'Login failed';
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow">
      <h2 class="text-2xl font-bold text-center mb-6">Banking Login</h2>

      <form @submit.prevent="login" class="space-y-4">
        <input
          v-model="form.email"
          type="email"
          placeholder="Email"
          class="w-full px-4 py-2 border rounded-lg focus:ring"
        />

        <input
          v-model="form.password"
          type="password"
          placeholder="Password"
          class="w-full px-4 py-2 border rounded-lg focus:ring"
        />

        <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>

        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700"
        >
          {{ loading ? 'Logging in...' : 'Login' }}
        </button>
      </form>
    </div>
  </div>
</template>
