<script setup>
import { ref, onMounted } from 'vue'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Loading from '@/components/ui/Loading.vue'
import EmptyState from '@/components/ui/EmptyState.vue'

const competitions = ref([])
const loading = ref(true)
const error = ref(null)

async function loadCompetitions() {
  loading.value = true
  error.value = null

  try {
    competitions.value = await api.get('/competitions')
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadCompetitions()
})
</script>

<template>
  <div class="competitions-page">
    <div class="page-header">
      <div>
        <h1 class="page-title">Compétitions</h1>
        <p class="page-subtitle">Gérez vos compétitions</p>
      </div>
      <Button variant="primary">Ajouter une compétition</Button>
    </div>

    <Loading v-if="loading" text="Chargement des compétitions..." />

    <div v-else-if="error" class="error">
      <p>Erreur: {{ error }}</p>
      <Button variant="secondary" @click="loadCompetitions">Réessayer</Button>
    </div>

    <EmptyState
      v-else-if="competitions.length === 0"
      title="Aucune compétition"
      description="Créez votre première compétition."
      action-label="Ajouter une compétition"
    />

    <div v-else class="competitions-grid">
      <Card v-for="comp in competitions" :key="comp.id" class="comp-card">
        <div class="comp-header">
          <span class="comp-type">{{ comp.type }}</span>
          <span class="comp-season">{{ comp.season }}</span>
        </div>
        <h3 class="comp-name">{{ comp.name }}</h3>
        <p class="comp-dates">
          {{ comp.start_date }} - {{ comp.end_date }}
        </p>
      </Card>
    </div>
  </div>
</template>

<style scoped>
.competitions-page {
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

.competitions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

.comp-card {
  transition: transform 0.2s;
}

.comp-card:hover {
  transform: translateY(-2px);
}

.comp-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
}

.comp-type {
  padding: 0.25rem 0.75rem;
  background: #fef3c7;
  color: #92400e;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
  text-transform: uppercase;
}

.comp-season {
  font-size: 0.875rem;
  color: #64748b;
}

.comp-name {
  margin: 0 0 0.5rem 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #1e293b;
}

.comp-dates {
  margin: 0;
  font-size: 0.875rem;
  color: #64748b;
}
</style>
