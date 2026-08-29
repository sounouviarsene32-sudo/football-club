<script setup>
import { ref, onMounted } from 'vue'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Loading from '@/components/ui/Loading.vue'

const stats = ref({
  teams: 0,
  players: 0,
  matches: 0,
  competitions: 0,
})
const loading = ref(true)
const error = ref(null)

async function loadStats() {
  loading.value = true
  error.value = null

  try {
    const [teamsRes] = await Promise.all([
      api.get('/football/teams'),
    ])

    stats.value = {
      teams: teamsRes.length || 0,
      players: 0,
      matches: 0,
      competitions: 0,
    }
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadStats()
})
</script>

<template>
  <div class="dashboard">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Vue d'ensemble de votre application</p>

    <Loading v-if="loading" text="Chargement des statistiques..." />

    <div v-else-if="error" class="error">
      <p>Erreur: {{ error }}</p>
      <button class="retry-btn" @click="loadStats">Réessayer</button>
    </div>

    <div v-else class="stats-grid">
      <Card v-for="stat in [
        { label: 'Équipes', value: stats.teams, icon: '⚽', color: '#2563eb' },
        { label: 'Joueurs', value: stats.players, icon: '👤', color: '#16a34a' },
        { label: 'Matchs', value: stats.matches, icon: '🏆', color: '#d97706' },
        { label: 'Compétitions', value: stats.competitions, icon: '🏅', color: '#9333ea' },
      ]" :key="stat.label" class="stat-card">
        <div class="stat-content">
          <div class="stat-icon" :style="{ background: stat.color + '20', color: stat.color }">
            {{ stat.icon }}
          </div>
          <div class="stat-info">
            <span class="stat-value">{{ stat.value }}</span>
            <span class="stat-label">{{ stat.label }}</span>
          </div>
        </div>
      </Card>
    </div>

    <Card title="Actions rapides" class="mt-6">
      <div class="quick-actions">
        <RouterLink to="/teams" class="action-link">Gérer les équipes</RouterLink>
        <RouterLink to="/players" class="action-link">Gérer les joueurs</RouterLink>
        <RouterLink to="/matches" class="action-link">Gérer les matchs</RouterLink>
      </div>
    </Card>
  </div>
</template>

<style scoped>
.dashboard {
  max-width: 1200px;
  margin: 0 auto;
}

.page-title {
  font-size: 1.875rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0 0 0.5rem 0;
}

.page-subtitle {
  color: #64748b;
  margin: 0 0 2rem 0;
}

.error {
  text-align: center;
  padding: 2rem;
  color: #dc2626;
}

.retry-btn {
  margin-top: 1rem;
  padding: 0.625rem 1.25rem;
  background: #2563eb;
  color: white;
  border: none;
  border-radius: 0.375rem;
  cursor: pointer;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.stat-content {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.stat-icon {
  width: 3rem;
  height: 3rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 0.5rem;
  font-size: 1.5rem;
}

.stat-info {
  display: flex;
  flex-direction: column;
}

.stat-value {
  font-size: 1.875rem;
  font-weight: 700;
  color: #1e293b;
  line-height: 1;
}

.stat-label {
  font-size: 0.875rem;
  color: #64748b;
  margin-top: 0.25rem;
}

.mt-6 {
  margin-top: 1.5rem;
}

.quick-actions {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.action-link {
  padding: 0.625rem 1.25rem;
  background: #f3f4f6;
  color: #374151;
  text-decoration: none;
  border-radius: 0.375rem;
  font-weight: 500;
  transition: all 0.2s;
}

.action-link:hover {
  background: #2563eb;
  color: white;
}
</style>
