#!/bin/bash

echo "🔧 CORRECTION COMPLÈTE DES ROUTES FACTURES"
echo "==========================================="

# 1. ÉTAPE 1: Ajouter la route manquante dans web.php
echo "📝 1. Ajout de la route manquante pour supprimer les paiements..."

# Créer un backup du fichier routes
cp routes/web.php routes/web.php.backup

# Ajouter la route manquante après la ligne des autres routes de paiements
# Dans le bloc "Routes pour les factures (liées aux chantiers)"
sed -i '' '/Route::get.*factures.*paiements.*FactureController@recapitulatifPaiements/a\
        Route::delete('\''factures/{facture}/paiements/{paiement}'\'', [PaiementController::class, '\''destroy'\''])->name('\''chantiers.factures.paiements.destroy'\'');
' routes/web.php

echo "✅ Route ajoutée: chantiers.factures.paiements.destroy"

# 2. ÉTAPE 2: Créer backups des vues
echo "📁 2. Sauvegarde des vues..."
cp resources/views/factures/index.blade.php resources/views/factures/index.blade.php.backup
cp resources/views/factures/show.blade.php resources/views/factures/show.blade.php.backup  
cp resources/views/factures/paiements.blade.php resources/views/factures/paiements.blade.php.backup

# 3. ÉTAPE 3: Corrections dans index.blade.php
echo "🔧 3. Correction de factures/index.blade.php..."

sed -i '' 's|route('\''factures\.pdf'\''|route('\''chantiers.factures.pdf'\''|g' resources/views/factures/index.blade.php
sed -i '' 's|route('\''factures\.envoyer'\''|route('\''chantiers.factures.envoyer'\''|g' resources/views/factures/index.blade.php
sed -i '' 's|route('\''factures\.relance'\''|route('\''chantiers.factures.relance'\''|g' resources/views/factures/index.blade.php
sed -i '' 's|route('\''factures\.dupliquer'\''|route('\''chantiers.factures.dupliquer'\''|g' resources/views/factures/index.blade.php
sed -i '' 's|route('\''factures\.paiements\.store'\''|route('\''chantiers.factures.paiement'\''|g' resources/views/factures/index.blade.php

# 4. ÉTAPE 4: Corrections dans show.blade.php
echo "🔧 4. Correction de factures/show.blade.php..."

sed -i '' 's|route('\''factures\.envoyer'\''|route('\''chantiers.factures.envoyer'\''|g' resources/views/factures/show.blade.php
sed -i '' 's|route('\''factures\.pdf'\''|route('\''chantiers.factures.pdf'\''|g' resources/views/factures/show.blade.php
sed -i '' 's|route('\''factures\.dupliquer'\''|route('\''chantiers.factures.dupliquer'\''|g' resources/views/factures/show.blade.php
sed -i '' 's|route('\''factures\.relance'\''|route('\''chantiers.factures.relance'\''|g' resources/views/factures/show.blade.php
sed -i '' 's|route('\''factures\.paiements\.store'\''|route('\''chantiers.factures.paiement'\''|g' resources/views/factures/show.blade.php
sed -i '' 's|route('\''factures\.paiements\.destroy'\''|route('\''chantiers.factures.paiements.destroy'\''|g' resources/views/factures/show.blade.php

# 5. ÉTAPE 5: Corrections dans paiements.blade.php
echo "🔧 5. Correction de factures/paiements.blade.php..."

sed -i '' 's|route('\''factures\.pdf'\''|route('\''chantiers.factures.pdf'\''|g' resources/views/factures/paiements.blade.php
sed -i '' 's|route('\''factures\.relance'\''|route('\''chantiers.factures.relance'\''|g' resources/views/factures/paiements.blade.php
sed -i '' 's|route('\''factures\.paiements\.store'\''|route('\''chantiers.factures.paiement'\''|g' resources/views/factures/paiements.blade.php

# 6. ÉTAPE 6: Ajouter le contrôleur manquant (PaiementController)
echo "📝 6. Vérification du contrôleur PaiementController..."

if [ ! -f "app/Http/Controllers/PaiementController.php" ]; then
    echo "⚠️  PaiementController manquant - création nécessaire"
    cat > app/Http/Controllers/PaiementController.php << 'EOF'
<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Supprimer un paiement
     */
    public function destroy(Paiement $paiement)
    {
        $facture = $paiement->facture;
        $chantier = $facture->chantier;
        
        $this->authorize('gererPaiements', $facture);

        try {
            $montant = $paiement->montant;
            $paiement->delete();
            
            return back()->with('success', "Paiement de {$montant}€ supprimé avec succès.");
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
EOF
    echo "✅ PaiementController créé"
else
    echo "✅ PaiementController existe déjà"
fi

# 7. ÉTAPE 7: Ajouter l'import dans web.php si nécessaire
echo "📝 7. Vérification des imports dans web.php..."

if ! grep -q "PaiementController" routes/web.php; then
    sed -i '' '/use App\\Http\\Controllers\\FactureController;/a\
use App\\Http\\Controllers\\PaiementController;
' routes/web.php
    echo "✅ Import PaiementController ajouté"
else
    echo "✅ Import PaiementController déjà présent"
fi

echo ""
echo "🎉 CORRECTION TERMINÉE !"
echo "======================="
echo ""
echo "📋 Résumé des corrections:"
echo "• ✅ Route ajoutée: chantiers.factures.paiements.destroy"
echo "• ✅ factures.pdf → chantiers.factures.pdf"
echo "• ✅ factures.envoyer → chantiers.factures.envoyer" 
echo "• ✅ factures.relance → chantiers.factures.relance"
echo "• ✅ factures.dupliquer → chantiers.factures.dupliquer"
echo "• ✅ factures.paiements.store → chantiers.factures.paiement"
echo "• ✅ factures.paiements.destroy → chantiers.factures.paiements.destroy"
echo "• ✅ PaiementController créé/vérifié"
echo ""
echo "🧪 TESTS À FAIRE:"
echo "1. php artisan route:clear"
echo "2. php artisan serve"
echo "3. Accéder à une facture"
echo "4. Tester toutes les actions (PDF, envoi, paiements)"
echo ""
echo "🔙 Pour annuler toutes les modifications:"
echo "   cp routes/web.php.backup routes/web.php"
echo "   cp resources/views/factures/*.backup resources/views/factures/"
echo "   rm app/Http/Controllers/PaiementController.php # si créé"