# Déploiement sur Render.com

Ce guide explique comment déployer CertiCarte sur Render.com.

## Configuration

Les fichiers suivants sont nécessaires pour le déploiement sur Render :

- `Dockerfile.render` : Dockerfile spécifique pour Render
- `render.yaml` : Configuration Render (optionnel)
- `docker/nginx-render.conf` : Configuration Nginx pour Render
- `docker/start-render.sh` : Script de démarrage pour Render

## Déploiement

### Option 1 : Via Render Dashboard

1. Allez sur [Render Dashboard](https://dashboard.render.com)
2. Cliquez sur "New +" > "Web Service"
3. Connectez votre dépôt GitHub
4. Configurez le service :
   - **Name** : certicarte
   - **Runtime** : Docker
   - **Dockerfile Path** : `Dockerfile.render`
   - **Docker Context** : `.` (point)
   - **Plan** : Free (ou paid selon vos besoins)

5. Ajoutez les variables d'environnement :
   - `APP_ENV` : production
   - `APP_DEBUG` : false
   - `APP_KEY` : Généré automatiquement par Render
   - `APP_URL` : Votre URL Render (ex: https://certicarte.onrender.com)
   - `DB_CONNECTION` : mysql
   - `DB_HOST` : (sera fourni par Render Database)
   - `DB_PORT` : (sera fourni par Render Database)
   - `DB_DATABASE` : (sera fourni par Render Database)
   - `DB_USERNAME` : (sera fourni par Render Database)
   - `DB_PASSWORD` : (sera fourni par Render Database)

6. Créez une base de données MySQL :
   - Cliquez sur "New +" > "PostgreSQL" ou "MySQL"
   - Configurez la base de données
   - Notez les informations de connexion

7. Liez la base de données au service web dans les variables d'environnement

### Option 2 : Via render.yaml (Recommandé)

1. Créez un fichier `render.yaml` à la racine du projet (déjà créé)
2. Push le fichier sur GitHub
3. Render détectera automatiquement le fichier et créera les services

## Points importants

1. **Port** : Render utilise la variable d'environnement `PORT` qui est automatiquement définie. Le script de démarrage configure Nginx pour utiliser ce port.

2. **Base de données** : La base de données MySQL doit être créée séparément et liée au service web via les variables d'environnement.

3. **Timeout** : Le script de démarrage attend 60 secondes maximum pour la connexion à la base de données avant de continuer.

4. **Migrations** : Les migrations s'exécutent automatiquement au démarrage.

## Dépannage

### L'application ne démarre pas

Vérifiez les logs dans le dashboard Render :
- Le service web devrait afficher les logs de démarrage
- Vérifiez que la base de données est accessible

### Erreur "No open ports detected"

- Vérifiez que `Dockerfile.render` expose le bon port
- Vérifiez que le script de démarrage configure Nginx correctement

### Erreur de connexion à la base de données

- Vérifiez que la base de données MySQL est créée
- Vérifiez que les variables d'environnement de connexion sont correctes
- Vérifiez que la base de données est dans la même région que le service web

## Configuration recommandée

Pour la production, utilisez :
- Plan **Starter** ou supérieur (évite le spin-down)
- Base de données **Starter** ou supérieur
- Configurez un domaine personnalisé
- Activez les backups automatiques de la base de données

