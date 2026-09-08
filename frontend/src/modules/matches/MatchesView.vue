<script setup>
import { ref, onMounted, computed } from 'vue'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Loading from '@/components/ui/Loading.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import Input from '@/components/ui/Input.vue'

const matches = ref([])
const teams = ref([])
const competitions = ref([])
const loading = ref(true)
const error = ref(null)
const showModal = ref(false)
const editingMatch = ref(null)
const deletingId = ref(null)
const formLoading = ref(false)

const form = ref({
  home_team_id: '',
  away_team_id: '',
  competition_id: '',
  date: '',
  venue: '',
  status: 'scheduled',
  home_score: '',
  away_score: ''
})
const formErrors = ref({})

const isEditing = computed(() => editingMatch.value !== null)
const modalTitle = computed(() => isEditing.value ? 'Modifier le match' : 'Ajouter un match')

const statusOptions = [
  { value: 'scheduled', label: 'Programmé' },
  { value: 'live', label: 'En cours' },
  { value: 'finished', label: 'Terminé' }
]

async function loadMatches() {
  loading.value = true
  error.value = null

  try {
    const [matchesData, teamsData, competitionsData] = await Promise.all([
      api.get('/matches'),
      api.get('/teams'),
      api.get('/competitions')
    ])
    matches.value = matchesData
    teams.value = teamsData
    competitions.value = competitionsData
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

function getTeamName(teamId) {
  const team = teams.value.find(t => t.id === teamId)
  return team ? team.name : 'Inconnu'
}

function getCompetitionName(compId) {
  const comp = competitions.value.find(c => c.id === compId)
  return comp ? comp.name : 'Non défini'
}

function formatDate(dateStr) {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}

function getStatusClass(status) {
  const classes = {
    scheduled: 'status-scheduled',
    live: 'status-live',
    finished: 'status-finished'
  }
  return classes[status] || 'status-scheduled'
}

function getStatusLabel(status) {
  const labels = {
    scheduled: 'Programmé',
    live: 'En cours',
    finished: 'Terminé'
  }
  return labels[status] || status
}

function openCreateModal() {
  editingMatch.value = null
  form.value = {
    home_team_id: '',
    away_team_id: '',
    competition_id: '',
    date: '',
    venue: '',
    status: 'scheduled',
    home_score: '',
    away_score: ''
  }
  formErrors.value = {}
  showModal.value = true
}

function openEditModal(match) {
  editingMatch.value = match
  form.value = {
    home_team_id: match.home_team_id,
    away_team_id: match.away_team_id,
    competition_id: match.competition_id,
    date: match.date ? match.date.split(' ')[0] : '',
    venue: match.venue || '',
    status: match.status,
    home_score: match.home_score ?? '',
    away_score: match.away_score ?? ''
  }
  formErrors.value = {}
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  editingMatch.value = null
}

async function handleSubmit() {
  formLoading.value = true
  formErrors.value = {}

  try {
    const data = { ...form.value }
    if (!data.home_score && data.home_score !== 0) delete data.home_score
    if (!data.away_score && data.away_score !== 0) delete data.away_score

    if (isEditing.value) {
      await api.put(`/matches/${editingMatch.value.id}`, data)
    } else {
      await api.post('/matches', data)
    }
    await loadMatches()
    closeModal()
  } catch (err) {
    formErrors.value = { general: err.message }
  } finally {
    formLoading.value = false
  }
}

async function handleDelete(id) {
  if (!confirm('Êtes-vous sûr de vouloir supprimer ce match ?')) return

  deletingId.value = id
  try {
    await api.delete(`/matches/${id}`)
    await loadMatches()
  } catch (err) {
    error.value = err.message
  } finally {
    deletingId.value = null
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
      <Button variant="primary" @click="openCreateModal">Ajouter un match</Button>
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
      @action="openCreateModal"
    />

    <div v-else class="matches-list">
      <Card v-for="match in matches" :key="match.id" class="match-card">
        <div class="match-header">
          <span class="match-status" :class="getStatusClass(match.status)">
            {{ getStatusLabel(match.status) }}
          </span>
          <span class="match-date">{{ formatDate(match.date) }}</span>
        </div>
        <div class="match-teams">
          <div class="team">
            <span class="team-name">{{ getTeamName(match.home_team_id) }}</span>
            <span class="score">{{ match.home_score ?? '-' }}</span>
          </div>
          <span class="vs">VS</span>
          <div class="team">
            <span class="score">{{ match.away_score ?? '-' }}</span>
            <span class="team-name">{{ getTeamName(match.away_team_id) }}</span>
          </div>
        </div>
        <div class="match-info">
          <span class="competition">{{ getCompetitionName(match.competition_id) }}</span>
          <span v-if="match.venue" class="venue">{{ match.venue }}</span>
        </div>
        <div class="match-actions">
          <Button variant="ghost" size="sm" @click="openEditModal(match)">Modifier</Button>
          <Button variant="danger" size="sm" :loading="deletingId === match.id" @click="handleDelete(match.id)">Supprimer</Button>
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

          <div class="form-row">
            <div class="input-wrapper">
              <label class="input-label">Équipe domicile *</label>
              <select
                v-model="form.home_team_id"
                class="input select"
                :class="{ 'input-error': formErrors.home_team_id }"
              >
                <option value="">Sélectionner</option>
                <option v-for="team in teams" :key="team.id" :value="team.id">
                  {{ team.name }}
                </option>
              </select>
              <span v-if="formErrors.home_team_id" class="input-error-text">{{ formErrors.home_team_id }}</span>
            </div>

            <div class="input-wrapper">
              <label class="input-label">Équipe visiteur *</label>
              <select
                v-model="form.away_team_id"
                class="input select"
                :class="{ 'input-error': formErrors.away_team_id }"
              >
                <option value="">Sélectionner</option>
                <option v-for="team in teams" :key="team.id" :value="team.id">
                  {{ team.name }}
                </option>
              </select>
              <span v-if="formErrors.away_team_id" class="input-error-text">{{ formErrors.away_team_id }}</span>
            </div>
          </div>

          <div class="input-wrapper">
            <label class="input-label">Compétition *</label>
            <select
              v-model="form.competition_id"
              class="input select"
              :class="{ 'input-error': formErrors.competition_id }"
            >
              <option value="">Sélectionner</option>
              <option v-for="comp in competitions" :key="comp.id" :value="comp.id">
                {{ comp.name }}
              </option>
            </select>
            <span v-if="formErrors.competition_id" class="input-error-text">{{ formErrors.competition_id }}</span>
          </div>

          <Input
            v-model="form.date"
            label="Date et heure *"
            type="datetime-local"
            :error="formErrors.date"
          />

          <Input
            v-model="form.venue"
            label="Lieu"
            placeholder="Stade, ville..."
            :error="formErrors.venue"
          />

          <div class="input-wrapper">
            <label class="input-label">Statut *</label>
            <select
              v-model="form.status"
              class="input select"
              :class="{ 'input-error': formErrors.status }"
            >
              <option v-for="opt in statusOptions" :key="opt.value" :value="opt.value">
                {{ opt.label }}
              </option>
            </select>
          </div>

          <div class="form-row">
            <Input
              v-model="form.home_score"
              label="Score domicile"
              type="number"
              min="0"
              placeholder="0"
              :error="formErrors.home_score"
            />
            <Input
              v-model="form.away_score"
              label="Score visiteur"
              type="number"
              min="0"
              placeholder="0"
              :error="formErrors.away_score"
            />
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
  display: flex;
  align-items: center;
  gap: 1rem;
}

.team-name {
  font-weight: 500;
  color: #1e293b;
}

.score {
  font-size: 1.5rem;
  font-weight: 700;
  color: #2563eb;
  min-width: 2rem;
  text-align: center;
}

.vs {
  font-weight: 600;
  color: #94a3b8;
}

.match-info {
  display: flex;
  justify-content: center;
  gap: 1rem;
  font-size: 0.875rem;
  color: #64748b;
  margin-bottom: 0.5rem;
}

.match-actions {
  display: flex;
  justify-content: flex-end;
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
  max-width: 550px;
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

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
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

.select {
  padding: 0.625rem 0.875rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 0.95rem;
  font-family: inherit;
  background: white;
  cursor: pointer;
}

.select:focus {
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
