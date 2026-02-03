# 🚀 Guide de Déploiement Symfony sur Railway

## ✅ Checklist Pré-Déploiement

### 1. Configuration Locale
- [ ] Vérifier que `.env.prod` existe
- [ ] Vérifier `nixpacks.toml` avec PHP 8.4
- [ ] Vérifier `Procfile` avec commande de démarrage
- [ ] Vérifier `railway.json` (optionnel)
- [ ] Vérifier `package.json` avec scripts build

### 2. Code & Dépendances
- [ ] `composer install` exécuté localement
- [ ] `yarn install` exécuté localement  
- [ ] `yarn run build` réussi (public/build/ créé)
- [ ] `composer.json` contient `ext-pdo_mysql`
- [ ] Pas de secrets commitées dans `.env` ou `.env.local`

### 3. Assets & Frontend
- [ ] `public/build/app.js` existe
- [ ] `public/build/app.css` existe
- [ ] `public/build/runtime.js` existe
- [ ] `public/build/manifest.json` existe
- [ ] `webpack.config.js` utilise `public/build/` comme output path

### 4. Base de Données
- [ ] `config/packages/doctrine.yaml` configuré pour MySQL 8
- [ ] `DATABASE_URL` sera défini dans Railway dashboard
- [ ] Migrations sont à jour (`migrations/` rempli)
- [ ] `.env.prod` ne contient pas de credentials

### 5. Sécurité & Env
- [ ] `APP_ENV=prod` dans `.env.prod`
- [ ] `APP_DEBUG=0` dans `.env.prod`
- [ ] `APP_SECRET` sera défini dans Railway dashboard
- [ ] Pas de `.env.local` commitée

---

## 📋 Variables d'Environnement Railway

Ajouter ces variables dans le Railway Dashboard (Variables > Add Variable):

```env
# Requis
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=<générer-une-clé-aléatoire-longue>
DATABASE_URL=mysql://<user>:<password>@mysql-host:<port>/<database>?serverVersion=8.0.32&charset=utf8mb4

# Optionnel
TRUSTED_PROXIES=*
TRUSTED_HOSTS=^(.+\.)?railway\.app$
MAILER_DSN=null://null
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
```

---

## 🔧 Commandes Locales de Test

### Tester la build production localement:

```bash
# 1. Nettoyer les artifacts
rm -rf var/cache/* var/log/* public/build

# 2. Installer dépendances (prod only)
composer install --no-dev --optimize-autoloader

# 3. Compiler assets
yarn install --frozen-lockfile
yarn run build

# 4. Nettoyer les caches
php bin/console cache:clear --no-warmup --no-interaction

# 5. Vérifier que ça démarre
php -S 0.0.0.0:8080 -t public

# Visiter http://localhost:8080
```

### Test complet avec script:

```bash
bash scripts/prepare-railway.sh
```

---

## 🚀 Déploiement sur Railway

### Étape 1: Créer le projet Railway

```bash
# Login à Railway (si nécessaire)
railway login

# Initialiser le projet Railway
railway init

# Ou créer via le dashboard: https://railway.app/dashboard
```

### Étape 2: Configurer les services

```bash
# Ajouter MySQL
railway add --service mysql

# Définir les variables d'env
railway variables set APP_ENV=prod
railway variables set APP_DEBUG=0
railway variables set APP_SECRET=$(openssl rand -hex 32)

# DATABASE_URL sera générée automatiquement par Railway si MySQL est attaché
# Vérifier: railway variables
```

### Étape 3: Pousser et déployer

```bash
# Ajouter le remote Railway
railway link <project-id>

# Ou directement:
git push railway main

# Railway détecte nixpacks.toml automatiquement
# et lance:
# - composer install --no-dev --optimize-autoloader
# - yarn install --frozen-lockfile
# - yarn run build
# - php -S 0.0.0.0:8080 -t public
```

### Étape 4: Vérifier le déploiement

```bash
# Voir les logs
railway logs

# Vérifier le status
railway status

# Visiter l'app
railway open
```

---

## 📡 Fichiers de Configuration Railway

### `nixpacks.toml` (Recommandé - Nixpacks Builder)

