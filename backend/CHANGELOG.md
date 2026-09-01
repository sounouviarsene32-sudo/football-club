# Changelog

Tous les changements notables de ce projet seront documentés dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/),
et ce projet adhère au [Semantic Versioning](https://semver.org/lang/fr/).

## [1.0.0] - 2026-09-01

### 🎉 Première version - Système d'authentification complet

### Ajouté
- ✅ Installation et configuration de Laravel Sanctum 4.3
- ✅ Module d'authentification complet dans `app/Modules/Auth/`
- ✅ Contrôleur `AuthController` avec 5 méthodes :
  - `register()` - Inscription utilisateur
  - `login()` - Connexion utilisateur
  - `logout()` - Déconnexion utilisateur
  - `me()` - Profil utilisateur authentifié
  - `refresh()` - Rafraîchissement du token
- ✅ Services d'authentification (`AuthService`, `AuthServiceInterface`)
- ✅ Repositories (`AuthRepository`, `AuthRepositoryInterface`)
- ✅ Validation des requêtes :
  - `LoginRequest` - Validation connexion
  - `RegisterRequest` - Validation inscription avec messages en français
- ✅ Resource API : `UserResource` pour formater les réponses
- ✅ Provider : `AuthServiceProvider` pour l'injection de dépendances
- ✅ Guard API `sanctum` dans `config/auth.php`
- ✅ Middleware `statefulApi` dans `bootstrap/app.php`
- ✅ Routes API d'authentification dans `routes/api.php`
- ✅ Trait `HasApiTokens` au modèle `User`
- ✅ Migration `personal_access_tokens` pour Sanctum
- ✅ Seeder `UserSeeder` avec comptes de test :
  - Admin : `admin@football-club.com` / `Admin123!`
  - Test : `test@football-club.com` / `Test123!`
  - 10 utilisateurs aléatoires

### Documentation
- ✅ `README.md` - Guide principal du projet
- ✅ `QUICK_START_AUTH.md` - Guide de démarrage rapide
- ✅ `AUTH_API_DOCUMENTATION.md` - Documentation complète de l'API
- ✅ `AUTHENTICATION_SUMMARY.md` - Résumé technique de l'implémentation
- ✅ `app/Modules/Auth/README.md` - Documentation du module Auth
- ✅ `API_TESTS.http` - Tests avec REST Client (VS Code)
- ✅ `postman_collection.json` - Collection Postman complète
- ✅ `CHANGELOG.md` - Ce fichier

### Configuration
- ✅ `.env.example` mis à jour avec :
  - Locale française par défaut (`APP_LOCALE=fr`)
  - Configuration Sanctum (`SANCTUM_STATEFUL_DOMAINS`)
  - Variables d'authentification
- ✅ Configuration du guard API avec Sanctum

### Sécurité
- ✅ Hashing des mots de passe avec bcrypt
- ✅ Validation stricte des entrées utilisateur
- ✅ Génération et gestion sécurisée des tokens
- ✅ Révocation automatique des tokens à la déconnexion
- ✅ Révocation des anciens tokens lors de la connexion
- ✅ Protection CSRF pour les APIs stateful
- ✅ Middleware `auth:sanctum` sur les routes protégées

### Routes protégées (exemples)
- ✅ `GET /api/football/teams` - Liste des équipes (requiert authentification)
- ✅ `GET /api/football/teams/{id}` - Détails d'une équipe (requiert authentification)

### Tests
- ✅ Collection Postman avec tests automatiques
- ✅ Fichier REST Client pour VS Code
- ✅ Exemples cURL dans la documentation
- ✅ Tests de validation (erreurs 422)
- ✅ Tests d'authentification (erreurs 401)
- ✅ Tests des routes protégées

### Technique
- ✅ Architecture Repository-Service Pattern
- ✅ Injection de dépendances (IoC)
- ✅ Interfaces pour services et repositories
- ✅ Type hints et return types sur toutes les méthodes
- ✅ Documentation PHPDoc complète
- ✅ Respect des conventions Laravel
- ✅ Code PSR-4 compliant

### Modifié
- 🔧 `app/Models/User.php` - Ajout du trait `HasApiTokens`
- 🔧 `config/auth.php` - Ajout du guard `api` avec driver `sanctum`
- 🔧 `bootstrap/app.php` - Configuration du middleware statefulApi
- 🔧 `bootstrap/providers.php` - Enregistrement de `AuthServiceProvider`
- 🔧 `routes/api.php` - Ajout des routes d'authentification et protection des routes existantes
- 🔧 `.env.example` - Mise à jour avec configuration Sanctum et locale française

### Dépendances
- ✅ `laravel/sanctum` ^4.3 - Authentification API
- ✅ `laravel/framework` ^13.17 - Framework Laravel
- ✅ `laravel/tinker` ^3.0 - REPL Laravel

## [0.1.0] - Initial (avant authentification)

### Existant
- Installation Laravel 13 de base
- Modules préparés (structure uniquement) :
  - Competitions
  - Dashboard
  - Events
  - Matches
  - News
  - Players
  - Staff
  - Teams
  - Trainings
- Configuration SQLite
- Routes de test `/api/football/teams`

---

## Types de changements

- `Ajouté` pour les nouvelles fonctionnalités
- `Modifié` pour les changements aux fonctionnalités existantes
- `Déprécié` pour les fonctionnalités qui seront bientôt supprimées
- `Supprimé` pour les fonctionnalités supprimées
- `Corrigé` pour les corrections de bugs
- `Sécurité` pour inviter les utilisateurs à mettre à jour en cas de vulnérabilités

---

## Versions à venir

### [1.1.0] - Planifié
- [ ] Email verification
- [ ] Password reset
- [ ] Rate limiting sur les routes sensibles
- [ ] Tests unitaires et fonctionnels

### [1.2.0] - Planifié
- [ ] Rôles et permissions (Spatie Laravel Permission)
- [ ] Refresh token rotation
- [ ] Token expiration configurable
- [ ] Activity logs

### [2.0.0] - Planifié
- [ ] Implémentation complète des modules métier
- [ ] Upload d'images
- [ ] API de statistiques
- [ ] Système de notifications

---

**Note**: Les dates suivent le format ISO 8601 (YYYY-MM-DD)
