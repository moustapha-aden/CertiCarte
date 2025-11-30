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
   - **Important** : Render's free tier may only support PostgreSQL
   - Si MySQL est disponible : Cliquez sur "New +" > "MySQL"
   - Si MySQL n'est pas disponible : Vous devrez soit :
     a) Utiliser PostgreSQL et adapter l'application
     b) Utiliser un plan payant qui supporte MySQL
     c) Utiliser une base de données MySQL externe (ex: PlanetScale, Railway)
   - Configurez la base de données
   - Notez les informations de connexion

7. Liez la base de données au service web :
   - Dans les variables d'environnement du service web
   - Utilisez les valeurs fournies par Render pour la base de données
   - Ou configurez manuellement si vous utilisez une base externe

### Option 2 : Via render.yaml (Recommandé)

1. Créez un fichier `render.yaml` à la racine du projet (déjà créé)
2. **Important** : Si Render crée une base PostgreSQL par défaut (free tier), vous devrez :
   - Soit créer manuellement une base MySQL via le dashboard
   - Soit adapter l'application pour utiliser PostgreSQL
3. Push le fichier sur GitHub
4. Render détectera automatiquement le fichier et créera les services
5. Vérifiez que la base de données créée correspond au type attendu (MySQL)

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

Cette erreur signifie que Render ne détecte pas de processus écoutant sur le port fourni par la variable `PORT`.

**Solutions :**
1. Vérifiez que `Dockerfile.render` expose un port (par défaut 10000)
2. Vérifiez que le script `docker/start-render.sh` configure Nginx pour écouter sur `${PORT}`
3. Vérifiez les logs pour voir si Nginx démarre correctement
4. Le script génère maintenant dynamiquement la config Nginx avec le bon port
5. Assurez-vous que PHP-FPM démarre avant Nginx (déjà géré dans le script)

### Erreur de connexion à la base de données

**Symptômes :** "Database is unavailable - sleeping" dans les logs

**Solutions :**
1. **Type de base de données** : Vérifiez que vous utilisez MySQL et non PostgreSQL
   - Si Render a créé PostgreSQL par défaut, créez manuellement MySQL
   - Ou adaptez l'application pour PostgreSQL
2. **Variables d'environnement** : Vérifiez que toutes les variables DB_* sont correctement définies
3. **Région** : Assurez-vous que la base de données est dans la même région que le service web
4. **Timeout** : Le script attend jusqu'à 120 secondes pour la connexion
5. **Logs** : Vérifiez les logs pour voir les erreurs de connexion spécifiques
6. **Base externe** : Si vous utilisez une base MySQL externe, vérifiez les paramètres de connexion

## Configuration recommandée

Pour la production, utilisez :
- Plan **Starter** ou supérieur (évite le spin-down)
- Base de données **Starter** ou supérieur
- Configurez un domaine personnalisé
- Activez les backups automatiques de la base de données

