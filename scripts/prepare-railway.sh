#!/bin/bash
# Railway Deployment Preparation Script
# Usage: bash scripts/prepare-railway.sh

set -e

echo "🚀 Préparation du projet Symfony pour Railway..."
echo "================================================="

# 1. Nettoyer les artifacts
echo "📦 Nettoyage des fichiers temporaires..."
rm -rf var/cache/*
rm -rf var/log/*
rm -rf node_modules/
rm -rf vendor/

# 2. Installer les dépendances
echo "📥 Installation des dépendances Composer (production)..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "📥 Installation des dépendances Yarn..."
yarn install --frozen-lockfile

# 3. Compiler les assets
echo "🔨 Compilation des assets avec Webpack Encore..."
yarn run build

# 4. Nettoyer les caches Symfony
echo "🧹 Nettoyage des caches Symfony..."
php bin/console cache:clear --no-warmup --no-interaction

# 5. Vérifier que les assets sont générés
if [ -d "public/build" ]; then
    echo "✅ Assets compilés: $(ls -la public/build/ | grep -c '\.js\|\.css') fichiers générés"
else
    echo "❌ ERREUR: Le dossier public/build n'existe pas!"
    exit 1
fi

# 6. Vérifier la config Doctrine
echo "🔍 Vérification de la configuration Doctrine..."
php bin/console doctrine:database:create --if-not-exists --no-interaction || echo "⚠️ Base de données potentiellement déjà créée"

echo ""
echo "================================================="
echo "✅ Préparation terminée!"
echo ""
echo "📋 Prochaines étapes:"
echo "  1. Vérifier les variables d'environnement sur Railway"
echo "  2. Pousser le code sur Git: git add . && git commit -m 'Prepare for Railway' && git push"
echo "  3. Railway détectera automatiquement nixpacks.toml ou Procfile"
echo ""
echo "🔗 Docs: https://docs.railway.app/"
