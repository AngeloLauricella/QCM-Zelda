# -------------------------------
# Dockerfile Symfony + Node pour Render
# -------------------------------

# Étape 0 : Image PHP CLI
FROM php:8.2-cli

# Installer les dépendances système
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

# Copier tout le projet AVANT composer install pour que bin/console existe
COPY . .

# Installer les dépendances PHP
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
