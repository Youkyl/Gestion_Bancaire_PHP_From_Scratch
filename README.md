# 🏦 Gestion Bancaire - Application PHP

Application de gestion bancaire développée en PHP natif avec architecture MVC.

## 📱 Fonctionnalités

- ✅ Gestion des comptes bancaires (Épargne et Chèque)
- ✅ Gestion des transactions (Dépôt, Retrait, Transfert)
- ✅ Calcul automatique des frais de transaction
- ✅ Pagination des listes
- ✅ Interface responsive (mobile, tablette, desktop)
- ✅ Menu hamburger pour mobile

## 🛠️ Technologies

- **Backend** : PHP 8.x
- **Base de données** : PostgreSQL
- **Frontend** : HTML, CSS, JavaScript
- **Architecture** : MVC (Model-View-Controller)
- **Gestion des dépendances** : Composer

## 📦 Installation locale

### Prérequis

- PHP 8.0 ou supérieur
- PostgreSQL
- Composer

### Étapes

1. **Cloner le projet**

```bash
git clone <votre-repo>
cd PHP_From_scratch
```

2. **Installer les dépendances**

```bash
composer install
```

3. **Configurer la base de données**

Créez un fichier `.env` à la racine (copier depuis `.env.example`) :

```bash
cp .env.example .env
```

Modifiez les valeurs dans `.env` :

```dotenv
DATABASE_DRIVE=pgsql
DATABASE_NAME=gestion_bancairedbase
DATABASE_HOST=localhost
DATABASE_PORT=5432
DATABASE_USER=postgres
DATABASE_PASSWORD=votre_mot_de_passe
```

4. **Créer la base de données**

Créez la base PostgreSQL et les tables nécessaires.

5. **Démarrer le serveur**

```bash
php -S localhost:8000 -t public
```

6. **Accéder à l'application**

Ouvrez votre navigateur : `http://localhost:8000`

## 📱 Accès depuis mobile (réseau local)

Pour accéder depuis votre iPhone/smartphone :

1. Démarrez le serveur sur toutes les interfaces :

```bash
php -S 0.0.0.0:8000 -t public
```

2. Trouvez votre IP locale :

```bash
ipconfig | Select-String -Pattern "IPv4"  # Windows
ifconfig | grep "inet "                     # Mac/Linux
```

3. Accédez depuis votre mobile : `http://192.168.x.x:8000`

## 🚀 Déploiement en production

### Option 1 : Render (Cloud - Facile)

Déploiement cloud simple et rapide avec Docker.

📖 Guide complet : [DEPLOY_RENDER.md](DEPLOY_RENDER.md)

**Avantages** :
- ✅ Gratuit pour démarrer
- ✅ Déploiement automatique
- ✅ HTTPS inclus
- ✅ Pas de maintenance serveur

### Option 2 : Serveur personnel (VPS/Dédié)

Déploiement sur votre propre serveur avec contrôle total.

📖 Guide complet : [DEPLOY_PERSONAL_SERVER.md](DEPLOY_PERSONAL_SERVER.md)

**Avantages** :
- ✅ Contrôle total
- ✅ Performance dédiée
- ✅ Configuration personnalisée
- ✅ Apprentissage DevOps

**Résumé rapide** :

1. Push votre code sur GitHub
2. Créez un Web Service sur [Render](https://render.com)
3. Configurez `DATABASE_URL` dans les variables d'environnement
4. Déployez !

Votre app sera accessible via : `https://votre-app.onrender.com`

## 📁 Structure du projet

```
PHP_From_scratch/
├── config/           # Configuration (bootstrap, constantes)
├── public/           # Point d'entrée (index.php, assets)
│   ├── css/         # Styles
│   └── js/          # Scripts JavaScript
├── src/
│   └── App/
│       ├── controllers/    # Contrôleurs MVC
│       ├── core/          # Classes core (Router, Database, etc.)
│       ├── entity/        # Entités métier
│       ├── repository/    # Accès aux données
│       └── service/       # Logique métier
├── templates/        # Vues HTML/PHP
├── vendor/          # Dépendances Composer
├── .env             # Variables d'environnement (non versionné)
├── .env.example     # Modèle de configuration
├── composer.json    # Dépendances PHP
└── render.yaml      # Configuration Render
```

## 🔧 Configuration

### Constantes

Les constantes sont configurées dans `config/constant.php` et détectent automatiquement :
- Le protocole (HTTP/HTTPS)
- L'hôte et le port
- Les chemins CSS/JS

### Base de données

Deux modes de connexion :

**Local** : Utilisez les variables `DATABASE_*` dans `.env`

**NeonDB (cloud)** : Décommentez `DATABASE_URL` dans `.env`

## 👨‍💻 Développement

### Ajouter un contrôleur

1. Créez le fichier dans `src/App/controllers/`
2. Étendez la classe `Controller`
3. Ajoutez les routes dans `Router.php`

### Ajouter une vue

1. Créez le fichier dans `templates/`
2. Utilisez `base.layout.html.php` comme modèle
3. Appelez depuis le contrôleur avec `$this->render()`

## 📄 Licence

MIT

## 👤 Auteur

Youkyl - youhounk@gmail.com
