# Résolution des erreurs de déploiement Docker

## ❌ Erreur : `composer install` échoue (exit code: 1)

### Cause
Le fichier `composer.lock` était manquant ou Composer n'avait pas accès aux outils nécessaires (git, unzip).

### Solution appliquée

1. **Génération de composer.lock**
   ```bash
   composer update --no-interaction
   ```
   
2. **Mise à jour du Dockerfile**
   - Ajout de `git`, `unzip`, `zip` (requis par Composer)
   - Copie de `composer.lock` dans l'image Docker
   
3. **Mise à jour du .gitignore**
   - `composer.lock` n'est plus ignoré (bonne pratique pour garantir des versions cohérentes)

### Vérification avant déploiement

```bash
# Vérifier que composer.lock existe
ls composer.lock

# Tester le build Docker localement (optionnel)
docker build -t test-gestion-bancaire .
docker run -p 8000:8000 -e DATABASE_URL="votre_url" test-gestion-bancaire
```

## 🔧 Autres erreurs Docker courantes

### Erreur : "failed to solve: dockerfile parse error"

**Cause** : Syntaxe invalide dans le Dockerfile

**Solution** :
- Vérifiez les sauts de ligne et l'indentation
- Assurez-vous que chaque instruction `RUN` se termine correctement
- Utilisez `\` pour les commandes multi-lignes

### Erreur : "ERROR: Could not find a version that matches..."

**Cause** : Conflit de versions dans composer.json

**Solution** :
```bash
# Mettre à jour composer.lock
composer update

# Ou forcer une version spécifique
composer require package/name:^version
```

### Erreur : "exec ENTRYPOINT: exec format error"

**Cause** : Problème de format de fichier (Windows CRLF vs Unix LF)

**Solution** :
```bash
# Convertir les fins de ligne du script start.sh
dos2unix start.sh

# Ou configurer Git
git config --global core.autocrlf false
```

### Erreur : Port déjà utilisé

**Cause** : Le port $PORT n'est pas disponible

**Solution** : Render gère automatiquement le port, assurez-vous d'utiliser `${PORT}` :
```bash
CMD php -S 0.0.0.0:${PORT:-8000} -t public
```

### Erreur : "Cannot write to directory"

**Cause** : Problème de permissions dans le conteneur

**Solution** : Ajoutez dans le Dockerfile :
```dockerfile
RUN chown -R www-data:www-data /app
USER www-data
```

## 🚀 Déploiement après correction

### 1. Commiter les changements

```bash
git add .
git commit -m "Fix: Ajout composer.lock et amélioration Dockerfile"
git push origin main
```

### 2. Redéployer sur Render

Render détectera automatiquement le nouveau commit et redémarrera le build.

### 3. Vérifier les logs

Dans Render Dashboard :
- Allez dans votre service
- Cliquez sur l'onglet **"Logs"**
- Vérifiez que le build se termine avec succès

## 📋 Checklist avant déploiement

- ✅ `composer.lock` existe et est versionné
- ✅ `Dockerfile` est valide (pas d'erreur de syntaxe)
- ✅ `.env.example` est à jour
- ✅ `DATABASE_URL` est configurée dans Render
- ✅ Le projet se build localement : `composer install`
- ✅ Tous les fichiers sont commités sur Git

## 🔍 Déboguer localement avec Docker

```bash
# Construire l'image
docker build -t gestion-bancaire .

# Tester avec une base de données
docker run -p 8000:8000 \
  -e DATABASE_URL="postgresql://user:pass@host/db" \
  gestion-bancaire

# Accéder au conteneur pour déboguer
docker run -it gestion-bancaire /bin/bash
```

## 💡 Bonnes pratiques

1. **Toujours versionner composer.lock**
   - Garantit des versions de dépendances identiques partout
   - Évite les surprises en production

2. **Tester localement avant de pusher**
   ```bash
   composer install
   php -S localhost:8000 -t public
   ```

3. **Garder les dépendances à jour**
   ```bash
   composer update
   composer outdated
   ```

4. **Utiliser .dockerignore**
   - Réduit la taille de l'image
   - Accélère le build

## 🆘 Support

Si vous rencontrez d'autres erreurs :

1. **Consultez les logs Render** (onglet Logs dans le dashboard)
2. **Vérifiez la syntaxe** du Dockerfile
3. **Testez en local** avec Docker Desktop
4. **Vérifiez les versions PHP** requises dans composer.json

---

**Erreur résolue ?** Vous pouvez maintenant déployer sur Render ! 🎉
