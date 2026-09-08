<script setup>
import { ref, onMounted, computed } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Loading from '@/components/ui/Loading.vue'
import EmptyState from '@/components/ui/EmptyState.vue'
import Input from '@/components/ui/Input.vue'

const authStore = useAuthStore()
const news = ref([])
const loading = ref(true)
const error = ref(null)
const showModal = ref(false)
const editingNews = ref(null)
const deletingId = ref(null)
const formLoading = ref(false)

const form = ref({
  title: '',
  content: '',
  published_at: ''
})
const formErrors = ref({})

const isEditing = computed(() => editingNews.value !== null)
const modalTitle = computed(() => isEditing.value ? 'Modifier l\'actualité' : 'Publier une actualité')

async function loadNews() {
  loading.value = true
  error.value = null

  try {
    news.value = await api.get('/news')
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

function formatDate(dateStr) {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' })
}

function openCreateModal() {
  editingNews.value = null
  const now = new Date().toISOString().slice(0, 16)
  form.value = { title: '', content: '', published_at: now }
  formErrors.value = {}
  showModal.value = true
}

function openEditModal(item) {
  editingNews.value = item
  form.value = {
    title: item.title,
    content: item.content,
    published_at: item.published_at ? item.published_at.split(' ')[0] + 'T' + (item.published_at.split(' ')[1] || '00:00') : ''
  }
  formErrors.value = {}
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  editingNews.value = null
}

async function handleSubmit() {
  formLoading.value = true
  formErrors.value = {}

  try {
    const data = { ...form.value }

    if (isEditing.value) {
      await api.put(`/news/${editingNews.value.id}`, data)
    } else {
      data.author_id = authStore.user?.id || 1
      await api.post('/news', data)
    }
    await loadNews()
    closeModal()
  } catch (err) {
    formErrors.value = { general: err.message }
  } finally {
    formLoading.value = false
  }
}

async function handleDelete(id) {
  if (!confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')) return

  deletingId.value = id
  try {
    await api.delete(`/news/${id}`)
    await loadNews()
  } catch (err) {
    error.value = err.message
  } finally {
    deletingId.value = null
  }
}

onMounted(() => {
  loadNews()
})
</script>

<template>
  <div class="news-page">
    <div class="page-header">
      <div>
        <h1 class="page-title">Actualités</h1>
        <p class="page-subtitle">Les dernières nouvelles</p>
      </div>
      <Button variant="primary" @click="openCreateModal">Publier une actualité</Button>
    </div>

    <Loading v-if="loading" text="Chargement des actualités..." />

    <div v-else-if="error" class="error">
      <p>Erreur: {{ error }}</p>
      <Button variant="secondary" @click="loadNews">Réessayer</Button>
    </div>

    <EmptyState
      v-else-if="news.length === 0"
      title="Aucune actualité"
      description="Soyez le premier à publier."
      action-label="Publier"
      @action="openCreateModal"
    />

    <div v-else class="news-list">
      <Card v-for="item in news" :key="item.id" class="news-card">
        <div class="news-header">
          <span class="news-date">{{ formatDate(item.published_at) }}</span>
          <div class="news-actions">
            <Button variant="ghost" size="sm" @click="openEditModal(item)">Modifier</Button>
            <Button variant="danger" size="sm" :loading="deletingId === item.id" @click="handleDelete(item.id)">Supprimer</Button>
          </div>
        </div>
        <h3 class="news-title">{{ item.title }}</h3>
        <p class="news-content">{{ item.content }}</p>
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
            v-model="form.title"
            label="Titre *"
            placeholder="Titre de l'actualité"
            :error="formErrors.title"
          />

          <div class="input-wrapper">
            <label class="input-label">Contenu *</label>
            <textarea
              v-model="form.content"
              class="input textarea"
              placeholder="Contenu de l'actualité..."
              rows="5"
              :class="{ 'input-error': formErrors.content }"
            ></textarea>
            <span v-if="formErrors.content" class="input-error-text">{{ formErrors.content }}</span>
          </div>

          <Input
            v-model="form.published_at"
            label="Date de publication *"
            type="datetime-local"
            :error="formErrors.published_at"
          />

          <div class="modal-actions">
            <Button variant="secondary" type="button" @click="closeModal">Annuler</Button>
            <Button variant="primary" type="submit" :loading="formLoading">
              {{ isEditing ? 'Modifier' : 'Publier' }}
            </Button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
.news-page {
  max-width: 800px;
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

.news-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.news-card {
  transition: transform 0.2s;
}

.news-card:hover {
  transform: translateX(4px);
}

.news-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
}

.news-date {
  font-size: 0.875rem;
  color: #64748b;
}

.news-actions {
  display: flex;
  gap: 0.5rem;
}

.news-title {
  margin: 0 0 0.75rem 0;
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
}

.news-content {
  margin: 0;
  font-size: 0.95rem;
  color: #475569;
  line-height: 1.6;
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
  max-width: 600px;
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

.textarea {
  padding: 0.625rem 0.875rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 0.95rem;
  font-family: inherit;
  background: white;
  resize: vertical;
  min-height: 100px;
}

.textarea:focus {
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
