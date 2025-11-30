# Configuration Docker pour CertiCarte

Cette configuration Docker permet de déployer facilement l'application CertiCarte avec tous ses services nécessaires.

## Prérequis

- Docker (version 20.10 ou supérieure)
- Docker Compose (version 2.0 ou supérieure)

## Services inclus

1. **app** : Conteneur PHP 8.2-FPM avec Laravel
2. **web** : Serveur Nginx
3. **db** : Base de données MySQL 8.0
4. **queue** : Worker pour les queues Laravel (optionnel)

## Démarrage rapide

### 1. Configuration de l'environnement

Créez un fichier `.env` à la racine du projet (ou utilisez `.env.example`) avec les variables suivantes :

```env
APP_NAME=CertiCarte
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=certicarte
DB_USERNAME=certicarte_user
DB_PASSWORD=votre_mot_de_passe_securise
DB_ROOT_PASSWORD=mot_de_passe_root_securise

# Optionnel : Port pour l'application
APP_PORT=80

# Optionnel : Port pour MySQL (si vous voulez y accéder depuis l'extérieur)
DB_PORT=3306
```

### 2. Construire et démarrer les conteneurs

```bash
# Construire les images
docker-compose build

# Démarrer les services
docker-compose up -d

# Voir les logs
docker-compose logs -f
```

### 3. Initialisation de la base de données

Le script d'initialisation s'exécute automatiquement au démarrage, mais vous pouvez aussi exécuter manuellement :

```bash
# Exécuter les migrations
docker-compose exec app php artisan migrate

# Peupler la base de données (optionnel)
docker-compose exec app php artisan db:seed
```

### 4. Accéder à l'application

L'application sera accessible à l'adresse : `http://localhost` (ou le port spécifié dans `APP_PORT`)

## Commandes utiles

### Gestion des conteneurs

```bash
# Démarrer les services
docker-compose up -d

# Arrêter les services
docker-compose stop

# Arrêter et supprimer les conteneurs
docker-compose down

# Voir les logs
docker-compose logs -f app
docker-compose logs -f web
docker-compose logs -f db

# Redémarrer un service
docker-compose restart app
```

### Commandes Artisan

```bash
# Exécuter une commande Artisan
docker-compose exec app php artisan [command]

# Exemples
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### Accès à la base de données

```bash
# Se connecter à MySQL depuis le conteneur
docker-compose exec db mysql -u certicarte_user -p certicarte

# Ou depuis votre machine (si DB_PORT est exposé)
mysql -h localhost -P 3306 -u certicarte_user -p certicarte
```

### Gestion des permissions

Si vous rencontrez des problèmes de permissions :

```bash
# Corriger les permissions du storage
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Rebuild complet

Si vous devez reconstruire complètement :

```bash
# Arrêter et supprimer tout
docker-compose down -v

# Reconstruire les images
docker-compose build --no-cache

# Redémarrer
docker-compose up -d
```

## Structure des volumes

- `./` : Code source de l'application (monté en volume pour le développement)
- `db_data` : Données persistantes de MySQL
- `./storage` : Fichiers de stockage Laravel

## Optimisation pour la production

Avant de déployer en production, assurez-vous de :

1. **Variables d'environnement** : Configurez `APP_ENV=production` et `APP_DEBUG=false`
2. **Mots de passe** : Utilisez des mots de passe forts pour la base de données
3. **Cache** : Exécutez les commandes de cache :
   ```bash
   docker-compose exec app php artisan config:cache
   docker-compose exec app php artisan route:cache
   docker-compose exec app php artisan view:cache
   ```
4. **SSL/TLS** : Configurez un reverse proxy (comme Traefik ou Nginx) avec SSL devant le conteneur
5. **Backups** : Configurez des sauvegardes régulières du volume `db_data`

## Dépannage

### L'application ne démarre pas

Vérifiez les logs :
```bash
docker-compose logs app
docker-compose logs web
docker-compose logs db
```

### Erreurs de base de données

Vérifiez que le service `db` est démarré et sain :
```bash
docker-compose ps
docker-compose exec db mysqladmin ping -h localhost -u root -p
```

### Problèmes de permissions

```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 775 /var/www/html/storage
```

### Les assets ne se chargent pas

Assurez-vous que les assets sont bien buildés :
```bash
# Rebuild les assets dans le conteneur
docker-compose exec app npm install
docker-compose exec app npm run build
```

Ou reconstruisez l'image complète :
```bash
docker-compose build --no-cache app
docker-compose up -d
```

## Architecture

```
┌─────────────┐
│   Browser   │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│     Web     │ (Nginx :80)
│  Container  │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│     App     │ (PHP-FPM :9000)
│  Container  │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│     DB      │ (MySQL :3306)
│  Container  │
└─────────────┘
```

## Support

Pour plus d'informations, consultez la [documentation principale](../README.md).

