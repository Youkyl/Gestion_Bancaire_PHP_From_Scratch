# Utiliser l'image PHP officielle avec Apache
FROM php:8.2-cli

# Installer les extensions PHP nécessaires pour PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /app

# Copier les fichiers de dépendances
COPY composer.json composer.lock* ./

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copier tout le code de l'application
COPY . .

# Exposer le port (Render va définir $PORT dynamiquement)
EXPOSE 8000

# Commande de démarrage
CMD php -S 0.0.0.0:${PORT:-8000} -t public
