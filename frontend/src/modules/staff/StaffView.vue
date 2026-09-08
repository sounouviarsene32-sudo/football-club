<script setup>
import { ref, onMounted, computed } from 'vue'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Loading from '@/components/ui/Loading.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import Input from '@/components/ui/Input.vue'

const staff = ref([])
const teams = ref([])
const loading = ref(true)
const error = ref(null)
const showModal = ref(false)
const editingMember = ref(null)
const deletingId = ref(null)
const formLoading = ref(false)

const form = ref({
  first_name: '',
  last_name: '',
  role: '',
  team_id: '',
  email: '',
  phone: ''
})
const formErrors = ref({})

const isEditing = computed(() => editingMember.value !== null)
const modalTitle = computed(() => isEditing.value ? 'Modifier le membre' : 'Ajouter un membre')

const roleOptions = ['Entraîneur principal', 'Entraîneur adjoint', 'Préparateur physique', 'Médecin', 'Kinésithérapeute', 'Manager', 'Directeur technique', 'Analyste vidéo', 'Autre']

async function loadStaff() {
  loading.value = true
  error.value = null

  try {
    const [staffData, teamsData] = await Promise.all([
      api.get('/staff'),
      api.get('/teams')
    ])
    staff.value = staffData
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

function openCreateModal() {
  editingMember.value = null
  form.value = { first_name: '', last_name: '', role: '', team_id: '', email: '', phone: '' }
  formErrors.value = {}
  showModal.value = true
}

function openEditModal(member) {
  editingMember.value = member
  form.value = { ...member }
  formErrors.value = {}
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  editingMember.value = null
}

async function handleSubmit() {
  formLoading.value = true
  formErrors.value = {}

  try {
    if (isEditing.value) {
      await api.put(`/staff/${editingMember.value.id}`, form.value)
    } else {
      await api.post('/staff', form.value)
    }
    await loadStaff()
    closeModal()
  } catch (err) {
    formErrors.value = { general: err.message }
  } finally {
    formLoading.value = false
  }
}

async function handleDelete(id) {
  if (!confirm('Êtes-vous sûr de vouloir supprimer ce membre du staff ?')) return

  deletingId.value = id
  try {
    await api.delete(`/staff/${id}`)
    await loadStaff()
  } catch (err) {
    error.value = err.message
  } finally {
    deletingId.value = null
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
      <Button variant="primary" @click="openCreateModal">Ajouter un membre</Button>
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
      @action="openCreateModal"
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
            <p class="staff-team">Équipe: {{ getTeamName(member.team_id) }}</p>
            <p class="staff-contact">{{ member.email }} {{ member.phone ? '| ' + member.phone : '' }}</p>
          </div>
          <div class="staff-actions">
            <Button variant="ghost" size="sm" @click="openEditModal(member)">Modifier</Button>
            <Button variant="danger" size="sm" :loading="deletingId === member.id" @click="handleDelete(member.id)">Supprimer</Button>
          </div>
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
            <Input
              v-model="form.first_name"
              label="Prénom *"
              placeholder="Prénom"
              :error="formErrors.first_name"
            />
            <Input
              v-model="form.last_name"
              label="Nom *"
              placeholder="Nom"
              :error="formErrors.last_name"
            />
          </div>

          <div class="input-wrapper">
            <label class="input-label">Rôle *</label>
            <select
              v-model="form.role"
              class="input select"
              :class="{ 'input-error': formErrors.role }"
            >
              <option value="">Sélectionner</option>
              <option v-for="role in roleOptions" :key="role" :value="role">
                {{ role }}
              </option>
            </select>
            <span v-if="formErrors.role" class="input-error-text">{{ formErrors.role }}</span>
          </div>

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
            v-model="form.email"
            label="Email *"
            type="email"
            placeholder="email@exemple.com"
            :error="formErrors.email"
          />

          <Input
            v-model="form.phone"
            label="Téléphone"
            type="tel"
            placeholder="+33 6 00 00 00 00"
            :error="formErrors.phone"
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

.staff-details {
  flex: 1;
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

.staff-team {
  margin: 0.25rem 0 0 0;
  font-size: 0.875rem;
  color: #64748b;
}

.staff-contact {
  margin: 0.25rem 0 0 0;
  font-size: 0.875rem;
  color: #64748b;
}

.staff-actions {
  display: flex;
  gap: 0.5rem;
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
