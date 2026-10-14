<script setup>
import { useAuthStore } from './stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const handleLogout = () => {
  authStore.logout()
  router.push('/login')
}
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <aside v-if="authStore.isAuthenticated()" class="w-64 bg-white shadow-lg flex flex-col">
      <div class="p-6 border-b">
        <h1 class="text-xl font-bold text-gray-800">SecureGuard HR</h1>
        <p class="text-sm text-gray-500">{{ authStore.user?.name }}</p>
      </div>
      <nav class="p-4 space-y-2 flex-1">
        <router-link
          to="/"
          class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition"
          active-class="bg-blue-50 text-blue-600"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
          </svg>
          Dashboard
        </router-link>

        <router-link
          v-if="authStore.hasRole(['admin', 'manager'])"
          to="/employees"
          class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition"
          active-class="bg-blue-50 text-blue-600"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
          </svg>
          Employees
        </router-link>

        <router-link
          v-if="authStore.hasRole(['admin', 'manager'])"
          to="/departments"
          class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition"
          active-class="bg-blue-50 text-blue-600"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
          </svg>
          Departments
        </router-link>

        <router-link
          to="/attendance"
          class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition"
          active-class="bg-blue-50 text-blue-600"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Attendance
        </router-link>

        <router-link
          to="/leaves"
          class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition"
          active-class="bg-blue-50 text-blue-600"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
          Leaves
        </router-link>

        <router-link
          v-if="authStore.hasRole(['admin', 'manager'])"
          to="/payroll"
          class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition"
          active-class="bg-blue-50 text-blue-600"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Payroll
        </router-link>

        <router-link
          v-if="authStore.hasRole(['admin'])"
          to="/tax-rules"
          class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition"
          active-class="bg-blue-50 text-blue-600"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
          </svg>
          Tax Rules
        </router-link>
      </nav>
      <div class="p-4 border-t">
        <button
          @click="handleLogout"
          class="w-full flex items-center px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
          </svg>
          Logout
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-auto">
      <router-view />
    </main>
  </div>
</template>
