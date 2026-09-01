# Guide de Déploiement

Ce guide vous aide à déployer l'application Football Club Backend en production.

---

## 📋 Prérequis Production

### Serveur
- **PHP** : 8.3 ou supérieur
- **Composer** : Dernière version
- **Serveur Web** : Nginx ou Apache
- **Base de données** : MySQL 8.0+ ou PostgreSQL 13+
- **SSL/TLS** : Certificat valide (Let's Encrypt recommandé)

### Extensions PHP requises
```bash
php -m | grep -E 'bcmath|ctype|fileinfo|json|mbstring|openssl|pdo|tokenizer|xml'
```

Extensions nécessaires :
- bcmath
- ctype
- fileinfo
- json
- mbstring
- openssl
- PDO
- tokenizer
- xml
- curl

---

## 🚀 Étapes de Déploiement

### 1. Préparation du Serveur

#### Sur Ubuntu/Debian
```bash
# Mettre à jour le système
sudo apt update && sudo apt upgrade -y

# Installer PHP 8.3 et extensions
sudo apt install -y php8.3 php8.3-fpm php8.3-mysql php8.3-mbstring \
  php8.3-xml php8.3-curl php8.3-bcmath php8.3-zip

# Installer Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Installer Nginx
sudo apt install -y nginx

# Installer MySQL
sudo apt install -y mysql-server
```

### 2. Cloner le Projet

```bash
# Naviguer vers le répertoire web
cd /var/www

# Cloner le projet (remplacer par votre repo)
git clone https://github.com/votre-repo/football-club.git
cd football-club/backend

# Définir les permissions
sudo chown -R www-data:www-data /var/www/football-club/backend
sudo chmod -R 755 /var/www/football-club/backend
sudo chmod -R 775 storage bootstrap/cache
```

### 3. Configuration de l'Application

```bash
# Installer les dépendances (sans dev)
composer install --optimize-autoloader --no-dev

# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 4. Configuration .env Production

```env
# Application
APP_NAME="Football Club API"
APP_ENV=production
APP_KEY=base64:VOTRE_CLE_GENEREE
APP_DEBUG=false
APP_URL=https://api.votre-domaine.com

# Locale
APP_LOCALE=fr
APP_FALLBACK_LOCALE=en

# Database (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=football_club_prod
DB_USERNAME=football_user
DB_PASSWORD=VOTRE_MOT_DE_PASSE_SECURISE

# Cache & Queue
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail (configurer selon votre service)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@votre-domaine.com"
MAIL_FROM_NAME="${APP_NAME}"

# Sanctum
SANCTUM_STATEFUL_DOMAINS=votre-domaine.com,www.votre-domaine.com
SESSION_DOMAIN=.votre-domaine.com

# Auth
AUTH_GUARD=api

# Logs
LOG_CHANNEL=stack
LOG_LEVEL=error
```

### 5. Configuration de la Base de Données

```bash
# Se connecter à MySQL
sudo mysql

# Créer la base de données et l'utilisateur
CREATE DATABASE football_club_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'football_user'@'localhost' IDENTIFIED BY 'VOTRE_MOT_DE_PASSE_SECURISE';
GRANT ALL PRIVILEGES ON football_club_prod.* TO 'football_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Exécuter les migrations
php artisan migrate --force

# (Optionnel) Seeder pour données initiales
php artisan db:seed --force
```

### 6. Optimisation Laravel

```bash
# Optimiser l'autoloader
composer dump-autoload --optimize

# Cacher la configuration
php artisan config:cache

# Cacher les routes
php artisan route:cache

# Cacher les vues
php artisan view:cache

# Cacher les événements
php artisan event:cache
```

### 7. Configuration Nginx

Créer `/etc/nginx/sites-available/football-club`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name api.votre-domaine.com;
    
    # Redirection HTTP vers HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name api.votre-domaine.com;

    root /var/www/football-club/backend/public;
    index index.php;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/api.votre-domaine.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/api.votre-domaine.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss application/rss+xml font/truetype font/opentype application/vnd.ms-fontobject image/svg+xml;

    # Logs
    access_log /var/log/nginx/football-club-access.log;
    error_log /var/log/nginx/football-club-error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Timeout pour requêtes longues
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Activer le site :
```bash
sudo ln -s /etc/nginx/sites-available/football-club /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 8. SSL avec Let's Encrypt

```bash
# Installer Certbot
sudo apt install -y certbot python3-certbot-nginx

# Obtenir le certificat SSL
sudo certbot --nginx -d api.votre-domaine.com

# Renouvellement automatique (déjà configuré par défaut)
sudo certbot renew --dry-run
```

### 9. Configuration PHP-FPM

Éditer `/etc/php/8.3/fpm/pool.d/www.conf` :

```ini
; Process manager
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500

; Timeouts
request_terminate_timeout = 300s
```

Redémarrer PHP-FPM :
```bash
sudo systemctl restart php8.3-fpm
```

### 10. Tâches Planifiées (Cron)

```bash
# Éditer le crontab
sudo crontab -e

# Ajouter la ligne suivante
* * * * * cd /var/www/football-club/backend && php artisan schedule:run >> /dev/null 2>&1
```

### 11. Supervisor (Queue Worker)

Installer Supervisor :
```bash
sudo apt install -y supervisor
```

Créer `/etc/supervisor/conf.d/football-club-worker.conf` :

```ini
[program:football-club-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/football-club/backend/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/football-club/backend/storage/logs/worker.log
stopwaitsecs=3600
```

Démarrer :
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start football-club-worker:*
```

---

## 🔒 Sécurité en Production

### 1. Pare-feu (UFW)

```bash
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
sudo ufw enable
```

### 2. Fail2Ban

```bash
# Installer Fail2Ban
sudo apt install -y fail2ban

# Créer la configuration
sudo nano /etc/fail2ban/jail.local
```

Contenu :
```ini
[sshd]
enabled = true
maxretry = 3
bantime = 3600

[nginx-http-auth]
enabled = true
```

Démarrer :
```bash
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

### 3. Permissions Strictes

```bash
cd /var/www/football-club/backend

# Fichiers en lecture seule pour www-data
sudo find . -type f -exec chmod 644 {} \;
sudo find . -type d -exec chmod 755 {} \;

# Storage et cache en écriture
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### 4. Variables Sensibles

Ne **JAMAIS** commiter le fichier `.env` dans Git.

Utiliser des variables d'environnement serveur ou un gestionnaire de secrets (Vault, AWS Secrets Manager).

---

## 📊 Monitoring et Logs

### 1. Logs Laravel

```bash
# Voir les logs en temps réel
tail -f /var/www/football-club/backend/storage/logs/laravel.log

# Rotation des logs (configurer logrotate)
sudo nano /etc/logrotate.d/football-club
```

Contenu :
```
/var/www/football-club/backend/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

### 2. Monitoring avec Laravel Telescope (optionnel)

```bash
composer require laravel/telescope
php artisan telescope:install
php artisan migrate
```

**⚠️ Attention** : Restreindre l'accès à Telescope en production via `app/Providers/TelescopeServiceProvider.php`

---

## 🔄 Mise à Jour de l'Application

### Script de déploiement

Créer `deploy.sh` :

```bash
#!/bin/bash

cd /var/www/football-club/backend

# Mode maintenance
php artisan down

# Récupérer les dernières modifications
git pull origin main

# Installer les dépendances
composer install --no-dev --optimize-autoloader

# Migrations
php artisan migrate --force

# Optimisations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Redémarrer les services
sudo systemctl reload php8.3-fpm
sudo supervisorctl restart football-club-worker:*

# Sortir du mode maintenance
php artisan up

echo "✅ Déploiement terminé !"
```

Rendre exécutable :
```bash
chmod +x deploy.sh
```

---

## 🧪 Tests en Production

### Health Check
```bash
curl https://api.votre-domaine.com/api/health
```

### Test d'authentification
```bash
curl -X POST https://api.votre-domaine.com/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@football-club.com","password":"Admin123!"}'
```

---

## 📈 Performance

### 1. OPcache PHP

Éditer `/etc/php/8.3/fpm/php.ini` :

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
```

### 2. Redis Cache

```bash
sudo apt install -y redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server
```

### 3. HTTP/2 et Compression

Déjà configuré dans la configuration Nginx ci-dessus.

---

## 🆘 Dépannage Production

### Erreur 500
```bash
# Vérifier les logs
tail -f storage/logs/laravel.log
tail -f /var/log/nginx/football-club-error.log
```

### Permissions
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Effacer les caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Vérifier les services
```bash
sudo systemctl status nginx
sudo systemctl status php8.3-fpm
sudo systemctl status mysql
sudo systemctl status redis-server
sudo supervisorctl status
```

---

## ✅ Checklist de Déploiement

- [ ] Serveur configuré avec PHP 8.3+
- [ ] Base de données MySQL/PostgreSQL installée
- [ ] SSL/TLS configuré (HTTPS)
- [ ] Variables d'environnement configurées
- [ ] Migrations exécutées
- [ ] Optimisations Laravel appliquées
- [ ] Nginx configuré et redémarré
- [ ] Permissions correctes sur les dossiers
- [ ] Tâches planifiées (cron) configurées
- [ ] Queue workers (Supervisor) configurés
- [ ] Pare-feu activé
- [ ] Fail2Ban configuré
- [ ] Logs fonctionnels
- [ ] Health check OK
- [ ] Test d'authentification OK
- [ ] Backup automatique configuré

---

## 🔐 Backup

### Script de backup automatique

Créer `/usr/local/bin/backup-football-club.sh` :

```bash
#!/bin/bash

BACKUP_DIR="/backups/football-club"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="football_club_prod"
DB_USER="football_user"
DB_PASS="VOTRE_MOT_DE_PASSE"

mkdir -p $BACKUP_DIR

# Backup base de données
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Backup fichiers
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/football-club/backend/storage

# Garder seulement les 7 derniers jours
find $BACKUP_DIR -mtime +7 -delete

echo "✅ Backup créé : $DATE"
```

Ajouter au cron :
```bash
0 2 * * * /usr/local/bin/backup-football-club.sh >> /var/log/backup-football-club.log 2>&1
```

---

**🎉 Votre application est maintenant en production !**
