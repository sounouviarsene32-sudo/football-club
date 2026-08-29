<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Loading from '@/components/ui/Loading.vue'

const route = useRoute()
const team = ref(null)
const loading = ref(true)
const error = ref(null)

async function loadTeam() {
  loading.value = true
  error.value = null

  try {
    team.value = await api.get(`/football/teams/${route.params.id}`)
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadTeam()
})
</script>

<template>
  <div class="team-detail">
    <div class="page-header">
      <div>
        <h1 class="page-title">{{ team?.name || 'Détails équipe' }}</h1>
        <p v-if="team" class="page-subtitle">
          {{ team.city }}, {{ team.country }}
        </p>
      </div>
      <div class="header-actions">
        <Button variant="ghost" @click="$router.back()">Retour</Button>
        <Button variant="primary">Modifier</Button>
      </div>
    </div>

    <Loading v-if="loading" text="Chargement..." />

    <div v-else-if="error" class="error">
      <p>Erreur: {{ error }}</p>
      <Button variant="secondary" @click="loadTeam">Réessayer</Button>
    </div>

    <div v-else-if="team" class="team-content">
      <Card title="Informations générales">
        <div class="info-grid">
          <div class="info-item">
            <span class="info-label">Nom</span>
            <span class="info-value">{{ team.name }}</span>
          </div>
          <div class="info-item">
            <span class="info-label">Ville</span>
            <span class="info-value">{{ team.city }}</span>
          </div>
          <div class="info-item">
            <span class="info-label">Pays</span>
            <span class="info-value">{{ team.country }}</span>
          </div>
          <div class="info-item">
            <span class="info-label">Stade</span>
            <span class="info-value">{{ team.stadium }}</span>
          </div>
          <div class="info-item">
            <span class="info-label">Fondé en</span>
            <span class="info-value">{{ team.founded }}</span>
          </div>
        </div>
      </Card>
    </div>
  </div>
</template>

<style scoped>
.team-detail {
  max-width: 800px;
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

.header-actions {
  display: flex;
  gap: 0.75rem;
}

.error {
  text-align: center;
  padding: 3rem;
  color: #dc2626;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 1.5rem;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 0.375rem;
}

.info-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.025em;
}

.info-value {
  font-size: 1rem;
  color: #1e293b;
  font-weight: 500;
}
</style>
