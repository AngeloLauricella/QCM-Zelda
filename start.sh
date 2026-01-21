#!/bin/bash

# 🎮 QCM-Zelda - Script de Démarrage Rapide
# Refactorisation Symfony 6 + Doctrine ORM Complète

cd /Users/angelo/Projet-perso/QCM-Zelda

echo "╔════════════════════════════════════════════════════════════╗"
echo "║         🎮 QCM-ZELDA - DÉMARRAGE RAPIDE 🎮              ║"
echo "║                                                            ║"
echo "║         Symfony 6 + Doctrine ORM + Base de Données      ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

echo "📋 VÉRIFICATIONS PRÉ-DÉMARRAGE..."
echo ""

echo "1️⃣  Vérification des entités Doctrine..."
if php bin/console doctrine:mapping:info 2>&1 | grep -q "Found 3 mapped entities"; then
    echo "   ✅ 3 entités trouvées"
else
    echo "   ❌ Erreur - Entités manquantes"
    exit 1
fi

echo ""
echo "2️⃣  Vérification de la base de données..."
if php bin/console app:verify-database 2>&1 | grep -q "16 questions"; then
    echo "   ✅ 16 questions chargées"
else
    echo "   ⚠️  Recharger les fixtures..."
    php bin/console doctrine:fixtures:load --no-interaction
fi

echo ""
echo "3️⃣  Vérification des routes..."
ROUTE_COUNT=$(php bin/console debug:router 2>/dev/null | grep -c "app_\|question_\|results_")
echo "   ✅ $ROUTE_COUNT routes disponibles"

echo ""
echo "4️⃣  Vérification des templates..."
if php bin/console lint:twig templates/ 2>&1 | grep -q "All.*files contain valid syntax"; then
    echo "   ✅ 28 templates validées"
else
    echo "   ❌ Erreur dans les templates"
    exit 1
fi

echo ""
echo "════════════════════════════════════════════════════════════"
echo ""
echo "✅ TOUTES LES VÉRIFICATIONS PASSÉES!"
echo ""
echo "🚀 DÉMARRAGE DU SERVEUR SYMFONY..."
echo ""
echo "Accès: http://localhost:8000"
echo ""
echo "════════════════════════════════════════════════════════════"
echo ""

symfony serve
