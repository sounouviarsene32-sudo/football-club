# Exemples d'Utilisation de l'API

Ce document fournit des exemples pratiques pour utiliser l'API d'authentification dans différents contextes.

---

## 📋 Table des matières

1. [cURL](#curl)
2. [JavaScript (Fetch API)](#javascript-fetch-api)
3. [JavaScript (Axios)](#javascript-axios)
4. [PHP](#php)
5. [Python](#python)
6. [React (avec Context API)](#react-avec-context-api)
7. [Vue.js (avec Pinia)](#vuejs-avec-pinia)

---

## cURL

### Inscription
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "Password123!",
    "password_confirmation": "Password123!"
  }'
```

### Connexion
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "Password123!"
  }'
```

### Obtenir le profil
```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### Déconnexion
```bash
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

## JavaScript (Fetch API)

### Service d'authentification
```javascript
// authService.js
const API_URL = 'http://localhost:8000/api';

class AuthService {
  // Inscription
  async register(name, email, password, passwordConfirmation) {
    const response = await fetch(`${API_URL}/auth/register`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        name,
        email,
        password,
        password_confirmation: passwordConfirmation,
      }),
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.message || 'Erreur lors de l\'inscription');
    }

    const data = await response.json();
    // Sauvegarder le token
    localStorage.setItem('auth_token', data.token);
    return data;
  }

  // Connexion
  async login(email, password) {
    const response = await fetch(`${API_URL}/auth/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({ email, password }),
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.message || 'Identifiants invalides');
    }

    const data = await response.json();
    localStorage.setItem('auth_token', data.token);
    return data;
  }

  // Obtenir l'utilisateur actuel
  async getCurrentUser() {
    const token = localStorage.getItem('auth_token');
    if (!token) throw new Error('Non authentifié');

    const response = await fetch(`${API_URL}/auth/me`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      },
    });

    if (!response.ok) {
      throw new Error('Session expirée');
    }

    return await response.json();
  }

  // Déconnexion
  async logout() {
    const token = localStorage.getItem('auth_token');
    if (!token) return;

    await fetch(`${API_URL}/auth/logout`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      },
    });

    localStorage.removeItem('auth_token');
  }

  // Vérifier si l'utilisateur est authentifié
  isAuthenticated() {
    return !!localStorage.getItem('auth_token');
  }

  // Obtenir le token
  getToken() {
    return localStorage.getItem('auth_token');
  }
}

export const authService = new AuthService();
```

### Utilisation
```javascript
// Exemple d'utilisation
import { authService } from './authService';

// Connexion
try {
  const result = await authService.login('john@example.com', 'Password123!');
  console.log('Connecté:', result.user);
} catch (error) {
  console.error('Erreur:', error.message);
}

// Obtenir le profil
try {
  const userData = await authService.getCurrentUser();
  console.log('Utilisateur:', userData.user);
} catch (error) {
  console.error('Non authentifié');
}

