# ✅ Railway Deployment - Guide Complet

## 📋 Statut du Projet

✅ **Configuration Railway complète et validée localement**

- PHP 8.4 configuré
- Assets Webpack compilés (7 fichiers)
- Cache Symfony généré (prod)
- Composer lock file mis à jour
- Doctrine MySQL 8.0.32 configuré

---

## 🚀 Déploiement en 5 Minutes

### Étape 1: Vérifier en local

```bash
cd /Users/angelo/Projet-perso/QCM-Zelda

# Nettoyage
rm -rf var/cache var/log node_modules vendor

# Installation production
composer update --no-dev --optimize-autoloader
yarn install --frozen-lockfile

# Compilation des assets
NODE_ENV=production yarn run build

# Cache
php bin/console cache:clear --env=prod --no-warmup

# Vérification
ls -la public/build/
# Doit afficher: app.*.js, app.*.css, runtime.*.js, entrypoints.json
```

### Étape 2: Pousser sur Git

```bash
cd /Users/angelo/Projet-perso/QCM-Zelda
git add .
git commit -m "Setup Railway deployment"
git push origin main
```

### Étape 3: Initialiser Railway

```bash
# Installer Railway CLI
npm i -g @railway/cli

# Se connecter
railway login

# Créer le projet
railway init

# Ajouter MySQL
railway add --service mysql

# Lier le Git (optionnel)
railway link <PROJECT_ID>
```

### Étape 4: Configurer les Variables

```bash
# Générer APP_SECRET
APP_SECRET=$(openssl rand -hex 32)
echo "App Secret: $APP_SECRET"

# Définir les variables dans Railway
railway variables set APP_ENV=prod
railway variables set APP_DEBUG=0
railway variables set APP_SECRET="$APP_SECRET"

# Vérifier
railway variables
```

### Étape 5: Déployer

```bash
# Option A: Via Git push
git push railway main

# Option B: Via railway CLI
railway up

# Option C: Via le dashboard Railway
# Aller à https://railway.app/dashboard et ajouter une GitHub connection
```

### Étape 6: Monitoring

```bash
# Voir les logs
railway logs -f

# Ouvrir l'app
railway open

# Status
railway status
```

---

## 📁 Fichiers de Configuration

### nixpacks.toml ✅
Configuration du build pour Railway:
- PHP 8.4
- Extensions: pdo_mysql, intl, zip, opcache, apcu
- Node.js + Yarn
- 3 phases: setup, build, start

### .env.prod ✅
Variables de production:
- `APP_ENV=prod`
- `APP_DEBUG=0`
- `DATABASE_URL` (défini dans Railway)
- `TRUSTED_PROXIES=*`
- `APP_SECRET` (généré et défini dans Railway)

### Procfile ✅
Commande de démarrage:
```
web: php -S 0.0.0.0:${PORT:-8080} -t public
```

### railway.json ✅
Configuration alternative (optionnel).

### composer.json ✅
- PHP 8.4 requis
- Toutes les extensions présentes

### webpack.config.js ✅
- Output: `public/build/`
- Versioning activé
- Build production optimisé

---

## 🔗 Environnement MySQL sur Railway

### Variables à Configurer

```bash
# Railroad détecte automatiquement MySQL:
DATABASE_URL=mysql://root:password@localhost:3306/app

# Railway génère automatiquement:
# ${MYSQL_URL} ou ${DATABASE_PUBLIC_URL}

# À ajouter manuellement:
TRUSTED_PROXIES=*
TRUSTED_HOSTS=.railway.app
```

### Passer le DATABASE_URL

```bash
# Option 1: Railway génère MYSQL_URL automatiquement
# Utiliser ${MYSQL_URL} comme DATABASE_URL

# Option 2: Définir manuellement
railway variables set DATABASE_URL="mysql://user:pass@host:3306/db"
```

---

## ✔️ Pré-Déploiement Checklist

