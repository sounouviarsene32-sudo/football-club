<script setup>
import { ref, reactive } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AppButton from '@/components/ui/Button.vue'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const showPassword = ref(false)
const showConfirmPassword = ref(false)

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const errors = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

function validate() {
  let valid = true
  errors.name = ''
  errors.email = ''
  errors.password = ''
  errors.password_confirmation = ''

  if (!form.name) {
    errors.name = 'Le nom est requis'
    valid = false
  } else if (form.name.trim().length < 2) {
    errors.name = 'Le nom doit contenir au moins 2 caractères'
    valid = false
  }

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
  } else if (!/[A-Z]/.test(form.password)) {
    errors.password = 'Le mot de passe doit contenir une majuscule'
    valid = false
  } else if (!/[0-9]/.test(form.password)) {
    errors.password = 'Le mot de passe doit contenir un chiffre'
    valid = false
  }

  if (!form.password_confirmation) {
    errors.password_confirmation = 'La confirmation du mot de passe est requise'
    valid = false
  } else if (form.password !== form.password_confirmation) {
    errors.password_confirmation = 'Les mots de passe ne correspondent pas'
    valid = false
  }

  return valid
}

async function handleSubmit() {
  if (!validate()) return
  authStore.clearError()
  try {
    await authStore.register(form)
    const redirect = route.query.redirect || '/'
    router.push(redirect)
  } catch (err) {
    if (err.message.includes('422')) {
      errors.email = 'Cet email est déjà utilisé'
    }
  }
}

function togglePassword(field) {
  if (field === 'password') {
    showPassword.value = !showPassword.value
  } else {
    showConfirmPassword.value = !showConfirmPassword.value
  }
}
</script>

<template>
  <form class="auth-form" @submit.prevent="handleSubmit">
    <h2 class="auth-title">Inscription</h2>
    <p class="auth-subtitle">Créez votre compte</p>

    <div v-if="authStore.error" class="auth-error-banner">
      {{ authStore.error }}
    </div>

    <div class="form-group">
      <label class="form-label" for="name">Nom complet</label>
      <input
        id="name"
        v-model="form.name"
        type="text"
        class="form-input"
        :class="{ 'form-input-error': errors.name }"
        placeholder="Jean Dupont"
        autocomplete="name"
      />
      <span v-if="errors.name" class="form-error">{{ errors.name }}</span>
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
          autocomplete="new-password"
        />
        <button
          type="button"
          class="password-toggle"
          @click="togglePassword('password')"
        >
          {{ showPassword ? 'Masquer' : 'Afficher' }}
        </button>
      </div>
      <span v-if="errors.password" class="form-error">{{ errors.password }}</span>
    </div>

    <div class="form-group">
      <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
      <div class="password-wrapper">
        <input
          id="password_confirmation"
          v-model="form.password_confirmation"
          :type="showConfirmPassword ? 'text' : 'password'"
          class="form-input"
          :class="{ 'form-input-error': errors.password_confirmation }"
          placeholder="••••••••"
          autocomplete="new-password"
        />
        <button
          type="button"
          class="password-toggle"
          @click="togglePassword('confirm')"
        >
          {{ showConfirmPassword ? 'Masquer' : 'Afficher' }}
        </button>
      </div>
      <span v-if="errors.password_confirmation" class="form-error">{{ errors.password_confirmation }}</span>
    </div>

    <AppButton
      type="submit"
      variant="primary"
      size="lg"
      :loading="authStore.loading"
      :disabled="authStore.loading"
      class="auth-submit"
    >
      S'inscrire
    </AppButton>

    <p class="auth-footer">
      Vous avez déjà un compte ?
      <RouterLink to="/login" class="auth-link">Se connecter</RouterLink>
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