// Déconnexion
await authService.logout();
```

---

## JavaScript (Axios)

### Configuration Axios
```javascript
// api.js
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Intercepteur pour ajouter le token automatiquement
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Intercepteur pour gérer les erreurs d'authentification
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token expiré ou invalide
      localStorage.removeItem('auth_token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;
```

### Service d'authentification avec Axios
```javascript
// authService.js
import api from './api';

export const authService = {
  async register(name, email, password, passwordConfirmation) {
    const { data } = await api.post('/auth/register', {
      name,
      email,
      password,
      password_confirmation: passwordConfirmation,
    });
    localStorage.setItem('auth_token', data.token);
    return data;
  },

  async login(email, password) {
    const { data } = await api.post('/auth/login', { email, password });
    localStorage.setItem('auth_token', data.token);
    return data;
  },

  async logout() {
    await api.post('/auth/logout');
    localStorage.removeItem('auth_token');
  },

  async getCurrentUser() {
    const { data } = await api.get('/auth/me');
    return data;
  },

  async refreshToken() {
    const { data } = await api.post('/auth/refresh');
    localStorage.setItem('auth_token', data.token);
    return data;
  },

  getToken() {
    return localStorage.getItem('auth_token');
  },

  isAuthenticated() {
    return !!this.getToken();
  },
};
```

---

## PHP

### Client API PHP
```php
<?php
// ApiClient.php

class ApiClient
{
    private string $baseUrl = 'http://localhost:8000/api';
    private ?string $token = null;

    public function __construct(?string $token = null)
    {
        $this->token = $token;
    }

    private function request(string $method, string $endpoint, ?array $data = null): array
    {
        $url = $this->baseUrl . $endpoint;
        
        $options = [
            'http' => [
                'method' => $method,
                'header' => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                ],
            ],
        ];

        if ($this->token) {
            $options['http']['header'][] = "Authorization: Bearer {$this->token}";
        }

        if ($data !== null) {
            $options['http']['content'] = json_encode($data);
        }

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        
        return json_decode($response, true);
    }

    public function register(string $name, string $email, string $password): array
    {
        $data = $this->request('POST', '/auth/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $this->token = $data['token'];
        return $data;
    }

    public function login(string $email, string $password): array
    {
        $data = $this->request('POST', '/auth/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->token = $data['token'];
        return $data;
    }

    public function logout(): array
    {
        $data = $this->request('POST', '/auth/logout');
        $this->token = null;
        return $data;
    }

    public function getCurrentUser(): array
    {
        return $this->request('GET', '/auth/me');
    }

    public function getToken(): ?string
    {
        return $this->token;
    }
}

// Utilisation
$client = new ApiClient();

// Connexion
$result = $client->login('john@example.com', 'Password123!');
echo "Connecté: " . $result['user']['name'] . "\n";

// Obtenir le profil
$userData = $client->getCurrentUser();
echo "Email: " . $userData['user']['email'] . "\n";

// Déconnexion
$client->logout();
```

---

## Python

### Client API Python
```python
# api_client.py
import requests
from typing import Optional, Dict

class ApiClient:
    def __init__(self, base_url: str = "http://localhost:8000/api"):
        self.base_url = base_url
        self.token: Optional[str] = None
        self.session = requests.Session()
        self.session.headers.update({
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        })

    def _update_token(self, token: str):
        """Mettre à jour le token d'authentification"""
        self.token = token
        self.session.headers.update({
            'Authorization': f'Bearer {token}'
        })

    def register(self, name: str, email: str, password: str) -> Dict:
        """Inscription d'un nouvel utilisateur"""
        response = self.session.post(
            f'{self.base_url}/auth/register',
            json={
                'name': name,
                'email': email,
                'password': password,
                'password_confirmation': password
            }
        )
        response.raise_for_status()
        data = response.json()
        self._update_token(data['token'])
        return data

    def login(self, email: str, password: str) -> Dict:
        """Connexion utilisateur"""
        response = self.session.post(
            f'{self.base_url}/auth/login',
            json={'email': email, 'password': password}
        )
        response.raise_for_status()
        data = response.json()
        self._update_token(data['token'])
        return data

    def logout(self) -> Dict:
        """Déconnexion"""
        response = self.session.post(f'{self.base_url}/auth/logout')
        response.raise_for_status()
        self.token = None
        del self.session.headers['Authorization']
        return response.json()

    def get_current_user(self) -> Dict:
        """Obtenir l'utilisateur actuel"""
        response = self.session.get(f'{self.base_url}/auth/me')
        response.raise_for_status()
        return response.json()

    def refresh_token(self) -> Dict:
        """Rafraîchir le token"""
        response = self.session.post(f'{self.base_url}/auth/refresh')
        response.raise_for_status()
        data = response.json()
        self._update_token(data['token'])
        return data

# Utilisation
if __name__ == '__main__':
    client = ApiClient()
    
    # Connexion
    try:
        result = client.login('john@example.com', 'Password123!')
        print(f"Connecté: {result['user']['name']}")
        
        # Obtenir le profil
        user_data = client.get_current_user()
        print(f"Email: {user_data['user']['email']}")
        
        # Déconnexion
        client.logout()
        print("Déconnecté")
        
    except requests.HTTPError as e:
        print(f"Erreur: {e}")
```

---

## React (avec Context API)

### Context d'authentification
```jsx
// AuthContext.jsx
import React, { createContext, useState, useContext, useEffect } from 'react';
import axios from 'axios';

const AuthContext = createContext(null);

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [token, setToken] = useState(localStorage.getItem('auth_token'));

  // Intercepteur pour ajouter le token
  useEffect(() => {
    const interceptor = api.interceptors.request.use(
      (config) => {
        if (token) {
          config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
      },
      (error) => Promise.reject(error)
    );

    return () => api.interceptors.request.eject(interceptor);
  }, [token]);

  // Charger l'utilisateur au démarrage
  useEffect(() => {
    if (token) {
      fetchUser();
    } else {
      setLoading(false);
    }
  }, [token]);

  const fetchUser = async () => {
    try {
      const { data } = await api.get('/auth/me');
      setUser(data.user);
    } catch (error) {
      logout();
    } finally {
      setLoading(false);
    }
  };

  const register = async (name, email, password, passwordConfirmation) => {
    const { data } = await api.post('/auth/register', {
      name,
      email,
      password,
      password_confirmation: passwordConfirmation,
    });
    setToken(data.token);
    setUser(data.user);
    localStorage.setItem('auth_token', data.token);
    return data;
  };

  const login = async (email, password) => {
    const { data } = await api.post('/auth/login', { email, password });
    setToken(data.token);
    setUser(data.user);
    localStorage.setItem('auth_token', data.token);
    return data;
  };

  const logout = async () => {
    try {
      await api.post('/auth/logout');
    } finally {
      setToken(null);
      setUser(null);
      localStorage.removeItem('auth_token');
    }
  };

  const value = {
    user,
    loading,
    register,
    login,
    logout,
    isAuthenticated: !!user,
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth doit être utilisé dans un AuthProvider');
  }
  return context;
};
```

### Utilisation dans un composant
```jsx
// LoginPage.jsx
import React, { useState } from 'react';
import { useAuth } from './AuthContext';
import { useNavigate } from 'react-router-dom';

export const LoginPage = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    
    try {
      await login(email, password);
      navigate('/dashboard');
    } catch (err) {
      setError(err.response?.data?.message || 'Erreur de connexion');
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <h1>Connexion</h1>
      {error && <div className="error">{error}</div>}
      
      <input
        type="email"
        placeholder="Email"
        value={email}
        onChange={(e) => setEmail(e.target.value)}
        required
      />
      
      <input
        type="password"
        placeholder="Mot de passe"
        value={password}
        onChange={(e) => setPassword(e.target.value)}
        required
      />
      
      <button type="submit">Se connecter</button>
    </form>
  );
};
```

---

## Vue.js (avec Pinia)

### Store d'authentification
```javascript
// stores/auth.js
import { defineStore } from 'pinia';
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('auth_token'),
  }),

  getters: {
    isAuthenticated: (state) => !!state.user,
  },

  actions: {
    setupInterceptors() {
      api.interceptors.request.use(
        (config) => {
          if (this.token) {
            config.headers.Authorization = `Bearer ${this.token}`;
          }
          return config;
        },
        (error) => Promise.reject(error)
      );
    },

    async register(name, email, password, passwordConfirmation) {
      const { data } = await api.post('/auth/register', {
        name,
        email,
        password,
        password_confirmation: passwordConfirmation,
      });
      this.setAuth(data.token, data.user);
      return data;
    },

    async login(email, password) {
      const { data } = await api.post('/auth/login', { email, password });
      this.setAuth(data.token, data.user);
      return data;
    },

    async logout() {
      try {
        await api.post('/auth/logout');
      } finally {
        this.clearAuth();
      }
    },

    async fetchUser() {
      try {
        const { data } = await api.get('/auth/me');
        this.user = data.user;
      } catch (error) {
        this.clearAuth();
        throw error;
      }
    },

    setAuth(token, user) {
      this.token = token;
      this.user = user;
      localStorage.setItem('auth_token', token);
    },

    clearAuth() {
      this.token = null;
      this.user = null;
      localStorage.removeItem('auth_token');
    },
  },
});
```

### Utilisation dans un composant
```vue
<!-- LoginPage.vue -->
<template>
  <form @submit.prevent="handleLogin">
    <h1>Connexion</h1>
    <div v-if="error" class="error">{{ error }}</div>
    
    <input
      v-model="email"
      type="email"
      placeholder="Email"
      required
    />
    
    <input
      v-model="password"
      type="password"
      placeholder="Mot de passe"
      required
    />
    
    <button type="submit" :disabled="loading">
      {{ loading ? 'Connexion...' : 'Se connecter' }}
    </button>
  </form>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const email = ref('');
