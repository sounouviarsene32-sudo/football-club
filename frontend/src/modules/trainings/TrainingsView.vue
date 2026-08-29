<script setup>
import { ref, onMounted } from 'vue'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Loading from '@/components/ui/Loading.vue'
import EmptyState from '@/components/ui/EmptyState.vue'

const trainings = ref([])
const loading = ref(true)
const error = ref(null)

async function loadTrainings() {
  loading.value = true
  error.value = null

  try {
    trainings.value = await api.get('/trainings')
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadTrainings()
})
</script>

<template>
  <div class="trainings-page">
    <div class="page-header">
      <div>
        <h1 class="page-title">Entraînements</h1>
        <p class="page-subtitle">Gérez vos séances d'entraînement</p>
      </div>
      <Button variant="primary">Ajouter un entraînement</Button>
    </div>

    <Loading v-if="loading" text="Chargement des entraînements..." />

    <div v-else-if="error" class="error">
      <p>Erreur: {{ error }}</p>
      <Button variant="secondary" @click="loadTrainings">Réessayer</Button>
    </div>

    <EmptyState
      v-else-if="trainings.length === 0"
      title="Aucun entraînement"
      description="Planifiez votre première séance."
      action-label="Ajouter un entraînement"
    />

    <div v-else class="trainings-list">
      <Card v-for="training in trainings" :key="training.id" class="training-card">
        <div class="training-header">
          <span class="training-type">{{ training.type }}</span>
          <span class="training-date">{{ training.date }}</span>
        </div>
        <h3 class="training-location">{{ training.location }}</h3>
        <p v-if="training.notes" class="training-notes">{{ training.notes }}</p>
      </Card>
    </div>
  </div>
</template>

<style scoped>
.trainings-page {
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

.trainings-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

.training-card {
  transition: transform 0.2s;
}

.training-card:hover {
  transform: translateY(-2px);
}

.training-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
}

.training-type {
  padding: 0.25rem 0.75rem;
  background: #dbeafe;
  color: #1e40af;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
  text-transform: uppercase;
}

.training-date {
  font-size: 0.875rem;
  color: #64748b;
}

.training-location {
  margin: 0 0 0.5rem 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #1e293b;
}

.training-notes {
  margin: 0;
  font-size: 0.875rem;
  color: #64748b;
  line-height: 1.5;
}
</style>
