<?php

namespace App\Services;

use App\Models\Devis;
use App\Models\Chantier;
use App\Models\User;
use App\Enums\StatutDevis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ConversionService
{
    private CalculService $calculService;

    public function __construct(?CalculService $calculService = null)
    {
        $this->calculService = $calculService ?? new CalculService();
    }

    /**
     * Convertir un prospect accepté en chantier
     */
    public function convertirProspectEnChantier(Devis $prospect, array $data): array
    {
        try {
            // Vérifications préalables
            if (!$prospect->isProspect()) {
                throw new Exception("Ce devis n'est pas un prospect");
            }

            if ($prospect->statut !== StatutDevis::PROSPECT_ACCEPTE) {
                throw new Exception("Seuls les prospects acceptés peuvent être convertis en chantier");
            }

            if ($prospect->chantier_converti_id) {
                throw new Exception("Ce prospect a déjà été converti en chantier");
            }

            DB::beginTransaction();

            // 1. Créer ou récupérer le client
            $client = $this->creerClientSiNecessaire($prospect->client_info, $data['client'] ?? []);

            // 2. Créer le chantier
            $chantier = $this->creerChantier($prospect, $client, $data['chantier'] ?? []);

            // 3. Dupliquer le devis pour le chantier
            $devisChantier = $this->dupliquerDevisPourChantier($prospect, $chantier);

            // 4. Mettre à jour le prospect original
            $this->marquerProspectConverti($prospect, $chantier);

            DB::commit();

            Log::info("Conversion prospect vers chantier réussie", [
                'prospect_id' => $prospect->id,
                'chantier_id' => $chantier->id,
                'client_id' => $client->id,
                'devis_chantier_id' => $devisChantier->id
            ]);

            return [
                'chantier' => $chantier,
                'client' => $client,
                'devis_chantier' => $devisChantier,
                'prospect_original' => $prospect->fresh()
            ];

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erreur conversion prospect: " . $e->getMessage());
            throw new Exception("Impossible de convertir le prospect: " . $e->getMessage());
        }
    }

    /**
     * Créer ou récupérer un client existant
     */
    public function creerClientSiNecessaire(array $clientInfo, array $donneesSupplementaires = []): User
    {
        $email = $clientInfo['email'];
        
        // Rechercher un client existant
        $client = User::where('email', $email)
            ->where('role', 'client')
            ->first();

        if ($client) {
            // Mettre à jour les informations si nécessaire
            $donneesMAJ = array_filter([
                'name' => $donneesSupplementaires['nom'] ?? $clientInfo['nom'] ?? $client->name,
                'telephone' => $donneesSupplementaires['telephone'] ?? $clientInfo['telephone'] ?? $client->telephone,
                'adresse' => $donneesSupplementaires['adresse'] ?? $clientInfo['adresse'] ?? $client->adresse,
                'company_name' => $donneesSupplementaires['entreprise'] ?? $client->company_name,
                'city' => $donneesSupplementaires['ville'] ?? $client->city,
                'postal_code' => $donneesSupplementaires['code_postal'] ?? $client->postal_code,
            ]);

            if (!empty($donneesMAJ)) {
                $client->update($donneesMAJ);
            }

            Log::info("Client existant récupéré et mis à jour", ['client_id' => $client->id]);
            return $client;
        }

        // Créer un nouveau client
        $clientData = [
            'name' => $donneesSupplementaires['nom'] ?? $clientInfo['nom'] ?? 'Client ' . $email,
            'email' => $email,
            'telephone' => $donneesSupplementaires['telephone'] ?? $clientInfo['telephone'],
            'adresse' => $donneesSupplementaires['adresse'] ?? $clientInfo['adresse'],
            'company_name' => $donneesSupplementaires['entreprise'] ?? null,
            'city' => $donneesSupplementaires['ville'] ?? null,
            'postal_code' => $donneesSupplementaires['code_postal'] ?? null,
            'role' => 'client',
            'password' => bcrypt(str()->random(16)), // Mot de passe temporaire
            'email_verified_at' => now(),
            'active' => true,
        ];

        $client = User::create($clientData);

        Log::info("Nouveau client créé", ['client_id' => $client->id, 'email' => $email]);
        return $client;
    }

    /**
     * Créer le chantier
     */
    private function creerChantier(Devis $prospect, User $client, array $donneesChantier): Chantier
    {
        $chantierData = [
            'titre' => $donneesChantier['titre'] ?? "Chantier - " . $prospect->titre,
            'description' => $donneesChantier['description'] ?? $prospect->description,
            'client_id' => $client->id,
            'commercial_id' => $prospect->commercial_id,
            'statut' => 'planifie',
            'date_debut' => $donneesChantier['date_debut'] ?? now()->addDays(7),
            'date_fin_prevue' => $donneesChantier['date_fin_prevue'] ?? now()->addDays(30),
            'budget' => $prospect->montant_ttc,
            'notes' => $donneesChantier['notes'] ?? "Chantier créé à partir du prospect {$prospect->numero}",
            'avancement_global' => 0.00,
            'active' => true,
            'hidden_for_commercial' => false,
        ];

        $chantier = Chantier::create($chantierData);

        Log::info("Chantier créé", [
            'chantier_id' => $chantier->id,
            'titre' => $chantier->titre,
            'client_id' => $client->id
        ]);

        return $chantier;
    }

    /**
     * Dupliquer le devis prospect pour le chantier
     */
    public function dupliquerDevisPourChantier(Devis $prospect, Chantier $chantier): Devis
    {
        // Créer le nouveau devis
        $devisData = [
            'chantier_id' => $chantier->id,
            'commercial_id' => $prospect->commercial_id,
            'titre' => "Devis Chantier - " . $chantier->titre,
            'description' => $prospect->description,
            'statut' => StatutDevis::CHANTIER_VALIDE,
            'client_info' => $prospect->client_info,
            'date_emission' => now()->toDateString(),
            'date_validite' => now()->addDays(90), // Plus long pour les chantiers
            'taux_tva' => $prospect->taux_tva,
            'delai_realisation' => $prospect->delai_realisation,
            'modalites_paiement' => $prospect->modalites_paiement,
            'conditions_generales' => $prospect->conditions_generales,
            'notes_internes' => "Devis créé à partir du prospect {$prospect->numero}",
            'reference_externe' => $prospect->reference_externe,
        ];

        $devisChantier = Devis::create($devisData);

        // Dupliquer les lignes
        $this->calculService->duppliquerLignes($prospect, $devisChantier);

        Log::info("Devis chantier créé", [
            'devis_id' => $devisChantier->id,
            'numero' => $devisChantier->numero,
            'chantier_id' => $chantier->id
        ]);

        return $devisChantier;
    }

    /**
     * Marquer le prospect comme converti
     */
    private function marquerProspectConverti(Devis $prospect, Chantier $chantier): void
    {
        $prospect->update([
            'chantier_converti_id' => $chantier->id,
            'date_conversion_chantier' => now(),
        ]);

        // Ajouter une entrée dans l'historique
        $prospect->changerStatut(
            StatutDevis::CHANTIER_VALIDE,
            "Prospect converti en chantier #{$chantier->id}"
        );
    }

    /**
     * Préparer la conversion (validation préalable)
     */
    public function preparerConversion(Devis $prospect): array
    {
        if (!$prospect->isProspect()) {
            throw new Exception("Ce devis n'est pas un prospect");
        }

        if ($prospect->statut !== StatutDevis::PROSPECT_ACCEPTE) {
            throw new Exception("Le prospect doit être accepté pour être converti");
        }

        // Vérifier les données nécessaires
        $donneesManquantes = [];
        $clientInfo = $prospect->client_info;

        if (empty($clientInfo['email'])) {
            $donneesManquantes[] = "Email client";
        }

        if (empty($clientInfo['nom'])) {
            $donneesManquantes[] = "Nom client";
        }

        if (empty($prospect->montant_ttc) || $prospect->montant_ttc <= 0) {
            $donneesManquantes[] = "Montant du prospect";
        }

        // Rechercher si un client existe déjà
        $clientExistant = null;
        if (!empty($clientInfo['email'])) {
            $clientExistant = User::where('email', $clientInfo['email'])
                ->where('role', 'client')
                ->first();
        }

        return [
            'peut_convertir' => empty($donneesManquantes),
            'donnees_manquantes' => $donneesManquantes,
            'client_existant' => $clientExistant ? [
                'id' => $clientExistant->id,
                'nom' => $clientExistant->name,
                'entreprise' => $clientExistant->company_name,
                'telephone' => $clientExistant->telephone,
                'adresse' => $clientExistant->adresse
            ] : null,
            'donnees_recommandees' => [
                'chantier' => [
                    'titre' => "Chantier - " . $prospect->titre,
                    'date_debut' => now()->addDays(7)->format('Y-m-d'),
                    'date_fin_prevue' => now()->addDays(30)->format('Y-m-d'),
                    'description' => $prospect->description
                ]
            ]
        ];
    }

    /**
     * Annuler une conversion (si le chantier n'a pas commencé)
     */
    public function annulerConversion(Devis $prospect): bool
    {
        try {
            if (!$prospect->chantier_converti_id) {
                throw new Exception("Ce prospect n'a pas été converti");
            }

            $chantier = Chantier::find($prospect->chantier_converti_id);
            if (!$chantier) {
                throw new Exception("Chantier introuvable");
            }

            // Vérifier que le chantier n'a pas commencé
            if ($chantier->statut !== 'planifie') {
                throw new Exception("Impossible d'annuler: le chantier a déjà commencé");
            }

            // Vérifier qu'il n'y a pas de devis chantier avec facture
            $devisChantier = Devis::where('chantier_id', $chantier->id)
                ->whereNotNull('facture_id')
                ->exists();

            if ($devisChantier) {
                throw new Exception("Impossible d'annuler: des factures ont été émises");
            }

            DB::beginTransaction();

            // Supprimer les devis chantiers
            Devis::where('chantier_id', $chantier->id)->delete();

            // Supprimer le chantier
            $chantier->delete();

            // Remettre le prospect en statut accepté
            $prospect->update([
                'chantier_converti_id' => null,
                'date_conversion_chantier' => null,
            ]);

            $prospect->changerStatut(
                StatutDevis::PROSPECT_ACCEPTE,
                'Conversion annulée - retour en prospect accepté'
            );

            DB::commit();

            Log::info("Conversion annulée avec succès", [
                'prospect_id' => $prospect->id,
                'chantier_supprime' => $chantier->id
            ]);

            return true;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erreur annulation conversion: " . $e->getMessage());
            throw new Exception("Impossible d'annuler la conversion: " . $e->getMessage());
        }
    }

    /**
     * Obtenir les statistiques de conversion
     */
    public function obtenirStatistiquesConversion(?int $commercialId = null): array
    {
        $query = Devis::prospects();
        
        if ($commercialId) {
            $query->where('commercial_id', $commercialId);
        }

        $prospects = $query->get();
        $acceptes = $prospects->where('statut', StatutDevis::PROSPECT_ACCEPTE);
        $convertis = $prospects->whereNotNull('chantier_converti_id');

        return [
            'prospects_acceptes' => $acceptes->count(),
            'prospects_convertis' => $convertis->count(),
            'prospects_convertibles' => $acceptes->whereNull('chantier_converti_id')->count(),
            'taux_conversion' => $acceptes->count() > 0 
                ? round(($convertis->count() / $acceptes->count()) * 100, 2)
                : 0,
            'ca_converti' => $convertis->sum('montant_ttc'),
            'ca_en_attente_conversion' => $acceptes->whereNull('chantier_converti_id')->sum('montant_ttc'),
            'delai_moyen_conversion' => $this->calculerDelaiMoyenConversion($convertis),
        ];
    }

    /**
     * Obtenir les prospects prêts à être convertis
     */
    public function obtenirProspectsConvertibles(?int $commercialId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Devis::prospects()
            ->where('statut', StatutDevis::PROSPECT_ACCEPTE)
            ->whereNull('chantier_converti_id');
            
        if ($commercialId) {
            $query->where('commercial_id', $commercialId);
        }

        return $query->with(['commercial'])
            ->orderBy('date_reponse', 'asc')
            ->get();
    }

    /**
     * Valider les données de conversion
     */
    public function validerDonneesConversion(array $data): array
    {
        $erreurs = [];

        // Validation chantier
        if (empty($data['chantier']['titre'])) {
            $erreurs[] = "Le titre du chantier est obligatoire";
        }

        if (empty($data['chantier']['date_debut'])) {
            $erreurs[] = "La date de début du chantier est obligatoire";
        } elseif (strtotime($data['chantier']['date_debut']) < strtotime('today')) {
            $erreurs[] = "La date de début ne peut pas être dans le passé";
        }

        if (empty($data['chantier']['date_fin_prevue'])) {
            $erreurs[] = "La date de fin prévue est obligatoire";
        } elseif (
            !empty($data['chantier']['date_debut']) && 
            strtotime($data['chantier']['date_fin_prevue']) <= strtotime($data['chantier']['date_debut'])
        ) {
            $erreurs[] = "La date de fin doit être postérieure à la date de début";
        }

        // Validation client (optionnelle)
        if (!empty($data['client']['email'])) {
            if (!filter_var($data['client']['email'], FILTER_VALIDATE_EMAIL)) {
                $erreurs[] = "Format d'email client invalide";
            }
        }

        return [
            'valide' => empty($erreurs),
            'erreurs' => $erreurs
        ];
    }

    /**
     * Calculer le délai moyen de conversion
     */
    private function calculerDelaiMoyenConversion(\Illuminate\Support\Collection $convertis): ?float
    {
        $delais = $convertis->filter(function($prospect) {
            return $prospect->date_reponse && $prospect->date_conversion_chantier;
        })->map(function($prospect) {
            return $prospect->date_reponse->diffInDays($prospect->date_conversion_chantier);
        });

        return $delais->count() > 0 ? round($delais->avg(), 1) : null;
    }

    /**
     * Vérifier les conditions de conversion
     */
    public function verifierConditionsConversion(Devis $prospect): array
    {
        $conditions = [
            'est_prospect' => [
                'valide' => $prospect->isProspect(),
                'message' => 'Le devis doit être un prospect'
            ],
            'statut_accepte' => [
                'valide' => $prospect->statut === StatutDevis::PROSPECT_ACCEPTE,
                'message' => 'Le prospect doit être accepté'
            ],
            'pas_deja_converti' => [
                'valide' => !$prospect->chantier_converti_id,
                'message' => 'Le prospect ne doit pas déjà être converti'
            ],
            'client_info_complete' => [
                'valide' => !empty($prospect->client_info['email']) && !empty($prospect->client_info['nom']),
                'message' => 'Les informations client doivent être complètes'
            ],
            'montant_valide' => [
                'valide' => $prospect->montant_ttc > 0,
                'message' => 'Le montant du prospect doit être positif'
            ]
        ];

        $toutesConditionsRemplies = collect($conditions)->every(fn($condition) => $condition['valide']);

        return [
            'peut_convertir' => $toutesConditionsRemplies,
            'conditions' => $conditions,
            'conditions_manquantes' => collect($conditions)
                ->filter(fn($condition) => !$condition['valide'])
                ->pluck('message')
                ->toArray()
        ];
    }
}