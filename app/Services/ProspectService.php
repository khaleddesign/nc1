<?php

namespace App\Services;

use App\Models\Devis;
use App\Models\User;
use App\Enums\StatutDevis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class ProspectService
{
    private CalculService $calculService;

    public function __construct(?CalculService $calculService = null)
    {
        $this->calculService = $calculService ?? new CalculService();
    }

    /**
     * Créer un nouveau prospect (devis sans chantier)
     */
    public function creerProspect(array $data): Devis
    {
        try {
            DB::beginTransaction();

            // Validation des données obligatoires
            $this->validerDonneesProspect($data);

            // Préparer les informations client
            $clientInfo = [
                'nom' => $data['client_nom'],
                'email' => $data['client_email'],
                'telephone' => $data['client_telephone'] ?? null,
                'adresse' => $data['client_adresse'] ?? null,
            ];

            // Créer le devis prospect (sans chantier_id)
            $devis = Devis::create([
                'chantier_id' => null, // Pas de chantier pour les prospects
                'commercial_id' => $data['commercial_id'] ?? Auth::id(),
                'titre' => $data['titre'],
                'description' => $data['description'] ?? '',
                'statut' => StatutDevis::PROSPECT_BROUILLON,
                'client_info' => $clientInfo,
                'date_validite' => $data['date_validite'],
                'taux_tva' => $data['taux_tva'] ?? 20.00,
                'delai_realisation' => $data['delai_realisation'] ?? 30,
                'modalites_paiement' => $data['modalites_paiement'] ?? 'Paiement à 30 jours',
                'conditions_generales' => $data['conditions_generales'] ?? '',
                'notes_internes' => $data['notes_internes'] ?? '',
                'reference_externe' => $data['reference_externe'] ?? null,
            ]);

            // Créer les lignes si fournies
            if (isset($data['lignes']) && is_array($data['lignes'])) {
                foreach ($data['lignes'] as $index => $ligneData) {
                    $this->calculService->creerLigneDevis($devis, $ligneData, $index + 1);
                }
                $this->calculService->calculerTotauxDevis($devis);
            }

            DB::commit();

            Log::info("Prospect créé avec succès", [
                'devis_id' => $devis->id,
                'numero' => $devis->numero,
                'client_email' => $clientInfo['email']
            ]);

            return $devis;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erreur création prospect: " . $e->getMessage());
            throw new Exception("Impossible de créer le prospect: " . $e->getMessage());
        }
    }

    /**
     * Envoyer un prospect au client
     */
    public function envoyerProspect(Devis $prospect, array $options = []): bool
    {
        try {
            // Vérifier que c'est bien un prospect
            if (!$prospect->isProspect()) {
                throw new Exception("Ce devis n'est pas un prospect");
            }

            // Vérifier le statut
            if (!$prospect->statut->peutEtreEnvoye()) {
                throw new Exception("Ce prospect ne peut pas être envoyé dans son état actuel");
            }

            // Vérifier l'email client
            $clientEmail = $prospect->client_info['email'] ?? null;
            if (empty($clientEmail)) {
                throw new Exception("Email client requis pour l'envoi");
            }

            DB::beginTransaction();

            // Changer le statut
            $prospect->changerStatut(
                StatutDevis::PROSPECT_ENVOYE, 
                $options['motif'] ?? 'Prospect envoyé au client'
            );

            $prospect->update([
                'date_envoi' => now(),
                'date_validite' => $options['date_validite'] ?? $prospect->date_validite ?? now()->addDays(30)
            ]);

            // TODO: Intégration avec service email si besoin
            if ($options['envoyer_email'] ?? true) {
                // $this->envoyerEmailProspect($prospect, $options);
            }

            DB::commit();

            Log::info("Prospect envoyé avec succès", [
                'devis_id' => $prospect->id,
                'client_email' => $clientEmail
            ]);

            return true;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erreur envoi prospect: " . $e->getMessage());
            throw new Exception("Impossible d'envoyer le prospect: " . $e->getMessage());
        }
    }

    /**
     * Accepter un prospect
     */
    public function accepterProspect(Devis $prospect, array $data = []): Devis
    {
        try {
            // Vérifications
            if (!$prospect->isProspect()) {
                throw new Exception("Ce devis n'est pas un prospect");
            }

            if (!$prospect->statut->peutEtreAccepte()) {
                throw new Exception("Ce prospect ne peut pas être accepté dans son état actuel");
            }

            DB::beginTransaction();

            // Changer le statut
            $prospect->changerStatut(
                StatutDevis::PROSPECT_ACCEPTE,
                'Prospect accepté par le client'
            );

            // Mettre à jour les données d'acceptation
            $prospect->update([
                'date_reponse' => now(),
                'signature_client' => $data['signature'] ?? null,
                'signed_at' => isset($data['signature']) ? now() : null,
                'signature_ip' => $data['ip'] ?? request()->ip(),
            ]);

            DB::commit();

            Log::info("Prospect accepté avec succès", [
                'devis_id' => $prospect->id,
                'date_acceptation' => now()
            ]);

            return $prospect;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erreur acceptation prospect: " . $e->getMessage());
            throw new Exception("Impossible d'accepter le prospect: " . $e->getMessage());
        }
    }

    /**
     * Obtenir tous les prospects d'un commercial
     */
    public function obtenirProspectsCommercial(?int $commercialId = null): \Illuminate\Database\Eloquent\Collection
    {
        $commercialId = $commercialId ?? Auth::id();
        
        return Devis::prospects()
            ->where('commercial_id', $commercialId)
            ->with(['commercial', 'lignes'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtenir les prospects nécessitant attention
     */
    public function getProspectsAttention(?int $commercialId = null): array
    {
        $commercialId = $commercialId ?? Auth::id();
        
        $baseQuery = Devis::prospects()->where('commercial_id', $commercialId);

        return [
            'expires_bientot' => (clone $baseQuery)
                ->where('statut', StatutDevis::PROSPECT_ENVOYE)
                ->where('date_validite', '<=', now()->addDays(7))
                ->where('date_validite', '>', now())
                ->get(),
                
            'sans_reponse' => (clone $baseQuery)
                ->where('statut', StatutDevis::PROSPECT_ENVOYE)
                ->where('date_envoi', '<=', now()->subDays(14))
                ->whereNull('date_reponse')
                ->get(),
                
            'en_negociation' => (clone $baseQuery)
                ->where('statut', StatutDevis::PROSPECT_NEGOCIE)
                ->get(),
                
            'convertibles' => (clone $baseQuery)
                ->where('statut', StatutDevis::PROSPECT_ACCEPTE)
                ->get(),
        ];
    }

    /**
     * Dupliquer un prospect
     */
    public function duppliquerProspect(Devis $prospectOriginal, array $modifications = []): Devis
    {
        try {
            if (!$prospectOriginal->isProspect()) {
                throw new Exception("Seuls les prospects peuvent être dupliqués via ce service");
            }

            DB::beginTransaction();

            // Créer le nouveau prospect
            $nouveauProspect = $prospectOriginal->replicate([
                'id', 'numero', 'created_at', 'updated_at',
                'date_envoi', 'date_reponse', 'signature_client', 'signed_at', 'signature_ip'
            ]);

            // Appliquer les modifications
            $nouveauProspect->fill([
                'titre' => $modifications['titre'] ?? $prospectOriginal->titre . ' (Copie)',
                'description' => $modifications['description'] ?? $prospectOriginal->description,
                'statut' => StatutDevis::PROSPECT_BROUILLON,
                'date_validite' => $modifications['date_validite'] ?? now()->addDays(30),
                'notes_internes' => $modifications['notes_internes'] ?? $prospectOriginal->notes_internes,
            ]);

            $nouveauProspect->save();

            // Dupliquer les lignes
            $this->calculService->duppliquerLignes($prospectOriginal, $nouveauProspect);

            DB::commit();

            Log::info("Prospect dupliqué avec succès", [
                'original_id' => $prospectOriginal->id,
                'nouveau_id' => $nouveauProspect->id,
                'nouveau_numero' => $nouveauProspect->numero
            ]);

            return $nouveauProspect;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erreur duplication prospect: " . $e->getMessage());
            throw new Exception("Impossible de dupliquer le prospect: " . $e->getMessage());
        }
    }

    /**
     * Obtenir les statistiques des prospects
     */
    public function getStatistiquesProspects(?int $commercialId = null): array
    {
        $commercialId = $commercialId ?? Auth::id();
        
        $query = Devis::prospects();
        
        if ($commercialId) {
            $query->where('commercial_id', $commercialId);
        }

        $prospects = $query->get();

        return [
            'total' => $prospects->count(),
            'brouillon' => $prospects->where('statut', StatutDevis::PROSPECT_BROUILLON)->count(),
            'envoyes' => $prospects->where('statut', StatutDevis::PROSPECT_ENVOYE)->count(),
            'negocies' => $prospects->where('statut', StatutDevis::PROSPECT_NEGOCIE)->count(),
            'acceptes' => $prospects->where('statut', StatutDevis::PROSPECT_ACCEPTE)->count(),
            'convertibles' => $prospects->where('statut', StatutDevis::PROSPECT_ACCEPTE)->count(),
            'montant_total' => $prospects->sum('montant_ttc'),
            'montant_moyen' => $prospects->count() > 0 ? $prospects->avg('montant_ttc') : 0,
            'taux_conversion' => $this->calculerTauxConversion($prospects),
        ];
    }

    /**
     * Vérifier si un prospect peut être converti
     */
    public function peutEtreConverti(Devis $prospect): bool
    {
        return $prospect->isProspect() && 
               $prospect->statut === StatutDevis::PROSPECT_ACCEPTE &&
               !empty($prospect->client_info['email']);
    }

    /**
     * Refuser un prospect
     */
    public function refuserProspect(Devis $prospect, array $data = []): Devis
    {
        try {
            // Vérifications
            if (!$prospect->isProspect()) {
                throw new Exception("Ce devis n'est pas un prospect");
            }

            if (!$prospect->statut->peutEtreAccepte()) {
                throw new Exception("Ce prospect ne peut pas être refusé dans son état actuel");
            }

            DB::beginTransaction();

            // Ajouter un statut refusé dans l'historique (pas dans l'enum principal)
            $prospect->changerStatut(
                StatutDevis::PROSPECT_ENVOYE, // Retour au statut envoyé
                'Prospect refusé par le client: ' . ($data['raison'] ?? 'Aucune raison')
            );

            $prospect->update([
                'date_reponse' => now(),
                'notes_internes' => ($prospect->notes_internes ?? '') . "\n[REFUSÉ] " . ($data['raison'] ?? 'Aucune raison donnée'),
            ]);

            DB::commit();

            Log::info("Prospect refusé", [
                'devis_id' => $prospect->id,
                'raison' => $data['raison'] ?? 'Non spécifiée'
            ]);

            return $prospect;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erreur refus prospect: " . $e->getMessage());
            throw new Exception("Impossible de refuser le prospect: " . $e->getMessage());
        }
    }

    /**
     * Relancer un prospect sans réponse
     */
    public function relancerProspect(Devis $prospect, array $options = []): bool
    {
        try {
            if (!$prospect->isProspect()) {
                throw new Exception("Ce devis n'est pas un prospect");
            }

            if ($prospect->statut !== StatutDevis::PROSPECT_ENVOYE) {
                throw new Exception("Seuls les prospects envoyés peuvent être relancés");
            }

            // Ajouter une note de relance
            $motif = $options['motif'] ?? 'Relance automatique - Pas de réponse';
            
            $prospect->changerStatut(
                StatutDevis::PROSPECT_ENVOYE, // Reste dans le même statut
                $motif
            );

            // TODO: Envoyer email de relance
            if ($options['envoyer_email'] ?? true) {
                // $this->envoyerEmailRelance($prospect, $options);
            }

            Log::info("Prospect relancé", [
                'devis_id' => $prospect->id,
                'motif' => $motif
            ]);

            return true;

        } catch (Exception $e) {
            Log::error("Erreur relance prospect: " . $e->getMessage());
            throw new Exception("Impossible de relancer le prospect: " . $e->getMessage());
        }
    }

    /**
     * Obtenir les prospects à relancer
     */
    public function getProspectsARelancer(?int $commercialId = null, int $joursAttente = 7): \Illuminate\Database\Eloquent\Collection
    {
        $query = Devis::prospects()
            ->where('statut', StatutDevis::PROSPECT_ENVOYE)
            ->where('date_envoi', '<=', now()->subDays($joursAttente))
            ->whereNull('date_reponse');

        if ($commercialId) {
            $query->where('commercial_id', $commercialId);
        }

        return $query->orderBy('date_envoi', 'asc')->get();
    }

    /**
     * Validation des données de création de prospect
     */
    private function validerDonneesProspect(array $data): void
    {
        if (empty($data['titre'])) {
            throw new Exception("Le titre du prospect est obligatoire");
        }

        if (empty($data['client_nom'])) {
            throw new Exception("Le nom du client est obligatoire");
        }

        if (empty($data['client_email'])) {
            throw new Exception("L'email du client est obligatoire");
        }

        if (!filter_var($data['client_email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Format d'email invalide");
        }

        // Vérifier unicité email pour les nouveaux prospects
        $emailExiste = Devis::prospects()
            ->whereJsonContains('client_info->email', $data['client_email'])
            ->exists();

        if ($emailExiste) {
            throw new Exception("Un prospect existe déjà avec cet email");
        }

        if (isset($data['commercial_id'])) {
            if (!User::find($data['commercial_id'])) {
                throw new Exception("Commercial introuvable");
            }
        }
    }

    /**
     * Calculer le taux de conversion des prospects
     */
    private function calculerTauxConversion($prospects): float
    {
        $total = $prospects->count();
        if ($total === 0) {
            return 0;
        }

        $acceptes = $prospects->where('statut', StatutDevis::PROSPECT_ACCEPTE)->count();
        return round(($acceptes / $total) * 100, 2);
    }

    /**
     * Rechercher des prospects par critères
     */
    public function rechercherProspects(array $criteres, ?int $commercialId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Devis::prospects();
        
        if ($commercialId) {
            $query->where('commercial_id', $commercialId);
        }

        // Filtre par statut
        if (!empty($criteres['statut'])) {
            $query->where('statut', $criteres['statut']);
        }

        // Recherche textuelle
        if (!empty($criteres['recherche'])) {
            $recherche = $criteres['recherche'];
            $query->where(function($q) use ($recherche) {
                $q->where('titre', 'like', "%{$recherche}%")
                  ->orWhere('numero', 'like', "%{$recherche}%")
                  ->orWhereJsonContains('client_info->nom', $recherche)
                  ->orWhereJsonContains('client_info->email', $recherche);
            });
        }

        // Filtre par date
        if (!empty($criteres['date_debut']) && !empty($criteres['date_fin'])) {
            $query->whereBetween('created_at', [$criteres['date_debut'], $criteres['date_fin']]);
        }

        // Filtre par montant
        if (!empty($criteres['montant_min'])) {
            $query->where('montant_ttc', '>=', $criteres['montant_min']);
        }

        if (!empty($criteres['montant_max'])) {
            $query->where('montant_ttc', '<=', $criteres['montant_max']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Obtenir les prospects expirés
     */
    public function getProspectsExpires(?int $commercialId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Devis::prospects()
            ->whereIn('statut', [StatutDevis::PROSPECT_ENVOYE, StatutDevis::PROSPECT_NEGOCIE])
            ->where('date_validite', '<', now());

        if ($commercialId) {
            $query->where('commercial_id', $commercialId);
        }

        return $query->orderBy('date_validite', 'asc')->get();
    }

    /**
     * Prolonger la validité d'un prospect
     */
    public function prolongerValidite(Devis $prospect, int $jours = 30): Devis
    {
        try {
            if (!$prospect->isProspect()) {
                throw new Exception("Ce devis n'est pas un prospect");
            }

            if (!in_array($prospect->statut, [StatutDevis::PROSPECT_ENVOYE, StatutDevis::PROSPECT_NEGOCIE])) {
                throw new Exception("Seuls les prospects envoyés ou négociés peuvent être prolongés");
            }

            $nouvelleDateValidite = now()->addDays($jours);
            
            $prospect->update([
                'date_validite' => $nouvelleDateValidite
            ]);

            $prospect->changerStatut(
                $prospect->statut, // Garde le même statut
                "Validité prolongée de {$jours} jours (jusqu'au " . $nouvelleDateValidite->format('d/m/Y') . ")"
            );

            Log::info("Validité prospect prolongée", [
                'devis_id' => $prospect->id,
                'nouvelle_date' => $nouvelleDateValidite->format('Y-m-d'),
                'jours_ajoutes' => $jours
            ]);

            return $prospect;

        } catch (Exception $e) {
            Log::error("Erreur prolongation validité: " . $e->getMessage());
            throw new Exception("Impossible de prolonger la validité: " . $e->getMessage());
        }
    }

    /**
     * Obtenir le résumé d'activité d'un commercial
     */
    public function getResumeActivite(?int $commercialId = null, int $nbJours = 30): array
    {
        $commercialId = $commercialId ?? Auth::id();
        $dateDebut = now()->subDays($nbJours);

        $prospects = Devis::prospects()
            ->where('commercial_id', $commercialId)
            ->where('created_at', '>=', $dateDebut)
            ->get();

        return [
            'periode' => $nbJours . ' derniers jours',
            'prospects_crees' => $prospects->count(),
            'prospects_envoyes' => $prospects->where('date_envoi', '>=', $dateDebut)->count(),
            'prospects_acceptes' => $prospects->where('date_reponse', '>=', $dateDebut)
                ->where('statut', StatutDevis::PROSPECT_ACCEPTE)->count(),
            'ca_potentiel' => $prospects->sum('montant_ttc'),
            'ca_accepte' => $prospects->where('statut', StatutDevis::PROSPECT_ACCEPTE)->sum('montant_ttc'),
            'taux_acceptation' => $prospects->count() > 0 
                ? round(($prospects->where('statut', StatutDevis::PROSPECT_ACCEPTE)->count() / $prospects->count()) * 100, 2)
                : 0,
            'delai_moyen_reponse' => $this->calculerDelaiMoyenReponse($prospects),
        ];
    }

    /**
     * Calculer le délai moyen de réponse
     */
    private function calculerDelaiMoyenReponse(\Illuminate\Support\Collection $prospects): ?float
    {
        $prospectsAvecReponse = $prospects->filter(function($prospect) {
            return $prospect->date_envoi && $prospect->date_reponse;
        });

        if ($prospectsAvecReponse->count() === 0) {
            return null;
        }

        $delais = $prospectsAvecReponse->map(function($prospect) {
            return $prospect->date_envoi->diffInDays($prospect->date_reponse);
        });

        return round($delais->avg(), 1);
    }
}