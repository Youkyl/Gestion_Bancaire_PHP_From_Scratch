# Déploiement sur Serveur Personnel

Guide complet pour déployer votre application de gestion bancaire sur votre propre serveur.

## 📋 Prérequis

### Matériel/Serveur
- **VPS** (Virtual Private Server) : DigitalOcean, Linode, OVH, etc.
- **Serveur dédié** : Votre propre machine avec IP publique
- **Minimum recommandé** :
  - 1 CPU
  - 1 GB RAM
  - 20 GB de stockage
  - Ubuntu 22.04 LTS (ou Debian 11+)

### Accès
- Accès SSH root ou sudo
- Nom de domaine (optionnel mais recommandé)
- IP publique fixe

## 🚀 Étape 1 : Préparer le serveur

### 1.1 Connexion SSH

```bash
ssh root@votre-ip-serveur
# ou
ssh votre-utilisateur@votre-ip-serveur
```

### 1.2 Mise à jour du système

```bash
sudo apt update
sudo apt upgrade -y
```

### 1.3 Créer un utilisateur (si vous êtes root)

```bash
adduser deploy
usermod -aG sudo deploy
su - deploy
```

## 🔧 Étape 2 : Installer la stack PHP

### 2.1 Installer PHP 8.2

```bash
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-pgsql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip
```

### 2.2 Vérifier l'installation

```bash
php -v
# Devrait afficher PHP 8.2.x
```

### 2.3 Installer Composer

```bash
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

## 🗄️ Étape 3 : Installer PostgreSQL

### 3.1 Installation

```bash
sudo apt install -y postgresql postgresql-contrib
```

### 3.2 Créer la base de données

```bash
sudo -u postgres psql

