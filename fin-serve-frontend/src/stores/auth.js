// src/stores/auth.js
import { defineStore } from 'pinia'

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
