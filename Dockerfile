# Multi-stage build pour optimiser la taille de l'image

# Stage 1: Builder pour les dépendances Node.js
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copier les fichiers de dépendances Node.js et configs nécessaires
COPY package*.json ./
COPY vite.config.js ./
COPY tailwind.config.js* ./

# Installer toutes les dépendances (y compris dev dependencies pour le build)
# Utiliser npm ci si package-lock.json existe, sinon npm install
# Utiliser le cache npm pour accélérer les builds suivants
RUN if [ -f package-lock.json ]; then npm ci --prefer-offline --no-audit; else npm install --prefer-offline --no-audit; fi

# Copier les fichiers source nécessaires pour le build
COPY resources ./resources
COPY public ./public

# Build des assets Vite (caché si resources/ et public/ n'ont pas changé)
RUN npm run build

# Stage 2: Image PHP-FPM finale
FROM php:8.2-fpm-alpine AS app

# Installer les dépendances système
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    icu-dev \
    mysql-client \
    nginx \
    supervisor

# Installer les extensions PHP nécessaires
# Note: Cette étape est lente mais mise en cache par Docker
RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mysqli \
    gd \
    zip \
    bcmath \
    mbstring \
    intl \
    opcache

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de dépendances Composer
COPY composer.json composer.lock ./

# Installer les dépendances PHP sans exécuter les scripts (pour éviter les erreurs de fichiers manquants)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copier tous les fichiers de l'application
COPY . .

# Copier les assets buildés depuis le stage node-builder
COPY --from=node-builder /app/public/build ./public/build

# Exécuter les scripts post-install maintenant que tous les fichiers sont présents
RUN composer dump-autoload --optimize && \
    php artisan package:discover --ansi || true

# Créer les répertoires nécessaires avec les bonnes permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Copier le script d'initialisation
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000

# Script d'entrée
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["php-fpm"]

