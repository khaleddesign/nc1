<?php
// app/Models/Devis.php - VERSION ENRICHIE

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Devis extends Model
{
    use HasFactory;

    protected $table = 'devis';

    protected $fillable = [
        'numero', 'chantier_id', 'commercial_id', 'titre', 'description',
        'statut', 'type_devis', 'statut_prospect', 'client_info', 
        'date_emission', 'date_validite', 'date_envoi', 'date_reponse', 
        'montant_ht', 'montant_tva', 'montant_ttc', 'taux_tva', 
        'conditions_generales', 'delai_realisation', 'modalites_paiement', 
        'signature_client', 'signed_at', 'signature_ip', 'facture_id', 
        'converted_at', 'notes_internes', 'chantier_converti_id', 
        'date_conversion', 'historique_negociation', 'reference_externe',
        'donnees_structurees', 'format_electronique', 'hash_integrite',
        'conforme_loi', 'date_transmission', 'numero_chronologique'
    ];

    protected $casts = [
        'client_info' => 'array',
        'date_emission' => 'date',
        'date_validite' => 'date',
        'date_envoi' => 'datetime',
        'date_reponse' => 'datetime',
        'signed_at' => 'datetime',
        'converted_at' => 'datetime',
        'date_conversion' => 'datetime',
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
        'taux_tva' => 'decimal:2',
        'delai_realisation' => 'integer',
        'donnees_structurees' => 'array',
        'historique_negociation' => 'array',
        'conforme_loi' => 'boolean',
        'date_transmission' => 'datetime',
    ];

    // ====================================================
    // CONSTANTES POUR LES FLUX
    // ====================================================
    
    const TYPE_PROSPECT = 'prospect';
    const TYPE_CHANTIER = 'chantier';
    const TYPE_CONVERTI = 'converti';
    
    const STATUT_PROSPECT_BROUILLON = 'brouillon';
    const STATUT_PROSPECT_ENVOYE = 'envoye';
    const STATUT_PROSPECT_NEGOCIE = 'negocie';
    const STATUT_PROSPECT_ACCEPTE = 'accepte';
    const STATUT_PROSPECT_REFUSE = 'refuse';
    const STATUT_PROSPECT_EXPIRE = 'expire';
    const STATUT_PROSPECT_CONVERTI = 'converti';

    // ====================================================
    // RELATIONS
    // ====================================================

    public function chantier()
    {
        return $this->belongsTo(Chantier::class);
    }

    public function commercial()
    {
        return $this->belongsTo(User::class, 'commercial_id');
    }

    public function lignes()
    {
        return $this->morphMany(Ligne::class, 'ligneable')->orderBy('ordre');
    }

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    /**
     * Chantier créé lors de la conversion (pour prospects)
     */
    public function chantierConverti()
    {
        return $this->belongsTo(Chantier::class, 'chantier_converti_id');
    }

    // ====================================================
    // SCOPES POUR LES FLUX
    // ====================================================

    /**
     * Scope pour les devis prospects uniquement
     */
    public function scopeProspects($query)
    {
        return $query->where('type_devis', self::TYPE_PROSPECT);
    }

    /**
     * Scope pour les devis liés à des chantiers
     */
    public function scopeChantiers($query)
    {
        return $query->where('type_devis', self::TYPE_CHANTIER);
    }

    /**
     * Scope pour les devis convertis
     */
    public function scopeConvertis($query)
    {
        return $query->where('type_devis', self::TYPE_CONVERTI);
    }

    /**
     * Scope pour les prospects en cours de négociation
     */
    public function scopeEnNegociation($query)
    {
        return $query->prospects()
                    ->whereIn('statut_prospect', [
                        self::STATUT_PROSPECT_ENVOYE,
                        self::STATUT_PROSPECT_NEGOCIE
                    ]);
    }

    /**
     * Scope pour les prospects convertibles
     */
    public function scopeConvertibles($query)
    {
        return $query->prospects()
                    ->where('statut_prospect', self::STATUT_PROSPECT_ACCEPTE)
                    ->whereNull('chantier_converti_id');
    }

    // ====================================================
    // MÉTHODES MÉTIER POUR LES FLUX
    // ====================================================

    /**
     * Vérifier si c'est un devis prospect
     */
    public function isProspect(): bool
    {
        return $this->type_devis === self::TYPE_PROSPECT;
    }

    /**
     * Vérifier si c'est un devis lié à un chantier
     */
    public function isChantier(): bool
    {
        return $this->type_devis === self::TYPE_CHANTIER;
    }

    /**
     * Vérifier si c'est un prospect converti
     */
    public function isConverti(): bool
    {
        return $this->type_devis === self::TYPE_CONVERTI;
    }

    /**
     * Vérifier si le prospect peut être converti en chantier
     */
    public function peutEtreConverti(): bool
    {
        return $this->isProspect() && 
               $this->statut_prospect === self::STATUT_PROSPECT_ACCEPTE &&
               !$this->chantier_converti_id;
    }

    /**
     * Convertir un devis prospect en chantier
     */
    public function convertirEnChantier(array $donnees_chantier): Chantier
    {
        if (!$this->peutEtreConverti()) {
            throw new \Exception('Ce devis ne peut pas être converti en chantier');
        }

        DB::beginTransaction();
        
        try {
            // Créer le chantier
            $chantier = Chantier::create([
                'titre' => $donnees_chantier['titre'] ?? $this->titre,
                'description' => $donnees_chantier['description'] ?? $this->description,
                'client_id' => $this->getClientId(),
                'commercial_id' => $this->commercial_id,
                'statut' => 'planifie',
                'date_debut' => $donnees_chantier['date_debut'] ?? now()->addDays(7),
                'date_fin_prevue' => $donnees_chantier['date_fin_prevue'] ?? now()->addDays(30),
                'budget' => $this->montant_ttc,
                'notes' => "Chantier créé à partir du devis prospect {$this->numero}",
            ]);

            // Mettre à jour le devis prospect
            $this->update([
                'type_devis' => self::TYPE_CONVERTI,
                'statut_prospect' => self::STATUT_PROSPECT_CONVERTI,
                'chantier_converti_id' => $chantier->id,
                'date_conversion' => now(),
            ]);

            // Créer un nouveau devis lié au chantier (copie du prospect)
            $devis_chantier = $this->replicate([
                'id', 'numero', 'created_at', 'updated_at', 'type_devis',
                'statut_prospect', 'chantier_converti_id', 'date_conversion'
            ]);
            
            $devis_chantier->fill([
                'chantier_id' => $chantier->id,
                'type_devis' => self::TYPE_CHANTIER,
                'statut' => 'accepte',
                'statut_prospect' => null,
                'titre' => $this->titre . ' (Chantier)',
            ]);
            
            $devis_chantier->save();

            // Copier les lignes vers le nouveau devis
            foreach ($this->lignes as $ligne) {
                $nouvelle_ligne = $ligne->replicate();
                $devis_chantier->lignes()->save($nouvelle_ligne);
            }

            $devis_chantier->calculerMontants();

            DB::commit();
            
            return $chantier;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Ajouter une version à l'historique de négociation
     */
    public function ajouterVersionNegociation(string $motif, array $modifications = []): void
    {
        if (!$this->isProspect()) {
            return;
        }

        $historique = $this->historique_negociation ?? [];
        
        $historique[] = [
            'version' => count($historique) + 1,
            'date' => now()->toISOString(),
            'motif' => $motif,
            'modifications' => $modifications,
            'montant_ht' => $this->montant_ht,
            'montant_ttc' => $this->montant_ttc,
            'utilisateur' => auth()->user()?->name,
        ];

        $this->update([
            'historique_negociation' => $historique,
            'statut_prospect' => self::STATUT_PROSPECT_NEGOCIE
        ]);
    }

    /**
     * Obtenir l'ID du client (depuis client_info ou relation)
     */
    private function getClientId(): int
    {
        // Si le devis est lié à un chantier, récupérer le client du chantier
        if ($this->chantier_id) {
            return $this->chantier->client_id;
        }

        // Sinon, chercher ou créer le client depuis client_info
        if (isset($this->client_info['email'])) {
            $client = User::firstOrCreate(
                ['email' => $this->client_info['email']],
                [
                    'name' => $this->client_info['nom'],
                    'role' => 'client',
                    'telephone' => $this->client_info['telephone'] ?? null,
                    'adresse' => $this->client_info['adresse'] ?? null,
                    'password' => bcrypt('temp_password_' . time()),
                    'email_verified_at' => now(),
                ]
            );
            
            return $client->id;
        }

        throw new \Exception('Impossible de déterminer le client pour ce devis');
    }

    // ====================================================
    // MÉTHODES MÉTIER EXISTANTES (conservées)
    // ====================================================

    public function calculerMontants(): void
    {
        static $calculatingTotals = false;

        if ($calculatingTotals) {
            return;
        }

        $calculatingTotals = true;

        try {
            $montantHT = $this->lignes->sum('montant_ht');
            $montantTVA = $this->lignes->sum('montant_tva');
            $montantTTC = $montantHT + $montantTVA;

            DB::table('devis')
                ->where('id', $this->id)
                ->update([
                    'montant_ht' => $montantHT,
                    'montant_tva' => $montantTVA,
                    'montant_ttc' => $montantTTC,
                    'updated_at' => now(),
                ]);

            $this->montant_ht = $montantHT;
            $this->montant_tva = $montantTVA;
            $this->montant_ttc = $montantTTC;

        } finally {
            $calculatingTotals = false;
        }
    }

    public static function genererNumero(): string
    {
        $annee = date('Y');
        $dernierNumero = static::where('numero', 'like', "DEV-{$annee}-%")
            ->orderBy('numero', 'desc')
            ->value('numero');

        if ($dernierNumero) {
            $numero = (int) substr($dernierNumero, -3);
            $numero++;
        } else {
            $numero = 1;
        }

        return sprintf('DEV-%s-%03d', $annee, $numero);
    }

    public function isExpire(): bool
    {
        return $this->date_validite->isPast() && 
               in_array($this->getStatutActuel(), ['envoye', 'negocie']);
    }

    public function peutEtreModifie(): bool
    {
        $statut = $this->getStatutActuel();
        
        if ($this->isProspect()) {
            return in_array($statut, ['brouillon', 'envoye', 'negocie']) && !$this->chantier_converti_id;
        }
        
        return in_array($statut, ['brouillon', 'envoye']) && !$this->facture_id;
    }

    public function peutEtreAccepte(): bool
    {
        $statut = $this->getStatutActuel();
        
        if ($this->isProspect()) {
            return in_array($statut, ['envoye', 'negocie']) && !$this->isExpire();
        }
        
        return $statut === 'envoye' && !$this->isExpire();
    }

    /**
     * Obtenir le statut actuel selon le type de devis
     */
    public function getStatutActuel(): string
    {
        return $this->isProspect() ? $this->statut_prospect : $this->statut;
    }

    public function accepter(): void
    {
        if ($this->isProspect()) {
            $this->update([
                'statut_prospect' => self::STATUT_PROSPECT_ACCEPTE,
                'date_reponse' => now(),
            ]);
        } else {
            $this->update([
                'statut' => 'accepte',
                'date_reponse' => now(),
            ]);
        }
    }

    public function refuser(): void
    {
        if ($this->isProspect()) {
            $this->update([
                'statut_prospect' => self::STATUT_PROSPECT_REFUSE,
                'date_reponse' => now(),
            ]);
        } else {
            $this->update([
                'statut' => 'refuse',
                'date_reponse' => now(),
            ]);
        }
    }

    public function marquerEnvoye(): void
    {
        if ($this->isProspect()) {
            $this->update([
                'statut_prospect' => self::STATUT_PROSPECT_ENVOYE,
                'date_envoi' => now(),
            ]);
        } else {
            $this->update([
                'statut' => 'envoye',
                'date_envoi' => now(),
            ]);
        }
    }

    // ====================================================
    // ACCESSEURS AMÉLIORÉS
    // ====================================================

    public function getStatutBadgeClass(): string
    {
        $statut = $this->getStatutActuel();
        
        return match ($statut) {
            'brouillon' => 'bg-gray-100 text-gray-800',
            'envoye' => 'bg-blue-100 text-blue-800',
            'negocie' => 'bg-yellow-100 text-yellow-800',
            'accepte' => 'bg-green-100 text-green-800',
            'refuse' => 'bg-red-100 text-red-800',
            'expire' => 'bg-orange-100 text-orange-800',
            'converti' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatutTexte(): string
    {
        $statut = $this->getStatutActuel();
        
        $textes = [
            'brouillon' => 'Brouillon',
            'envoye' => 'Envoyé',
            'negocie' => 'En négociation',
            'accepte' => 'Accepté',
            'refuse' => 'Refusé',
            'expire' => 'Expiré',
            'converti' => 'Converti en chantier',
        ];
        
        return $textes[$statut] ?? 'Inconnu';
    }

    public function getClientNomAttribute(): string
    {
        return $this->client_info['nom'] ?? $this->chantier?->client?->name ?? 'Client inconnu';
    }

    /**
     * Obtenir le type de devis avec emoji pour l'affichage
     */
    public function getTypeDevisTexte(): string
    {
        return match ($this->type_devis) {
            self::TYPE_PROSPECT => '🎯 Prospect',
            self::TYPE_CHANTIER => '🏗️ Chantier',
            self::TYPE_CONVERTI => '✅ Converti',
            default => '❓ Inconnu',
        };
    }

    // ====================================================
    // ÉVÉNEMENTS
    // ====================================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($devis) {
            if (!$devis->numero) {
                $devis->numero = static::genererNumero();
            }
            if (!$devis->date_emission) {
                $devis->date_emission = now();
            }
            if (!$devis->date_validite) {
                $devis->date_validite = now()->addDays(30);
            }
            
            // Définir le type par défaut selon la présence d'un chantier
            if (!$devis->type_devis) {
                $devis->type_devis = $devis->chantier_id ? self::TYPE_CHANTIER : self::TYPE_PROSPECT;
            }
            
            // Définir le statut initial selon le type
            if ($devis->isProspect() && !$devis->statut_prospect) {
                $devis->statut_prospect = self::STATUT_PROSPECT_BROUILLON;
            }
        });

        static::created(function ($devis) {
            if (config('facturation.facturation_electronique.active', false)) {
                try {
                    $devis->genererConformiteElectronique();
                } catch (\Exception $e) {
                    \Log::warning('Erreur génération conformité électronique devis: ' . $e->getMessage());
                }
            }
        });
    }

    // ====================================================
    // MÉTHODES FACTURATION ÉLECTRONIQUE (conservées)
    // ====================================================

    public function estConformeFacturationElectronique(): bool
    {
        return $this->conforme_loi && 
               !empty($this->donnees_structurees) && 
               !empty($this->hash_integrite);
    }

    public function genererConformiteElectronique(): void
    {
        app(\App\Services\FacturationElectroniqueService::class)->marquerConformeDevis($this);
    }

    public function verifierIntegriteElectronique(): bool
    {
        return app(\App\Services\FacturationElectroniqueService::class)->verifierIntegriteDevis($this);
    }

    public function exporterFormatElectronique(string $format = 'json'): array
    {
        return app(\App\Services\FacturationElectroniqueService::class)->exporterFormatElectroniqueDevis($this, $format);
    }

    public function getStatutConformiteAttribute(): string
    {
        if (!$this->conforme_loi) {
            return 'non_conforme';
        }
        
        if (!$this->verifierIntegriteElectronique()) {
            return 'integrite_compromise';
        }
        
        return 'conforme';
    }

    public function getStatutConformiteBadgeAttribute(): string
    {
        return match($this->statut_conformite) {
            'conforme' => 'bg-green-100 text-green-800',
            'non_conforme' => 'bg-red-100 text-red-800',
            'integrite_compromise' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}