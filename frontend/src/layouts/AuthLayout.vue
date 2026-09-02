<template>
  <div class="auth-layout">
    <div class="auth-bg">
      <div class="auth-shape auth-shape-1"></div>
      <div class="auth-shape auth-shape-2"></div>
      <div class="auth-shape auth-shape-3"></div>
    </div>
    <div class="auth-container">
      <div class="auth-brand">
        <h1 class="auth-logo">Laravel Foot</h1>
        <p class="auth-tagline">Gestion de club de football</p>
      </div>
      <div class="auth-card">
        <slot />
      </div>
    </div>
  </div>
</template>

<style scoped>
.auth-layout {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
  background: #f8fafc;
  padding: 1.5rem;
}

.auth-bg {
  position: absolute;
  inset: 0;
  z-index: 0;
  overflow: hidden;
}

.auth-shape {
  position: absolute;
  border-radius: 50%;
  opacity: 0.15;
}

.auth-shape-1 {
  width: 500px;
  height: 500px;
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  top: -150px;
  right: -100px;
}

.auth-shape-2 {
  width: 350px;
  height: 350px;
  background: linear-gradient(135deg, #1e40af, #2563eb);
  bottom: -100px;
  left: -80px;
}

.auth-shape-3 {
  width: 200px;
  height: 200px;
  background: linear-gradient(135deg, #3b82f6, #60a5fa);
  top: 40%;
  left: 50%;
  transform: translateX(-50%);
}

.auth-container {
  position: relative;
  z-index: 1;
  width: 100%;
  max-width: 420px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1.5rem;
}

.auth-brand {
  text-align: center;
  margin-bottom: 0.5rem;
}

.auth-logo {
  font-size: 1.75rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
  letter-spacing: -0.02em;
}

.auth-tagline {
  font-size: 0.9rem;
  color: #64748b;
  margin: 0.5rem 0 0;
}

.auth-card {
  width: 100%;
  background: white;
  border-radius: 1rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.01);
  padding: 2.25rem;
  border: 1px solid #e2e8f0;
}

@media (max-width: 480px) {
  .auth-card {
    padding: 1.75rem;
  }
}
</style>
