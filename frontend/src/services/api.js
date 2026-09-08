const API_BASE_URL = import.meta.env.VITE_API_URL || '/api'

function getAuthHeaders() {
  const token = localStorage.getItem('auth_token')
  return token ? { Authorization: `Bearer ${token}` } : {}
}

async function request(endpoint, options = {}) {
    const url = `${API_BASE_URL}${endpoint}`
    const config = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...getAuthHeaders(),
            ...options.headers,
        },
        ...options,
    }

    try {
        const response = await fetch(url, config)

        if (!response.ok) {
            const error = await response.json().catch(() => ({ message: response.statusText }))
            throw new Error(error.message || `HTTP error! status: ${response.status}`)
        }

        const data = await response.json()
        return data.data ?? data
    } catch (error) {
        console.error(`API request failed: ${endpoint}`, error)
        throw error
    }
}

export const api = {
    get: (endpoint, options = {}) => request(endpoint, { method: 'GET', ...options }),
    post: (endpoint, data, options = {}) => request(endpoint, {
        method: 'POST',
        body: JSON.stringify(data),
        ...options,
    }),
    put: (endpoint, data, options = {}) => request(endpoint, {
        method: 'PUT',
        body: JSON.stringify(data),
        ...options,
    }),
    delete: (endpoint, options = {}) => request(endpoint, { method: 'DELETE', ...options }),
}
