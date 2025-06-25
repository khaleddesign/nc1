#!/bin/bash

echo "🔍 VÉRIFICATION STRUCTURE LARAVEL BTP"
echo "====================================="
echo ""

echo "📁 1. MODÈLES DISPONIBLES :"
echo "----------------------------"
ls -la app/Models/ 2>/dev/null || echo "❌ Dossier app/Models/ introuvable"
echo ""

echo "🏗️ 2. CONTRÔLEURS DISPONIBLES :"
echo "-------------------------------"
ls -la app/Http/Controllers/ 2>/dev/null | grep -v "\.php$" | head -5
ls -la app/Http/Controllers/ 2>/dev/null | grep -E "(Devis|Facture|Paiement|Chantier)" || echo "ℹ️ Aucun contrôleur spécifique trouvé"
echo ""

echo "🗂️ 3. MIGRATIONS DISPONIBLES :"
echo "------------------------------"
ls -la database/migrations/ 2>/dev/null | tail -10
echo ""

echo "🛠️ 4. FACTORIES DISPONIBLES :"
echo "-----------------------------"
ls -la database/factories/ 2>/dev/null || echo "❌ Dossier database/factories/ introuvable"
echo ""

echo "📝 5. TESTS EXISTANTS :"
echo "----------------------"
echo "Unit Tests:"
ls -la tests/Unit/ 2>/dev/null || echo "❌ Dossier tests/Unit/ introuvable"
echo ""
echo "Feature Tests:"
ls -la tests/Feature/ 2>/dev/null || echo "❌ Dossier tests/Feature/ introuvable"
echo ""

echo "🎯 PROCHAINE ÉTAPE :"
echo "==================="
echo "Exécutez maintenant :"
echo "php artisan tinker"
echo ""
echo "Puis dans Tinker, copiez-collez ceci :"
echo "Schema::getColumnListing('users')"
echo "Schema::getColumnListing('chantiers')"
echo "Schema::getColumnListing('devis')"
echo "Schema::getColumnListing('factures')"
echo "Schema::getColumnListing('paiements')"
echo "Schema::getColumnListing('lignes')"
echo "exit"
echo ""
echo "📋 ET ENFIN :"
echo "php artisan route:list | grep -E '(facture|devis|paiement|chantier)'"
