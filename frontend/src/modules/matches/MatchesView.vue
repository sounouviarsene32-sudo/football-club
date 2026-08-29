<script setup>
import { ref, onMounted } from 'vue'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Loading from '@/components/ui/Loading.vue'
import EmptyState from '@/components/ui/EmptyState.vue'

const matches = ref([])
const loading = ref(true)
const error = ref(null)

async function loadMatches() {
  loading.value = true
  error.value = null

  try {
    matches.value = await api.get('/matches')
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadMatches()
})
</script>

<template>
  <div class="matches-page">
    <div class="page-header">
      <div>
        <h1 class="page-title">Matchs</h1>
        <p class="page-subtitle">Gérez vos matchs</p>
      </div>
      <Button variant="primary">Ajouter un match</Button>
    </div>

    <Loading v-if="loading" text="Chargement des matchs..." />

    <div v-else-if="error" class="error">
      <p>Erreur: {{ error }}</p>
      <Button variant="secondary" @click="loadMatches">Réessayer</Button>
    </div>

    <EmptyState
      v-else-if="matches.length === 0"
      title="Aucun match"
      description="Planifiez votre premier match."
      action-label="Ajouter un match"
    />

    <div v-else class="matches-list">
      <Card v-for="match in matches" :key="match.id" class="match-card">
        <div class="match-header">
          <span class="match-status" :class="`status-${match.status}`">
            {{ match.status }}
          </span>
          <span class="match-date">{{ match.date }}</span>
        </div>
        <div class="match-teams">
          <span class="team">Équipe A</span>
          <span class="score">{{ match.home_score }} - {{ match.away_score }}</span>
          <span class="team">Équipe B</span>
        </div>
      </Card>
    </div>
  </div>
</template>

<style scoped>
.matches-page {
  max-width: 1200px;
  margin: 0 auto;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 2rem;
}

.page-title {
  font-size: 1.875rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0 0 0.5rem 0;
}

.page-subtitle {
  color: #64748b;
  margin: 0;
}

.error {
  text-align: center;
  padding: 3rem;
  color: #dc2626;
}

.matches-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.match-card {
  transition: transform 0.2s;
}

.match-card:hover {
  transform: translateX(4px);
}

.match-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.match-status {
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
  text-transform: uppercase;
}

.status-scheduled {
  background: #dbeafe;
  color: #1e40af;
}

.status-live {
  background: #dcfce7;
  color: #166534;
}

.status-finished {
  background: #f3f4f6;
  color: #374151;
}

.match-date {
  font-size: 0.875rem;
  color: #64748b;
}

.match-teams {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1.5rem;
  padding: 1rem 0;
}

.team {
  font-weight: 500;
  color: #1e293b;
}

.score {
  font-size: 1.5rem;
  font-weight: 700;
  color: #2563eb;
  min-width: 4rem;
  text-align: center;
}
</style>
