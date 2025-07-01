<?php

namespace App\Services;

use App\Models\Facture;
use Carbon\Carbon;

class FacturationElectroniqueService
{
    public function genererDonneesStructurees(Facture $facture): array
    {
        return [
            'format_version' => '1.0',
            'invoice_data' => [
                'invoice_number' => $facture->numero,
                'chronological_number' => $this->genererNumeroChronologique($facture),
                'issue_date' => $facture->date_emission->format('Y-m-d'),
                'due_date' => $facture->date_echeance->format('Y-m-d'),
                'invoice_type' => 'commercial_invoice',
                'currency' => 'EUR',
            ],
            'supplier' => [
                'name' => config('facturation.entreprise.nom'),
                'siret' => config('facturation.entreprise.siret'),
                'vat_number' => config('facturation.entreprise.tva_numero'),
                'address' => [
                    'street' => config('facturation.entreprise.adresse'),
                    'city' => config('facturation.entreprise.ville'),
                    'postal_code' => config('facturation.entreprise.code_postal'),
                ],
                'contact' => [
                    'phone' => config('facturation.entreprise.telephone'),
                    'email' => config('facturation.entreprise.email'),
                ]
            ],
            'customer' => [
                'name' => $facture->client_info['nom'],
                'email' => $facture->client_info['email'] ?? null,
                'phone' => $facture->client_info['telephone'] ?? null,
                'address' => $facture->client_info['adresse'] ?? null,
            ],
            'invoice_lines' => $facture->lignes->map(function($ligne) {
                return [
                    'line_number' => $ligne->ordre,
                    'description' => $ligne->designation,
                    'additional_description' => $ligne->description,
                    'quantity' => $ligne->quantite,
                    'unit' => $ligne->unite,
                    'unit_price_ht' => $ligne->prix_unitaire_ht,
                    'discount_percentage' => $ligne->remise_pourcentage,
                    'vat_rate' => $ligne->taux_tva,
                    'total_ht' => $ligne->montant_ht,
                    'total_vat' => $ligne->montant_tva,
                    'total_ttc' => $ligne->montant_ttc,
                    'category' => $ligne->categorie,
                ];
            }),
            'totals' => [
                'total_ht' => $facture->montant_ht,
                'total_vat' => $facture->montant_tva,
                'total_ttc' => $facture->montant_ttc,
                'amount_paid' => $facture->montant_paye,
                'amount_due' => $facture->montant_restant,
            ],
            'payment_terms' => $facture->conditions_reglement,
            'metadata' => [
                'created_at' => $facture->created_at->toISOString(),
                'updated_at' => $facture->updated_at->toISOString(),
                'generation_timestamp' => now()->toISOString(),
            ]
        ];
    }

    public function marquerConforme(Facture $facture): void
    {
        $donnees = $this->genererDonneesStructurees($facture);
        $hash = hash('sha256', json_encode($donnees, JSON_UNESCAPED_UNICODE));
        
        $facture->update([
            'donnees_structurees' => $donnees,
            'hash_integrite' => $hash,
            'conforme_loi' => true,
            'format_electronique' => 'json',
            'numero_chronologique' => $donnees['invoice_data']['chronological_number'],
        ]);
    }

    private function genererNumeroChronologique(Facture $facture): string
    {
        $annee = $facture->date_emission->year;
        $count = Facture::whereYear('date_emission', $annee)
                        ->where('id', '<=', $facture->id)
                        ->count();
        
        return sprintf('%d-%06d', $annee, $count);
    }

    public function verifierIntegrite(Facture $facture): bool
    {
        if (!$facture->donnees_structurees || !$facture->hash_integrite) {
            return false;
        }
        
        $hash_calcule = hash('sha256', json_encode($facture->donnees_structurees, JSON_UNESCAPED_UNICODE));
        return hash_equals($facture->hash_integrite, $hash_calcule);
    }

    public function exporterFormatElectronique(Facture $facture, string $format = 'json'): array
    {
        if (!$facture->conforme_loi) {
            $this->marquerConforme($facture);
        }

        return match($format) {
            'json' => $facture->donnees_structurees,
            'xml' => $this->convertirEnXml($facture->donnees_structurees),
            default => throw new \InvalidArgumentException("Format non supporté: {$format}")
        };
    }

    private function convertirEnXml(array $donnees): string
    {
        // Conversion basique XML - à améliorer selon vos besoins
        $xml = new \SimpleXMLElement('<facture_electronique/>');
        $this->arrayToXml($donnees, $xml);
        return $xml->asXML();
    }

