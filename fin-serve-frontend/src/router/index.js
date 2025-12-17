import { createRouter, createWebHistory } from 'vue-router'
import api from '@/services/api';

// =======================================================
// 1. DEFINE AND INITIALIZE ROUTER (Fixes the ReferenceError)
// =======================================================
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),

  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { left: 0, top: 0 }
  },
  
  routes: [

    // ============= PORTAL (ROOT PAGE) =============
    {
      path: '/',
      name: 'Home',
      component: () => import('../views/portal_page/Home.vue'),
      meta: {
        title: 'Home',
        public: true,
      },
    },

    // ============= AUTH (PUBLIC) =============
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

    // ============= DASHBOARD =============
    {
      path: '/finserve-bank',
      name: 'FinServeDashboard',
      component: () => import('../views/Ecommerce.vue'),
      meta: {
        title: 'Dashboard',
      },
    },

    // Role-Based Dashboards
    {
      path: '/admin/dashboard',
      component: () => import('@/views/dashboard/AdminDash.vue'),
      meta: { requiresAuth: true, roles: ['admin'] },
    },
    {
      path: '/branch-manager/dashboard',
      component: () => import('@/views/dashboard/ManagerDash.vue'),
      meta: { requiresAuth: true, roles: ['branch-manager'] },
    },
    {
      path: '/loan-officer/dashboard',
      component: () => import('@/views/dashboard/OfficerDash.vue'),
      meta: { requiresAuth: true, roles: ['loan-officer'] },
    },
    {
      path: '/teller/dashboard',
      component: () => import('@/views/dashboard/TellerDash.vue'),
      meta: { requiresAuth: true, roles: ['bank-teller'] },
    },
    {
      path: '/customer/dashboard',
      component: () => import('@/views/dashboard/CustomerDash.vue'),
      meta: { requiresAuth: true, roles: ['customer'] },
    },


    // ============= CALENDAR =============
    {
      path: '/calendar',
      name: 'Calendar',
      component: () => import('../views/Others/Calendar.vue'),
      meta: {
        title: 'Calendar',
      },
    },

    // ============= PROFILE =============
    {
      path: '/profile',
      name: 'Profile',
      component: () => import('../views/Others/UserProfile.vue'),
      meta: {
        title: 'Profile',
      },
    },

    // ============= FORMS =============
    {
      path: '/form-elements',
      name: 'Form Elements',
      component: () => import('../views/Forms/FormElements.vue'),
      meta: {
        title: 'Form Elements',
      },
    },

    // ============= TABLES =============
    {
      path: '/basic-tables',
      name: 'Basic Tables',
      component: () => import('../views/Tables/BasicTables.vue'),
      meta: {
        title: 'Basic Tables',
      },
    },

    // ============= CHARTS =============
    {
      path: '/line-chart',
      name: 'Line Chart',
      component: () => import('../views/Chart/LineChart/LineChart.vue'),
      meta: {
        title: 'Line Chart',
      },
    },
    {
      path: '/bar-chart',
      name: 'Bar Chart',
      component: () => import('../views/Chart/BarChart/BarChart.vue'),
      meta: {
        title: 'Bar Chart',
      },
    },

    // ============= UI ELEMENTS =============
    {
      path: '/alerts',
      name: 'Alerts',
      component: () => import('../views/UiElements/Alerts.vue'),
      meta: {
        title: 'Alerts',
      },
    },
    {
      path: '/avatars',
      name: 'Avatars',
      component: () => import('../views/UiElements/Avatars.vue'),
      meta: {
        title: 'Avatars',
      },
    },
    {
      path: '/badge',
      name: 'Badge',
      component: () => import('../views/UiElements/Badges.vue'),
      meta: {
        title: 'Badge',
      },
    },
    {
      path: '/buttons',
      name: 'Buttons',
      component: () => import('../views/UiElements/Buttons.vue'),
      meta: {
        title: 'Buttons',
      },
    },
    {
      path: '/images',
      name: 'Images',
      component: () => import('../views/UiElements/Images.vue'),
      meta: {
        title: 'Images',
      },
    },
    {
      path: '/videos',
      name: 'Videos',
      component: () => import('../views/UiElements/Videos.vue'),
      meta: {
        title: 'Videos',
      },
    },

    // ============= PAGES =============
    {
      path: '/blank',
      name: 'Blank',
      component: () => import('../views/Pages/BlankPage.vue'),
      meta: {
        title: 'Blank Page',
      },
    },

    // ============= ERRORS =============
    {
      path: '/error-404',
      name: '404 Error',
      component: () => import('../views/Errors/FourZeroFour.vue'),
      meta: {
        title: '404 Error',
      },
    },

    // ============= AUTH =============
    {
      path: '/signin',
      name: 'Signin',
      component: () => import('../views/Auth/Signin.vue'),
      meta: {
        title: 'Signin',
      },
    },
    {
      path: '/signup',
      name: 'Signup',
      component: () => import('../views/Auth/Signup.vue'),
      meta: {
        title: 'Signup',
      },
    },
  ],
})


// =======================================================
// 2. AUTHENTICATION AND ROLE CHECK GLOBAL GUARD
// =======================================================
router.beforeEach(async (to, from, next) => {
  const token = localStorage.getItem('token');

  // Check if the route is protected AND the user is not logged in
  if (!to.meta.public && !token) {
    return next('/login');
  }

  // Check for required roles if defined on the route
  if (to.meta.roles && token) {
    try {
      // Fetch user data/role
      const res = await api.get('/me');
      const role = res.data.role.slug;

      // Check if the user's role is included in the allowed roles for this route
      if (!to.meta.roles.includes(role)) {
        return next('/error-404'); // Forbidden access
      }
    } catch (error) {
      // Handle API errors (e.g., token expired, invalid)
      console.error('Error fetching user data for role check:', error);
      localStorage.removeItem('token'); // Clear token if validation failed
      return next('/login'); // Redirect to login
    }
  }

  // Continue to the route
  next();
});

// =======================================================
// 3. PAGE TITLE HANDLER GLOBAL GUARD
// =======================================================
router.beforeEach((to, from, next) => {
  const pageTitle = to.meta.title ?? to.name ?? 'FinServe Bank'
  document.title = `FinServe Bank — ${pageTitle}`
  next()
})

// =======================================================
// 4. EXPORT ROUTER
// =======================================================
export default router