# Dans le prompt PostgreSQL :
CREATE DATABASE gestion_bancaire;
CREATE USER appuser WITH PASSWORD 'votre_mot_de_passe_securise';
GRANT ALL PRIVILEGES ON DATABASE gestion_bancaire TO appuser;
\q
```

### 3.3 Autoriser les connexions locales

```bash
sudo nano /etc/postgresql/14/main/pg_hba.conf
```

Vérifiez que cette ligne existe :
```
local   all             all                                     md5
```

Redémarrer PostgreSQL :
```bash
sudo systemctl restart postgresql
```

## 🌐 Étape 4 : Installer et configurer Nginx

### 4.1 Installation

```bash
sudo apt install -y nginx
```

### 4.2 Créer la configuration du site

```bash
sudo nano /etc/nginx/sites-available/gestion-bancaire
```

Collez cette configuration :

```nginx
server {
    listen 80;
    server_name votre-domaine.com;  # Ou votre IP
    root /var/www/gestion-bancaire/public;
    index index.php;

    access_log /var/log/nginx/gestion-bancaire-access.log;
    error_log /var/log/nginx/gestion-bancaire-error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 4.3 Activer le site

```bash
sudo ln -s /etc/nginx/sites-available/gestion-bancaire /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

## 📦 Étape 5 : Déployer l'application

### 5.1 Créer le répertoire

```bash
sudo mkdir -p /var/www/gestion-bancaire
sudo chown -R $USER:www-data /var/www/gestion-bancaire
```

### 5.2 Option A : Cloner depuis Git

```bash
cd /var/www/gestion-bancaire
git clone https://github.com/votre-username/votre-repo.git .
```

### 5.2 Option B : Upload par SFTP

Utilisez FileZilla, WinSCP ou scp :

```bash
# Depuis votre machine locale
scp -r C:\Users\youho\OneDrive\Desktop\Formation_Baila\Projet_6\PHP_From_scratch/* deploy@votre-ip:/var/www/gestion-bancaire/
```

### 5.3 Installer les dépendances

```bash
cd /var/www/gestion-bancaire
composer install --no-dev --optimize-autoloader
```

### 5.4 Créer le fichier .env

```bash
nano .env
```

Collez votre configuration :

```dotenv
DATABASE_DRIVE=pgsql
DATABASE_NAME=gestion_bancaire
DATABASE_HOST=localhost
DATABASE_PORT=5432
DATABASE_USER=appuser
DATABASE_PASSWORD=votre_mot_de_passe_securise
```

### 5.5 Configurer les permissions

```bash
sudo chown -R www-data:www-data /var/www/gestion-bancaire
sudo chmod -R 755 /var/www/gestion-bancaire
sudo chmod 600 /var/www/gestion-bancaire/.env
```

## 🔒 Étape 6 : Sécuriser avec SSL (HTTPS)

### 6.1 Installer Certbot

```bash
sudo apt install -y certbot python3-certbot-nginx
```

### 6.2 Obtenir un certificat SSL

```bash
sudo certbot --nginx -d votre-domaine.com
```

Suivez les instructions. Certbot configurera automatiquement Nginx pour HTTPS.

### 6.3 Renouvellement automatique

Le certificat se renouvelle automatiquement. Testez :

```bash
sudo certbot renew --dry-run
```

## 🛡️ Étape 7 : Sécuriser le serveur

### 7.1 Configurer le firewall

```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
sudo ufw status
```

### 7.2 Désactiver l'accès SSH root

```bash
sudo nano /etc/ssh/sshd_config
```

Modifiez :
```
PermitRootLogin no
```

Redémarrez :
```bash
sudo systemctl restart sshd
```

### 7.3 Installer Fail2Ban (protection contre les attaques)

```bash
sudo apt install -y fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

## 🔄 Étape 8 : Automatiser les déploiements

### 8.1 Script de déploiement

Créez un script `deploy.sh` :

```bash
nano /var/www/gestion-bancaire/deploy.sh
```

```bash
#!/bin/bash

echo "🚀 Déploiement en cours..."

# Aller dans le répertoire
cd /var/www/gestion-bancaire

# Mettre à jour le code
git pull origin main

# Installer les dépendances
composer install --no-dev --optimize-autoloader

# Corriger les permissions
sudo chown -R www-data:www-data /var/www/gestion-bancaire

echo "✅ Déploiement terminé !"
```

Rendez-le exécutable :
```bash
chmod +x /var/www/gestion-bancaire/deploy.sh
```

### 8.2 Déployer facilement

```bash
./deploy.sh
```

## 🌍 Accès à votre application

### Avec nom de domaine :
```
https://votre-domaine.com
```

### Avec IP uniquement :
```
http://votre-ip-serveur
```

## 📱 Configuration pour mobile

Votre application sera automatiquement accessible depuis mobile, tablette, ordinateur via l'URL de votre serveur !

## 🔍 Vérification et tests

### Tester Nginx

```bash
sudo nginx -t
sudo systemctl status nginx
```

### Tester PHP-FPM

```bash
sudo systemctl status php8.2-fpm
```

### Tester PostgreSQL

```bash
sudo systemctl status postgresql
psql -U appuser -d gestion_bancaire -h localhost
```

### Consulter les logs

```bash
# Logs Nginx
sudo tail -f /var/log/nginx/gestion-bancaire-error.log

# Logs PHP
sudo tail -f /var/log/php8.2-fpm.log
```

## 🆘 Dépannage

### Erreur 502 Bad Gateway

```bash
# Vérifier PHP-FPM
sudo systemctl restart php8.2-fpm
```

### Erreur de connexion base de données

```bash
# Vérifier PostgreSQL
sudo systemctl status postgresql

# Tester la connexion
psql -U appuser -d gestion_bancaire -h localhost -W
```

### Erreur de permissions

```bash
sudo chown -R www-data:www-data /var/www/gestion-bancaire
sudo chmod -R 755 /var/www/gestion-bancaire
```

## 💰 Estimation des coûts

### VPS recommandés (prix mensuels) :

| Fournisseur | Plan | Prix | Specs |
|------------|------|------|-------|
| **DigitalOcean** | Basic Droplet | $6/mois | 1 CPU, 1GB RAM |
| **Hetzner** | CX11 | €4/mois | 1 CPU, 2GB RAM |
| **OVH** | VPS Starter | €4/mois | 1 CPU, 2GB RAM |
| **Linode** | Nanode | $5/mois | 1 CPU, 1GB RAM |

### + Nom de domaine : ~€10/an

## 🎯 Avantages serveur personnel

- ✅ **Contrôle total** sur la configuration
- ✅ **Pas de limitations** de plateforme
- ✅ **Performance** : ressources dédiées
- ✅ **Personnalisation** : configuration libre
- ✅ **Apprentissage** : compétences DevOps

## ⚠️ Maintenance régulière

### Hebdomadaire

```bash
# Mettre à jour le système
sudo apt update && sudo apt upgrade -y
```

### Mensuelle

```bash
# Vérifier l'espace disque
df -h

# Vérifier les logs
sudo du -sh /var/log/*

# Nettoyer les anciens logs
sudo journalctl --vacuum-time=7d
```

### Sauvegarde de la base de données

```bash
# Créer un backup
pg_dump -U appuser gestion_bancaire > backup_$(date +%Y%m%d).sql

# Restaurer
psql -U appuser gestion_bancaire < backup_20260215.sql
```

## 🚀 Alternative : Serveur à domicile

Si vous avez un ordinateur/serveur à domicile :

1. **Installer Ubuntu Server** sur la machine
2. Suivre les mêmes étapes ci-dessus
3. **Configurer le port forwarding** sur votre routeur (port 80 et 443)
4. **Utiliser un DNS dynamique** (No-IP, DuckDNS) pour avoir un nom de domaine gratuit
5. **Attention** : Votre IP publique peut changer (sauf abonnement IP fixe)

---

**Besoin d'aide ?** Consultez les logs et la documentation officielle de chaque composant !
