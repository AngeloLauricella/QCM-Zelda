# 🚀 Railway Deployment - Quick Start

**Status**: ✅ **PRÊT POUR RAILWAY**

Tous les checks sont passés. Ton projet est 100% configuré pour Railway.

---

## 5️⃣ Étapes pour Déployer

### 1️⃣ Pousser sur Git

```bash
cd /Users/angelo/Projet-perso/QCM-Zelda

git add .
git commit -m "Configure for Railway deployment"
git push origin main
```

### 2️⃣ Initialiser Railway (si c'est la première fois)

```bash
# Installer Railway CLI (une fois)
npm install -g @railway/cli

# Se connecter à Railway
railway login

# Créer un nouveau projet
railway init

# Ajouter MySQL
railway add --service mysql
```

### 3️⃣ Configurer les Variables

```bash
# Générer un APP_SECRET sécurisé
APP_SECRET=$(openssl rand -hex 32)

# Définir les variables dans Railway
railway variables set \
  APP_ENV=prod \
  APP_DEBUG=0 \
  APP_SECRET="$APP_SECRET"

# Vérifier
railway variables
```

### 4️⃣ Déployer

```bash
# Option A: Via Git (recommandé)
git push railway main

# Option B: Via Railway CLI
railway up

# Option C: Via le Dashboard
# https://railway.app/dashboard -> GitHub -> Push auto
```

### 5️⃣ Vérifier

```bash
# Voir les logs
railway logs -f

# Ouvrir l'app dans le navigateur
railway open

# Status
railway status
```

---

## 📋 Qu'est-ce qui a été configuré?

✅ **PHP 8.4** - Configuration nixpacks.toml  
✅ **Extensions** - pdo_mysql, intl, zip, opcache, apcu  
✅ **Node.js** - Pour Webpack Encore  
✅ **Assets** - 7 fichiers compilés dans public/build/  
✅ **Doctrine** - MySQL 8.0.32 configuré  
✅ **Environment** - .env.prod avec production defaults  
✅ **Procfile** - PORT dynamique ${PORT:-8080}  
✅ **DATABASE_URL** - Template, défini dans Railway  

---

## 🔐 Variables à Configurer dans Railway

```
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=[GÉNÉRÉ AU DESSUS]
DATABASE_URL=mysql://root:password@localhost:3306/db  # Railway génère auto
TRUSTED_PROXIES=*
TRUSTED_HOSTS=.railway.app
```

Railway génère automatiquement `DATABASE_URL` via la connexion MySQL. Tu n'as juste à la copier.

---

## 🆘 Troubleshooting Rapide

**"DatabaseConnection Error"**
```bash
# Railway génère MYSQL_URL auto
railway variables
# Copier la valeur de MYSQL_URL
railway variables set DATABASE_URL="${MYSQL_URL}"
```

**"Assets not found (404)"**
```bash
# Vérifier les assets compilés
railway ssh
ls -la public/build/

# Si vide, recompiler
php bin/console asset-map:install
```

**"Migrations failed"**
```bash
railway ssh
php bin/console doctrine:migrations:migrate
```

**"Extension intl missing" (warning)**
- C'est juste un warning, déjà configuré, ignore

---

## 📊 Post-Déploiement

### Tester l'App

1. `railway open` pour ouvrir dans le navigateur
2. Tester les pages:
   - `/` (accueil)
   - `/register` (formulaire)
   - `/login` (connexion)
   - `/profile` (base de données)
   - `/scores` (base de données)

### Vérifier les Logs

```bash
railway logs -f

# Chercher des erreurs
railway logs | grep ERROR
railway logs | grep Exception
```

### Vérifier la DB

```bash
railway ssh
php bin/console doctrine:database:list
php bin/console doctrine:migrations:status
php bin/console doctrine:query:sql "SELECT COUNT(*) FROM user"
```

---

## 📁 Fichiers Clés

| Fichier | Rôle | ✅ Status |
|---------|------|----------|
| `nixpacks.toml` | Configuration build Railway | ✅ OK |
| `.env.prod` | Variables production | ✅ OK |
| `Procfile` | Commande de démarrage | ✅ OK |
| `railway.json` | Config alternative | ✅ OK |
| `composer.json` | PHP 8.4 requis | ✅ OK |
| `webpack.config.js` | Assets build | ✅ OK |
| `public/build/` | Assets compilés | ✅ OK |

---

## 🎯 Commands Rapides

```bash
# Railway CLI
railway init               # Créer projet
railway add --service X    # Ajouter MySQL
railway variables          # Voir les variables
railway up                 # Déployer
railway logs -f            # Logs en direct
railway ssh                # Terminal interactif
railway open               # Ouvrir app
railway restart            # Redémarrer
railway delete             # Supprimer projet

# Git
git push railway main      # Déployer via Git
```

---

## ✨ Résumé

**Tout est prêt!** Tu n'as qu'à:

1. `git push` ton code
2. `railway init` et `railway add --service mysql`
3. `railway variables set APP_ENV=prod APP_DEBUG=0`
4. `git push railway main`

**Temps total: 5 minutes** ⏱️

🚀 Bon déploiement!
