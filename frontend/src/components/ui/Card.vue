<script setup>
defineProps({
  title: String,
  subtitle: String,
  padding: {
    type: String,
    default: 'normal',
    validator: (value) => ['none', 'normal', 'large'].includes(value),
  },
})
</script>

<template>
  <div class="card" :class="`card-padding-${padding}`">
    <div v-if="title || subtitle" class="card-header">
      <h3 v-if="title" class="card-title">{{ title }}</h3>
      <p v-if="subtitle" class="card-subtitle">{{ subtitle }}</p>
    </div>
    <div class="card-body">
      <slot />
    </div>
  </div>
</template>

<style scoped>
.card {
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.card-padding-none .card-body {
  padding: 0;
}

.card-padding-normal .card-body {
  padding: 1.5rem;
}

.card-padding-large .card-body {
  padding: 2rem;
}

.card-header {
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.card-title {
  margin: 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #1e293b;
}

.card-subtitle {
  margin: 0.25rem 0 0 0;
  font-size: 0.875rem;
  color: #64748b;
}
</style>
