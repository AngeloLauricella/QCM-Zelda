# -------------------------------
# Dockerfile Symfony + Node pour Render (Production)
# -------------------------------

# Étape 0 : Image PHP CLI compatible Symfony 6.2 ou moins
FROM php:8.4-fpm


# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zip curl libonig-dev \
    && docker-php-ext-install intl pdo_mysql zip mbstring

# Installer Composer depuis l'image officielle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Installer Node.js LTS (18)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Définir le dossier de travail
WORKDIR /app

# Copier tout le projet AVANT composer install
COPY . .

# Définir l'environnement Symfony en production pour éviter DebugBundle
ENV APP_ENV=prod

# Installer les dépendances PHP uniquement pour la production
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --ignore-platform-reqs

# Installer et builder les assets JS/CSS
RUN npm install && npm run build

# Créer et sécuriser les dossiers Symfony
RUN mkdir -p var/cache var/log var/sessions \
    && chown -R www-data:www-data var \
    && chmod -R 777 var

# Exposer le port pour Symfony
EXPOSE 10000

# Lancer le serveur interne Symfony
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
