import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('token'))
  const role = ref(localStorage.getItem('role'))

  const login = async (credentials) => {
    try {
      const response = await api.post('/login', credentials)
      token.value = response.data.token
      user.value = response.data.user
      role.value = response.data.user.role
      localStorage.setItem('token', response.data.token)
      localStorage.setItem('role', response.data.user.role)
      return true
    } catch (error) {
      console.error('Login failed:', error)
      return false
    }
  }

  const logout = () => {
    user.value = null
    token.value = null
    role.value = null
    localStorage.removeItem('token')
    localStorage.removeItem('role')
  }

  const isAuthenticated = () => !!token.value

  const hasRole = (roles) => {
    if (!role.value) return false
    if (Array.isArray(roles)) {
      return roles.includes(role.value)
    }
    return role.value === roles
  }

  return {
    user,
    token,
    role,
    login,
    logout,
    isAuthenticated,
    hasRole
  }
})
