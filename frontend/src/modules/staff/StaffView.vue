<script setup>
import { ref, onMounted } from 'vue'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Loading from '@/components/ui/Loading.vue'
import EmptyState from '@/components/ui/EmptyState.vue'

const staff = ref([])
const loading = ref(true)
const error = ref(null)

async function loadStaff() {
  loading.value = true
  error.value = null

  try {
    staff.value = await api.get('/staff')
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadStaff()
})
</script>

<template>
  <div class="staff-page">
    <div class="page-header">
      <div>
        <h1 class="page-title">Staff</h1>
        <p class="page-subtitle">Gérez votre staff technique</p>
      </div>
      <Button variant="primary">Ajouter un membre</Button>
    </div>

    <Loading v-if="loading" text="Chargement du staff..." />

    <div v-else-if="error" class="error">
      <p>Erreur: {{ error }}</p>
      <Button variant="secondary" @click="loadStaff">Réessayer</Button>
    </div>

    <EmptyState
      v-else-if="staff.length === 0"
      title="Aucun membre"
      description="Ajoutez votre premier membre du staff."
      action-label="Ajouter"
    />

    <div v-else class="staff-list">
      <Card v-for="member in staff" :key="member.id" class="staff-card">
        <div class="staff-info">
          <div class="staff-avatar">
            {{ member.first_name[0] }}{{ member.last_name[0] }}
          </div>
          <div class="staff-details">
            <h3>{{ member.first_name }} {{ member.last_name }}</h3>
            <p class="staff-role">{{ member.role }}</p>
            <p class="staff-contact">{{ member.email }} | {{ member.phone }}</p>
          </div>
        </div>
      </Card>
    </div>
  </div>
</template>

<style scoped>
.staff-page {
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

.staff-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.staff-card {
  transition: transform 0.2s;
}

.staff-card:hover {
  transform: translateX(4px);
}

.staff-info {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.staff-avatar {
  width: 3rem;
  height: 3rem;
  border-radius: 50%;
  background: #16a34a;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.875rem;
  flex-shrink: 0;
}

.staff-details h3 {
  margin: 0;
  font-size: 1rem;
  font-weight: 600;
  color: #1e293b;
}

.staff-role {
  margin: 0.25rem 0 0 0;
  font-size: 0.875rem;
  color: #2563eb;
  font-weight: 500;
}

.staff-contact {
  margin: 0.25rem 0 0 0;
  font-size: 0.875rem;
  color: #64748b;
}
</style>
