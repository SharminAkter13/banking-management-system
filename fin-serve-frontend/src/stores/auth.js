import { defineStore } from 'pinia'
import api from '@/services/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) || null,
    token: localStorage.getItem('token') || null,
  }),

  getters: {
    // Get user role
   role: (state) => state.user?.role?.slug || null,
    // Check if user is logged in
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    // Fetch current user from API
    async fetchUser() {
      try {
        const response = await api.get('/me') // your /me endpoint
        this.user = response.data
        localStorage.setItem('user', JSON.stringify(this.user))
      } catch (error) {
        this.user = null
        this.token = null
        localStorage.removeItem('user')
        localStorage.removeItem('token')
        console.error('Failed to fetch user:', error)
      }
    },

    // Save user and token on login
    login(data) {
      this.user = data.user
      this.token = data.token
      localStorage.setItem('token', data.token)
      localStorage.setItem('user', JSON.stringify(data.user))
    },

    // Logout user
    logout() {
      this.user = null
      this.token = null
      localStorage.removeItem('user')
      localStorage.removeItem('token')
    },
  },
})
