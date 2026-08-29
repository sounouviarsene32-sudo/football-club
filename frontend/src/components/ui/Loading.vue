<script setup>
defineProps({
  text: {
    type: String,
    default: 'Chargement...',
  },
  fullscreen: {
    type: Boolean,
    default: false,
  },
})
</script>

<template>
  <div v-if="fullscreen" class="loading-fullscreen">
    <div class="loading-spinner"></div>
    <p v-if="text" class="loading-text">{{ text }}</p>
  </div>
  <div v-else class="loading-inline">
    <div class="loading-spinner"></div>
    <p v-if="text" class="loading-text">{{ text }}</p>
  </div>
</template>

<style scoped>
.loading-fullscreen {
  position: fixed;
  inset: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.9);
  z-index: 1000;
  gap: 1rem;
}

.loading-inline {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3rem;
  gap: 1rem;
}

.loading-spinner {
  width: 2.5rem;
  height: 2.5rem;
  border: 3px solid #e5e7eb;
  border-top-color: #2563eb;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

.loading-text {
  margin: 0;
  color: #64748b;
  font-size: 0.95rem;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
