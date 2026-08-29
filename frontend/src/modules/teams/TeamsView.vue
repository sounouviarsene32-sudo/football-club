<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Loading from '@/components/ui/Loading.vue'
import EmptyState from '@/components/ui/EmptyState.vue'

const router = useRouter()
const teams = ref([])
const loading = ref(true)
const error = ref(null)

async function loadTeams() {
  loading.value = true
  error.value = null

  try {
    teams.value = await api.get('/football/teams')
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

function goToDetail(id) {
  router.push(`/teams/${id}`)
}

onMounted(() => {
  loadTeams()
})
</script>

<template>
  <div class="teams-page">
    <div class="page-header">
      <div>
        <h1 class="page-title">Équipes</h1>
        <p class="page-subtitle">Gérez vos équipes de football</p>
      </div>
      <Button variant="primary">Ajouter une équipe</Button>
    </div>

    <Loading v-if="loading" text="Chargement des équipes..." />

    <div v-else-if="error" class="error">
      <p>Erreur: {{ error }}</p>
      <Button variant="secondary" @click="loadTeams">Réessayer</Button>
    </div>

    <EmptyState
      v-else-if="teams.length === 0"
      title="Aucune équipe"
      description="Commencez par ajouter votre première équipe."
      action-label="Ajouter une équipe"
    />

    <div v-else class="teams-grid">
      <Card
        v-for="team in teams"
        :key="team.id"
        class="team-card"
        :title="team.name"
        :subtitle="`${team.city}, ${team.country}`"
      >
        <template #default>
          <div class="team-actions">
            <Button variant="ghost" size="sm" @click="goToDetail(team.id)">
              Voir détails
            </Button>
          </div>
        </template>
      </Card>
    </div>
  </div>
</template>

<style scoped>
.teams-page {
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

.teams-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

.team-card {
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.team-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.team-actions {
  display: flex;
  gap: 0.5rem;
  margin-top: 1rem;
}
</style>
