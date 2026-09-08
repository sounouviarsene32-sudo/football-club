<script setup>
import { ref, onMounted, computed } from 'vue'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Loading from '@/components/ui/Loading.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import Input from '@/components/ui/Input.vue'

const teams = ref([])
const loading = ref(true)
const error = ref(null)
const showModal = ref(false)
const editingTeam = ref(null)
const deletingId = ref(null)
const formLoading = ref(false)

const form = ref({
  name: '',
  city: '',
  country: '',
  stadium: '',
  founded: ''
})
const formErrors = ref({})

const isEditing = computed(() => editingTeam.value !== null)
const modalTitle = computed(() => isEditing.value ? 'Modifier l\'équipe' : 'Ajouter une équipe')

async function loadTeams() {
  loading.value = true
  error.value = null

  try {
    teams.value = await api.get('/teams')
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

function openCreateModal() {
  editingTeam.value = null
  form.value = { name: '', city: '', country: '', stadium: '', founded: '' }
  formErrors.value = {}
  showModal.value = true
}

function openEditModal(team) {
  editingTeam.value = team
  form.value = { ...team }
  formErrors.value = {}
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  editingTeam.value = null
}

async function handleSubmit() {
  formLoading.value = true
  formErrors.value = {}

  try {
    if (isEditing.value) {
      await api.put(`/teams/${editingTeam.value.id}`, form.value)
    } else {
      await api.post('/teams', form.value)
    }
    await loadTeams()
    closeModal()
  } catch (err) {
    if (err.message.includes('validation') || err.message.includes('422')) {
      formErrors.value = { general: err.message }
    } else {
      formErrors.value = { general: err.message }
    }
  } finally {
    formLoading.value = false
  }
}

async function handleDelete(id) {
  if (!confirm('Êtes-vous sûr de vouloir supprimer cette équipe ?')) return

  deletingId.value = id
  try {
    await api.delete(`/teams/${id}`)
    await loadTeams()
  } catch (err) {
    error.value = err.message
  } finally {
    deletingId.value = null
  }
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
      <Button variant="primary" @click="openCreateModal">Ajouter une équipe</Button>
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
      @action="openCreateModal"
    />

    <div v-else class="teams-grid">
      <Card
        v-for="team in teams"
        :key="team.id"
        class="team-card"
      >
        <template #header>
          <div class="card-header">
            <h3 class="team-name">{{ team.name }}</h3>
            <div class="card-actions">
              <Button variant="ghost" size="sm" @click.stop="openEditModal(team)">Modifier</Button>
              <Button variant="danger" size="sm" :loading="deletingId === team.id" @click.stop="handleDelete(team.id)">Supprimer</Button>
            </div>
          </div>
        </template>
        <div class="team-info">
          <p><strong>Ville:</strong> {{ team.city }}</p>
          <p><strong>Pays:</strong> {{ team.country }}</p>
          <p v-if="team.stadium"><strong>Stade:</strong> {{ team.stadium }}</p>
          <p v-if="team.founded"><strong>Fondée:</strong> {{ team.founded }}</p>
        </div>
      </Card>
    </div>

    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal">
        <div class="modal-header">
          <h2>{{ modalTitle }}</h2>
          <button class="modal-close" @click="closeModal">&times;</button>
        </div>
        <form @submit.prevent="handleSubmit" class="modal-form">
          <div v-if="formErrors.general" class="form-error">{{ formErrors.general }}</div>

          <Input
            v-model="form.name"
            label="Nom de l'équipe *"
            placeholder="Ex: FC Barcelona"
            :error="formErrors.name"
          />
          <Input
            v-model="form.city"
            label="Ville *"
            placeholder="Ex: Barcelona"
            :error="formErrors.city"
          />
          <Input
            v-model="form.country"
            label="Pays *"
            placeholder="Ex: Spain"
            :error="formErrors.country"
          />
          <Input
            v-model="form.stadium"
            label="Stade"
            placeholder="Ex: Camp Nou"
            :error="formErrors.stadium"
          />
          <Input
            v-model="form.founded"
            label="Année de fondation"
            type="number"
            placeholder="Ex: 1899"
            :error="formErrors.founded"
          />

          <div class="modal-actions">
            <Button variant="secondary" type="button" @click="closeModal">Annuler</Button>
            <Button variant="primary" type="submit" :loading="formLoading">
              {{ isEditing ? 'Modifier' : 'Ajouter' }}
            </Button>
          </div>
        </form>
      </div>
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
  transition: transform 0.2s, box-shadow 0.2s;
}

.team-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.team-name {
  margin: 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #1e293b;
}

.card-actions {
  display: flex;
  gap: 0.5rem;
}

.team-info p {
  margin: 0.25rem 0;
  color: #64748b;
}

.team-info strong {
  color: #374151;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal {
  background: white;
  border-radius: 0.5rem;
  width: 100%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.25rem;
  border-bottom: 1px solid #e5e7eb;
}

.modal-header h2 {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 600;
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #6b7280;
  padding: 0;
  line-height: 1;
}

.modal-close:hover {
  color: #374151;
}

.modal-form {
  padding: 1.25rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-error {
  color: #dc2626;
  font-size: 0.875rem;
  padding: 0.5rem;
  background: #fef2f2;
  border-radius: 0.25rem;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  margin-top: 0.5rem;
}
</style>
