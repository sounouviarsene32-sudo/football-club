# Module d'Authentification

Ce module fournit une implémentation complète de l'authentification API avec Laravel Sanctum.

## Structure

```
Auth/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php      # Contrôleur principal d'authentification
│   │   └── Controller.php          # Contrôleur de base
│   ├── Requests/
│   │   ├── LoginRequest.php        # Validation de la connexion
│   │   └── RegisterRequest.php     # Validation de l'inscription
│   └── Resources/
│       └── UserResource.php        # Transformation des données utilisateur
├── Repositories/
│   ├── AuthRepository.php          # Implémentation du repository
│   └── AuthRepositoryInterface.php # Interface du repository
├── Services/
│   ├── AuthService.php             # Logique métier
│   └── AuthServiceInterface.php    # Interface du service
└── Providers/
    └── AuthServiceProvider.php     # Service provider du module
```

## Fonctionnalités

### ✅ Inscription
- Validation des données (nom, email, mot de passe)
- Création de compte utilisateur
- Génération automatique de token

### ✅ Connexion
- Validation des identifiants
- Révocation des anciens tokens
- Génération d'un nouveau token

### ✅ Déconnexion
- Révocation du token actuel
- Suppression sécurisée de la session

### ✅ Profil utilisateur
- Récupération des informations de l'utilisateur authentifié
- Format JSON standardisé

### ✅ Rafraîchissement de token
- Génération d'un nouveau token
- Révocation de l'ancien token

## Utilisation

### Inscription d'un utilisateur

```php
POST /api/auth/register

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "Password123!",
  "password_confirmation": "Password123!"
}
```

### Connexion

```php
POST /api/auth/login

{
  "email": "john@example.com",
  "password": "Password123!"
}
```

### Accès aux routes protégées

```php
GET /api/auth/me
Authorization: Bearer {token}
```

## Dépendances

- Laravel Sanctum
- Laravel Framework 13+
- PHP 8.3+

## Configuration

Le module utilise le guard `api` configuré dans `config/auth.php` :

```php
'guards' => [
    'api' => [
        'driver' => 'sanctum',
        'provider' => 'users',
    ],
],
```

## Sécurité

- Mots de passe hashés avec bcrypt
- Tokens révoqués à la déconnexion
- Validation stricte des entrées
- Protection CSRF pour les APIs stateful

## Extension

Pour ajouter de nouvelles fonctionnalités :

1. Ajouter des méthodes dans `AuthServiceInterface`
2. Implémenter dans `AuthService`
3. Créer les requests de validation si nécessaire
4. Ajouter les endpoints dans `AuthController`
5. Mettre à jour les routes dans `routes/api.php`

## Tests

```bash
# Créer un utilisateur de test
php artisan tinker
>>> User::factory()->create(['email' => 'test@example.com'])

# Tester avec cURL
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'
```
