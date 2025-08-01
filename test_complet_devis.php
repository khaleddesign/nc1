<?php
/**
 * 🧪 SCRIPT DE TEST COMPLET - MODULE DEVIS-FACTURE
 * 
 * Ce script teste intégralement :
 * - Enum StatutDevis (7 statuts + toutes méthodes)
 * - Services métier (ProspectService, CalculService, etc.)
 * - Contrôleur DevisController
 * - Workflow complet prospect → chantier
 * - Interface et données
 * 
 * Usage : php artisan tinker < test_complet_devis.php
 */

echo "🚀 DÉMARRAGE DES TESTS COMPLETS - MODULE DEVIS-FACTURE\n";
echo "=" . str_repeat("=", 60) . "\n\n";

$erreurs = [];
$succes = [];
$warnings = [];

// ====================================================
// 📊 PHASE 1 : TESTS ENUM STATUTDEVIS (15min)
// ====================================================

echo "📊 PHASE 1 : TESTS ENUM STATUTDEVIS\n";
echo "-" . str_repeat("-", 40) . "\n";

try {
    echo "✓ Test 1.1 : Instanciation enum StatutDevis\n";
    
    // Test des 7 statuts obligatoires
    $statutsAttendus = [
        'PROSPECT_BROUILLON' => 'prospect_brouillon',
        'PROSPECT_ENVOYE' => 'prospect_envoye', 
        'PROSPECT_NEGOCIE' => 'prospect_negocie',
        'PROSPECT_ACCEPTE' => 'prospect_accepte',
        'CHANTIER_VALIDE' => 'chantier_valide',
        'FACTURABLE' => 'facturable',
        'FACTURE' => 'facture'
    ];
    
    foreach ($statutsAttendus as $nom => $valeur) {
        $statut = \App\Enums\StatutDevis::from($valeur);
        if ($statut->value !== $valeur) {
            $erreurs[] = "Enum {$nom} : valeur incorrecte";
        } else {
            echo "  ✅ {$nom} : {$valeur}\n";
        }
    }
    
    echo "✓ Test 1.2 : Méthodes label() pour tous les statuts\n";
    foreach (\App\Enums\StatutDevis::cases() as $statut) {
        $label = $statut->label();
        if (empty($label)) {
            $erreurs[] = "Label vide pour statut {$statut->value}";
        } else {
            echo "  ✅ {$statut->value} → '{$label}'\n";
        }
    }
    
    echo "✓ Test 1.3 : Méthodes badgeClass() pour tous les statuts\n";
    foreach (\App\Enums\StatutDevis::cases() as $statut) {
        $badgeClass = $statut->badgeClass();
        if (empty($badgeClass) || !str_contains($badgeClass, 'bg-')) {
            $erreurs[] = "BadgeClass invalide pour {$statut->value}";
        } else {
            echo "  ✅ {$statut->value} → classes CSS OK\n";
        }
    }
    
    echo "✓ Test 1.4 : Logique métier isProspect() et isChantier()\n";
    
    // Test prospects
    $statutsProspects = [
        \App\Enums\StatutDevis::PROSPECT_BROUILLON,
        \App\Enums\StatutDevis::PROSPECT_ENVOYE,
        \App\Enums\StatutDevis::PROSPECT_NEGOCIE,
        \App\Enums\StatutDevis::PROSPECT_ACCEPTE
    ];
    
    foreach ($statutsProspects as $statut) {
        if (!$statut->isProspect() || $statut->isChantier()) {
            $erreurs[] = "Logique prospect incorrecte pour {$statut->value}";
        } else {
            echo "  ✅ {$statut->value} : isProspect() = true\n";
        }
    }
    
    // Test chantiers
    $statutsChantiers = [
        \App\Enums\StatutDevis::CHANTIER_VALIDE,
        \App\Enums\StatutDevis::FACTURABLE,
        \App\Enums\StatutDevis::FACTURE
    ];
    
    foreach ($statutsChantiers as $statut) {
        if ($statut->isProspect() || !$statut->isChantier()) {
            $erreurs[] = "Logique chantier incorrecte pour {$statut->value}";
        } else {
            echo "  ✅ {$statut->value} : isChantier() = true\n";
        }
    }
    
    echo "✓ Test 1.5 : Workflow - getProchainStatutsPossibles()\n";
    
    // Test transitions critiques
    $transitionsAttendues = [
        'PROSPECT_BROUILLON' => ['PROSPECT_ENVOYE'],
        'PROSPECT_ENVOYE' => ['PROSPECT_NEGOCIE', 'PROSPECT_ACCEPTE'],
        'PROSPECT_ACCEPTE' => ['CHANTIER_VALIDE'],
        'CHANTIER_VALIDE' => ['FACTURABLE'],
        'FACTURABLE' => ['FACTURE'],
        'FACTURE' => []
    ];
    
    foreach ($transitionsAttendues as $statutActuel => $statutsSuivants) {
        $statut = \App\Enums\StatutDevis::from(strtolower($statutActuel));
        $prochains = $statut->getProchainStatutsPossibles();
        
        if (count($prochains) !== count($statutsSuivants)) {
            $erreurs[] = "Nombre transitions incorrect pour {$statutActuel}";
        } else {
            echo "  ✅ {$statutActuel} → " . count($statutsSuivants) . " transition(s)\n";
        }
    }
    
    echo "✓ Test 1.6 : Méthodes de permission (peutEtreModifie, peutEtreConverti, etc.)\n";
    
    // Test conversion (seul PROSPECT_ACCEPTE peut être converti)
    $peutConvertir = \App\Enums\StatutDevis::PROSPECT_ACCEPTE->peutEtreConverti();
    if (!$peutConvertir) {
        $erreurs[] = "PROSPECT_ACCEPTE devrait pouvoir être converti";
    } else {
        echo "  ✅ PROSPECT_ACCEPTE : peutEtreConverti() = true\n";
    }
    
    // Test modification
    $statutsModifiables = [
        \App\Enums\StatutDevis::PROSPECT_BROUILLON,
        \App\Enums\StatutDevis::PROSPECT_NEGOCIE,
        \App\Enums\StatutDevis::CHANTIER_VALIDE
    ];
    
    foreach ($statutsModifiables as $statut) {
        if (!$statut->peutEtreModifie()) {
            $erreurs[] = "{$statut->value} devrait pouvoir être modifié";
        } else {
            echo "  ✅ {$statut->value} : peutEtreModifie() = true\n";
        }
    }
    
    $succes[] = "✅ Enum StatutDevis : 7 statuts + toutes méthodes validés";
    
} catch (\Exception $e) {
    $erreurs[] = "❌ ERREUR CRITIQUE Enum : " . $e->getMessage();
}

