<script setup>
import { ref, onMounted } from 'vue'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Loading from '@/components/ui/Loading.vue'
import EmptyState from '@/components/ui/EmptyState.vue'

const events = ref([])
const loading = ref(true)
const error = ref(null)

async function loadEvents() {
  loading.value = true
  error.value = null

  try {
    events.value = await api.get('/events')
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadEvents()
})
</script>

<template>
  <div class="events-page">
    <div class="page-header">
      <div>
        <h1 class="page-title">Événements</h1>
        <p class="page-subtitle">Gérez vos événements</p>
      </div>
      <Button variant="primary">Créer un événement</Button>
    </div>

    <Loading v-if="loading" text="Chargement des événements..." />

    <div v-else-if="error" class="error">
      <p>Erreur: {{ error }}</p>
      <Button variant="secondary" @click="loadEvents">Réessayer</Button>
    </div>

    <EmptyState
      v-else-if="events.length === 0"
      title="Aucun événement"
      description="Organisez votre premier événement."
      action-label="Créer"
    />

    <div v-else class="events-list">
      <Card v-for="event in events" :key="event.id" class="event-card">
        <div class="event-header">
          <span class="event-type">{{ event.type }}</span>
          <span class="event-date">{{ event.date }}</span>
        </div>
        <h3 class="event-name">{{ event.name }}</h3>
        <p class="event-location">{{ event.location }}</p>
      </Card>
    </div>
  </div>
</template>

<style scoped>
.events-page {
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

.events-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

.event-card {
  transition: transform 0.2s;
}

.event-card:hover {
  transform: translateY(-2px);
}

.event-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
}

.event-type {
  padding: 0.25rem 0.75rem;
  background: #fce7f3;
  color: #9d174d;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
  text-transform: uppercase;
}

.event-date {
  font-size: 0.875rem;
  color: #64748b;
}

.event-name {
  margin: 0 0 0.5rem 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #1e293b;
}

.event-location {
  margin: 0;
  font-size: 0.875rem;
  color: #64748b;
}
</style>
