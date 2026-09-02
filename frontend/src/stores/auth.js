import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { api } from '@/services/api'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem('auth_token') || '')
  const user = ref(JSON.parse(localStorage.getItem('auth_user') || 'null'))
  const loading = ref(false)
  const error = ref('')

  const isAuthenticated = computed(() => !!token.value)

  function getAuthHeaders() {
    return token.value ? { Authorization: `Bearer ${token.value}` } : {}
  }

  async function register(data) {
    loading.value = true
    error.value = ''
    try {
      const response = await api.post('/auth/register', data)
      token.value = response.token
      user.value = response.user
      localStorage.setItem('auth_token', response.token)
      localStorage.setItem('auth_user', JSON.stringify(response.user))
      return response
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function login(data) {
    loading.value = true
    error.value = ''
    try {
      const response = await api.post('/auth/login', data)
      token.value = response.token
      user.value = response.user
      localStorage.setItem('auth_token', response.token)
      localStorage.setItem('auth_user', JSON.stringify(response.user))
      return response
    } catch (err) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    loading.value = true
    error.value = ''
    try {
      if (token.value) {
        await api.post('/auth/logout', {}, { headers: getAuthHeaders() })
      }
    } catch (err) {
      error.value = err.message
    } finally {
      token.value = ''
      user.value = null
      localStorage.removeItem('auth_token')
      localStorage.removeItem('auth_user')
      loading.value = false
    }
  }

  async function fetchUser() {
    if (!token.value) return null
    try {
      const response = await api.get('/auth/me', { headers: getAuthHeaders() })
      user.value = response.user
      localStorage.setItem('auth_user', JSON.stringify(response.user))
      return response.user
    } catch (err) {
      token.value = ''
      user.value = null
      localStorage.removeItem('auth_token')
      localStorage.removeItem('auth_user')
      return null
    }
  }

  function clearError() {
    error.value = ''
  }

  return {
    token,
    user,
    loading,
    error,
    isAuthenticated,
    getAuthHeaders,
    register,
    login,
    logout,
    fetchUser,
    clearError,
  }
})
