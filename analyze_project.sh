#!/bin/bash

echo "==================== STRUCTURE COMPLÈTE DU PROJET ===================="
echo ""

# Affichage de l'arborescence complète avec limitation de profondeur
echo "📁 ARBORESCENCE GÉNÉRALE (3 niveaux):"
tree -a -L 3 --dirsfirst 2>/dev/null || find . -type d -not -path '*/\.*' | head -50 | sort

echo ""
echo "📁 DOSSIERS APP/ (structure détaillée):"
find app -type d 2>/dev/null | sort

echo ""
echo "📁 DOSSIERS RESOURCES/ (structure détaillée):"
find resources -type d 2>/dev/null | sort

echo ""
echo "📁 DOSSIERS ROUTES/ (fichiers de routes):"
ls -la routes/ 2>/dev/null

echo ""
echo "📁 CONTRÔLEURS EXISTANTS:"
find app/Http/Controllers -name "*.php" 2>/dev/null | sort

echo ""
echo "📁 MODÈLES EXISTANTS:"
find app/Models -name "*.php" 2>/dev/null | sort

echo ""
echo "📁 POLICIES EXISTANTES:"
find app/Policies -name "*.php" 2>/dev/null | sort

echo ""
echo "📁 MIDDLEWARE EXISTANTS:"
find app/Http/Middleware -name "*.php" 2>/dev/null | sort

echo ""
echo "📁 VUES EXISTANTES (par dossier):"
find resources/views -type d 2>/dev/null | sort

echo ""
echo "📁 MIGRATIONS EXISTANTES:"
ls -la database/migrations/ 2>/dev/null | tail -20

echo ""
echo "📁 CONFIGURATION IMPORTANTE:"
echo "--- composer.json (dépendances) ---"
if [ -f composer.json ]; then
    echo "✅ composer.json existe"
    grep -A 5 -B 5 '"require"' composer.json | head -15
else
    echo "❌ composer.json non trouvé"
fi

echo ""
echo "--- .env (configuration) ---"
if [ -f .env ]; then
    echo "✅ .env existe"
    echo "Base de données:"
    grep "DB_" .env | head -5
    echo "App config:"
    grep "APP_" .env | head -5
else
    echo "❌ .env non trouvé"
fi

echo ""
echo "--- config/app.php (providers) ---"
if [ -f config/app.php ]; then
    echo "✅ config/app.php existe"
    grep -A 3 -B 3 "providers" config/app.php | head -10
else
    echo "❌ config/app.php non trouvé"
fi

echo ""
echo "🔍 RECHERCHE DE FICHIERS SPÉCIFIQUES:"
echo ""

echo "--- Kernel.php (middleware) ---"
find . -name "Kernel.php" -exec echo "Trouvé: {}" \;

echo "--- RouteServiceProvider.php ---"
find . -name "RouteServiceProvider.php" -exec echo "Trouvé: {}" \;

echo "--- AuthServiceProvider.php (policies) ---"
find . -name "AuthServiceProvider.php" -exec echo "Trouvé: {}" \;

echo ""
echo "🔧 VÉRIFICATION DES ROUTES:"
if [ -f routes/web.php ]; then
    echo "✅ routes/web.php existe"
    echo "Nombre de lignes dans web.php:"
    wc -l routes/web.php
    echo ""
    echo "Routes principales définies:"
    grep -n "Route::" routes/web.php | head -10
else
    echo "❌ routes/web.php non trouvé"
fi

if [ -f routes/api.php ]; then
    echo "✅ routes/api.php existe"
    echo "Nombre de lignes dans api.php:"
    wc -l routes/api.php
else
    echo "❌ routes/api.php non trouvé"
fi

echo ""
echo "🗄️ VÉRIFICATION BASE DE DONNÉES:"
echo "Migrations existantes (10 dernières):"
ls -t database/migrations/ 2>/dev/null | head -10

echo ""
echo "📋 RÉSUMÉ FINAL:"
echo "Contrôleurs: $(find app/Http/Controllers -name "*.php" 2>/dev/null | wc -l)"
echo "Modèles: $(find app/Models -name "*.php" 2>/dev/null | wc -l)"
echo "Policies: $(find app/Policies -name "*.php" 2>/dev/null | wc -l)"
echo "Middleware: $(find app/Http/Middleware -name "*.php" 2>/dev/null | wc -l)"
echo "Vues: $(find resources/views -name "*.blade.php" 2>/dev/null | wc -l)"
echo "Migrations: $(ls database/migrations/ 2>/dev/null | wc -l)"

echo ""
echo "==================== FIN DE L'ANALYSE ===================="
