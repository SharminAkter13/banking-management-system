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

// ================= Account Management =================
{
      path: '/admin/types',
      name: 'AccountTypeManager',
      component: () => import('@/views/accounts/AccountTypeManager.vue'),
      meta: { title: 'Account Types', requiresAuth: true, roles: ['admin'] }
    },
    {
      path: '/admin/status',
      name: 'AccountStatusManager',
      component: () => import('@/views/accounts/AccountStatusManager.vue'),
      meta: { title: 'Account Status', requiresAuth: true, roles: ['admin'] }
    },
     {
      path: '/accounts',
      name: 'AccountManager',
      component: () => import('@/views/accounts/AccountManager.vue'),
      meta: { title: 'Accounts', requiresAuth: true, roles: ['admin', 'branch-manager','loan-officer','bank-teller','customer'] }
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
      meta: { title: 'Create Branch', requiresAuth: true, roles: ['admin'] }
    },
    {
      path: '/admin/branches/:id/edit',
      name: 'BranchEdit',
      component: () => import('@/views/branches/BranchForm.vue'),
      meta: { title: 'Edit Branch', requiresAuth: true, roles: ['admin', 'branch-manager'] }
    },

// ================= Employee Management =================
    {
      path: '/admin/employees',
      name: 'EmployeeList',
      component: () => import('@/views/employees/EmployeeList.vue'),
      meta: { title: 'Employee List', requiresAuth: true, roles: ['admin', 'branch-manager'] }
    },
    {
      path: '/admin/employees/create',
      name: 'EmployeeCreate',
      component: () => import('@/views/employees/EmployeeCreate.vue'),
      meta: { title: 'Add Employee', requiresAuth: true, roles: ['admin', 'branch-manager'] }
    },
    {
      path: '/admin/employees/:id', // FIXED: Removed /edit for viewing
      name: 'EmployeeView',
      component: () => import('@/views/employees/EmployeeView.vue'),
      meta: { title: 'View Employee', requiresAuth: true, roles: ['admin', 'branch-manager'] }
    },
    {
      path: '/admin/employees/:id/edit',
      name: 'EmployeeEdit',
      component: () => import('@/views/employees/EmployeeEdit.vue'),
      meta: { title: 'Edit Employee', requiresAuth: true, roles: ['admin', 'branch-manager'] }
    },


    // ================= Loan Management =================
    {
      path: '/admin/loans',
      name: 'LoanList',
      component: () => import('@/views/loans/LoanList.vue'),
      meta: { title: 'Loans', requiresAuth: true, roles: ['admin', 'branch-manager','loan-officer','customer'] }
    },
    
    {
      path: '/admin/loans/create',
      name: 'LoanCreate',
      component: () => import('@/views/loans/LoanForm.vue'),
      meta: { title: 'Create Loan', requiresAuth: true, roles: ['admin', 'branch-manager','loan-officer','customer'] }
    },
    {
      path: '/admin/loans/:id/edit',
      name: 'LoanEdit',
      component: () => import('@/views/loans/LoanForm.vue'),
      meta: { title: 'Edit Loan', requiresAuth: true, roles: ['admin', 'branch-manager','loan-officer','customer'] }
    },

     {
      path: '/admin/loan-types',
      name: 'LoanType',
      component: () => import('@/views/loans/LoanType.vue'),
      meta: { title: 'Loan-types', requiresAuth: true, roles: ['admin', 'branch-manager','loan-officer'] }
    },
     {
      path: '/customer/loan-payments',
      name: 'LoanPayment',
      component: () => import('@/views/loans/LoanPayment.vue'),
      meta: { title: 'Loan-payments', requiresAuth: true, roles: ['admin', 'branch-manager','loan-officer','customer'] }
    },
    {
  path: '/admin/transactions',
  component: () => import('@/views/transactions/TransactionList.vue'),
  meta: { requiresAuth: true, roles: ['admin','bank-teller','branch-manager','customer'] }
},
{
  path: '/admin/transactions/create',
  component: () => import('@/views/transactions/TransactionForm.vue'),
  meta: { requiresAuth: true, roles: ['admin','bank-teller','customer'] }
},
{
  path: '/admin/transactions/:id/edit',
  component: () => import('@/views/transactions/TransactionForm.vue'),
  meta: { requiresAuth: true, roles: ['admin','bank-teller'] }
},
     {
      path: '/loan/installments',
      name: 'InstallmentList',
      component: () => import('@/views/loans/InstallmentList.vue'),
      meta: { title: 'Installment', requiresAuth: true, roles: ['admin', 'branch-manager','loan-officer','customer'] }
    },

     {
      path: '/admin/interest-rates',
      name: 'InterestRatesList',
      component: () => import('@/views/interests/InterestRateList.vue'),
      meta: { title: 'InterestRates', requiresAuth: true, roles: ['admin', 'branch-manager',] }
    },
     {
      path: '/admin/interest-rates/create',
      name: 'InterestRateForm',
      component: () => import('@/views/interests/InterestRateForm.vue'),
      meta: { title: 'CreateInterestRate', requiresAuth: true, roles: ['admin', 'branch-manager',] }
    },
    {
      path: '/admin/approvals',
      name: 'ApprovalList',
      component: () => import('@/views/approvals/ApprovalList.vue'),
      meta: { title: 'ApprovalList', requiresAuth: true, roles: ['admin', 'branch-manager','loan-officer','bank-teller'] }
    },
    {
      path: '/admin/approvals/:id',
      name: 'ApprovalDetail',
      component: () => import('@/views/approvals/ApprovalDetail.vue'),
      meta: { title: 'ApprovalDetail', requiresAuth: true, roles: ['admin', 'branch-manager','loan-officer','bank-teller'] }
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
