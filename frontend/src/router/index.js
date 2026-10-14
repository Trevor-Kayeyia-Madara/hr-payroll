import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import Login from '../views/Login.vue'
import Dashboard from '../views/Dashboard.vue'
import Employees from '../views/Employees.vue'
import Departments from '../views/Departments.vue'
import Attendance from '../views/Attendance.vue'
import Leaves from '../views/Leaves.vue'
import Payroll from '../views/Payroll.vue'
import TaxRules from '../views/TaxRules.vue'
import PayslipSettings from '../views/PayslipSettings.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: Login,
      meta: { requiresAuth: false }
    },
    {
      path: '/',
      name: 'dashboard',
      component: Dashboard,
      meta: { requiresAuth: true, roles: ['admin', 'manager', 'employee'] }
    },
    {
      path: '/employees',
      name: 'employees',
      component: Employees,
      meta: { requiresAuth: true, roles: ['admin', 'manager'] }
    },
    {
      path: '/departments',
      name: 'departments',
      component: Departments,
      meta: { requiresAuth: true, roles: ['admin', 'manager'] }
    },
    {
      path: '/attendance',
      name: 'attendance',
      component: Attendance,
      meta: { requiresAuth: true, roles: ['admin', 'manager', 'employee'] }
    },
    {
      path: '/leaves',
      name: 'leaves',
      component: Leaves,
      meta: { requiresAuth: true, roles: ['admin', 'manager', 'employee'] }
    },
    {
      path: '/payroll',
      name: 'payroll',
      component: Payroll,
      meta: { requiresAuth: true, roles: ['admin', 'manager'] }
    },
    {
      path: '/tax-rules',
      name: 'tax-rules',
      component: TaxRules,
      meta: { requiresAuth: true, roles: ['admin'] }
    },
    {
      path: '/payslip-settings',
      name: 'payslip-settings',
      component: PayslipSettings,
      meta: { requiresAuth: true, roles: ['admin'] }
    }
  ]
})

router.beforeEach((to, from) => {
  const authStore = useAuthStore()

  if (to.meta.requiresAuth && !authStore.isAuthenticated()) {
    return '/login'
  } else if (to.path === '/login' && authStore.isAuthenticated()) {
    return '/'
  } else if (to.meta.roles && !authStore.hasRole(to.meta.roles)) {
    return '/'
  }
})

export default router
