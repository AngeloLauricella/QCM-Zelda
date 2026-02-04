FROM php:8.2-cli

# Dépendances système
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zip curl nodejs npm \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dossier de travail
WORKDIR /app

# Copier le projet
COPY . .

# Installer dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Build assets
RUN npm install
RUN npm run build

# Permissions cache/logs
RUN chmod -R 777 var

# Port exposé
EXPOSE 10000

# Lancer Symfony
CMD php -S 0.0.0.0:$PORT -t public