const password = ref('');
const error = ref('');
const loading = ref(false);

const handleLogin = async () => {
  error.value = '';
  loading.value = true;
  
  try {
    await authStore.login(email.value, password.value);
    router.push('/dashboard');
  } catch (err) {
    error.value = err.response?.data?.message || 'Erreur de connexion';
  } finally {
    loading.value = false;
  }
};
</script>
```

---

## 🔒 Bonnes Pratiques

### Gestion du Token
```javascript
// ✅ BON - Token dans localStorage (développement)
localStorage.setItem('auth_token', token);

// ⚠️ MIEUX - Token dans httpOnly cookie (production)
// Configuré côté serveur, plus sécurisé contre XSS

// ❌ MAUVAIS - Token dans sessionStorage
// Perdu à la fermeture de l'onglet
```

### Gestion des Erreurs
```javascript
try {
  await authService.login(email, password);
} catch (error) {
  if (error.response) {
    // Erreur de l'API
    switch (error.response.status) {
      case 401:
        console.error('Identifiants invalides');
        break;
      case 422:
        console.error('Données invalides:', error.response.data.errors);
        break;
      default:
        console.error('Erreur serveur');
    }
  } else if (error.request) {
    // Pas de réponse du serveur
    console.error('Serveur injoignable');
  } else {
    // Erreur de configuration
    console.error('Erreur:', error.message);
  }
}
```

---

Tous ces exemples sont prêts à l'emploi et peuvent être adaptés selon vos besoins spécifiques !
