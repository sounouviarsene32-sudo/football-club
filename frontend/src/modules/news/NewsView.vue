<script setup>
import { ref, onMounted } from 'vue'
import { api } from '@/services/api'
import Card from '@/components/ui/Card.vue'
import Button from '@/components/ui/Button.vue'
import Loading from '@/components/ui/Loading.vue'
import EmptyState from '@/components/ui/EmptyState.vue'

const news = ref([])
const loading = ref(true)
const error = ref(null)

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
      <Button variant="primary">Publier une actualité</Button>
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
    />

    <div v-else class="news-list">
      <Card v-for="item in news" :key="item.id" class="news-card">
        <div class="news-header">
          <span class="news-date">{{ item.published_at }}</span>
        </div>
        <h3 class="news-title">{{ item.title }}</h3>
        <p class="news-content">{{ item.content }}</p>
      </Card>
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
  margin-bottom: 0.75rem;
}

.news-date {
  font-size: 0.875rem;
  color: #64748b;
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
</style>
