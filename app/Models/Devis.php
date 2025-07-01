<?php

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
        'statut', 'client_info', 'date_emission', 'date_validite',
        'date_envoi', 'date_reponse', 'montant_ht', 'montant_tva',
        'montant_ttc', 'taux_tva', 'conditions_generales',
        'delai_realisation', 'modalites_paiement', 'signature_client',
        'signed_at', 'signature_ip', 'facture_id', 'converted_at',
        'notes_internes'
    ];

    protected $casts = [
        'client_info' => 'array',
        'date_emission' => 'date',
        'date_validite' => 'date',
        'date_envoi' => 'datetime',
        'date_reponse' => 'datetime',
        'signed_at' => 'datetime',
        'converted_at' => 'datetime',
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
        'taux_tva' => 'decimal:2',
        'delai_realisation' => 'integer',
    ];

    // Flag pour éviter la récursion
    protected static $calculatingTotals = false;

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

    // ====================================================
    // MÉTHODES MÉTIER
    // ====================================================

    /**
     * Calculer les montants du devis
     */
    public function calculerMontants(): void
    {
        // Éviter la récursion
        if (static::$calculatingTotals) {
            return;
        }

        static::$calculatingTotals = true;

        try {
            $montantHT = $this->lignes->sum('montant_ht');
            $montantTVA = $this->lignes->sum('montant_tva');
            $montantTTC = $montantHT + $montantTVA;

            // Mise à jour directe sans trigger d'événements
            DB::table('devis')
                ->where('id', $this->id)
                ->update([
                    'montant_ht' => $montantHT,
                    'montant_tva' => $montantTVA,
                    'montant_ttc' => $montantTTC,
                    'updated_at' => now(),
                ]);

            // Mettre à jour l'instance actuelle
            $this->montant_ht = $montantHT;
            $this->montant_tva = $montantTVA;
            $this->montant_ttc = $montantTTC;

        } finally {
            static::$calculatingTotals = false;
        }
    }

    /**
     * Générer un numéro de devis
     */
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

    /**
     * Vérifier si le devis est expiré
     */
    public function isExpire(): bool
    {
        return $this->date_validite->isPast() && $this->statut === 'envoye';
    }

    /**
     * Vérifier si le devis peut être modifié
     */
    public function peutEtreModifie(): bool
    {
        return in_array($this->statut, ['brouillon', 'envoye']) && 
               !$this->facture_id;
    }

    /**
     * Vérifier si le devis peut être accepté
     */
    public function peutEtreAccepte(): bool
    {
        return $this->statut === 'envoye' && !$this->isExpire();
    }

    /**
     * Vérifier si le devis peut être converti en facture
     */
    public function peutEtreConverti(): bool
    {
        return $this->statut === 'accepte' && !$this->facture_id;
    }

    /**
     * Accepter le devis
     */
    public function accepter(): void
    {
        $this->update([
            'statut' => 'accepte',
            'date_reponse' => now(),
        ]);
    }

    /**
     * Refuser le devis
     */
    public function refuser(): void
    {
        $this->update([
            'statut' => 'refuse',
            'date_reponse' => now(),
        ]);
    }

    /**
     * Marquer le devis comme envoyé
     */
    public function marquerEnvoye(): void
    {
        $this->update([
            'statut' => 'envoye',
            'date_envoi' => now(),
        ]);
    }

    /**
     * Signer électroniquement le devis
     */
    public function signerElectroniquement(string $signature, string $ip): void
    {
        $this->update([
            'signature_client' => $signature,
            'signature_ip' => $ip,
            'signed_at' => now(),
        ]);
    }

    // ====================================================
    // ACCESSEURS (Méthodes pour les vues)
    // ====================================================

    /**
     * 🔧 CORRIGÉ : Méthode normale au lieu d'attribut
     */
    public function getStatutBadgeClass(): string
    {
        return match ($this->statut) {
            'brouillon' => 'badge-secondary',
            'envoye' => 'badge-info', 
            'accepte' => 'badge-success',
            'refuse' => 'badge-danger',
            'expire' => 'badge-warning',
            default => 'badge-secondary',
        };
    }

    /**
     * 🔧 CORRIGÉ : Méthode normale au lieu d'attribut
     */
    public function getStatutTexte(): string
    {
        return match ($this->statut) {
            'brouillon' => 'Brouillon',
            'envoye' => 'Envoyé',
            'accepte' => 'Accepté',
            'refuse' => 'Refusé',
            'expire' => 'Expiré',
            default => 'Inconnu',
        };
    }

    /**
     * 🔧 CORRIGÉ : Accesseur Laravel classique
     */
    public function getClientNomAttribute(): string
    {
        return $this->client_info['nom'] ?? $this->chantier?->client?->name ?? 'Client inconnu';
    }

    // ====================================================
    // SCOPES
    // ====================================================

    public function scopeEnCours($query)
    {
        return $query->whereIn('statut', ['brouillon', 'envoye']);
    }

    public function scopeExpires($query)
    {
        return $query->where('statut', 'envoye')
            ->where('date_validite', '<', now());
    }

    public function scopeAcceptes($query)
    {
        return $query->where('statut', 'accepte');
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
        });
    }
}