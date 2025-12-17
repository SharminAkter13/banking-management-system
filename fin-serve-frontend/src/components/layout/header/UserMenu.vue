<template>
  <div class="relative" ref="dropdownRef">
    <!-- User Button -->
    <button
      class="flex items-center text-gray-700 dark:text-gray-400"
      @click.prevent="toggleDropdown"
    >
      <span class="mr-3 overflow-hidden rounded-full h-11 w-11">
        <img :src="user.avatar || '/images/user/owner.jpg'" alt="User" />
      </span>

      <span class="block mr-1 font-medium text-theme-sm">{{ user.name }}</span>

      <ChevronDownIcon :class="{ 'rotate-180': dropdownOpen }" />
    </button>

    <!-- Dropdown Menu -->
    <div
      v-if="dropdownOpen"
      class="absolute right-0 mt-[17px] flex w-[260px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark"
    >
      <!-- User Info -->
      <div>
        <span class="block font-medium text-gray-700 text-theme-sm dark:text-gray-400">
          {{ user.name }}
        </span>
        <span class="mt-0.5 block text-theme-xs text-gray-500 dark:text-gray-400">
          {{ user.email }}
        </span>
      </div>

      <!-- Menu Items -->
      <ul class="flex flex-col gap-1 pt-4 pb-3 border-b border-gray-200 dark:border-gray-800">
        <li v-for="item in filteredMenuItems" :key="item.href">
          <router-link
            :to="item.href"
            class="flex items-center gap-3 px-3 py-2 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
          >
            <component
              :is="item.icon"
              class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300"
            />
            {{ item.text }}
          </router-link>
        </li>
      </ul>

      <!-- Dashboard Link -->
      <router-link
        :to="dashboardRoute"
        class="flex items-center gap-3 px-3 py-2 mt-3 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
      >
        <UserCircleIcon class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300" />
        Dashboard
      </router-link>

      <!-- Logout -->
      <button
        @click="signOut"
        class="flex items-center gap-3 px-3 py-2 mt-1 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300 w-full text-left"
      >
        <LogoutIcon class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300" />
        Sign out
      </button>
    </div>
  </div>
</template>

<script setup>
import {
  UserCircleIcon,
  ChevronDownIcon,
  LogoutIcon,
  SettingsIcon,
  InfoCircleIcon
} from '@/icons'
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

// Dropdown state
const dropdownOpen = ref(false)
const dropdownRef = ref(null)
const router = useRouter()

// User state
const user = ref({
  name: 'Loading...',
  email: '',
  role: '',
  avatar: ''
})

// Fetch logged-in user data
const fetchUser = async () => {
  try {
    const res = await api.get('/me')
    user.value = res.data
    localStorage.setItem('role', user.value.role.slug) // optional
  } catch (error) {
    console.error('Failed to fetch user data:', error)
    router.push('/login')
  }
}
fetchUser()

// Menu items
const menuItems = [
  { href: '/profile', icon: UserCircleIcon, text: 'Profile' },
  { href: '/settings', icon: SettingsIcon, text: 'Settings' },
  { href: '/support', icon: InfoCircleIcon, text: 'Support' }
]

// Filter only valid menu items (those that exist in routes)
const filteredMenuItems = computed(() =>
  menuItems.filter(item => router.resolve(item.href).matched.length)
)

// Compute dashboard link based on role
const dashboardRoute = computed(() => {
  switch (user.value.role.slug) {
    case 'admin': return '/admin/dashboard'
    case 'branch-manager': return '/branch-manager/dashboard'
    case 'loan-officer': return '/loan-officer/dashboard'
    case 'bank-teller': return '/teller/dashboard'
    case 'customer': return '/customer/dashboard'
    default: return '/login'
  }
})

// Dropdown functions
const toggleDropdown = () => { dropdownOpen.value = !dropdownOpen.value }
const closeDropdown = () => { dropdownOpen.value = false }

// Click outside to close dropdown
const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    closeDropdown()
  }
}
onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))

// Logout
const signOut = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('role')
  router.push('/login')
  closeDropdown()
}
</script>