- [x] PHP 8.4 configuré dans nixpacks.toml
- [x] Extensions MySQL, intl, zip configurées
- [x] .env.prod créé avec APP_ENV=prod
- [x] Doctrine MySQL 8.0.32 configuré
- [x] Assets Webpack compilés (7 fichiers générés)
- [x] Cache Symfony nettoyé
- [x] composer.lock mis à jour
- [x] Procfile avec PORT dynamique
- [x] Git prêt pour push

---

## 🐛 Troubleshooting

### "DATABASE_URL not found"
```bash
# Railway crée MYSQL_URL automatiquement
# Ajouter dans .env.prod:
railway variables set DATABASE_URL="${MYSQL_URL}"
```

### "Assets not loading"
```bash
# Vérifier que public/build/ a les fichiers
railway ssh
ls -la public/build/

# Recompiler si nécessaire
php bin/console asset-map:install
```

### "Migration error"
```bash
# Se connecter à la console Railway
railway ssh

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Ou voir leur statut
php bin/console doctrine:migrations:status
```

### "Intl extension missing" (warning)
```bash
# C'est juste un warning, déjà configuré dans nixpacks
# Rien à faire, juste ignorer
```

### Port non accessible
```bash
# Vérifier que Procfile utilise ${PORT:-8080}
cat Procfile

# Redémarrer
railway restart
```

---

## 📊 Post-Déploiement

### Vérifier la Santé

```bash
# Ouvrir l'app
railway open

# Tester les pages
# 1. Accueil: / (statique)
# 2. Enregistrement: /register (formulaire)
# 3. Connexion: /login (formulaire)
# 4. Profil: /profile (données DB)
# 5. Scores: /scores (données DB)

# Vérifier les logs
railway logs -f
```

### Vérifier la Base de Données

```bash
# Via SSH Railway
railway ssh

# Vérifier la connexion
php bin/console doctrine:database:list

# Vérifier les migrations
php bin/console doctrine:migrations:status

# Vérifier les données
php bin/console doctrine:query:sql "SELECT COUNT(*) FROM user"
```

### Vérifier les Assets

```bash
# Via SSH
railway ssh
ls -la public/build/

# Les fichiers doivent exister:
# - app.*.js
# - app.*.css
# - runtime.*.js
# - manifest.json
# - entrypoints.json
```

---

## 🔐 Sécurité

✅ **Validé**:
- `APP_DEBUG=0` en production
- `APP_SECRET` généré aléatoirement
- `DATABASE_URL` en variable d'environnement (pas en git)
- Pas de secrets en dur dans le code

⚠️ **À faire**:
- [ ] Vérifier CSRF_TOKEN en production
- [ ] Vérifier les headers de sécurité
- [ ] Activer HTTPS (Railway le fait auto)
- [ ] Whitelister les domaines dans TRUSTED_HOSTS

---

## 📞 Support Railway

- **Dashboard**: https://railway.app/dashboard
- **Docs**: https://docs.railway.app/
- **CLI Docs**: https://railway.app/cli
- **Community**: https://railway.app/community

---

## 🎯 Commandes Rapides

```bash
# Railway CLI
railway login              # Se connecter
railway init               # Créer un projet
railway add --service X    # Ajouter un service
railway variables          # Lister les variables
railway up                 # Déployer (sans Git)
railway logs -f            # Voir les logs
railway ssh                # Shell interactif
railway restart            # Redémarrer
railway open               # Ouvrir dans le navigateur

# Git push
git push railway main      # Déployer via Git
```

---

## ✨ Résumé

**Statut**: ✅ Prêt pour Railway

**Temps estimé de déploiement**: 5 minutes

**Fichiers modifiés**:
- nixpacks.toml (PHP 8.4 + extensions)
- .env.prod (production defaults)
- config/packages/doctrine.yaml (MySQL 8.0.32)
- Procfile (PORT dynamique)
- package.json (build:railway script)

**Fichiers créés**:
- railway.json (config alternative)
- scripts/prepare-railway.sh (local test)
- RAILWAY_COMMANDS.sh (quick commands)
- RAILWAY_DEPLOYMENT.md (full guide)

**Prochaine étape**: Exécuter `git push` et profiter du déploiement automatique! 🚀
