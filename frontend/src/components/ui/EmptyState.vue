<script setup>
defineProps({
  title: {
    type: String,
    default: 'Aucune donnée',
  },
  description: {
    type: String,
    default: 'Il n\'y a aucun élément à afficher pour le moment.',
  },
  actionLabel: String,
})
</script>

<template>
  <div class="empty-state">
    <div class="empty-icon">📭</div>
    <h3 class="empty-title">{{ title }}</h3>
    <p class="empty-description">{{ description }}</p>
    <button v-if="actionLabel" class="empty-action">
      {{ actionLabel }}
    </button>
  </div>
</template>

<style scoped>
.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem 2rem;
  text-align: center;
  color: #64748b;
}

.empty-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
  opacity: 0.6;
}

.empty-title {
  margin: 0 0 0.5rem 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #374151;
}

.empty-description {
  margin: 0 0 1.5rem 0;
  font-size: 0.95rem;
  max-width: 400px;
  line-height: 1.5;
}

.empty-action {
  padding: 0.625rem 1.25rem;
  background: #2563eb;
  color: white;
  border: none;
  border-radius: 0.375rem;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s;
}

.empty-action:hover {
  background: #1d4ed8;
}
</style>
