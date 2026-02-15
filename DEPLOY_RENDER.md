# Déploiement sur Render

Ce guide vous aide à déployer votre application de gestion bancaire sur Render.

## 📋 Prérequis

1. **Compte Render** : [Créer un compte gratuit](https://render.com)
2. **Base de données NeonDB** : Votre `DATABASE_URL` est déjà configurée dans `.env`
3. **Repository Git** : Votre code doit être sur GitHub, GitLab ou Bitbucket

## 🚀 Étapes de déploiement

### 1. Préparer votre repository

Assurez-vous que votre code est poussé sur Git :

```bash
git add .
git commit -m "Préparation pour déploiement Render"
git push origin main
```

### 2. Créer un Web Service sur Render

1. Connectez-vous sur [Render Dashboard](https://dashboard.render.com)
2. Cliquez sur **"New +"** → **"Web Service"**
3. Connectez votre repository Git
4. Configurez le service :

   - **Name** : `gestion-bancaire` (ou le nom de votre choix)
   - **Runtime** : `PHP`
   - **Build Command** : `composer install --no-dev --optimize-autoloader`
   - **Start Command** : `php -S 0.0.0.0:$PORT -t public`
   - **Plan** : `Free`

### 3. Configurer les variables d'environnement

Dans l'onglet **"Environment"**, ajoutez :

```
DATABASE_URL=postgresql://neondb_owner:npg_XQumwsN2F5ce@ep-dry-feather-aielzske-pooler.c-4.us-east-1.aws.neon.tech/neondb?sslmode=require&channel_binding=require&charset=utf8
```

### 4. Déployer

1. Cliquez sur **"Create Web Service"**
2. Render va automatiquement :
   - Cloner votre repository
   - Installer les dépendances PHP avec Composer
   - Démarrer votre serveur PHP
   - Vous fournir une URL publique (ex: `https://gestion-bancaire.onrender.com`)

## ✅ Vérification

Une fois déployé, testez votre application :

- Accédez à l'URL fournie par Render
- Vérifiez que la connexion à NeonDB fonctionne
- Testez les fonctionnalités principales

## 🔧 Configuration automatique

Le fichier `render.yaml` est déjà configuré. Vous pouvez aussi l'utiliser pour un déploiement automatique :

1. Dans Render Dashboard, cliquez sur **"New +"** → **"Blueprint"**
2. Sélectionnez votre repository
3. Render détectera automatiquement `render.yaml` et configurera tout

## 📱 Accès mobile

Votre application sera accessible depuis n'importe quel appareil via l'URL Render :

```
https://votre-app.onrender.com
```

## ⚠️ Note importante

Le plan gratuit de Render :
- Se met en veille après 15 minutes d'inactivité
- Prend ~30 secondes pour redémarrer au premier accès
- Parfait pour des démos et projets personnels

## 🆘 En cas de problème

Consultez les logs dans Render Dashboard → **"Logs"** pour diagnostiquer les erreurs.
