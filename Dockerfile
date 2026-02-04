# Image PHP CLI
FROM php:8.2-cli

# Dépendances système
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zip curl libonig-dev \
    && docker-php-ext-install intl pdo_mysql zip mbstring

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Installer Node.js LTS
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Dossier de travail
WORKDIR /app

# Copier uniquement pour Composer
COPY composer.json composer.lock ./

# Installer dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Copier tout le reste du projet
COPY . .

# Installer et builder assets JS/CSS
RUN npm install && npm run build

# Permissions cache/logs
RUN mkdir -p var/cache var/log var/sessions && chmod -R 777 var

# Port exposé
EXPOSE 10000

# Lancer Symfony
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