    private function arrayToXml(array $data, \SimpleXMLElement $xml): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subnode = $xml->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xml->addChild($key, htmlspecialchars($value));
            }
        }
    }




    // ====================================================
// MÉTHODES POUR LES DEVIS
// ====================================================

public function genererDonneesStructureesDevis(\App\Models\Devis $devis): array
{
    return [
        'format_version' => '1.0',
        'document_type' => 'devis',
        'quote_data' => [
            'quote_number' => $devis->numero,
            'chronological_number' => $this->genererNumeroChronologiqueDevis($devis),
            'issue_date' => $devis->date_emission->format('Y-m-d'),
            'validity_date' => $devis->date_validite->format('Y-m-d'),
            'quote_type' => 'commercial_quote',
            'currency' => 'EUR',
            'status' => $devis->statut,
        ],
        'supplier' => [
            'name' => config('facturation.entreprise.nom'),
            'siret' => config('facturation.entreprise.siret'),
            'vat_number' => config('facturation.entreprise.tva_numero'),
            'address' => [
                'street' => config('facturation.entreprise.adresse'),
                'city' => config('facturation.entreprise.ville'),
                'postal_code' => config('facturation.entreprise.code_postal'),
            ],
            'contact' => [
                'phone' => config('facturation.entreprise.telephone'),
                'email' => config('facturation.entreprise.email'),
            ]
        ],
        'customer' => [
            'name' => $devis->client_info['nom'],
            'email' => $devis->client_info['email'] ?? null,
            'phone' => $devis->client_info['telephone'] ?? null,
            'address' => $devis->client_info['adresse'] ?? null,
        ],
        'quote_lines' => $devis->lignes->map(function($ligne) {
            return [
                'line_number' => $ligne->ordre,
                'description' => $ligne->designation,
                'additional_description' => $ligne->description,
                'quantity' => $ligne->quantite,
                'unit' => $ligne->unite,
                'unit_price_ht' => $ligne->prix_unitaire_ht,
                'discount_percentage' => $ligne->remise_pourcentage,
                'vat_rate' => $ligne->taux_tva,
                'total_ht' => $ligne->montant_ht,
                'total_vat' => $ligne->montant_tva,
                'total_ttc' => $ligne->montant_ttc,
                'category' => $ligne->categorie,
            ];
        }),
        'totals' => [
            'total_ht' => $devis->montant_ht,
            'total_vat' => $devis->montant_tva,
            'total_ttc' => $devis->montant_ttc,
        ],
        'terms' => [
            'payment_terms' => $devis->modalites_paiement,
            'delivery_time' => $devis->delai_realisation,
            'general_conditions' => $devis->conditions_generales,
        ],
        'metadata' => [
            'created_at' => $devis->created_at->toISOString(),
            'updated_at' => $devis->updated_at->toISOString(),
            'generation_timestamp' => now()->toISOString(),
            'sent_at' => $devis->date_envoi?->toISOString(),
            'response_at' => $devis->date_reponse?->toISOString(),
        ]
    ];
}

public function marquerConformeDevis(\App\Models\Devis $devis): void
{
    $donnees = $this->genererDonneesStructureesDevis($devis);
    $hash = hash('sha256', json_encode($donnees, JSON_UNESCAPED_UNICODE));
    
    $devis->update([
        'donnees_structurees' => $donnees,
        'hash_integrite' => $hash,
        'conforme_loi' => true,
        'format_electronique' => 'json',
        'numero_chronologique' => $donnees['quote_data']['chronological_number'],
    ]);
}

private function genererNumeroChronologiqueDevis(\App\Models\Devis $devis): string
{
    $annee = $devis->date_emission->year;
    $count = \App\Models\Devis::whereYear('date_emission', $annee)
                  ->where('id', '<=', $devis->id)
                  ->count();
    
    return sprintf('DEV-%d-%06d', $annee, $count);
}

public function verifierIntegriteDevis(\App\Models\Devis $devis): bool
{
    if (!$devis->donnees_structurees || !$devis->hash_integrite) {
        return false;
    }
    
    $hash_calcule = hash('sha256', json_encode($devis->donnees_structurees, JSON_UNESCAPED_UNICODE));
    return hash_equals($devis->hash_integrite, $hash_calcule);
}

public function exporterFormatElectroniqueDevis(\App\Models\Devis $devis, string $format = 'json'): array
{
    if (!$devis->conforme_loi) {
        $this->marquerConformeDevis($devis);
    }

    return match($format) {
        'json' => $devis->donnees_structurees,
        'xml' => $this->convertirEnXml($devis->donnees_structurees),
        default => throw new \InvalidArgumentException("Format non supporté: {$format}")
    };
}
}


