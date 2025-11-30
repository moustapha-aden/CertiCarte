# Guide de dépannage pour le déploiement Render

## Problème actuel : "No open ports detected"

### Symptômes
- Les logs montrent "🚀 Starting Laravel application setup..." (message de `entrypoint.sh`)
- Au lieu de "🚀 Starting Laravel application on Render..." (message de `start-render.sh`)
- Erreur : "Port scan timeout reached, no open ports detected"

### Cause
Render utilise le **mauvais Dockerfile** (`Dockerfile` au lieu de `Dockerfile.render`).

## Solution 1 : Vérifier la configuration dans le Dashboard Render

1. Allez sur [Render Dashboard](https://dashboard.render.com)
2. Sélectionnez votre service **certicarte**
3. Allez dans **Settings**
4. Vérifiez la section **Build & Deploy** :
   - **Dockerfile Path** doit être : `Dockerfile.render`
   - **Docker Context** doit être : `.` (point)
5. Si ce n'est pas le cas, modifiez et sauvegardez
6. Cliquez sur **Manual Deploy** > **Deploy latest commit**

## Solution 2 : Recréer le service avec render.yaml

Si le service a été créé manuellement avant l'ajout de `render.yaml` :

1. **Option A : Supprimer et recréer** (recommandé si vous n'avez pas de données importantes)
   - Supprimez le service actuel dans Render
   - Connectez votre repo GitHub à Render
   - Render détectera automatiquement `render.yaml` et créera le service avec la bonne configuration

2. **Option B : Utiliser "Generate Blueprint"**
   - Dans le dashboard Render, cliquez sur **Generate Blueprint**
   - Cela créera un `render.yaml` basé sur votre configuration actuelle
   - Modifiez-le pour utiliser `Dockerfile.render`
   - Commitez et poussez les changements

## Solution 3 : Vérifier que Dockerfile.render est correct

Assurez-vous que `Dockerfile.render` :
- Utilise `CMD ["/start.sh"]` (pas `ENTRYPOINT` avec `entrypoint.sh`)
- Copie `docker/start-render.sh` vers `/start.sh`
- Expose le port 10000

## Vérification après correction

Après avoir corrigé la configuration, les logs devraient montrer :
```
🚀 Starting Laravel application on Render...
📋 Database configuration:
   DB_HOST: ...
   DB_PORT: ...
...
🔧 Configuring Nginx to listen on port 10000
✅ Starting Nginx on port 10000...
```

Au lieu de :
```
🚀 Starting Laravel application setup...
⏳ Waiting for database to be ready...
```

## Problème de base de données

Si vous voyez toujours "Database is unavailable - sleeping" :

1. **Vérifiez le type de base de données** :
   - Render free tier peut créer PostgreSQL par défaut
   - Votre application nécessite MySQL
   - Solution : Créez manuellement une base MySQL dans le dashboard Render

2. **Vérifiez les variables d'environnement** :
   - `DB_CONNECTION=mysql`
   - `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` doivent être définies

3. **Vérifiez que la base de données est dans la même région** que le service web

## Commandes utiles

Pour vérifier localement que `Dockerfile.render` fonctionne :
```bash
docker build -f Dockerfile.render -t certicarte-render .
docker run -p 10000:10000 -e PORT=10000 certicarte-render
```

## Support

Si le problème persiste :
1. Vérifiez les logs complets dans Render Dashboard > Logs
2. Vérifiez que tous les fichiers sont bien commités et poussés sur GitHub
3. Vérifiez que le service utilise bien `Dockerfile.render` dans Settings