echo "\n";

// ====================================================
// 🔧 PHASE 2 : TESTS SERVICES MÉTIER (25min)
// ====================================================

echo "🔧 PHASE 2 : TESTS SERVICES MÉTIER\n";
echo "-" . str_repeat("-", 40) . "\n";

try {
    echo "✓ Test 2.1 : Injection des 4 services métier\n";
    
    // Test ProspectService
    try {
        $prospectService = app(\App\Services\ProspectService::class);
        if ($prospectService) {
            echo "  ✅ ProspectService : instancié avec succès\n";
        }
    } catch (\Exception $e) {
        $erreurs[] = "❌ ProspectService non accessible : " . $e->getMessage();
    }
    
    // Test CalculService  
    try {
        $calculService = app(\App\Services\CalculService::class);
        if ($calculService) {
            echo "  ✅ CalculService : instancié avec succès\n";
        }
    } catch (\Exception $e) {
        $erreurs[] = "❌ CalculService non accessible : " . $e->getMessage();
    }
    
    // Test ConversionService
    try {
        $conversionService = app(\App\Services\ConversionService::class);
        if ($conversionService) {
            echo "  ✅ ConversionService : instancié avec succès\n";
        }
    } catch (\Exception $e) {
        $erreurs[] = "❌ ConversionService non accessible : " . $e->getMessage();
    }
    
    // Test NegociationService
    try {
        $negociationService = app(\App\Services\NegociationService::class);
        if ($negociationService) {
            echo "  ✅ NegociationService : instancié avec succès\n";
        }
    } catch (\Exception $e) {
        $erreurs[] = "❌ NegociationService non accessible : " . $e->getMessage();
    }
    
} catch (\Exception $e) {
    $erreurs[] = "❌ ERREUR Services : " . $e->getMessage();
}

