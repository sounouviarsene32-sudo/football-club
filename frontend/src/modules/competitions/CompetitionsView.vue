<script setup>
import { ref, onMounted, computed } from 'vue'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Loading from '@/components/ui/Loading.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import Input from '@/components/ui/Input.vue'

const competitions = ref([])
const loading = ref(true)
const error = ref(null)
const showModal = ref(false)
const editingComp = ref(null)
const deletingId = ref(null)
const formLoading = ref(false)

const form = ref({
  name: '',
  season: '',
  start_date: '',
  end_date: '',
  type: ''
})
const formErrors = ref({})

const isEditing = computed(() => editingComp.value !== null)
const modalTitle = computed(() => isEditing.value ? 'Modifier la compétition' : 'Ajouter une compétition')

const typeOptions = ['Championnat', 'Coupe', 'League Cup', 'Supercoupe', 'Amical']

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

function formatDate(dateStr) {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' })
}

function openCreateModal() {
  editingComp.value = null
  form.value = { name: '', season: '', start_date: '', end_date: '', type: '' }
  formErrors.value = {}
  showModal.value = true
}

function openEditModal(comp) {
  editingComp.value = comp
  form.value = {
    name: comp.name,
    season: comp.season,
    start_date: comp.start_date,
    end_date: comp.end_date,
    type: comp.type
  }
  formErrors.value = {}
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  editingComp.value = null
}

async function handleSubmit() {
  formLoading.value = true
  formErrors.value = {}

  try {
    if (isEditing.value) {
      await api.put(`/competitions/${editingComp.value.id}`, form.value)
    } else {
      await api.post('/competitions', form.value)
    }
    await loadCompetitions()
    closeModal()
  } catch (err) {
    formErrors.value = { general: err.message }
  } finally {
    formLoading.value = false
  }
}

async function handleDelete(id) {
  if (!confirm('Êtes-vous sûr de vouloir supprimer cette compétition ?')) return

  deletingId.value = id
  try {
    await api.delete(`/competitions/${id}`)
    await loadCompetitions()
  } catch (err) {
    error.value = err.message
  } finally {
    deletingId.value = null
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
      <Button variant="primary" @click="openCreateModal">Ajouter une compétition</Button>
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
      @action="openCreateModal"
    />

    <div v-else class="competitions-grid">
      <Card v-for="comp in competitions" :key="comp.id" class="comp-card">
        <div class="comp-header">
          <span class="comp-type">{{ comp.type }}</span>
          <span class="comp-season">{{ comp.season }}</span>
        </div>
        <h3 class="comp-name">{{ comp.name }}</h3>
        <p class="comp-dates">
          {{ formatDate(comp.start_date) }} - {{ formatDate(comp.end_date) }}
        </p>
        <div class="comp-actions">
          <Button variant="ghost" size="sm" @click="openEditModal(comp)">Modifier</Button>
          <Button variant="danger" size="sm" :loading="deletingId === comp.id" @click="handleDelete(comp.id)">Supprimer</Button>
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
            label="Nom *"
            placeholder="Ex: Ligue 1"
            :error="formErrors.name"
          />

          <Input
            v-model="form.season"
            label="Saison *"
            placeholder="Ex: 2024-2025"
            :error="formErrors.season"
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

          <div class="form-row">
            <Input
              v-model="form.start_date"
              label="Date de début *"
              type="date"
              :error="formErrors.start_date"
            />
            <Input
              v-model="form.end_date"
              label="Date de fin *"
              type="date"
              :error="formErrors.end_date"
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
  margin: 0 0 0.5rem 0;
  font-size: 0.875rem;
  color: #64748b;
}

.comp-actions {
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
