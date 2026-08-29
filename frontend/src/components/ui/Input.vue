<script setup>
defineProps({
  modelValue: [String, Number],
  type: {
    type: String,
    default: 'text',
  },
  label: String,
  placeholder: String,
  error: String,
  disabled: Boolean,
})
</script>

<template>
  <div class="input-wrapper">
    <label v-if="label" class="input-label">{{ label }}</label>
    <input
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      class="input"
      :class="{ 'input-error': error }"
      @input="$emit('update:modelValue', $event.target.value)"
    />
    <span v-if="error" class="input-error-text">{{ error }}</span>
  </div>
</template>

<style scoped>
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

.input {
  padding: 0.625rem 0.875rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 0.95rem;
  font-family: inherit;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.input:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.input:disabled {
  background: #f3f4f6;
  cursor: not-allowed;
}

.input-error {
  border-color: #dc2626;
}

.input-error:focus {
  box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.input-error-text {
  font-size: 0.875rem;
  color: #dc2626;
}
</style>
