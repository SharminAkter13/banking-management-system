import { createRouter, createWebHistory } from 'vue-router';
import api from '@/services/api';

// =======================================================
// 1. DEFINE AND INITIALIZE ROUTER
// =======================================================
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),

  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { left: 0, top: 0 };
  },

  routes: [
    {
      path: '/',
      name: 'Home',
      component: () => import('../views/portal_page/Home.vue'),
      meta: {
        title: 'Home',
        public: true,
      },
    },

    // ======================= Authentication Routes =======================
    {
      path: '/login',
      name: 'Login',
      component: () => import('../views/Auth/Login.vue'),
      meta: {
        title: 'Login',
        public: true,
      },
    },
    {
      path: '/register',
      name: 'Register',
      component: () => import('../views/Auth/Register.vue'),
      meta: {
        title: 'Register',
        public: true,
      },
    },

    // ======================= Role-Based Dashboards =======================
    // This abstract route allows the guard to catch "/dashboard" and redirect
    {
      path: '/dashboard',
      name: 'DashboardRedirect',
      meta: { requiresAuth: true },
    },
    {
      path: '/admin/dashboard',
      component: () => import('@/views/dashboard/AdminDash.vue'),
      meta: { title: 'Admin Dashboard', requiresAuth: true, roles: ['admin'] },
    },
    {
      path: '/manager/dashboard',
      component: () => import('@/views/dashboard/ManagerDash.vue'),
      meta: { title: 'Manager Dashboard', requiresAuth: true, roles: ['branch-manager'] },
    },
    {
      path: '/loan-officer/dashboard',
      component: () => import('@/views/dashboard/OfficerDash.vue'),
      meta: { title: 'Officer Dashboard', requiresAuth: true, roles: ['loan-officer'] },
    },
    {
      path: '/teller/dashboard',
      component: () => import('@/views/dashboard/TellerDash.vue'),
      meta: { title: 'Teller Dashboard', requiresAuth: true, roles: ['bank-teller'] },
    },
    {
      path: '/customer/dashboard',
      component: () => import('@/views/dashboard/CustomerDash.vue'),
      meta: { title: 'Customer Dashboard', requiresAuth: true, roles: ['customer'] },
    },

    // ======================= Error Routes =======================
    {
      path: '/error-404',
      name: '404 Error',
      component: () => import('../views/Errors/FourZeroFour.vue'),
      meta: {
        title: '404 Error',
        public: true, // Fix: Allows the 404 page to show even if not logged in
      },
    },

    // ======================= Default Route =======================
    {
      path: '/:catchAll(.*)',
      redirect: '/error-404',
    }
  ],
});

// =======================================================
// 2. COMBINED GLOBAL GUARD (Auth, Roles, and Titles)
// =======================================================
router.beforeEach(async (to, from, next) => {
  // A. Set Document Title
  const pageTitle = to.meta.title ?? to.name ?? 'FinServe Bank';
  document.title = `FinServe Bank — ${pageTitle}`;

  const token = localStorage.getItem('token');

  // B. Handle Public Routes
  if (to.meta.public) {
    return next();
  }

  // C. Handle Protected Routes without Token
  if (!token) {
    return next('/login');
  }

  // D. Handle Role-Based Logic and Dashboard Redirection
  try {
    // Fetch user data
    const res = await api.get('/me');
    const role = res.data.role.slug;

    // Logic for redirecting generic "/dashboard" to role-specific URL
    if (to.path === '/dashboard') {
      const dashboardMap = {
        'admin': '/admin/dashboard',
        'branch-manager': '/manager/dashboard',
        'loan-officer': '/loan-officer/dashboard',
        'bank-teller': '/teller/dashboard',
        'customer': '/customer/dashboard'
      };
      
      const targetPath = dashboardMap[role];
      return targetPath ? next(targetPath) : next('/error-404');
    }

    // Check if the user's role is allowed on the specific route
    if (to.meta.roles && !to.meta.roles.includes(role)) {
      console.warn(`User role "${role}" is not authorized for this route.`);
      return next('/error-404');
    }

    // If all checks pass
    next();
  } catch (error) {
    console.error('Auth check failed:', error);
    localStorage.removeItem('token');
    return next('/login');
  }
});

export default router;