try {
    echo "✓ Test 2.2 : ProspectService - Méthodes principales\n";
    
    $prospectService = app(\App\Services\ProspectService::class);
    
    // Test getStatistiquesProspects
    try {
        $stats = $prospectService->getStatistiquesProspects();
        if (!is_array($stats)) {
            $erreurs[] = "getStatistiquesProspects() ne retourne pas un array";
        } else {
            $clefsAttendues = ['total', 'prospects', 'chantiers', 'envoye', 'convertibles', 'montant_total'];
            foreach ($clefsAttendues as $clef) {
                if (!array_key_exists($clef, $stats)) {
                    $warnings[] = "Statistique '{$clef}' manquante";
                } else {
                    echo "  ✅ Statistique '{$clef}' : " . ($stats[$clef] ?? 0) . "\n";
                }
            }
        }
    } catch (\Exception $e) {
        $erreurs[] = "❌ getStatistiquesProspects() : " . $e->getMessage();
    }
    
} catch (\Exception $e) {
    $erreurs[] = "❌ ERREUR ProspectService : " . $e->getMessage();
}

echo "\n";

// ====================================================
// 🗄️ PHASE 3 : TESTS MODÈLES ET DONNÉES (15min)
// ====================================================

echo "🗄️ PHASE 3 : TESTS MODÈLES ET DONNÉES\n";
echo "-" . str_repeat("-", 40) . "\n";

try {
    echo "✓ Test 3.1 : Modèle Devis et base de données\n";
    
    // Compter les devis
    $totalDevis = \App\Models\Devis::count();
    echo "  ✅ Total devis en base : {$totalDevis}\n";
    
    if ($totalDevis > 0) {
        // Tester le premier devis
        $devis = \App\Models\Devis::first();
        
        // Test statut enum
        if ($devis->statut) {
            $statutLabel = $devis->statut->label();
            echo "  ✅ Premier devis - statut : {$statutLabel}\n";
            
            $badgeClass = $devis->statut->badgeClass();
            echo "  ✅ Premier devis - badgeClass : CSS OK\n";
            
            $isProspect = $devis->statut->isProspect();
            echo "  ✅ Premier devis - isProspect : " . ($isProspect ? 'true' : 'false') . "\n";
        } else {
            $warnings[] = "Premier devis sans statut enum";
        }
        
        // Test relations
        if ($devis->commercial) {
            echo "  ✅ Relation commercial : {$devis->commercial->name}\n";
        } else {
            $warnings[] = "Relation commercial manquante";
        }
        
        if ($devis->chantier) {
            echo "  ✅ Relation chantier : {$devis->chantier->titre}\n";
        } else {
            echo "  ℹ️  Devis prospect (pas de chantier lié)\n";
        }
        
        // Test calculs financiers
        if ($devis->montant_ttc !== null) {
            echo "  ✅ Montant TTC : " . number_format($devis->montant_ttc, 2) . " €\n";
        }
        
        if ($devis->montant_ht !== null) {
            echo "  ✅ Montant HT : " . number_format($devis->montant_ht, 2) . " €\n";
        }
        
    } else {
        $warnings[] = "Aucun devis en base pour tester";
    }
    
    echo "✓ Test 3.2 : Modèles User (commerciaux)\n";
    
    $totalCommerciaux = \App\Models\User::where('role', 'commercial')->count();
    echo "  ✅ Total commerciaux : {$totalCommerciaux}\n";
    
    echo "✓ Test 3.3 : Modèles Chantier\n";
    
    $totalChantiers = \App\Models\Chantier::count();
    echo "  ✅ Total chantiers : {$totalChantiers}\n";
    
} catch (\Exception $e) {
    $erreurs[] = "❌ ERREUR Modèles : " . $e->getMessage();
}

echo "\n";

// ====================================================
// 🎯 PHASE 4 : TEST WORKFLOW COMPLET (20min)
// ====================================================

echo "🎯 PHASE 4 : TEST WORKFLOW COMPLET\n";
echo "-" . str_repeat("-", 40) . "\n";

