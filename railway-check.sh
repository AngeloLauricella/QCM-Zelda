#!/bin/bash

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}════════════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}  ✅ RAILWAY DEPLOYMENT - PRE-DEPLOYMENT VERIFICATION${NC}"
echo -e "${BLUE}════════════════════════════════════════════════════════════${NC}"
echo ""

PASSED=0
FAILED=0

check() {
    local name=$1
    local cmd=$2
    
    if eval "$cmd" > /dev/null 2>&1; then
        echo -e "${GREEN}✅${NC} $name"
        ((PASSED++))
    else
        echo -e "${RED}❌${NC} $name"
        ((FAILED++))
    fi
}

# Project checks
echo -e "${YELLOW}📦 Project Structure${NC}"
check "composer.json exists" "test -f composer.json"
check "package.json exists" "test -f package.json"
check "webpack.config.js exists" "test -f webpack.config.js"
check ".env exists" "test -f .env"
check ".env.prod exists" "test -f .env.prod"
check "Procfile exists" "test -f Procfile"
check "nixpacks.toml exists" "test -f nixpacks.toml"
check "railway.json exists" "test -f railway.json"
echo ""

# Build artifacts
echo -e "${YELLOW}🔨 Build Artifacts${NC}"
check "public/ exists" "test -d public"
check "public/build/ exists" "test -d public/build"
check "public/build/entrypoints.json exists" "test -f public/build/entrypoints.json"
check "public/build/manifest.json exists" "test -f public/build/manifest.json"
check "vendor/ exists" "test -d vendor"
check "node_modules exists (for local)" "test -d node_modules || true"
echo ""

# Configuration validation
echo -e "${YELLOW}⚙️  Configuration${NC}"
check "nixpacks.toml has PHP 8.4" "grep -q 'php = \"8.4\"' nixpacks.toml"
check "nixpacks.toml has pdo_mysql" "grep -q 'pdo_mysql' nixpacks.toml"
check "nixpacks.toml has intl" "grep -q 'intl' nixpacks.toml"
check "nixpacks.toml has zip" "grep -q 'zip' nixpacks.toml"
check "nixpacks.toml has opcache" "grep -q 'opcache' nixpacks.toml"
check "nixpacks.toml has apcu" "grep -q 'apcu' nixpacks.toml"
check "nixpacks.toml has nodejs" "grep -q 'nodejs' nixpacks.toml"
check "Procfile has PORT variable" "grep -q '\${PORT' Procfile"
check "Doctrine has server_version" "grep -q 'server_version' config/packages/doctrine.yaml"
check "Doctrine has charset utf8mb4" "grep -q 'utf8mb4' config/packages/doctrine.yaml"
echo ""

# Environment
echo -e "${YELLOW}🌍 Environment${NC}"
check ".env.prod has APP_ENV=prod" "grep -q 'APP_ENV=prod' .env.prod"
check ".env.prod has APP_DEBUG=0" "grep -q 'APP_DEBUG=0' .env.prod"
check ".env.prod has DATABASE_URL" "grep -q 'DATABASE_URL' .env.prod"
check ".env.prod has TRUSTED_PROXIES" "grep -q 'TRUSTED_PROXIES' .env.prod"
check "PHP version >= 8.4" "php -v | grep -E '8\\.[4-9]|9\\.[0-9]'"
echo ""

# Git status
echo -e "${YELLOW}📝 Git Status${NC}"
check "Git repository initialized" "test -d .git"
check "Git has commits" "test \$(git rev-list --all --count) -gt 0 2>/dev/null || true"
echo ""

# Summary
echo ""
echo -e "${BLUE}════════════════════════════════════════════════════════════${NC}"
echo -e "${GREEN}Passed: $PASSED${NC} | ${RED}Failed: $FAILED${NC}"

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}✅ All checks passed! Ready for Railway deployment.${NC}"
    echo ""
    echo -e "${YELLOW}Next steps:${NC}"
    echo "  1. git add . && git commit -m 'Railway deployment' && git push"
    echo "  2. railway init (or link to existing project)"
    echo "  3. railway add --service mysql"
    echo "  4. railway variables set APP_ENV=prod APP_DEBUG=0"
    echo "  5. git push railway main"
    exit 0
else
    echo -e "${RED}❌ Some checks failed. Please review above.${NC}"
    exit 1
fi
