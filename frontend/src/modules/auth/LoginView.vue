<script setup>
import { ref, reactive } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AppButton from '@/components/ui/Button.vue'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const showPassword = ref(false)

const form = reactive({
  email: '',
  password: '',
})

const errors = reactive({
  email: '',
  password: '',
})

function validate() {
  let valid = true
  errors.email = ''
  errors.password = ''

  if (!form.email) {
    errors.email = 'L\'email est requis'
    valid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    errors.email = 'L\'email n\'est pas valide'
    valid = false
  }

  if (!form.password) {
    errors.password = 'Le mot de passe est requis'
    valid = false
  } else if (form.password.length < 8) {
    errors.password = 'Le mot de passe doit contenir au moins 8 caractères'
    valid = false
  }

  return valid
}

async function handleSubmit() {
  if (!validate()) return
  authStore.clearError()
  try {
    await authStore.login(form)
    const redirect = route.query.redirect || '/'
    router.push(redirect)
  } catch (err) {
    if (err.message.includes('422')) {
      errors.email = 'Identifiants invalides'
    }
  }
}

function togglePassword() {
  showPassword.value = !showPassword.value
}
</script>

<template>
  <form class="auth-form" @submit.prevent="handleSubmit">
    <h2 class="auth-title">Connexion</h2>
    <p class="auth-subtitle">Connectez-vous à votre compte</p>

    <div v-if="authStore.error" class="auth-error-banner">
      {{ authStore.error }}
    </div>

    <div class="form-group">
      <label class="form-label" for="email">Email</label>
      <input
        id="email"
        v-model="form.email"
        type="email"
        class="form-input"
        :class="{ 'form-input-error': errors.email }"
        placeholder="vous@exemple.com"
        autocomplete="email"
      />
      <span v-if="errors.email" class="form-error">{{ errors.email }}</span>
    </div>

    <div class="form-group">
      <label class="form-label" for="password">Mot de passe</label>
      <div class="password-wrapper">
        <input
          id="password"
          v-model="form.password"
          :type="showPassword ? 'text' : 'password'"
          class="form-input"
          :class="{ 'form-input-error': errors.password }"
          placeholder="••••••••"
          autocomplete="current-password"
        />
        <button
          type="button"
          class="password-toggle"
          @click="togglePassword"
        >
          {{ showPassword ? 'Masquer' : 'Afficher' }}
        </button>
      </div>
      <span v-if="errors.password" class="form-error">{{ errors.password }}</span>
    </div>

    <AppButton
      type="submit"
      variant="primary"
      size="lg"
      :loading="authStore.loading"
      :disabled="authStore.loading"
      class="auth-submit"
    >
      Se connecter
    </AppButton>

    <p class="auth-footer">
      Pas encore de compte ?
      <RouterLink to="/register" class="auth-link">Créer un compte</RouterLink>
    </p>
  </form>
</template>

<style scoped>
.auth-form {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.auth-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
  text-align: center;
}

.auth-subtitle {
  font-size: 0.9rem;
  color: #64748b;
  margin: -0.75rem 0 0;
  text-align: center;
}

.auth-error-banner {
  background: #fef2f2;
  color: #dc2626;
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  border: 1px solid #fecaca;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.375rem;
}

.form-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
}

.form-input {
  padding: 0.625rem 0.875rem;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  font-size: 0.95rem;
  font-family: inherit;
  transition: border-color 0.2s, box-shadow 0.2s;
  width: 100%;
}

.form-input:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-input-error {
  border-color: #dc2626;
}

.form-input-error:focus {
  box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.form-error {
  font-size: 0.875rem;
  color: #dc2626;
}

.password-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.password-wrapper .form-input {
  padding-right: 5.5rem;
}

.password-toggle {
  position: absolute;
  right: 0.5rem;
  background: none;
  border: none;
  color: #2563eb;
  font-size: 0.8rem;
  font-weight: 500;
  cursor: pointer;
  padding: 0.375rem 0.5rem;
  font-family: inherit;
  border-radius: 0.25rem;
  transition: background 0.2s;
}

.password-toggle:hover {
  background: #eff6ff;
}

.auth-submit {
  width: 100%;
  margin-top: 0.25rem;
}

.auth-footer {
  text-align: center;
  font-size: 0.875rem;
  color: #64748b;
  margin: 0;
}

.auth-link {
  color: #2563eb;
  font-weight: 500;
}

.auth-link:hover {
  text-decoration: underline;
}
</style>
