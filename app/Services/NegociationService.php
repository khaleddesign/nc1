<?php

namespace App\Services;

use App\Models\Devis;
use App\Enums\StatutDevis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class NegociationService
{
    private CalculService $calculService;

    public function __construct(?CalculService $calculService = null)
    {
        $this->calculService = $calculService ?? new CalculService();
    }

    /**
     * Ajouter une version de négociation à un prospect
     */
    public function ajouterVersion(Devis $devis, string $motif, array $modifications = []): Devis
    {
        try {
            // Vérifier que le devis peut être négocié
            if (!$this->peutNegocier($devis)) {
                throw new Exception("Ce devis ne peut pas être négocié dans son état actuel");
            }

            DB::beginTransaction();

            // Sauvegarder la version actuelle dans l'historique
            $this->sauvegarderVersionActuelle($devis);

            // Appliquer les modifications si fournies
            if (!empty($modifications['lignes'])) {
                $this->appliquerModificationsLignes($devis, $modifications['lignes']);
            }

            // Mettre à jour le statut et les informations de négociation
            $devis->changerStatut(
                StatutDevis::PROSPECT_NEGOCIE,
                $motif
            );

            $devis->update([
                'date_derniere_negociation' => now(),
            ]);

            // Ajouter l'entrée de négociation
            $this->ajouterEntreeNegociation($devis, $motif, $modifications);

            DB::commit();

            Log::info("Version de négociation ajoutée", [
                'devis_id' => $devis->id,
                'motif' => $motif,
                'version' => $this->obtenirVersionActuelle($devis)
            ]);

            return $devis;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erreur ajout version négociation: " . $e->getMessage());
            throw new Exception("Impossible d'ajouter la version: " . $e->getMessage());
        }
    }

    /**
     * Obtenir l'historique complet des négociations
     */
    public function obtenirHistorique(Devis $devis): array
    {
        $historique = json_decode($devis->historique_negociation ?? '[]', true);

        // Ajouter la version actuelle si elle n'est pas dans l'historique
        $versionActuelle = $this->construireVersionActuelle($devis);
        
        // Vérifier si la version actuelle existe déjà
        $versionExiste = false;
        foreach ($historique as $version) {
            if (($version['date'] ?? '') === $versionActuelle['date']) {
                $versionExiste = true;
                break;
            }
        }

        if (!$versionExiste) {
            $historique[] = $versionActuelle;
        }

        // Trier par date décroissante
        usort($historique, function($a, $b) {
            return strtotime($b['date'] ?? '1970-01-01') - strtotime($a['date'] ?? '1970-01-01');
        });

        return [
            'devis_id' => $devis->id,
            'version_actuelle' => $this->obtenirVersionActuelle($devis),
            'nombre_negociations' => count($historique) - 1, // -1 pour exclure la version initiale
            'historique' => $historique,
            'evolution_montant' => $this->calculerEvolutionMontant($historique),
            'derniere_modification' => $devis->date_derniere_negociation ?? $devis->updated_at
        ];
    }

    /**
     * Restaurer une version précédente
     */
    public function restaurerVersion(Devis $devis, string $dateVersion): Devis
    {
        try {
            if (!$this->peutNegocier($devis)) {
                throw new Exception("Ce devis ne peut pas être modifié");
            }

            $historique = json_decode($devis->historique_negociation ?? '[]', true);
            
            // Rechercher la version à restaurer
            $versionARestaurer = null;
            foreach ($historique as $version) {
                if ($version['date'] === $dateVersion) {
                    $versionARestaurer = $version;
                    break;
                }
            }

            if (!$versionARestaurer) {
                throw new Exception("Version du {$dateVersion} introuvable dans l'historique");
            }

            DB::beginTransaction();

            // Sauvegarder la version actuelle avant restauration
            $this->sauvegarderVersionActuelle($devis);

            // Restaurer les lignes si disponibles
            if (isset($versionARestaurer['lignes'])) {
                $this->restaurerLignes($devis, $versionARestaurer['lignes']);
            }

            // Ajouter une entrée de restauration
            $this->ajouterEntreeNegociation(
                $devis, 
                "Restauration de la version du {$dateVersion}",
                ['restauration' => true, 'version_restauree' => $dateVersion]
            );

            $devis->update([
                'date_derniere_negociation' => now(),
            ]);

            DB::commit();

            Log::info("Version restaurée avec succès", [
                'devis_id' => $devis->id,
                'version_restauree' => $dateVersion
            ]);

            return $devis;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erreur restauration version: " . $e->getMessage());
            throw new Exception("Impossible de restaurer la version: " . $e->getMessage());
        }
    }

    /**
     * Comparer deux versions
     */
    public function comparerVersions(Devis $devis, string $dateVersion1, string $dateVersion2): array
    {
        $historique = $this->obtenirHistorique($devis);
        
        $version1 = null;
        $version2 = null;

        foreach ($historique['historique'] as $version) {
            if ($version['date'] === $dateVersion1) $version1 = $version;
            if ($version['date'] === $dateVersion2) $version2 = $version;
        }

        if (!$version1 || !$version2) {
            throw new Exception("Une ou plusieurs versions spécifiées sont introuvables");
        }

        return [
            'version_1' => $version1,
            'version_2' => $version2,
            'differences' => [
                'montant' => [
                    'version_1' => $version1['montant_ttc'] ?? 0,
                    'version_2' => $version2['montant_ttc'] ?? 0,
                    'difference' => ($version2['montant_ttc'] ?? 0) - ($version1['montant_ttc'] ?? 0),
                    'pourcentage' => $this->calculerPourcentageDifference(
                        $version1['montant_ttc'] ?? 0,
                        $version2['montant_ttc'] ?? 0
                    )
                ],
                'lignes_modifiees' => $this->detecterLignesModifiees(
                    $version1['lignes'] ?? [],
                    $version2['lignes'] ?? []
                )
            ]
        ];
    }

    /**
     * Vérifier si un devis peut être négocié
     */
    public function peutNegocier(Devis $devis): bool
    {
        return $devis->isProspect() && 
               in_array($devis->statut, [
                   StatutDevis::PROSPECT_ENVOYE,
                   StatutDevis::PROSPECT_NEGOCIE
               ]);
    }

    /**
     * Obtenir les négociations en cours pour un commercial
     */
    public function obtenirNegociationsEnCours(?int $commercialId = null): \Illuminate\Database\Eloquent\Collection
    {
        $commercialId = $commercialId ?? Auth::id();

        return Devis::prospects()
            ->where('statut', StatutDevis::PROSPECT_NEGOCIE)
            ->where('commercial_id', $commercialId)
            ->with(['commercial'])
            ->orderBy('date_derniere_negociation', 'desc')
            ->get();
    }

    /**
     * Sauvegarder la version actuelle dans l'historique
     */
    private function sauvegarderVersionActuelle(Devis $devis): void
    {
        $historiqueActuel = json_decode($devis->historique_negociation ?? '[]', true);
        
        $nouvelleEntree = $this->construireVersionActuelle($devis);

        // Vérifier si cette version existe déjà (éviter les doublons)
        $versionExiste = false;
        foreach ($historiqueActuel as $version) {
            if (($version['date'] ?? '') === $nouvelleEntree['date']) {
                $versionExiste = true;
                break;
            }
        }

        if (!$versionExiste) {
            $historiqueActuel[] = $nouvelleEntree;
            
            $devis->update([
                'historique_negociation' => json_encode($historiqueActuel)
            ]);
        }
    }

    /**
     * Construire la version actuelle
     */
    private function construireVersionActuelle(Devis $devis): array
    {
        return [
            'date' => ($devis->date_derniere_negociation ?? $devis->updated_at)->toISOString(),
            'montant_ht' => $devis->montant_ht,
            'montant_tva' => $devis->montant_tva,
            'montant_ttc' => $devis->montant_ttc,
            'statut' => $devis->statut->value,
            'lignes' => $devis->lignes->map(function($ligne) {
                return [
                    'designation' => $ligne->designation,
                    'quantite' => $ligne->quantite,
                    'prix_unitaire_ht' => $ligne->prix_unitaire_ht,
                    'montant_ht' => $ligne->montant_ht,
                    'taux_tva' => $ligne->taux_tva,
                ];
            })->toArray(),
            'utilisateur' => Auth::user()?->name ?? 'Système',
            'est_version_actuelle' => true
        ];
    }

    /**
     * Ajouter une entrée de négociation
     */
    private function ajouterEntreeNegociation(Devis $devis, string $motif, array $modifications): void
    {
        $historiqueNegociation = json_decode($devis->historique_negociation ?? '[]', true);
        
        $nouvellEntree = [
            'date' => now()->toISOString(),
            'motif' => $motif,
            'modifications' => $modifications,
            'montant_ht' => $devis->montant_ht,
            'montant_tva' => $devis->montant_tva,
            'montant_ttc' => $devis->montant_ttc,
            'utilisateur' => Auth::user()?->name ?? 'Système',
            'statut' => $devis->statut->value,
        ];

        $historiqueNegociation[] = $nouvellEntree;

        $devis->update([
            'historique_negociation' => json_encode($historiqueNegociation)
        ]);
    }

    /**
     * Appliquer des modifications aux lignes
     */
    private function appliquerModificationsLignes(Devis $devis, array $nouvellsLignes): void
    {
        // Supprimer les anciennes lignes
        $devis->lignes()->delete();

        // Créer les nouvelles lignes
        foreach ($nouvellsLignes as $index => $ligneData) {
            $this->calculService->creerLigneDevis($devis, $ligneData, $index + 1);
        }

        // Recalculer les totaux
        $this->calculService->calculerTotauxDevis($devis);
    }

    /**
     * Restaurer les lignes d'une version
     */
    private function restaurerLignes(Devis $devis, array $lignesARestaurer): void
    {
        // Supprimer les lignes actuelles
        $devis->lignes()->delete();

        // Recréer les lignes de la version
        foreach ($lignesARestaurer as $index => $ligneData) {
            $this->calculService->creerLigneDevis($devis, [
                'designation' => $ligneData['designation'],
                'quantite' => $ligneData['quantite'],
                'prix_unitaire_ht' => $ligneData['prix_unitaire_ht'],
                'taux_tva' => $ligneData['taux_tva'] ?? 20.00,
            ], $index + 1);
        }

        // Recalculer les totaux
        $this->calculService->calculerTotauxDevis($devis);
    }

    /**
     * Calculer l'évolution du montant
     */
    private function calculerEvolutionMontant(array $historique): array
    {
        if (count($historique) < 2) {
            return ['evolution' => 0, 'pourcentage' => 0];
        }

        $premier = end($historique); // Le plus ancien
        $dernier = reset($historique); // Le plus récent

        $montantInitial = $premier['montant_ttc'] ?? 0;
        $montantFinal = $dernier['montant_ttc'] ?? 0;
        
        $evolution = $montantFinal - $montantInitial;
        $pourcentage = $montantInitial > 0 ? 
            round(($evolution / $montantInitial) * 100, 2) : 0;

        return [
            'evolution' => $evolution,
            'pourcentage' => $pourcentage,
            'montant_initial' => $montantInitial,
            'montant_final' => $montantFinal
        ];
    }

    /**
     * Détecter les lignes modifiées entre deux versions
     */
    private function detecterLignesModifiees(array $lignes1, array $lignes2): array
    {
        $modifications = [];

        $maxLignes = max(count($lignes1), count($lignes2));
        
        for ($i = 0; $i < $maxLignes; $i++) {
            $ligne1 = $lignes1[$i] ?? null;
            $ligne2 = $lignes2[$i] ?? null;

            if (!$ligne1 && $ligne2) {
                $modifications[] = [
                    'type' => 'ajout',
                    'ligne' => $i + 1,
                    'designation' => $ligne2['designation'] ?? 'N/A'
                ];
            } elseif ($ligne1 && !$ligne2) {
                $modifications[] = [
                    'type' => 'suppression',
                    'ligne' => $i + 1,
                    'designation' => $ligne1['designation'] ?? 'N/A'
                ];
            } elseif ($ligne1 && $ligne2) {
                $changements = [];
                
                if (($ligne1['quantite'] ?? 0) !== ($ligne2['quantite'] ?? 0)) {
                    $changements[] = 'quantité';
                }
                
                if (($ligne1['prix_unitaire_ht'] ?? 0) !== ($ligne2['prix_unitaire_ht'] ?? 0)) {
                    $changements[] = 'prix unitaire';
                }
                
                if (($ligne1['designation'] ?? '') !== ($ligne2['designation'] ?? '')) {
                    $changements[] = 'désignation';
                }

                if (!empty($changements)) {
                    $modifications[] = [
                        'type' => 'modification',
                        'ligne' => $i + 1,
                        'designation' => $ligne2['designation'] ?? 'N/A',
                        'changements' => $changements
                    ];
                }
            }
        }

        return $modifications;
    }

    /**
     * Calculer le pourcentage de différence entre deux montants
     */
    private function calculerPourcentageDifference(float $montant1, float $montant2): float
    {
        if ($montant1 == 0) {
            return $montant2 > 0 ? 100 : 0;
        }

        return round((($montant2 - $montant1) / $montant1) * 100, 2);
    }

    /**
     * Obtenir le numéro de version actuelle
     */
    private function obtenirVersionActuelle(Devis $devis): int
    {
        $historique = json_decode($devis->historique_negociation ?? '[]', true);
        return count($historique) + 1;
    }

    /**
     * Obtenir les statistiques de négociation
     */
    public function obtenirStatistiquesNegociation(?int $commercialId = null): array
    {
        $query = Devis::prospects();
        
        if ($commercialId) {
            $query->where('commercial_id', $commercialId);
        }

        $prospects = $query->get();
        $enNegociation = $prospects->where('statut', StatutDevis::PROSPECT_NEGOCIE);
        $negocies = $prospects->filter(function($prospect) {
            $historique = json_decode($prospect->historique_negociation ?? '[]', true);
            return count($historique) > 0;
        });

        return [
            'en_cours_negociation' => $enNegociation->count(),
            'total_negocie' => $negocies->count(),
            'taux_negociation' => $prospects->count() > 0 
                ? round(($negocies->count() / $prospects->count()) * 100, 2)
                : 0,
            'duree_moyenne_negociation' => $this->calculerDureeMoyenneNegociation($negocies),
            'evolution_montant_moyenne' => $this->calculerEvolutionMontantMoyenne($negocies)
        ];
    }

    /**
     * Calculer la durée moyenne de négociation
     */
    private function calculerDureeMoyenneNegociation(\Illuminate\Support\Collection $prospects): ?float
    {
        $durees = $prospects->map(function($prospect) {
            if (!$prospect->date_derniere_negociation || !$prospect->date_envoi) {
                return null;
            }
            return $prospect->date_envoi->diffInDays($prospect->date_derniere_negociation);
        })->filter();

        return $durees->count() > 0 ? round($durees->avg(), 1) : null;
    }

    /**
     * Calculer l'évolution moyenne des montants
     */
    private function calculerEvolutionMontantMoyenne(\Illuminate\Support\Collection $prospects): float
    {
        $evolutions = $prospects->map(function($prospect) {
            $historique = json_decode($prospect->historique_negociation ?? '[]', true);
            if (count($historique) < 2) {
                return null;
            }
            
            $evolution = $this->calculerEvolutionMontant($historique);
            return $evolution['pourcentage'];
        })->filter();

        return $evolutions->count() > 0 ? round($evolutions->avg(), 2) : 0;
    }
}