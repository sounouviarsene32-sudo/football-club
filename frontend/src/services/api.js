const API_BASE_URL = import.meta.env.VITE_API_URL || '/api'

async function request(endpoint, options = {}) {
    const url = `${API_BASE_URL}${endpoint}`
    const config = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
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

        return await response.json()
    } catch (error) {
        console.error(`API request failed: ${endpoint}`, error)
        throw error
    }
}

export const api = {
    get: (endpoint) => request(endpoint, { method: 'GET' }),
    post: (endpoint, data) => request(endpoint, {
        method: 'POST',
        body: JSON.stringify(data),
    }),
    put: (endpoint, data) => request(endpoint, {
        method: 'PUT',
        body: JSON.stringify(data),
    }),
    delete: (endpoint) => request(endpoint, { method: 'DELETE' }),
}
