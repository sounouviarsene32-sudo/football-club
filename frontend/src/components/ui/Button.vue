<script setup>
defineProps({
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'danger', 'ghost'].includes(value),
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value),
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  loading: {
    type: Boolean,
    default: false,
  },
})
</script>

<template>
  <button
    class="btn"
    :class="[
      `btn-${variant}`,
      `btn-${size}`,
      { 'btn-disabled': disabled || loading },
    ]"
    :disabled="disabled || loading"
  >
    <span v-if="loading" class="btn-spinner"></span>
    <slot />
  </button>
</template>

<style scoped>
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  border: none;
  border-radius: 0.375rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  font-family: inherit;
}

.btn-sm {
  padding: 0.375rem 0.75rem;
  font-size: 0.875rem;
}

.btn-md {
  padding: 0.625rem 1.25rem;
  font-size: 0.95rem;
}

.btn-lg {
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
}

.btn-primary {
  background: #2563eb;
  color: white;
}

.btn-primary:hover:not(.btn-disabled) {
  background: #1d4ed8;
}

.btn-secondary {
  background: #64748b;
  color: white;
}

.btn-secondary:hover:not(.btn-disabled) {
  background: #475569;
}

.btn-danger {
  background: #dc2626;
  color: white;
}

.btn-danger:hover:not(.btn-disabled) {
  background: #b91c1c;
}

.btn-ghost {
  background: transparent;
  color: #475569;
  border: 1px solid #d1d5db;
}

.btn-ghost:hover:not(.btn-disabled) {
  background: #f3f4f6;
}

.btn-disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-spinner {
  width: 1rem;
  height: 1rem;
  border: 2px solid transparent;
  border-top-color: currentColor;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
