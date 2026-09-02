<script setup>
import { computed } from 'vue'
import { RouterLink, RouterView, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const userInitial = computed(() => {
  if (!authStore.user?.name) return ''
  return authStore.user.name.charAt(0).toUpperCase()
})

async function handleLogout() {
  await authStore.logout()
  router.push({ name: 'login' })
}
</script>

<template>
  <div class="app-layout">
    <aside class="sidebar">
      <div class="logo">
        <h1>Laravel Foot</h1>
      </div>
      <nav class="nav">
        <RouterLink to="/" class="nav-link">Dashboard</RouterLink>
        <RouterLink to="/teams" class="nav-link">Équipes</RouterLink>
        <RouterLink to="/players" class="nav-link">Joueurs</RouterLink>
        <RouterLink to="/matches" class="nav-link">Matchs</RouterLink>
        <RouterLink to="/competitions" class="nav-link">Compétitions</RouterLink>
        <RouterLink to="/trainings" class="nav-link">Entraînements</RouterLink>
        <RouterLink to="/news" class="nav-link">Actualités</RouterLink>
        <RouterLink to="/events" class="nav-link">Événements</RouterLink>
        <RouterLink to="/staff" class="nav-link">Staff</RouterLink>
      </nav>
      <div class="sidebar-footer">
        <div class="user-info">
          <div class="user-avatar">{{ userInitial }}</div>
          <span class="user-name">{{ authStore.user?.name }}</span>
        </div>
        <button class="logout-btn" @click="handleLogout">
          Déconnexion
        </button>
      </div>
    </aside>
    <main class="main-content">
      <RouterView />
    </main>
  </div>
</template>

<style scoped>
.app-layout {
  display: flex;
  min-height: 100vh;
}

.sidebar {
  width: 260px;
  background: #1e293b;
  color: white;
  padding: 1.5rem;
  position: fixed;
  height: 100vh;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
}

.logo {
  margin-bottom: 2rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #334155;
}

.logo h1 {
  font-size: 1.25rem;
  margin: 0;
  font-weight: 600;
}

.nav {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  flex: 1;
}

.nav-link {
  color: #94a3b8;
  text-decoration: none;
  padding: 0.75rem 1rem;
  border-radius: 0.375rem;
  transition: all 0.2s;
  font-size: 0.95rem;
}

.nav-link:hover,
.nav-link.router-link-active {
  color: white;
  background: #334155;
}

.sidebar-footer {
  margin-top: auto;
  padding-top: 1rem;
  border-top: 1px solid #334155;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 0.75rem;
}

.user-avatar {
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  background: #2563eb;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.875rem;
  font-weight: 600;
  flex-shrink: 0;
}

.user-name {
  font-size: 0.875rem;
  color: #e2e8f0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.logout-btn {
  width: 100%;
  padding: 0.5rem;
  background: transparent;
  color: #94a3b8;
  border: 1px solid #475569;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s;
  font-family: inherit;
}

.logout-btn:hover {
  background: #334155;
  color: white;
  border-color: #64748b;
}

.main-content {
  flex: 1;
  margin-left: 260px;
  padding: 2rem;
  background: #f1f5f9;
  min-height: 100vh;
}

@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    position: relative;
    height: auto;
  }

  .main-content {
    margin-left: 0;
  }
}
</style>
