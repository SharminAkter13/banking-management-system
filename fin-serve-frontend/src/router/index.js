import { createRouter, createWebHistory } from 'vue-router'
import api from '@/services/api'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior() {
    return { top: 0 }
  },
  routes: [
    // ================= Public =================
    {
      path: '/',
      name: 'Home',
      component: () => import('@/views/portal_page/Home.vue'),
      meta: { title: 'Home', public: true }
    },
    {
      path: '/login',
      name: 'Login',
      component: () => import('@/views/Auth/Login.vue'),
      meta: { title: 'Login', public: true }
    },
    {
      path: '/register',
      name: 'Register',
      component: () => import('@/views/Auth/Register.vue'),
      meta: { title: 'Register', public: true }
    },

    // ================= Dashboard Redirect Logic =================
    {
      path: '/dashboard',
      name: 'DashboardRoot',
      beforeEnter: async (to, from, next) => {
        const token = localStorage.getItem('token')
        if (!token) return next('/login')

        try {
          const { data } = await api.get('/me')
          const role = data.role.slug

          const dashboardMap = {
            'admin': '/admin/dashboard',
            'branch-manager': '/manager/dashboard',
            'loan-officer': '/officer/dashboard',
            'bank-teller': '/teller/dashboard',
            'customer': '/customer/dashboard'
          }

          next(dashboardMap[role] || '/error-404')
        } catch (e) {
          next('/login')
        }
      }
    },

    // ================= Role Dashboards =================
    {
      path: '/admin/dashboard',
      component: () => import('@/views/dashboard/AdminDash.vue'),
      meta: { title: 'Admin Dashboard', requiresAuth: true, roles: ['admin'] }
    },
    {
      path: '/manager/dashboard',
      component: () => import('@/views/dashboard/ManagerDash.vue'),
      meta: { title: 'Manager Dashboard', requiresAuth: true, roles: ['branch-manager'] }
    },
    {
      path: '/officer/dashboard',
      component: () => import('@/views/dashboard/OfficerDash.vue'),
      meta: { title: 'Officer Dashboard', requiresAuth: true, roles: ['loan-officer'] }
    },
    {
      path: '/teller/dashboard',
      component: () => import('@/views/dashboard/TellerDash.vue'),
      meta: { title: 'Teller Dashboard', requiresAuth: true, roles: ['bank-teller'] }
    },
    {
      path: '/customer/dashboard',
      component: () => import('@/views/dashboard/CustomerDash.vue'),
      meta: { title: 'Customer Dashboard', requiresAuth: true, roles: ['customer'] }
    },

    // ======================= User Management =======================
    {
      path: '/admin/users',
      name: 'UserList',
      component: () => import('@/views/users/UsersList.vue'),
      meta: { title: 'Users', requiresAuth: true, roles: ['admin'] }
    },
    {
      path: '/admin/users/create',
      name: 'UserCreate',
      component: () => import('@/views/users/UserForm.vue'),
      meta: { title: 'Create User', requiresAuth: true, roles: ['admin'] }
    },
    {
      path: '/admin/users/:id/edit',
      name: 'UserEdit',
      component: () => import('@/views/users/UserForm.vue'),
      meta: { title: 'Edit User', requiresAuth: true, roles: ['admin'] }
    },

    // ================= Customer Management =================
    {
      path: '/admin/customers',
      name: 'CustomerList',
      component: () => import('@/views/customers/CustomerList.vue'),
      meta: { title: 'Customer List', requiresAuth: true, roles: ['admin'] }
    },
    {
      path: '/admin/customers/create',
      name: 'CustomerCreate',
      component: () => import('@/views/customers/CustomerForm.vue'),
      meta: { title: 'Create Customer', requiresAuth: true, roles: ['admin'] }
    },
    {
      path: '/admin/customers/:id/edit',
      name: 'CustomerEdit',
      component: () => import('@/views/customers/CustomerForm.vue'),
      meta: { title: 'Edit Customer', requiresAuth: true, roles: ['admin'] }
    },

    // ================= Branch Management =================
    {
      path: '/admin/branches',
      name: 'BranchList',
      component: () => import('@/views/branches/BranchList.vue'),
      meta: { title: 'Branches', requiresAuth: true, roles: ['admin', 'branch-manager'] }
    },
    {
      path: '/admin/branches/create',
      name: 'BranchCreate',
      component: () => import('@/views/branches/BranchForm.vue'),
      meta: { title: 'Create Branch', requiresAuth: true, roles: ['admin', 'branch-manager'] }
    },
    {
      path: '/admin/branches/:id/edit',
      name: 'BranchEdit',
      component: () => import('@/views/branches/BranchForm.vue'),
      meta: { title: 'Edit Branch', requiresAuth: true, roles: ['admin', 'branch-manager'] }
    },

    // ================= Loan Management =================
    {
      path: '/admin/loans',
      name: 'LoanList',
      component: () => import('@/views/loans/LoanList.vue'),
      meta: { title: 'Loans', requiresAuth: true, roles: ['admin', 'branch-manager','loan-officer'] }
    },
    {
      path: '/admin/loans/create',
      name: 'LoanCreate',
      component: () => import('@/views/loans/LoanForm.vue'),
      meta: { title: 'Create Loan', requiresAuth: true, roles: ['admin', 'branch-manager','loan-officer'] }
    },
    {
      path: '/admin/loans/:id/edit',
      name: 'LoanEdit',
      component: () => import('@/views/loans/LoanForm.vue'),
      meta: { title: 'Edit Loan', requiresAuth: true, roles: ['admin', 'branch-manager','loan-officer'] }
    },

    // ================= Errors =================
    {
      path: '/error-404',
      component: () => import('@/views/Errors/FourZeroFour.vue'),
      meta: { title: '404', public: true }
    },
    {
      path: '/:catchAll(.*)',
      redirect: '/error-404'
    }
  ]
})

// Global navigation guard to handle authorization and role-based access
router.beforeEach(async (to, from, next) => {
  document.title = `FinServe Bank — ${to.meta.title || 'App'}`

  const token = localStorage.getItem('token')

  // Public routes
  if (to.meta.public) return next()

  // Auth required
  if (!token) return next('/login')

  try {
    const { data } = await api.get('/me')
    const role = data.role.slug

    // Redirect based on role
    if (to.meta.roles && !to.meta.roles.includes(role)) {
      return next('/error-404')
    }

    next()
  } catch (e) {
    localStorage.removeItem('token')
    next('/login')
  }
})

export default router
