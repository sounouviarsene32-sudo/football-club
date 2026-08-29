<script setup>
import { ref, onMounted } from 'vue'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Loading from '@/components/ui/Loading.vue'
import EmptyState from '@/components/ui/EmptyState.vue'

const players = ref([])
const loading = ref(true)
const error = ref(null)

async function loadPlayers() {
  loading.value = true
  error.value = null

  try {
    players.value = await api.get('/players')
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadPlayers()
})
</script>

<template>
  <div class="players-page">
    <div class="page-header">
      <div>
        <h1 class="page-title">Joueurs</h1>
        <p class="page-subtitle">Gérez vos joueurs</p>
      </div>
      <Button variant="primary">Ajouter un joueur</Button>
    </div>

    <Loading v-if="loading" text="Chargement des joueurs..." />

    <div v-else-if="error" class="error">
      <p>Erreur: {{ error }}</p>
      <Button variant="secondary" @click="loadPlayers">Réessayer</Button>
    </div>

    <EmptyState
      v-else-if="players.length === 0"
      title="Aucun joueur"
      description="Ajoutez votre premier joueur."
      action-label="Ajouter un joueur"
    />

    <div v-else class="players-list">
      <Card v-for="player in players" :key="player.id" class="player-card">
        <div class="player-info">
          <div class="player-avatar">
            {{ player.first_name[0] }}{{ player.last_name[0] }}
          </div>
          <div class="player-details">
            <h3>{{ player.first_name }} {{ player.last_name }}</h3>
            <p>{{ player.position }}</p>
          </div>
        </div>
      </Card>
    </div>
  </div>
</template>

<style scoped>
.players-page {
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

.players-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.player-card {
  transition: transform 0.2s;
}

.player-card:hover {
  transform: translateX(4px);
}

.player-info {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.player-avatar {
  width: 3rem;
  height: 3rem;
  border-radius: 50%;
  background: #2563eb;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.875rem;
  flex-shrink: 0;
}

.player-details h3 {
  margin: 0;
  font-size: 1rem;
  font-weight: 600;
  color: #1e293b;
}

.player-details p {
  margin: 0.25rem 0 0 0;
  font-size: 0.875rem;
  color: #64748b;
}
</style>
