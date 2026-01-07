<template>
  <aside
    :class="[ 
      'fixed mt-16 flex flex-col lg:mt-0 top-0 px-5 left-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-99999 border-r border-gray-200', 
      { 
        'lg:w-[290px]': isExpanded || isMobileOpen || isHovered,
        'lg:w-[90px]': !isExpanded && !isHovered,
        'translate-x-0 w-[290px]': isMobileOpen,
        '-translate-x-full': !isMobileOpen,
        'lg:translate-x-0': true,
      },
    ]"
    @mouseenter="!isExpanded && (isHovered = true)"
    @mouseleave="isHovered = false"
  >
    <div :class="['py-8 flex', !isExpanded && !isHovered ? 'lg:justify-center' : 'justify-start']">
      <router-link to="/">
        <img v-if="isExpanded || isHovered || isMobileOpen" class="dark:hidden" src="/images/logo/logo.svg" alt="Logo" width="150" height="40" />
        <img v-if="isExpanded || isHovered || isMobileOpen" class="hidden dark:block" src="/images/logo/logo-dark.svg" alt="Logo" width="150" height="40" />
        <img v-else src="/images/logo/logo-icon.svg" alt="Logo" width="32" height="32" />
      </router-link>
    </div>

    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
      <nav class="mb-6">
        <div class="flex flex-col gap-4">
          <div v-for="(menuGroup, groupIndex) in filteredMenuGroups" :key="groupIndex">
            <h2 :class="['mb-4 text-xs uppercase flex leading-[20px] text-gray-400', !isExpanded && !isHovered ? 'lg:justify-center' : 'justify-start']">
              <template v-if="isExpanded || isHovered || isMobileOpen">
                {{ menuGroup.title }}
              </template>
              <HorizontalDots v-else />
            </h2>
            <ul class="flex flex-col gap-4">
              <li v-for="(item, index) in menuGroup.items" :key="item.name">
                <router-link
                  v-if="item.path"
                  :to="item.path"
                  :class="['menu-item group', { 'menu-item-active': isActive(item.path), 'menu-item-inactive': !isActive(item.path) }]"
                >
                  <span :class="[isActive(item.path) ? 'menu-item-icon-active' : 'menu-item-icon-inactive']">
                    <component :is="item.icon" />
                  </span>
                  <span v-if="isExpanded || isHovered || isMobileOpen" class="menu-item-text">{{ item.name }}</span>
                </router-link>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </aside>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useSidebar } from "@/composables/useSidebar";
import api from "@/services/api";
import { 
  GridIcon,
  UserCircleIcon,
  HorizontalDots
} from "../../icons";

const route = useRoute();
const { isExpanded, isMobileOpen, isHovered } = useSidebar();
const userRole = ref("");

// Fetch role once on mount to ensure menu items filter correctly
onMounted(async () => {
  const token = localStorage.getItem('token');
  if (token) {
    try {
      const response = await api.get('/me');
      userRole.value = response.data.role.slug; // Assuming slug is 'admin', 'branch-manager', etc.
    } catch (error) {
      userRole.value = "";
    }
  }
});

const getDashboardPath = computed(() => {
  const pathMap = {
    'admin': '/admin/dashboard',
    'branch-manager': '/manager/dashboard',
    'loan-officer': '/officer/dashboard',
    'bank-teller': '/teller/dashboard',
    'customer': '/customer/dashboard'
  }
  return pathMap[userRole.value] || '/dashboard';
});

const menuGroups = computed(() => [
  {
    title: "Menu",
    items: [
      {
        icon: GridIcon,
        name: "Dashboard",
        path: getDashboardPath.value,
      },
      {
        icon: UserCircleIcon,
        name: "Users",
        path: "/admin/users",
        roles: ['admin']
      },
      {
        icon: UserCircleIcon,
        name: "Customers",
        path: "/admin/customers",
        roles: ['admin', 'branch-manager']
      },
      {
        icon: UserCircleIcon,
        name: "Branches",
        path: "/admin/branches",
        roles: ['admin', 'branch-manager']
      },
      {
        icon: UserCircleIcon,
        name: "Employee",
        path: "/admin/employees",
        roles: ['admin', 'branch-manager']
      },
      {
        icon: UserCircleIcon,
        name: "Loans",
        path: "/admin/loans",
        roles: ['admin', 'branch-manager','loan-officer'] // Adding loan management to the sidebar
      },
       {
        icon: UserCircleIcon,
        name: "Loan Types",
        path: "/admin/loan-types",
        roles: ['admin', 'branch-manager','loan-officer'] // Adding loan management to the sidebar
      },
      {
        icon: UserCircleIcon,
        name: "Loan Payments",
        path: "/customer/loan-payments",
        roles: ['admin', 'branch-manager','loan-officer','customer'] // Adding loan management to the sidebar
      },
      {
  icon: UserCircleIcon, 
  name: 'Transactions', 
  path: '/admin/transactions',
  roles: ['admin','bank-teller','branch-manager']
},
{
  icon: UserCircleIcon,
  name: "Installments",
  path: "/loan/installments",
  roles: ['admin','branch-manager','loan-officer','customer']
},
 {
        icon: UserCircleIcon,
        name: "Interest Rates",
        path: "/admin/interest-rates",
        roles: ['admin', 'branch-manager']  // Only admin & branch manager can see
      },

    ],
  },
]);

const isActive = (path) => route.path === path;

const filteredMenuGroups = computed(() => {
  return menuGroups.value.map(group => {
    const filteredItems = group.items.filter(item => {
      if (!item.roles) return true;
      return item.roles.includes(userRole.value);
    });
    return { ...group, items: filteredItems };
  }).filter(group => group.items.length > 0);
});
</script>
