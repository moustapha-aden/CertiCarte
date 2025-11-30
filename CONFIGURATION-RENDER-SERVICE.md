# 📋 Configuration d'un nouveau service Render - Guide étape par étape

## ⚠️ IMPORTANT : Avant de créer un nouveau service

**Recommandation** : Modifiez plutôt le service existant "CertiCarte-1" au lieu d'en créer un nouveau.

1. Annulez la création du nouveau service
2. Allez dans votre service existant "CertiCarte-1"
3. Settings > Build & Deploy
4. Changez **Dockerfile Path** en `Dockerfile.render`
5. Sauvegardez et redéployez

---

## Si vous créez quand même un nouveau service

### Étape 1 : Configuration de base

Dans le formulaire que vous voyez :

1. **Name** : Utilisez un nom différent (ex: `certicarte-v2` ou `certicarte-new`)
2. **Branch** : `main` ✅ (déjà bon)
3. **Region** : Gardez "Oregon" ✅
4. **Instance Type** : Free (pour commencer) ✅

### Étape 2 : Configuration Docker (CRITIQUE)

Cliquez sur **Advanced** et configurez :

- **Dockerfile Path** : `Dockerfile.render` ⚠️ **OBLIGATOIRE**
- **Docker Context** : `.` (point)

⚠️ **SANS CETTE CONFIGURATION, LE SERVICE NE FONCTIONNERA PAS !**

### Étape 3 : Variables d'environnement

Dans la section **Environment Variables**, configurez au minimum :

#### Variables Laravel essentielles :
- `APP_ENV` = `production`
- `APP_DEBUG` = `false`
- `APP_KEY` = (cliquez sur "Generate" pour générer automatiquement)
- `APP_URL` = (sera rempli automatiquement après création, ou mettez `https://votre-service.onrender.com`)

#### Variables de base de données :
⚠️ **Vous devez d'abord créer une base de données MySQL !**

1. **Créez d'abord la base de données** :
   - Dans le dashboard Render, cliquez sur "New +" > "MySQL"
   - Nom : `certicarte-db`
   - Plan : Free
   - Région : Oregon (même région que le service web)

2. **Ensuite, ajoutez ces variables** :
   - `DB_CONNECTION` = `mysql`
   - `DB_HOST` = (copiez depuis votre base de données MySQL)
   - `DB_PORT` = (copiez depuis votre base de données MySQL, généralement `3306`)
   - `DB_DATABASE` = (nom de la base, ex: `certicarte`)
   - `DB_USERNAME` = (utilisateur de la base)
   - `DB_PASSWORD` = (mot de passe de la base)

#### Autres variables importantes :
- `SESSION_DRIVER` = `file`
- `CACHE_STORE` = `file`
- `QUEUE_CONNECTION` = `database`
- `FILESYSTEM_DISK` = `local`

### Étape 4 : Créer le service

Cliquez sur **Deploy web service**

### Étape 5 : Vérification

Après le déploiement, vérifiez les logs. Vous devriez voir :
```
🚀 Starting Laravel application on Render...
📋 Database configuration:
...
🔧 Configuring Nginx to listen on port 10000
✅ Starting Nginx on port 10000...
```

Si vous voyez "🚀 Starting Laravel application setup..." au lieu de "on Render", le Dockerfile Path n'est pas correct.

---

## 🎯 Configuration rapide avec render.yaml (RECOMMANDÉ)

Au lieu de créer manuellement, vous pouvez :

1. **Supprimez le service existant** (si vous voulez repartir à zéro)
2. **Connectez votre repo GitHub** à Render
3. **Render détectera automatiquement `render.yaml`**
4. **Cliquez sur "Generate Blueprint"** ou laissez Render créer les services automatiquement

Cela configurera tout automatiquement avec les bons paramètres.

---

## ⚠️ Problèmes courants

### "Name is already in use"
- Utilisez un nom différent
- Ou modifiez le service existant au lieu d'en créer un nouveau

### "No open ports detected"
- Vérifiez que **Dockerfile Path** = `Dockerfile.render`
- Vérifiez les logs pour voir quel script est utilisé

### "Database is unavailable"
- Vérifiez que vous avez créé une base **MySQL** (pas PostgreSQL)
- Vérifiez que les variables DB_* sont correctes
- Vérifiez que la base est dans la même région

---

## 📝 Checklist avant déploiement

- [ ] Dockerfile Path = `Dockerfile.render`
- [ ] Base de données MySQL créée
- [ ] Variables DB_* configurées
- [ ] APP_KEY généré
- [ ] APP_ENV = production
- [ ] APP_DEBUG = false

