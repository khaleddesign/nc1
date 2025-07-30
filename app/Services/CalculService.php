<?php

namespace App\Services;

use App\Models\Devis;
use App\Models\Ligne;
use App\Models\Facture;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CalculService
{
    /**
     * Verrous pour éviter les calculs en boucle
     */
    private static array $calculEnCours = [];
    private static array $lignesEnCours = [];

    /**
     * Calculer les totaux d'un devis de manière sécurisée
     */
    public function calculerTotauxDevis(Devis $devis): void
    {
        $key = 'devis_' . $devis->id;
        
        // Vérifier si un calcul est déjà en cours pour ce devis
        if (isset(self::$calculEnCours[$key])) {
            Log::debug("Calcul déjà en cours pour le devis {$devis->id}, ignoré");
            return;
        }

        self::$calculEnCours[$key] = true;

        try {
            // Recalculer d'abord toutes les lignes si nécessaire
            $this->recalculerLignesDevis($devis);

            // Puis calculer les totaux
            $montantHT = $devis->lignes->sum('montant_ht');
            $montantTVA = $devis->lignes->sum('montant_tva');
            $montantTTC = $montantHT + $montantTVA;

            // Mise à jour directe en base pour éviter les événements
            DB::table('devis')
                ->where('id', $devis->id)
                ->update([
                    'montant_ht' => $montantHT,
                    'montant_tva' => $montantTVA,
                    'montant_ttc' => $montantTTC,
                    'updated_at' => now(),
                ]);

            // Synchroniser l'instance sans déclencher d'événements
            $devis->setRawAttributes(array_merge($devis->getRawOriginal(), [
                'montant_ht' => $montantHT,
                'montant_tva' => $montantTVA,
                'montant_ttc' => $montantTTC,
                'updated_at' => now(),
            ]));

            Log::debug("Totaux calculés pour devis {$devis->id}: HT={$montantHT}, TVA={$montantTVA}, TTC={$montantTTC}");

        } catch (\Exception $e) {
            Log::error("Erreur lors du calcul des totaux devis {$devis->id}: " . $e->getMessage());
            throw $e;
        } finally {
            unset(self::$calculEnCours[$key]);
        }
    }

    /**
     * Calculer les totaux d'une facture de manière sécurisée
     */
    public function calculerTotauxFacture(Facture $facture): void
    {
        $key = 'facture_' . $facture->id;
        
        if (isset(self::$calculEnCours[$key])) {
            Log::debug("Calcul déjà en cours pour la facture {$facture->id}, ignoré");
            return;
        }

        self::$calculEnCours[$key] = true;

        try {
            // Recalculer les lignes d'abord
            $this->recalculerLignesFacture($facture);

            $montantHT = $facture->lignes->sum('montant_ht');
            $montantTVA = $facture->lignes->sum('montant_tva');
            $montantTTC = $montantHT + $montantTVA;
            $montantPaye = $facture->montant_paye ?? 0;
            $montantRestant = $montantTTC - $montantPaye;

            // Mise à jour directe
            DB::table('factures')
                ->where('id', $facture->id)
                ->update([
                    'montant_ht' => $montantHT,
                    'montant_tva' => $montantTVA,
                    'montant_ttc' => $montantTTC,
                    'montant_restant' => $montantRestant,
                    'updated_at' => now(),
                ]);

            // Synchroniser l'instance
            $facture->setRawAttributes(array_merge($facture->getRawOriginal(), [
                'montant_ht' => $montantHT,
                'montant_tva' => $montantTVA,
                'montant_ttc' => $montantTTC,
                'montant_restant' => $montantRestant,
                'updated_at' => now(),
            ]));

            Log::debug("Totaux calculés pour facture {$facture->id}: HT={$montantHT}, TVA={$montantTVA}, TTC={$montantTTC}");

        } catch (\Exception $e) {
            Log::error("Erreur lors du calcul des totaux facture {$facture->id}: " . $e->getMessage());
            throw $e;
        } finally {
            unset(self::$calculEnCours[$key]);
        }
    }

    /**
     * Créer une ligne pour un devis avec calculs corrects
     */
    public function creerLigneDevis(Devis $devis, array $donneesLigne, int $ordre): Ligne
    {
        // Valeurs par défaut et validation
        $quantite = max(0.01, floatval($donneesLigne['quantite'] ?? 1));
        $prixUnitaire = max(0, floatval($donneesLigne['prix_unitaire_ht'] ?? 0));
        $tauxTva = floatval($donneesLigne['taux_tva'] ?? $devis->taux_tva ?? 20.00);
        $remisePourcentage = max(0, min(100, floatval($donneesLigne['remise_pourcentage'] ?? 0)));

        // Calculs
        $montantBrut = $quantite * $prixUnitaire;
        $remiseMontant = $montantBrut * ($remisePourcentage / 100);
        $montantHt = $montantBrut - $remiseMontant;
        $montantTva = $montantHt * ($tauxTva / 100);
        $montantTtc = $montantHt + $montantTva;

        // Créer la ligne avec tous les montants pré-calculés
        $ligne = $devis->lignes()->create([
            'ordre' => $ordre,
            'designation' => $donneesLigne['designation'],
            'description' => $donneesLigne['description'] ?? null,
            'unite' => $donneesLigne['unite'] ?? 'unité',
            'quantite' => $quantite,
            'prix_unitaire_ht' => $prixUnitaire,
            'taux_tva' => $tauxTva,
            'remise_pourcentage' => $remisePourcentage,
            'remise_montant' => $remiseMontant,
            'categorie' => $donneesLigne['categorie'] ?? null,
            'montant_ht' => $montantHt,
            'montant_tva' => $montantTva,
            'montant_ttc' => $montantTtc,
        ]);

        Log::debug("Ligne créée pour devis {$devis->id}: {$donneesLigne['designation']} - {$montantTtc}€");

        return $ligne;
    }

    /**
     * Créer une ligne pour une facture avec calculs corrects
     */
    public function creerLigneFacture(Facture $facture, array $donneesLigne, int $ordre): Ligne
    {
        // Même logique que pour les devis
        $quantite = max(0.01, floatval($donneesLigne['quantite'] ?? 1));
        $prixUnitaire = max(0, floatval($donneesLigne['prix_unitaire_ht'] ?? 0));
        $tauxTva = floatval($donneesLigne['taux_tva'] ?? $facture->taux_tva ?? 20.00);
        $remisePourcentage = max(0, min(100, floatval($donneesLigne['remise_pourcentage'] ?? 0)));

        $montantBrut = $quantite * $prixUnitaire;
        $remiseMontant = $montantBrut * ($remisePourcentage / 100);
        $montantHt = $montantBrut - $remiseMontant;
        $montantTva = $montantHt * ($tauxTva / 100);
        $montantTtc = $montantHt + $montantTva;

        $ligne = $facture->lignes()->create([
            'ordre' => $ordre,
            'designation' => $donneesLigne['designation'],
            'description' => $donneesLigne['description'] ?? null,
            'unite' => $donneesLigne['unite'] ?? 'unité',
            'quantite' => $quantite,
            'prix_unitaire_ht' => $prixUnitaire,
            'taux_tva' => $tauxTva,
            'remise_pourcentage' => $remisePourcentage,
            'remise_montant' => $remiseMontant,
            'categorie' => $donneesLigne['categorie'] ?? null,
            'montant_ht' => $montantHt,
            'montant_tva' => $montantTva,
            'montant_ttc' => $montantTtc,
        ]);

        Log::debug("Ligne créée pour facture {$facture->id}: {$donneesLigne['designation']} - {$montantTtc}€");

        return $ligne;
    }

    /**
     * Mettre à jour une ligne existante
     */
    public function mettreAJourLigne(Ligne $ligne, array $nouvellesdonnees): void
    {
        $key = 'ligne_' . $ligne->id;
        
        if (isset(self::$lignesEnCours[$key])) {
            Log::debug("Mise à jour déjà en cours pour la ligne {$ligne->id}, ignoré");
            return;
        }

        self::$lignesEnCours[$key] = true;

        try {
            // Récupérer les nouvelles valeurs ou garder les anciennes
            $quantite = max(0.01, floatval($nouvellesdonnees['quantite'] ?? $ligne->quantite));
            $prixUnitaire = max(0, floatval($nouvellesdonnees['prix_unitaire_ht'] ?? $ligne->prix_unitaire_ht));
            $tauxTva = floatval($nouvellesdonnees['taux_tva'] ?? $ligne->taux_tva);
            $remisePourcentage = max(0, min(100, floatval($nouvellesdonnees['remise_pourcentage'] ?? $ligne->remise_pourcentage)));

            // Recalculer les montants
            $montantBrut = $quantite * $prixUnitaire;
            $remiseMontant = $montantBrut * ($remisePourcentage / 100);
            $montantHt = $montantBrut - $remiseMontant;
            $montantTva = $montantHt * ($tauxTva / 100);
            $montantTtc = $montantHt + $montantTva;

            // Mise à jour directe en base
            DB::table('lignes')
                ->where('id', $ligne->id)
                ->update([
                    'designation' => $nouvellesdonnees['designation'] ?? $ligne->designation,
                    'description' => $nouvellesdonnees['description'] ?? $ligne->description,
                    'unite' => $nouvellesdonnees['unite'] ?? $ligne->unite,
                    'quantite' => $quantite,
                    'prix_unitaire_ht' => $prixUnitaire,
                    'taux_tva' => $tauxTva,
                    'remise_pourcentage' => $remisePourcentage,
                    'remise_montant' => $remiseMontant,
                    'categorie' => $nouvellesdonnees['categorie'] ?? $ligne->categorie,
                    'montant_ht' => $montantHt,
                    'montant_tva' => $montantTva,
                    'montant_ttc' => $montantTtc,
                    'updated_at' => now(),
                ]);

            // Synchroniser l'instance
            $ligne->setRawAttributes(array_merge($ligne->getRawOriginal(), [
                'quantite' => $quantite,
                'prix_unitaire_ht' => $prixUnitaire,
                'taux_tva' => $tauxTva,
                'remise_pourcentage' => $remisePourcentage,
                'remise_montant' => $remiseMontant,
                'montant_ht' => $montantHt,
                'montant_tva' => $montantTva,
                'montant_ttc' => $montantTtc,
                'updated_at' => now(),
            ]));

            Log::debug("Ligne {$ligne->id} mise à jour: {$montantTtc}€");

        } catch (\Exception $e) {
            Log::error("Erreur lors de la mise à jour de la ligne {$ligne->id}: " . $e->getMessage());
            throw $e;
        } finally {
            unset(self::$lignesEnCours[$key]);
        }
    }

    /**
     * Recalculer toutes les lignes d'un devis
     */
    private function recalculerLignesDevis(Devis $devis): void
    {
        foreach ($devis->lignes as $ligne) {
            $this->mettreAJourLigne($ligne, []);
        }
    }

    /**
     * Recalculer toutes les lignes d'une facture
     */
    private function recalculerLignesFacture(Facture $facture): void
    {
        foreach ($facture->lignes as $ligne) {
            $this->mettreAJourLigne($ligne, []);
        }
    }

    /**
     * Supprimer une ligne et recalculer les totaux
     */
    public function supprimerLigne(Ligne $ligne): void
    {
        $ligneable = $ligne->ligneable;
        $ligneableType = get_class($ligneable);
        $ligneableId = $ligneable->id;

        // Supprimer la ligne
        $ligne->delete();

        // Recalculer les totaux du parent
        if ($ligneable instanceof Devis) {
            $this->calculerTotauxDevis($ligneable);
        } elseif ($ligneable instanceof Facture) {
            $this->calculerTotauxFacture($ligneable);
        }

        Log::debug("Ligne supprimée et totaux recalculés pour {$ligneableType} {$ligneableId}");
    }

    /**
     * Dupliquer les lignes d'un devis vers un autre
     */
    public function duppliquerLignes(Devis $devisSource, Devis $devisDestination): void
    {
        foreach ($devisSource->lignes as $index => $ligne) {
            $this->creerLigneDevis($devisDestination, [
                'designation' => $ligne->designation,
                'description' => $ligne->description,
                'unite' => $ligne->unite,
                'quantite' => $ligne->quantite,
                'prix_unitaire_ht' => $ligne->prix_unitaire_ht,
                'taux_tva' => $ligne->taux_tva,
                'remise_pourcentage' => $ligne->remise_pourcentage,
                'categorie' => $ligne->categorie,
            ], $index + 1);
        }

        $this->calculerTotauxDevis($devisDestination);
    }

    /**
     * Obtenir les statistiques de calcul (debug)
     */
    public function getStatistiquesCalcul(): array
    {
        return [
            'calculs_en_cours' => count(self::$calculEnCours),
            'lignes_en_cours' => count(self::$lignesEnCours),
            'calculs_actifs' => array_keys(self::$calculEnCours),
            'lignes_actives' => array_keys(self::$lignesEnCours),
        ];
    }

    /**
     * Nettoyer les verrous (en cas de problème)
     */
    public function nettoyerVerrous(): void
    {
        self::$calculEnCours = [];
        self::$lignesEnCours = [];
        Log::info("Verrous de calcul nettoyés");
    }
}