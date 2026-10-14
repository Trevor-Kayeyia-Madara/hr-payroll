<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-600 to-purple-700 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full mb-4">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
          </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">SecureGuard HR</h1>
        <p class="text-gray-600 mt-2">Security Company Management System</p>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
          <input
            v-model="form.email"
            type="email"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
            placeholder="Enter your email"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
          <input
            v-model="form.password"
            type="password"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
            placeholder="Enter your password"
          />
        </div>

        <div v-if="error" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
          {{ error }}
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 focus:ring-4 focus:ring-blue-300 transition disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span v-if="loading">Signing in...</span>
          <span v-else>Sign In</span>
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: ''
})

const loading = ref(false)
const error = ref('')

const handleLogin = async () => {
  loading.value = true
  error.value = ''

  try {
    // For demo, use hardcoded auth since backend doesn't have login endpoint yet
    if (form.value.email === 'admin@hr-payroll.com' && form.value.password === 'password') {
      authStore.user = { name: 'Admin User', email: form.value.email, role: 'admin' }
      authStore.token = 'demo-token'
      authStore.role = 'admin'
      localStorage.setItem('token', 'demo-token')
      localStorage.setItem('role', 'admin')
      router.push('/')
    } else if (form.value.email === 'manager@hr-payroll.com' && form.value.password === 'password') {
      authStore.user = { name: 'Manager User', email: form.value.email, role: 'manager' }
      authStore.token = 'demo-token'
      authStore.role = 'manager'
      localStorage.setItem('token', 'demo-token')
      localStorage.setItem('role', 'manager')
      router.push('/')
    } else {
      error.value = 'Invalid credentials'
    }
  } catch (err) {
    error.value = 'Login failed. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>
