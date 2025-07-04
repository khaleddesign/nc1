<?php

namespace App\Services;

use App\Models\Devis;
use App\Models\Facture;
use Carbon\Carbon;

class FacturationElectroniqueService
{
    /**
     * Générer les données structurées d'une facture
     */
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
                'name' => config('entreprise.nom', config('app.name')),
                'siret' => config('entreprise.siret', '12345678901234'),
                'vat_number' => config('entreprise.numero_tva', 'FR12345678901'),
                'address' => [
                    'street' => config('entreprise.adresse', '123 Rue Example'),
                    'city' => config('entreprise.ville', 'Paris'),
                    'postal_code' => config('entreprise.code_postal', '75001'),
                ],
                'contact' => [
                    'phone' => config('entreprise.telephone', '01 23 45 67 89'),
                    'email' => config('entreprise.email', 'contact@entreprise.com'),
                ]
            ],
            'customer' => [
                'name' => $facture->client_info['nom'] ?? 'Client inconnu',
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

    /**
     * Marquer une facture comme conforme
     */
    public function marquerConforme(Facture $facture): void
    {
        try {
            $donnees = $this->genererDonneesStructurees($facture);
            $hash = hash('sha256', json_encode($donnees, JSON_UNESCAPED_UNICODE));
            
            $facture->updateQuietly([
                'donnees_structurees' => $donnees,
                'hash_integrite' => $hash,
                'conforme_loi' => true,
                'format_electronique' => 'json',
                'numero_chronologique' => $donnees['invoice_data']['chronological_number'],
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur conformité facture: ' . $e->getMessage());
        }
    }

    /**
     * Générer le numéro chronologique pour une facture
     */
    private function genererNumeroChronologique(Facture $facture): string
    {
        $annee = $facture->date_emission->year;
        $count = Facture::whereYear('date_emission', $annee)
                        ->where('id', '<=', $facture->id)
                        ->count();
        
        return sprintf('%d-%06d', $annee, $count);
    }

    /**
     * Vérifier l'intégrité d'une facture
     */
    public function verifierIntegrite(Facture $facture): bool
    {
        if (!$facture->donnees_structurees || !$facture->hash_integrite) {
            return false;
        }
        
        $hash_calcule = hash('sha256', json_encode($facture->donnees_structurees, JSON_UNESCAPED_UNICODE));
        return hash_equals($facture->hash_integrite, $hash_calcule);
    }

    /**
     * Exporter au format électronique
     */
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

    // ====================================================
    // MÉTHODES POUR LES DEVIS
    // ====================================================

    /**
     * Générer les données structurées d'un devis
     */
    public function genererDonneesStructureesDevis(Devis $devis): array
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
                'status' => $devis->getStatutActuel(),
            ],
            'supplier' => [
                'name' => config('entreprise.nom', config('app.name')),
                'siret' => config('entreprise.siret', '12345678901234'),
                'vat_number' => config('entreprise.numero_tva', 'FR12345678901'),
                'address' => [
                    'street' => config('entreprise.adresse', '123 Rue Example'),
                    'city' => config('entreprise.ville', 'Paris'),
                    'postal_code' => config('entreprise.code_postal', '75001'),
                ],
                'contact' => [
                    'phone' => config('entreprise.telephone', '01 23 45 67 89'),
                    'email' => config('entreprise.email', 'contact@entreprise.com'),
                ]
            ],
            'customer' => [
                'name' => $devis->client_info['nom'] ?? 'Client inconnu',
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

    /**
     * Marquer un devis comme conforme
     */
    public function marquerConformeDevis(Devis $devis): void
    {
        try {
            $donnees = $this->genererDonneesStructureesDevis($devis);
            $hash = hash('sha256', json_encode($donnees, JSON_UNESCAPED_UNICODE));
            
            $devis->updateQuietly([
                'donnees_structurees' => $donnees,
                'hash_integrite' => $hash,
                'conforme_loi' => true,
                'format_electronique' => 'json',
                'numero_chronologique' => $donnees['quote_data']['chronological_number'],
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur conformité devis: ' . $e->getMessage());
        }
    }

    /**
     * Générer le numéro chronologique pour un devis
     */
    private function genererNumeroChronologiqueDevis(Devis $devis): string
    {
        $annee = $devis->date_emission->year;
        $count = Devis::whereYear('date_emission', $annee)
                      ->where('id', '<=', $devis->id)
                      ->count();
        
        return sprintf('DEV-%d-%06d', $annee, $count);
    }

    /**
     * Vérifier l'intégrité d'un devis
     */
    public function verifierIntegriteDevis(Devis $devis): bool
    {
        if (!$devis->donnees_structurees || !$devis->hash_integrite) {
            return false;
        }
        
        $hash_calcule = hash('sha256', json_encode($devis->donnees_structurees, JSON_UNESCAPED_UNICODE));
        return hash_equals($devis->hash_integrite, $hash_calcule);
    }

    /**
     * Exporter un devis au format électronique
     */
    public function exporterFormatElectroniqueDevis(Devis $devis, string $format = 'json'): array
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

    // ====================================================
    // MÉTHODES UTILITAIRES
    // ====================================================

    /**
     * Convertir un tableau en XML
     */
    private function convertirEnXml(array $donnees): string
    {
        $xml = new \SimpleXMLElement('<document_electronique/>');
        $this->arrayToXml($donnees, $xml);
        return $xml->asXML();
    }

    /**
     * Convertir récursivement un tableau en XML
     */
    private function arrayToXml(array $data, \SimpleXMLElement $xml): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = 'item';
                }
                $subnode = $xml->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                if (is_numeric($key)) {
                    $key = 'item';
                }
                $xml->addChild($key, htmlspecialchars($value ?? ''));
            }
        }
    }
}