<script setup>
import { RouterLink, RouterView } from 'vue-router'
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
