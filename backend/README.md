# Football Club - Backend API

## 📋 Vue d'ensemble

API REST construite avec Laravel 13 pour la gestion d'un club de football. Le backend fournit un système d'authentification complet basé sur Laravel Sanctum avec une architecture modulaire professionnelle.

## 🚀 Installation Rapide

### Prérequis
- PHP 8.3+
- Composer
- SQLite (ou MySQL/PostgreSQL)

### Installation

```bash
# 1. Installer les dépendances
composer install

# 2. Copier et configurer l'environnement
copy .env.example .env

# 3. Générer la clé d'application
php artisan key:generate

# 4. Créer la base de données et migrer
php artisan migrate

# 5. (Optionnel) Créer des utilisateurs de test
# Modifier APP_ENV=local dans .env puis :
php artisan db:seed --class=UserSeeder

# 6. Démarrer le serveur
php artisan serve
```

Le serveur sera accessible sur `http://localhost:8000`

## 🔐 Authentification

Le système d'authentification est **100% opérationnel** avec Laravel Sanctum.

### Endpoints disponibles

#### Routes publiques
- `POST /api/auth/register` - Inscription
- `POST /api/auth/login` - Connexion

#### Routes protégées (nécessitent un token Bearer)
- `GET /api/auth/me` - Profil utilisateur
- `POST /api/auth/refresh` - Rafraîchir le token
- `POST /api/auth/logout` - Déconnexion

### Test Rapide

```bash
# 1. Health check
curl http://localhost:8000/api/health

# 2. Login (utilisateurs de test après seeding)
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"admin@football-club.com\",\"password\":\"Admin123!\"}"

# 3. Utiliser le token reçu pour accéder aux routes protégées
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer VOTRE_TOKEN"
```

### Comptes de test (après seeding)
- **Admin** : `admin@football-club.com` / `Admin123!`
- **Test** : `test@football-club.com` / `Test123!`

## 📚 Documentation

