import { defineStore } from 'pinia'
import api from '@/services/api' 

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token'),
  }),

  getters: {
    role: (state) => state.user?.role?.slug,
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    // 1. Corrected 'sync' to 'async'
    async fetchUser() {
      try {
        const response = await api.get('/me') 
        this.user = response.data
      } catch (error) {
        this.user = null
        // If 401, you might want to clear token: localStorage.removeItem('token')
        console.error("Failed to fetch user:", error)
      }
    }, // 2. Added comma here

    login(data) {
      this.user = data.user
      this.token = data.token
      localStorage.setItem('token', data.token)
    },

    logout() {
      this.user = null
      this.token = null
      localStorage.removeItem('token')
    },
  },
})