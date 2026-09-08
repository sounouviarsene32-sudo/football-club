<script setup>
import { ref, onMounted, computed } from 'vue'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Loading from '@/components/ui/Loading.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import Input from '@/components/ui/Input.vue'

const trainings = ref([])
const teams = ref([])
const loading = ref(true)
const error = ref(null)
const showModal = ref(false)
const editingTraining = ref(null)
const deletingId = ref(null)
const formLoading = ref(false)

const form = ref({
  team_id: '',
  date: '',
  location: '',
  type: '',
  notes: ''
})
const formErrors = ref({})

const isEditing = computed(() => editingTraining.value !== null)
const modalTitle = computed(() => isEditing.value ? 'Modifier l\'entraînement' : 'Ajouter un entraînement')

const typeOptions = ['Technique', 'Tactique', 'Physique', 'Match amical', 'Récupération']

async function loadTrainings() {
  loading.value = true
  error.value = null

  try {
    const [trainingsData, teamsData] = await Promise.all([
      api.get('/trainings'),
      api.get('/teams')
    ])
    trainings.value = trainingsData
    teams.value = teamsData
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

function getTeamName(teamId) {
  const team = teams.value.find(t => t.id === teamId)
  return team ? team.name : 'Non assigné'
}

function formatDate(dateStr) {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}

function openCreateModal() {
  editingTraining.value = null
  form.value = { team_id: '', date: '', location: '', type: '', notes: '' }
  formErrors.value = {}
  showModal.value = true
}

function openEditModal(training) {
  editingTraining.value = training
  form.value = {
    team_id: training.team_id,
    date: training.date ? training.date.split(' ')[0] + 'T' + (training.date.split(' ')[1] || '00:00') : '',
    location: training.location,
    type: training.type,
    notes: training.notes || ''
  }
  formErrors.value = {}
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  editingTraining.value = null
}

async function handleSubmit() {
  formLoading.value = true
  formErrors.value = {}

  try {
    if (isEditing.value) {
      await api.put(`/trainings/${editingTraining.value.id}`, form.value)
    } else {
      await api.post('/trainings', form.value)
    }
    await loadTrainings()
    closeModal()
  } catch (err) {
    formErrors.value = { general: err.message }
  } finally {
    formLoading.value = false
  }
}

async function handleDelete(id) {
  if (!confirm('Êtes-vous sûr de vouloir supprimer cet entraînement ?')) return

  deletingId.value = id
  try {
    await api.delete(`/trainings/${id}`)
    await loadTrainings()
  } catch (err) {
    error.value = err.message
  } finally {
    deletingId.value = null
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
      <Button variant="primary" @click="openCreateModal">Ajouter un entraînement</Button>
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
      @action="openCreateModal"
    />

    <div v-else class="trainings-list">
      <Card v-for="training in trainings" :key="training.id" class="training-card">
        <div class="training-header">
          <span class="training-type">{{ training.type }}</span>
          <span class="training-date">{{ formatDate(training.date) }}</span>
        </div>
        <h3 class="training-location">{{ training.location }}</h3>
        <p class="training-team">Équipe: {{ getTeamName(training.team_id) }}</p>
        <p v-if="training.notes" class="training-notes">{{ training.notes }}</p>
        <div class="training-actions">
          <Button variant="ghost" size="sm" @click="openEditModal(training)">Modifier</Button>
          <Button variant="danger" size="sm" :loading="deletingId === training.id" @click="handleDelete(training.id)">Supprimer</Button>
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

          <div class="input-wrapper">
            <label class="input-label">Équipe *</label>
            <select
              v-model="form.team_id"
              class="input select"
              :class="{ 'input-error': formErrors.team_id }"
            >
              <option value="">Sélectionner une équipe</option>
              <option v-for="team in teams" :key="team.id" :value="team.id">
                {{ team.name }}
              </option>
            </select>
            <span v-if="formErrors.team_id" class="input-error-text">{{ formErrors.team_id }}</span>
          </div>

          <Input
            v-model="form.date"
            label="Date et heure *"
            type="datetime-local"
            :error="formErrors.date"
          />

          <Input
            v-model="form.location"
            label="Lieu *"
            placeholder="Ex: Terrain principal"
            :error="formErrors.location"
          />

          <div class="input-wrapper">
            <label class="input-label">Type *</label>
            <select
              v-model="form.type"
              class="input select"
              :class="{ 'input-error': formErrors.type }"
            >
              <option value="">Sélectionner</option>
              <option v-for="type in typeOptions" :key="type" :value="type">
                {{ type }}
              </option>
            </select>
            <span v-if="formErrors.type" class="input-error-text">{{ formErrors.type }}</span>
          </div>

          <div class="input-wrapper">
            <label class="input-label">Notes</label>
            <textarea
              v-model="form.notes"
              class="input textarea"
              placeholder="Notes additionnelles..."
              rows="3"
            ></textarea>
          </div>

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
  margin: 0 0 0.25rem 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #1e293b;
}

.training-team {
  margin: 0 0 0.5rem 0;
  font-size: 0.875rem;
  color: #64748b;
}

.training-notes {
  margin: 0;
  font-size: 0.875rem;
  color: #64748b;
  line-height: 1.5;
}

.training-actions {
  display: flex;
  gap: 0.5rem;
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #e5e7eb;
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

.input-wrapper {
  display: flex;
  flex-direction: column;
  gap: 0.375rem;
}

.input-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
}

.select, .textarea {
  padding: 0.625rem 0.875rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 0.95rem;
  font-family: inherit;
  background: white;
}

.select {
  cursor: pointer;
}

.textarea {
  resize: vertical;
  min-height: 80px;
}

.select:focus, .textarea:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.input-error {
  border-color: #dc2626;
}

.input-error-text {
  font-size: 0.875rem;
  color: #dc2626;
}
</style>