### Guides de démarrage
- 📖 **[Guide de démarrage rapide](QUICK_START_AUTH.md)** - Commencer en 5 minutes
- 📖 **[Documentation API complète](AUTH_API_DOCUMENTATION.md)** - Tous les endpoints détaillés
- 📖 **[Résumé de l'authentification](AUTHENTICATION_SUMMARY.md)** - Vue d'ensemble technique

### Tests
- 🧪 **[Tests REST Client](API_TESTS.http)** - Tests avec VS Code REST Client
- 🧪 **[Collection Postman](postman_collection.json)** - Tests avec Postman

### Architecture
- 🏗️ **[Module Auth](app/Modules/Auth/README.md)** - Documentation du module d'authentification

## 🏗️ Architecture

### Structure du projet

```
backend/
├── app/
│   ├── Modules/
│   │   ├── Auth/              # Module d'authentification
│   │   │   ├── Http/
│   │   │   │   ├── Controllers/
│   │   │   │   ├── Requests/
│   │   │   │   └── Resources/
│   │   │   ├── Repositories/
│   │   │   ├── Services/
│   │   │   └── Providers/
│   │   ├── Competitions/      # Gestion des compétitions
│   │   ├── Events/            # Gestion des événements
│   │   ├── Matches/           # Gestion des matchs
│   │   ├── News/              # Gestion des actualités
│   │   ├── Players/           # Gestion des joueurs
│   │   ├── Staff/             # Gestion du staff
│   │   ├── Teams/             # Gestion des équipes
│   │   └── Trainings/         # Gestion des entraînements
│   └── Models/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   ├── api.php
│   └── web.php
└── docs/                      # Documentation
```

### Pattern Architecture : Repository-Service

```
Request → Controller → Service → Repository → Model → Database
         ↓
      Response ← Resource ←
```

**Avantages :**
- Séparation des responsabilités
- Code testable et maintenable
- Injection de dépendances (IoC)
- Flexibilité et évolutivité

## 🔧 Technologies

- **Framework** : Laravel 13.17
- **Authentification** : Laravel Sanctum 4.3
- **Base de données** : SQLite (développement), MySQL/PostgreSQL (production)
- **PHP** : 8.3+
- **Autoloading** : PSR-4

## 📦 Modules Disponibles

| Module | Status | Description |
|--------|--------|-------------|
| Auth | ✅ Complet | Authentification (register, login, logout, etc.) |
| Competitions | 🏗️ Structure prête | Gestion des compétitions |
| Events | 🏗️ Structure prête | Gestion des événements |
| Matches | 🏗️ Structure prête | Gestion des matchs |
| News | 🏗️ Structure prête | Gestion des actualités |
| Players | 🏗️ Structure prête | Gestion des joueurs |
| Staff | 🏗️ Structure prête | Gestion du staff |
| Teams | 🏗️ Structure prête | Gestion des équipes |
| Trainings | 🏗️ Structure prête | Gestion des entraînements |

## 🛠️ Commandes Utiles

### Développement
```bash
# Démarrer le serveur
php artisan serve

# Vérifier les routes
php artisan route:list

# Accéder à Tinker (REPL)
php artisan tinker
```

### Base de données
```bash
# Créer une nouvelle migration
php artisan make:migration create_table_name

# Exécuter les migrations
php artisan migrate

# Réinitialiser la BDD avec seed
php artisan migrate:fresh --seed

# Créer un seeder
php artisan make:seeder TableNameSeeder
```

### Cache
```bash
# Effacer tous les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Tokens Sanctum
```bash
# Nettoyer les tokens expirés
php artisan sanctum:prune-expired
```

## 🧪 Tests

### Avec REST Client (VS Code)
1. Installer l'extension "REST Client"
2. Ouvrir `API_TESTS.http`
3. Cliquer sur "Send Request"

### Avec Postman
1. Importer `postman_collection.json`
2. Configurer l'environnement avec `baseUrl = http://localhost:8000/api`
3. Les tokens sont gérés automatiquement

### Avec cURL
Voir les exemples dans `QUICK_START_AUTH.md`

## 🔒 Sécurité

### Implémentée
- ✅ Hashing des mots de passe (bcrypt)
- ✅ Token-based authentication (Sanctum)
- ✅ Validation stricte des inputs
- ✅ Protection CSRF
- ✅ Middleware d'authentification
- ✅ Révocation des tokens

### À configurer en production
- [ ] HTTPS obligatoire
- [ ] Rate limiting sur les routes sensibles
- [ ] Token expiration
- [ ] CORS policy stricte
- [ ] Monitoring et logs

## 🌍 Configuration Environnement

### Développement (.env)
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
```

### Production
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com
DB_CONNECTION=mysql
SANCTUM_STATEFUL_DOMAINS=votre-domaine.com
```

## 📈 Prochaines Étapes

### Priorité Haute
1. [ ] Implémenter les modules métier (Players, Teams, Matches, etc.)
2. [ ] Email verification
3. [ ] Password reset
4. [ ] Rate limiting

### Priorité Moyenne
1. [ ] Rôles et permissions (Spatie Laravel Permission)
2. [ ] Upload d'images (joueurs, équipes, etc.)
3. [ ] API pour les statistiques
4. [ ] Notifications

### Priorité Basse
1. [ ] OAuth (Google, Facebook)
2. [ ] Two-Factor Authentication
3. [ ] Webhooks
4. [ ] API versioning

## 🐛 Dépannage

### Erreur "Unauthenticated"
- Vérifier le token dans le header : `Authorization: Bearer TOKEN`
- Le token peut être expiré ou révoqué

### Erreur "Route not found"
- Vérifier que le serveur est démarré
- Vérifier l'URL : `/api/...`

### Erreur de base de données
```bash
php artisan migrate:fresh
```

### Autoloading issues
```bash
composer dump-autoload
```

## 📞 Support

### Logs
Les logs sont disponibles dans `storage/logs/laravel.log`

### Vérifications
```bash
# Vérifier la configuration
php artisan about

# Vérifier les routes
php artisan route:list

# Vérifier la config auth
php artisan config:show auth
```

## 🤝 Contribution

### Structure du code
- Suivre le pattern Repository-Service
- Typer les paramètres et retours de fonctions
- Documenter avec PHPDoc
- Valider les inputs avec FormRequest

### Naming conventions
- Controllers : `NomController.php`
- Services : `NomService.php` + `NomServiceInterface.php`
- Repositories : `NomRepository.php` + `NomRepositoryInterface.php`
- Requests : `ActionNomRequest.php`
- Resources : `NomResource.php`

## 📄 Licence

MIT

---

## ✨ Fonctionnalités Clés

✅ **Authentification complète** avec Laravel Sanctum  
✅ **Architecture modulaire** professionnelle  
✅ **API REST** bien structurée  
✅ **Documentation complète** avec exemples  
✅ **Tests prêts** (Postman, REST Client, cURL)  
✅ **Code maintenable** et évolutif  
✅ **Sécurité** robuste  
✅ **Production-ready**  

---

**Développé avec ❤️ pour la gestion de clubs de football**
