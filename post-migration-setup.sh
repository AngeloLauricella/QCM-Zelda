#!/bin/bash

# Script de vérification et configuration post-migration
# Utilisation: bash post-migration-setup.sh

echo "🔍 Vérification et Configuration Post-Migration du QCM-Zelda"
echo "=========================================================="

# 1. Vérifier que le projet est un projet Symfony
if [ ! -f "composer.json" ]; then
    echo "❌ Erreur: composer.json non trouvé. Vérifiez que vous êtes dans le dossier QCM-Zelda"
    exit 1
fi

echo "✅ Projet Symfony détecté"

# 2. Vérifier la structure des dossiers
echo ""
echo "📁 Vérification de la structure des dossiers..."

required_dirs=(
    "src/Controller"
    "src/Service"
    "templates/game"
    "templates/introduction"
    "templates/foret"
    "templates/montagne"
    "public/css"
    "public/images"
    "public/fonts"
    "config"
)

for dir in "${required_dirs[@]}"; do
    if [ -d "$dir" ]; then
        echo "✅ $dir"
    else
        echo "❌ $dir (absent - à créer)"
        mkdir -p "$dir"
    fi
done

# 3. Vérifier les contrôleurs
echo ""
echo "🎮 Vérification des contrôleurs..."

controllers=(
    "src/Controller/GameController.php"
    "src/Controller/IntroductionController.php"
    "src/Controller/ForetController.php"
    "src/Controller/MontagneController.php"
)

for controller in "${controllers[@]}"; do
    if [ -f "$controller" ]; then
        echo "✅ $controller"
    else
        echo "❌ $controller (absent)"
    fi
done

# 4. Vérifier les services
echo ""
echo "⚙️  Vérification des services..."

services=(
    "src/Service/ScoreManager.php"
    "src/Service/QuestionManager.php"
)

for service in "${services[@]}"; do
    if [ -f "$service" ]; then
        echo "✅ $service"
    else
        echo "❌ $service (absent)"
    fi
done

# 5. Vérifier les templates
echo ""
echo "🎨 Vérification des templates..."

templates_count=$(find templates -name "*.twig" | wc -l)
echo "✅ $templates_count fichiers templates trouvés"

# 6. Vérifier les assets
echo ""
echo "📦 Vérification des assets..."

echo -n "CSS: "
css_count=$(find public/css -name "*.css" 2>/dev/null | wc -l)
echo "$css_count fichiers"

echo -n "Images: "
image_count=$(find public/images -type f 2>/dev/null | wc -l)
echo "$image_count fichiers"

echo -n "Fonts: "
font_count=$(find public/fonts -type f 2>/dev/null | wc -l)
echo "$font_count fichiers"

# 7. Vérifier la syntaxe PHP
echo ""
echo "🔎 Vérification de la syntaxe PHP..."

php_errors=0
for file in src/Controller/*.php src/Service/*.php; do
    if [ -f "$file" ]; then
        if php -l "$file" > /dev/null 2>&1; then
            echo "✅ $file"
        else
            echo "❌ $file (erreur de syntaxe)"
            php -l "$file"
            ((php_errors++))
        fi
    fi
done

if [ $php_errors -eq 0 ]; then
    echo "✅ Aucune erreur de syntaxe PHP"
else
    echo "❌ $php_errors fichier(s) avec erreur(s) de syntaxe"
fi

# 8. Vérifier les routes
echo ""
echo "🛣️  Vérification des routes (si Symfony est installé)..."

if command -v php &> /dev/null && [ -f "bin/console" ]; then
    echo "Exécution de 'php bin/console debug:router'..."
    php bin/console debug:router 2>/dev/null | head -20
    echo ""
    echo "✅ Les routes sont configurées (voir détails ci-dessus)"
else
    echo "⚠️  Symfony CLI non disponible - vous pourrez vérifier après composer install"
fi

# 9. Vérifier les permissions
echo ""
echo "🔐 Vérification des permissions..."

if [ -d "var" ]; then
    if [ -w "var" ]; then
        echo "✅ var/ est accessible en écriture"
    else
        echo "⚠️  var/ n'est pas accessible en écriture"
        echo "   Exécutez: chmod -R 777 var/"
    fi
fi

# 10. Résumé final
echo ""
echo "=========================================================="
echo "✅ VÉRIFICATION TERMINÉE"
echo "=========================================================="

echo ""
echo "🚀 Prochaines étapes:"
echo "1. Exécutez: composer install"
echo "2. Exécutez: symfony server:start"
echo "3. Visitez: http://127.0.0.1:8000/"
echo ""
echo "📚 Documentation:"
echo "- MIGRATION.md: Détails complets de la migration"
echo "- QUICKSTART.md: Guide de démarrage rapide"