```toml
[providers]
php = "8.4"

[phases.setup]
nixPkgs = [
    "php84",
    "php84Extensions.pdo_mysql",
    "php84Extensions.intl",
    "php84Extensions.zip",
    "php84Extensions.opcache",
    "nodejs",
    "yarn"
]

[phases.build]
cmds = [
    "composer install --no-dev --optimize-autoloader --no-interaction",
    "yarn install --frozen-lockfile",
    "yarn run build"
]

[phases.start]
cmds = [
    "rm -rf var/cache/*",
    "php bin/console cache:clear --no-warmup --no-interaction"
]

[start]
cmd = "php -S 0.0.0.0:${PORT:-8080} -t public"
```

### `Procfile` (Alternative - Buildpack classique)

```procfile
web: php -S 0.0.0.0:${PORT:-8080} -t public
```

### `railway.json` (Config JSON alternative)

```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "nixpacks",
    "buildCommand": "composer install --no-dev --optimize-autoloader && yarn install --frozen-lockfile && yarn run build"
  },
  "deploy": {
    "startCommand": "php -S 0.0.0.0:${PORT:-8080} -t public",
    "restartPolicyType": "on_failure"
  },
  "plugins": {
    "mysql": {
      "source": "mysql"
    }
  }
}
```

---

## 🔴 Problèmes Courants & Solutions

### ❌ "DATABASE_URL not found"

**Cause**: Variable d'env non définie sur Railway

**Solution**:
```bash
railway variables set DATABASE_URL=mysql://...
# Ou via le dashboard: Variables > Add Variable
```

### ❌ "Opcache conflicts"

**Cause**: PHP Opcache mal configuré en production

**Solution**: Déjà géré dans `nixpacks.toml` avec `opcache` extension

### ❌ "Assets not compiled"

**Cause**: `yarn run build` échoue ou `public/build` vide

**Solution**:
```bash
# Vérifier localement
yarn install --frozen-lockfile
yarn run build
ls -la public/build/

# Vérifier les logs Railway
railway logs -u=<deployment-id>
```

### ❌ "Port déjà utilisé"

**Cause**: PORT env var conflictuelle

**Solution**: Déjà géré dans `Procfile` avec `${PORT:-8080}`

### ❌ "MySQL connexion error"

**Cause**: DATABASE_URL format incorrect ou service non attaché

**Solution**:
```bash
# Vérifier la connexion
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate

# Format DATABASE_URL correct:
# mysql://user:password@host:port/database?serverVersion=8.0.32&charset=utf8mb4
```

---

## 📊 Monitoring Post-Déploiement

### Vérifications après le déploiement:

```bash
# 1. Logs
railway logs

# 2. Base de données
railway shell
php bin/console doctrine:database:list
php bin/console doctrine:migrations:status

# 3. Cache Symfony
rm -rf var/cache/* && php bin/console cache:clear

# 4. Assets
curl https://your-app.railway.app/build/app.js | head -10
```

### Health Check

```bash
# Railway peut configurer un health check
# GET /

# Vérifier que l'app répond
curl -I https://your-app.railway.app/
# HTTP/1.1 200 OK
```

---

## 🔐 Sécurité

### Points importants:

1. **Jamais** commiter `.env.local` ou `.env.prod.local`
2. **Toujours** utiliser Railway Variables pour les secrets
3. **Vérifier** que `APP_DEBUG=0` en production
4. **Configurer** CORS si API externe
5. **Utiliser** HTTPS (Railway fournit automatiquement)
6. **Activer** les migrations auto ou les faire manuellement

### Générer APP_SECRET sûr:

```bash
openssl rand -hex 32
# Copier dans Railway Variables
```

---

## 🎯 Résumé Déploiement Rapide

```bash
# 1. Prep locale
bash scripts/prepare-railway.sh

# 2. Commit
git add . && git commit -m "Prepare for Railway" && git push

# 3. Railway CLI
railway login
railway init
railway add --service mysql
railway variables set APP_ENV=prod APP_DEBUG=0 APP_SECRET=$(openssl rand -hex 32)

# 4. Deploy
git push railway main

# 5. Monitor
railway logs
railway open
```

---

## 📖 Documentation Externe

- [Railway Docs](https://docs.railway.app/)
- [Nixpacks Documentation](https://nixpacks.com/)
- [Symfony Deployment](https://symfony.com/doc/current/deployment.html)
- [Symfony & MySQL](https://symfony.com/doc/current/doctrine.html)

---

**Last Updated**: 3 février 2026  
**Symfony Version**: 8.0  
**PHP Version**: 8.4  
**Railway**: Latest
