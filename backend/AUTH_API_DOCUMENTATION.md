# Documentation API d'Authentification

## Vue d'ensemble

L'API d'authentification utilise **Laravel Sanctum** pour fournir un système d'authentification basé sur des tokens API sécurisés.

## Base URL
```
http://localhost:8000/api
```

---

## Endpoints d'authentification

### 1. Inscription (Register)

**POST** `/api/auth/register`

Crée un nouveau compte utilisateur.

#### Request Body
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "Password123!",
  "password_confirmation": "Password123!"
}
```

#### Validation Rules
- `name`: requis, string, max 255 caractères
- `email`: requis, email valide, unique
- `password`: requis, confirmé, conforme aux règles de sécurité Laravel
- `password_confirmation`: doit correspondre au mot de passe

#### Response (201 Created)
```json
{
  "message": "Utilisateur créé avec succès",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": null,
    "created_at": "2026-09-01T15:30:00.000000Z",
    "updated_at": "2026-09-01T15:30:00.000000Z"
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz123456789"
}
```

---

### 2. Connexion (Login)

**POST** `/api/auth/login`

Authentifie un utilisateur existant.

#### Request Body
```json
{
  "email": "john@example.com",
  "password": "Password123!"
}
```

#### Validation Rules
- `email`: requis, email valide
- `password`: requis

#### Response Success (200 OK)
```json
{
  "message": "Connexion réussie",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": null,
    "created_at": "2026-09-01T15:30:00.000000Z",
    "updated_at": "2026-09-01T15:30:00.000000Z"
  },
  "token": "2|zyxwvutsrqponmlkjihgfedcba987654321"
}
```

#### Response Error (401 Unauthorized)
```json
{
  "message": "Identifiants invalides"
}
```

**Note**: À chaque connexion, les anciens tokens sont révoqués pour des raisons de sécurité.

---

### 3. Déconnexion (Logout)

**POST** `/api/auth/logout`

Déconnecte l'utilisateur actuel et révoque le token en cours.

#### Headers
```
Authorization: Bearer {token}
```

#### Response (200 OK)
```json
{
  "message": "Déconnexion réussie"
}
```

---

### 4. Utilisateur actuel (Me)

**GET** `/api/auth/me`

Récupère les informations de l'utilisateur authentifié.

#### Headers
```
Authorization: Bearer {token}
```

#### Response (200 OK)
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": null,
    "created_at": "2026-09-01T15:30:00.000000Z",
    "updated_at": "2026-09-01T15:30:00.000000Z"
  }
}
```

---

### 5. Rafraîchir le token (Refresh)

**POST** `/api/auth/refresh`

Génère un nouveau token et révoque l'ancien.

#### Headers
```
Authorization: Bearer {token}
```

#### Response (200 OK)
```json
{
  "message": "Token rafraîchi",
  "token": "3|newtoken123456789abcdefghijklmnopqrstuvwxyz"
}
```

---

## Utilisation du token

Pour accéder aux routes protégées, incluez le token dans le header Authorization :

```bash
Authorization: Bearer {token}
```

### Exemple avec cURL
```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456789" \
  -H "Accept: application/json"
```

### Exemple avec JavaScript (Fetch)
```javascript
fetch('http://localhost:8000/api/auth/me', {
  method: 'GET',
  headers: {
    'Authorization': 'Bearer 1|abcdefghijklmnopqrstuvwxyz123456789',
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

---

## Routes protégées

Les routes suivantes sont maintenant protégées par l'authentification :

- **GET** `/api/football/teams` - Liste des équipes
- **GET** `/api/football/teams/{id}` - Détails d'une équipe

Ces routes nécessitent un token Bearer valide dans le header Authorization.

---

## Codes d'erreur

| Code | Description |
|------|-------------|
| 200  | Succès |
| 201  | Ressource créée |
| 401  | Non authentifié / Identifiants invalides |
| 422  | Erreur de validation |
| 500  | Erreur serveur |

---

## Exemples de tests avec cURL

### 1. Inscription
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "Password123!",
    "password_confirmation": "Password123!"
  }'
```

### 2. Connexion
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "Password123!"
  }'
```

### 3. Accéder à une route protégée
```bash
curl -X GET http://localhost:8000/api/football/teams \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

### 4. Déconnexion
```bash
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

---

## Architecture du module Auth

```
app/Modules/Auth/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   └── Controller.php
│   ├── Requests/
│   │   ├── LoginRequest.php
│   │   └── RegisterRequest.php
│   └── Resources/
│       └── UserResource.php
├── Repositories/
│   ├── AuthRepository.php
│   └── AuthRepositoryInterface.php
├── Services/
│   ├── AuthService.php
│   └── AuthServiceInterface.php
└── Providers/
    └── AuthServiceProvider.php
```

### Pattern Architecture
- **Controller**: Gère les requêtes HTTP et les réponses
- **Request**: Validation des données entrantes
- **Resource**: Transformation des données sortantes
- **Service**: Logique métier de l'authentification
- **Repository**: Interaction avec la base de données
- **Provider**: Enregistrement des dépendances (IoC)

---

## Sécurité

### Tokens
- Les tokens sont générés via Laravel Sanctum
- Stockés dans la table `personal_access_tokens`
- Pas d'expiration automatique (peut être configuré)
- Révocation à la déconnexion

### Mots de passe
- Hashés avec bcrypt
- Règles de validation configurables
- Vérification via `Hash::check()`

### Best Practices
✅ Toujours utiliser HTTPS en production  
✅ Stocker les tokens de manière sécurisée (localStorage/sessionStorage)  
✅ Implémenter un système de refresh token  
✅ Limiter les tentatives de connexion (rate limiting)  
✅ Valider les entrées utilisateur  

---

## Configuration

### Guard API (config/auth.php)
```php
'guards' => [
    'api' => [
        'driver' => 'sanctum',
        'provider' => 'users',
    ],
],
```

### Sanctum (config/sanctum.php)
```php
'expiration' => null, // Pas d'expiration par défaut
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
))),
```

---

## Prochaines étapes recommandées

1. **Vérification d'email**: Implémenter l'email verification
2. **Réinitialisation de mot de passe**: Routes forgot/reset password
3. **Rate Limiting**: Limiter les tentatives de connexion
4. **Refresh Token**: Système de rotation des tokens
5. **Rôles et Permissions**: Ajouter Spatie Laravel Permission
6. **2FA**: Authentification à deux facteurs
7. **OAuth**: Intégration Google, Facebook, etc.

---

## Support

Pour toute question ou problème :
- Vérifier les logs Laravel : `storage/logs/laravel.log`
- Tester avec Postman ou Insomnia
- Consulter la documentation Sanctum : https://laravel.com/docs/sanctum