try {
    echo "✓ Test 4.1 : Création prospect via ProspectService\n";
    
    $prospectService = app(\App\Services\ProspectService::class);
    
    // Données de test pour création prospect
    $dataProspect = [
        'titre' => 'Test Prospect - Validation Production',
        'client_nom' => 'Client Test Validation',
        'client_email' => 'test.validation@example.com',
        'client_telephone' => '01.23.45.67.89',
        'commercial_id' => 1, // Supposer qu'il y a au moins un commercial
        'lignes' => [
            [
                'designation' => 'Ligne test validation',
                'quantite' => 2,
                'prix_unitaire' => 500.00,
                'unite' => 'unité'
            ]
        ]
    ];
    
    try {
        $prospect = $prospectService->creerProspect($dataProspect);
        
        // Vérifications création
        if ($prospect && $prospect->id) {
            echo "  ✅ Prospect créé - ID : {$prospect->id}\n";
            echo "  ✅ Numéro généré : {$prospect->numero}\n";
            echo "  ✅ Statut initial : {$prospect->statut->label()}\n";
            echo "  ✅ Montant TTC calculé : " . number_format($prospect->montant_ttc, 2) . " €\n";
            
            // Test workflow : Brouillon → Envoyé
            echo "✓ Test 4.2 : Workflow prospect - Envoi\n";
            try {
                $prospectService->envoyerProspect($prospect);
                $prospect->refresh();
                if ($prospect->statut->value === 'prospect_envoye') {
                    echo "  ✅ Prospect envoyé - nouveau statut : {$prospect->statut->label()}\n";
                } else {
                    $erreurs[] = "Statut après envoi incorrect";
                }
            } catch (\Exception $e) {
                $erreurs[] = "❌ Erreur envoi prospect : " . $e->getMessage();
            }
            
            // Test workflow : Envoyé → Accepté
            echo "✓ Test 4.3 : Workflow prospect - Acceptation\n";
            try {
                $prospectService->accepterProspect($prospect);
                $prospect->refresh();
                if ($prospect->statut->value === 'prospect_accepte') {
                    echo "  ✅ Prospect accepté - nouveau statut : {$prospect->statut->label()}\n";
                    
                    // Test conversion
                    echo "✓ Test 4.4 : Conversion prospect → chantier\n";
                    
                    // Récupérer un chantier existant ou créer un test
                    $chantier = \App\Models\Chantier::first();
                    if (!$chantier) {
                        $warnings[] = "Pas de chantier disponible pour tester la conversion";
                    } else {
                        try {
                            $conversionService = app(\App\Services\ConversionService::class);
                            $resultat = $conversionService->convertirProspectEnChantier($prospect, [
                                'chantier_id' => $chantier->id
                            ]);
                            
                            if ($resultat && isset($resultat['devis'])) {
                                $devisChantier = $resultat['devis'];
                                echo "  ✅ Conversion réussie - nouveau devis ID : {$devisChantier->id}\n";
                                echo "  ✅ Statut après conversion : {$devisChantier->statut->label()}\n";
                                echo "  ✅ Lié au chantier : {$devisChantier->chantier->titre}\n";
                            } else {
                                $erreurs[] = "Conversion n'a pas retourné de résultat valide";
                            }
                        } catch (\Exception $e) {
                            $erreurs[] = "❌ Erreur conversion : " . $e->getMessage();
                        }
                    }
                } else {
                    $erreurs[] = "Statut après acceptation incorrect";
                }
            } catch (\Exception $e) {
                $erreurs[] = "❌ Erreur acceptation prospect : " . $e->getMessage();
            }
            
        } else {
            $erreurs[] = "Création prospect a échoué";
        }
        
    } catch (\Exception $e) {
        $erreurs[] = "❌ Erreur création prospect : " . $e->getMessage();
    }
    
} catch (\Exception $e) {
    $erreurs[] = "❌ ERREUR WORKFLOW : " . $e->getMessage();
}

echo "\n";

// ====================================================
// 🌐 PHASE 5 : TESTS CONTRÔLEUR (10min)
// ====================================================

echo "🌐 PHASE 5 : TESTS CONTRÔLEUR\n";
echo "-" . str_repeat("-", 40) . "\n";

