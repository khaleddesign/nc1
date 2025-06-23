<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EntrepriseSettings extends Model
{
    protected $table = 'entreprise_settings';
    
    protected $fillable = [
        'nom',
        'adresse',
        'code_postal',
        'ville',
        'telephone',
        'email',
        'site_web',
        'siret',
        'tva_intracommunautaire',
        'logo',
        'couleur_principale',
        'taux_tva_defaut',
        'modalites_paiement_defaut',
        'conditions_generales_defaut',
        'mentions_legales',
        'signature_automatique',
        'format_numerotation_devis',
        'format_numerotation_facture',
        'compteur_devis',
        'compteur_facture',
    ];

    protected $casts = [
        'taux_tva_defaut' => 'decimal:2',
        'signature_automatique' => 'boolean',
        'compteur_devis' => 'integer',
        'compteur_facture' => 'integer',
    ];

    /**
     * Récupérer les paramètres de l'entreprise
     */
    public static function getSettings(): array
    {
        $settings = static::first();
        
        if (!$settings) {
            return static::getDefaultSettings();
        }

        $data = $settings->toArray();
        
        // Ajouter l'URL du logo
        if ($settings->logo) {
            $data['logo_url'] = Storage::disk('public')->url($settings->logo);
            $data['logo_path'] = Storage::disk('public')->path($settings->logo);
        } else {
            $data['logo_url'] = null;
            $data['logo_path'] = null;
        }

        return array_merge(static::getDefaultSettings(), $data);
    }

    /**
     * Mettre à jour les paramètres
     */
    public static function updateSettings(array $data): void
    {
        $settings = static::first();
        
        if ($settings) {
            $settings->update($data);
        } else {
            static::create($data);
        }
    }

    /**
     * Valider les paramètres
     */
    public static function validateSettings(array $data): array
    {
        $validator = Validator::make($data, [
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:500',
            'code_postal' => 'nullable|string|max:10',
            'ville' => 'nullable|string|max:100',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'site_web' => 'nullable|url|max:255',
            'siret' => 'required|string|max:20',
            'tva_intracommunautaire' => 'nullable|string|max:20',
            'couleur_principale' => 'nullable|string|max:7',
            'taux_tva_defaut' => 'nullable|numeric|min:0|max:100',
            'modalites_paiement_defaut' => 'nullable|string|max:500',
            'conditions_generales_defaut' => 'nullable|string|max:2000',
            'mentions_legales' => 'nullable|string|max:2000',
            'signature_automatique' => 'nullable|boolean',
            'format_numerotation_devis' => 'nullable|string|max:50',
            'format_numerotation_facture' => 'nullable|string|max:50',
            'compteur_devis' => 'nullable|integer|min:1',
            'compteur_facture' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Valeurs par défaut
     */
    public static function getDefaultSettings(): array
    {
        return [
            'nom' => '',
            'adresse' => '',
            'code_postal' => '',
            'ville' => '',
            'telephone' => '',
            'email' => '',
            'site_web' => '',
            'siret' => '',
            'tva_intracommunautaire' => '',
            'logo' => null,
            'logo_url' => null,
            'logo_path' => null,
            'couleur_principale' => '#2563eb',
            'taux_tva_defaut' => 20.00,
            'modalites_paiement_defaut' => 'Paiement à 30 jours',
            'conditions_generales_defaut' => '',
            'mentions_legales' => '',
            'signature_automatique' => false,
            'format_numerotation_devis' => 'DEV-{YYYY}-{NNN}',
            'format_numerotation_facture' => 'FAC-{YYYY}-{NNN}',
            'compteur_devis' => 1,
            'compteur_facture' => 1,
        ];
    }

    /**
     * Vérifier si l'entreprise est configurée
     */
    public static function isConfigured(): bool
    {
        $settings = static::first();
        
        if (!$settings) {
            return false;
        }

        $required = ['nom', 'adresse', 'telephone', 'email', 'siret'];
        
        foreach ($required as $field) {
            if (empty($settings->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Obtenir l'URL du logo
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        return Storage::disk('public')->url($this->logo);
    }

    /**
     * Obtenir le chemin du logo
     */
    public function getLogoPathAttribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        return Storage::disk('public')->path($this->logo);
    }

    /**
     * Incrémenter le compteur de devis
     */
    public function incrementDevisCounter(): int
    {
        $this->increment('compteur_devis');
        return $this->compteur_devis;
    }

    /**
     * Incrémenter le compteur de factures
     */
    public function incrementFactureCounter(): int
    {
        $this->increment('compteur_facture');
        return $this->compteur_facture;
    }

    /**
     * Générer le prochain numéro de devis
     */
    public function getNextDevisNumber(): string
    {
        $format = $this->format_numerotation_devis ?? 'DEV-{YYYY}-{NNN}';
        $counter = $this->incrementDevisCounter();
        
        return str_replace(
            ['{YYYY}', '{YY}', '{MM}', '{NNN}', '{NNNN}'],
            [
                date('Y'),
                date('y'),
                date('m'),
                str_pad($counter, 3, '0', STR_PAD_LEFT),
                str_pad($counter, 4, '0', STR_PAD_LEFT)
            ],
            $format
        );
    }

    /**
     * Générer le prochain numéro de facture
     */
    public function getNextFactureNumber(): string
    {
        $format = $this->format_numerotation_facture ?? 'FAC-{YYYY}-{NNN}';
        $counter = $this->incrementFactureCounter();
        
        return str_replace(
            ['{YYYY}', '{YY}', '{MM}', '{NNN}', '{NNNN}'],
            [
                date('Y'),
                date('y'),
                date('m'),
                str_pad($counter, 3, '0', STR_PAD_LEFT),
                str_pad($counter, 4, '0', STR_PAD_LEFT)
            ],
            $format
        );
    }
}