# Guide de Démarrage Rapide - Authentification

## 🚀 Installation et Configuration

### 1. Installation des dépendances
```bash
cd backend
composer install
```

### 2. Configuration de l'environnement
```bash
# Copier le fichier .env.example
copy .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 3. Configuration de la base de données
Le projet utilise SQLite par défaut. La base de données sera créée automatiquement lors de la migration.

```bash
# Exécuter les migrations
php artisan migrate

# (Optionnel) Créer des utilisateurs de test
php artisan db:seed --class=UserSeeder
```

### 4. Démarrer le serveur
```bash
php artisan serve
```

Le serveur démarre sur `http://localhost:8000`

---

## ✅ Vérification de l'installation

### Test de santé de l'API
```bash
curl http://localhost:8000/api/health
```

Réponse attendue :
```json
{
  "status": "ok",
  "app": "Laravel",
  "time": "2026-09-01 15:30:00"
}
```

---

## 🔑 Tester l'authentification

### 1. Créer un compte (Register)
```bash
curl -X POST http://localhost:8000/api/auth/register ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"name\":\"John Doe\",\"email\":\"john@example.com\",\"password\":\"Password123!\",\"password_confirmation\":\"Password123!\"}"
```

### 2. Se connecter (Login)
```bash
curl -X POST http://localhost:8000/api/auth/login ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"email\":\"john@example.com\",\"password\":\"Password123!\"}"
```

**Réponse :**
```json
{
  "message": "Connexion réussie",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    ...
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz..."
}
```

**⚠️ Important : Copier le token pour les prochaines requêtes**

### 3. Accéder à votre profil (Route protégée)
```bash
curl -X GET http://localhost:8000/api/auth/me ^
  -H "Authorization: Bearer VOTRE_TOKEN_ICI" ^
  -H "Accept: application/json"
```

### 4. Accéder aux équipes de football (Route protégée)
```bash
curl -X GET http://localhost:8000/api/football/teams ^
  -H "Authorization: Bearer VOTRE_TOKEN_ICI" ^
  -H "Accept: application/json"
```

### 5. Se déconnecter
```bash
curl -X POST http://localhost:8000/api/auth/logout ^
  -H "Authorization: Bearer VOTRE_TOKEN_ICI" ^
  -H "Accept: application/json"
```

---

## 🧪 Utiliser les comptes de test

Si vous avez exécuté le seeder (`php artisan db:seed --class=UserSeeder`), vous pouvez utiliser :

### Compte Admin
```json
{
  "email": "admin@football-club.com",
  "password": "Admin123!"
}
```

### Compte Test
```json
{
  "email": "test@football-club.com",
  "password": "Test123!"
}
```

---

## 🛠️ Outils recommandés

### Option 1 : VS Code REST Client (Recommandé)
1. Installer l'extension "REST Client" dans VS Code
2. Ouvrir le fichier `API_TESTS.http`
3. Cliquer sur "Send Request" au-dessus de chaque requête

### Option 2 : Postman
1. Importer la collection depuis `AUTH_API_DOCUMENTATION.md`
2. Configurer l'environnement avec `baseUrl = http://localhost:8000/api`

### Option 3 : cURL
Utiliser les commandes ci-dessus directement dans le terminal

---

## 📋 Checklist de vérification

- [ ] Serveur Laravel démarré
- [ ] Migrations exécutées
- [ ] Health check fonctionnel
- [ ] Inscription d'un nouvel utilisateur réussie
- [ ] Connexion réussie et token reçu
- [ ] Accès au profil utilisateur avec le token
- [ ] Accès aux routes protégées avec le token
- [ ] Déconnexion réussie

---

## 🔒 Sécurité

### En développement
- Les tokens n'expirent pas par défaut
- CORS configuré pour localhost
- Logs détaillés activés

### En production (À configurer)
```env
APP_ENV=production
APP_DEBUG=false
SANCTUM_STATEFUL_DOMAINS=votredomaine.com
```

**Recommandations :**
- Activer HTTPS
- Configurer l'expiration des tokens
- Implémenter le rate limiting
- Activer la vérification d'email

---

## 🐛 Dépannage

### Erreur : "Unauthenticated"
- Vérifier que le token est bien inclus dans le header Authorization
- Vérifier le format : `Bearer TOKEN` (avec espace)
- Le token est peut-être expiré ou révoqué

### Erreur : "Route not found"
- Vérifier que le serveur est démarré : `php artisan serve`
- Vérifier l'URL : `http://localhost:8000/api/...`

### Erreur : "Database not found"
- Exécuter les migrations : `php artisan migrate`

### Erreur : "Validation failed"
- Vérifier les données envoyées
- Le mot de passe doit avoir au moins 8 caractères
- L'email doit être valide et unique

---

## 📚 Documentation complète

- **API Documentation** : Voir `AUTH_API_DOCUMENTATION.md`
- **Module Auth** : Voir `app/Modules/Auth/README.md`
- **Tests HTTP** : Voir `API_TESTS.http`

---

## 🎯 Prochaines étapes

1. ✅ **Authentification de base** - Fait !
2. 🔄 **Tester tous les endpoints**
3. 🎨 **Intégrer avec le frontend**
4. 🚀 **Ajouter des fonctionnalités avancées** :
   - Vérification d'email
   - Réinitialisation de mot de passe
   - Rôles et permissions
   - 2FA (Authentification à deux facteurs)

---

## 💡 Conseils

### Gestion des tokens frontend
```javascript
// Sauvegarder le token
localStorage.setItem('auth_token', token);

// Récupérer le token
const token = localStorage.getItem('auth_token');

// Configurer Axios
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
```

### Intercepteur de requêtes (Axios)
```javascript
axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response.status === 401) {
      // Rediriger vers login
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);
```

---

**Félicitations ! 🎉 Votre système d'authentification est maintenant opérationnel !**