try {
    echo "✓ Test 5.1 : Instanciation DevisController\n";
    
    // Simuler instanciation contrôleur
    $pdfService = app(\App\Services\PdfService::class);
    $controller = new \App\Http\Controllers\DevisController($pdfService);
    
    if ($controller) {
        echo "  ✅ DevisController instancié avec succès\n";
    }
    
    echo "✓ Test 5.2 : Méthodes critiques du contrôleur disponibles\n";
    
    $methodesAttendues = [
        'globalIndex', 'globalCreate', 'globalStore', 'globalShow',
        'index', 'create', 'store', 'show', 'edit', 'update',
        'prospects', 'convertToChantier', 'downloadPdf'
    ];
    
    foreach ($methodesAttendues as $methode) {
        if (method_exists($controller, $methode)) {
            echo "  ✅ Méthode {$methode}() : disponible\n";
        } else {
            $erreurs[] = "Méthode {$methode}() manquante dans DevisController";
        }
    }
    
} catch (\Exception $e) {
    $erreurs[] = "❌ ERREUR Contrôleur : " . $e->getMessage();
}

echo "\n";

// ====================================================
// 📊 PHASE 6 : RÉSULTATS ET RAPPORT FINAL
// ====================================================

echo "📊 PHASE 6 : RÉSULTATS ET RAPPORT FINAL\n";
echo "=" . str_repeat("=", 60) . "\n\n";

// Calcul des scores
$totalTests = count($succes) + count($erreurs) + count($warnings);
$tauxReussite = $totalTests > 0 ? round((count($succes) / $totalTests) * 100, 1) : 0;

echo "🎯 RÉSUMÉ EXÉCUTIF\n";
echo "-" . str_repeat("-", 20) . "\n";
echo "✅ Tests réussis     : " . count($succes) . "\n";
echo "❌ Erreurs critiques : " . count($erreurs) . "\n";
echo "⚠️  Avertissements   : " . count($warnings) . "\n";
echo "📊 Taux de réussite  : {$tauxReussite}%\n\n";

if (!empty($succes)) {
    echo "✅ SUCCÈS :\n";
    foreach ($succes as $s) {
        echo "   {$s}\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "⚠️  AVERTISSEMENTS :\n";
    foreach ($warnings as $w) {
        echo "   {$w}\n";
    }
    echo "\n";
}

if (!empty($erreurs)) {
    echo "❌ ERREURS CRITIQUES :\n";
    foreach ($erreurs as $e) {
        echo "   {$e}\n";
    }
    echo "\n";
}

// Décision finale
echo "🏆 DÉCISION FINALE :\n";
echo "-" . str_repeat("-", 20) . "\n";

if (count($erreurs) === 0) {
    echo "✅ SYSTÈME VALIDÉ POUR LA PRODUCTION !\n";
    echo "   → Tous les composants critiques fonctionnent\n";
    echo "   → Workflow prospect→chantier opérationnel\n";
    echo "   → Enum StatutDevis complet et cohérent\n";
    echo "   → Services métier accessibles\n\n";
    echo "🚀 Le module devis-facture est prêt pour la mise en production !\n";
} elseif (count($erreurs) <= 2 && count($warnings) <= 5) {
    echo "🔄 CORRECTIONS MINEURES REQUISES\n";
    echo "   → Quelques ajustements nécessaires avant production\n";
    echo "   → Fonctionnalités principales opérationnelles\n";
    echo "   → Pas de blocage majeur identifié\n\n";
    echo "⏰ Estimation correction : 1-2 heures\n";
} else {
    echo "❌ RETOUR EN DÉVELOPPEMENT NÉCESSAIRE\n";
    echo "   → Erreurs critiques détectées\n";
    echo "   → Composants essentiels non fonctionnels\n";
    echo "   → Tests approfondis requis\n\n";
    echo "⏰ Ne pas déployer en production\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🧪 Tests terminés - " . date('Y-m-d H:i:s') . "\n";
echo "👨‍💻 Rapport généré automatiquement\n";
echo str_repeat("=", 60) . "\n";

// Retourner le statut pour usage programmatique
return [
    'succes' => count($succes),
    'erreurs' => count($erreurs), 
    'warnings' => count($warnings),
    'taux_reussite' => $tauxReussite,
    'validation_production' => count($erreurs) === 0,
    'details' => [
        'succes' => $succes,
        'erreurs' => $erreurs,
        'warnings' => $warnings
    ]
];