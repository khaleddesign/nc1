<?php

namespace App\Services;

use App\Models\Devis;
use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class PdfService
{
    /**
     * Générer le PDF d'un devis
     */
    public function genererDevisPdf(Devis $devis): string
    {
        try {
            // Charger les relations nécessaires
            $devis->load(['chantier.client', 'commercial', 'lignes']);
            
            $data = [
                'devis' => $devis,
                'entreprise' => $this->getEntrepriseInfo(),
                'date_generation' => now()->format('d/m/Y H:i'),
            ];

            // Vérifier si la vue existe, sinon utiliser une vue par défaut
            if (View::exists('pdf.devis')) {
                $html = View::make('pdf.devis', $data)->render();
            } else {
                $html = $this->genererHtmlDevisDefaut($devis, $data['entreprise']);
            }

            $pdf = Pdf::loadHTML($html)
                ->setPaper('A4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isPhpEnabled' => true,
                    'isRemoteEnabled' => false,
                    'margin_top' => 10,
                    'margin_bottom' => 10,
                    'margin_left' => 10,
                    'margin_right' => 10,
                ]);
            
            return $pdf->output();
            
        } catch (\Exception $e) {
            \Log::error('Erreur génération PDF devis: ' . $e->getMessage());
            throw new \Exception('Erreur lors de la génération du PDF du devis: ' . $e->getMessage());
        }
    }

    /**
     * Générer le PDF d'une facture
     */
    public function genererFacturePdf(Facture $facture): string
    {
        try {
            // Charger les relations nécessaires
            $facture->load(['chantier.client', 'commercial', 'lignes', 'paiements']);
            
            $data = [
                'facture' => $facture,
                'entreprise' => $this->getEntrepriseInfo(),
                'date_generation' => now()->format('d/m/Y H:i'),
            ];

            // Vérifier si la vue existe, sinon utiliser une vue par défaut
            if (View::exists('pdf.facture')) {
                $html = View::make('pdf.facture', $data)->render();
            } else {
                $html = $this->genererHtmlFactureDefaut($facture, $data['entreprise']);
            }

            $pdf = Pdf::loadHTML($html)
                ->setPaper('A4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isPhpEnabled' => true,
                    'isRemoteEnabled' => false,
                    'margin_top' => 10,
                    'margin_bottom' => 10,
                    'margin_left' => 10,
                    'margin_right' => 10,
                ]);
            
            return $pdf->output();
            
        } catch (\Exception $e) {
            \Log::error('Erreur génération PDF facture: ' . $e->getMessage());
            throw new \Exception('Erreur lors de la génération du PDF de la facture: ' . $e->getMessage());
        }
    }

    /**
     * Générer un PDF simple avec du HTML
     */
    public function genererPdfDepuisHtml(string $html, array $options = []): string
    {
        $defaultOptions = [
            'format' => 'A4',
            'orientation' => 'portrait',
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper($options['format'], $options['orientation']);
        
        return $pdf->output();
    }

    /**
     * Obtenir les informations de l'entreprise
     */
    private function getEntrepriseInfo(): array
    {
        return [
            'nom' => config('entreprise.nom', config('app.name', 'Mon Entreprise')),
            'adresse' => config('entreprise.adresse', '123 Rue de l\'Exemple'),
            'ville' => config('entreprise.ville', 'Paris'),
            'code_postal' => config('entreprise.code_postal', '75001'),
            'telephone' => config('entreprise.telephone', '01 23 45 67 89'),
            'email' => config('entreprise.email', 'contact@entreprise.com'),
            'siret' => config('entreprise.siret', '12345678901234'),
            'tva' => config('entreprise.numero_tva', 'FR12345678901'),
            'site_web' => config('entreprise.site_web', 'www.entreprise.com'),
            'logo' => config('entreprise.logo', null),
        ];
    }

    /**
     * Générer HTML par défaut pour un devis (si la vue n'existe pas)
     */
    private function genererHtmlDevisDefaut(Devis $devis, array $entreprise): string
    {
        $clientNom = $devis->client_info['nom'] ?? $devis->chantier?->client?->name ?? 'Client inconnu';
        $clientAdresse = $devis->client_info['adresse'] ?? $devis->chantier?->client?->adresse ?? '';
        
        $lignesHtml = '';
        foreach ($devis->lignes as $ligne) {
            $lignesHtml .= "
                <tr>
                    <td>{$ligne->designation}</td>
                    <td style='text-align: center;'>{$ligne->quantite}</td>
                    <td style='text-align: center;'>{$ligne->unite}</td>
                    <td style='text-align: right;'>" . number_format($ligne->prix_unitaire_ht, 2, ',', ' ') . " €</td>
                    <td style='text-align: right;'>" . number_format($ligne->montant_ht, 2, ',', ' ') . " €</td>
                </tr>";
        }

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>Devis {$devis->numero}</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; }
                .header { border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
                .company { float: left; width: 50%; }
                .client { float: right; width: 50%; text-align: right; }
                .clear { clear: both; }
                .title { text-align: center; font-size: 18px; font-weight: bold; margin: 30px 0; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .totals { width: 300px; margin-left: auto; margin-top: 20px; }
                .totals td { border: 1px solid #333; }
                .total-final { font-weight: bold; background-color: #f0f0f0; }
            </style>
        </head>
        <body>
            <div class='header'>
                <div class='company'>
                    <strong>{$entreprise['nom']}</strong><br>
                    {$entreprise['adresse']}<br>
                    {$entreprise['ville']}<br>
                    Tél: {$entreprise['telephone']}<br>
                    Email: {$entreprise['email']}<br>
                    SIRET: {$entreprise['siret']}
                </div>
                <div class='client'>
                    <strong>DEVIS DESTINÉ À :</strong><br>
                    {$clientNom}<br>
                    {$clientAdresse}
                </div>
                <div class='clear'></div>
            </div>

            <div class='title'>DEVIS N° {$devis->numero}</div>

            <p><strong>Date d'émission :</strong> {$devis->date_emission->format('d/m/Y')}</p>
            <p><strong>Date de validité :</strong> {$devis->date_validite->format('d/m/Y')}</p>
            
            " . ($devis->description ? "<p><strong>Description :</strong> {$devis->description}</p>" : "") . "

            <table>
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th>Quantité</th>
                        <th>Unité</th>
                        <th>Prix unitaire HT</th>
                        <th>Total HT</th>
                    </tr>
                </thead>
                <tbody>
                    {$lignesHtml}
                </tbody>
            </table>

            <table class='totals'>
                <tr>
                    <td>Total HT</td>
                    <td style='text-align: right;'>" . number_format($devis->montant_ht, 2, ',', ' ') . " €</td>
                </tr>
                <tr>
                    <td>TVA ({$devis->taux_tva}%)</td>
                    <td style='text-align: right;'>" . number_format($devis->montant_tva, 2, ',', ' ') . " €</td>
                </tr>
                <tr class='total-final'>
                    <td><strong>Total TTC</strong></td>
                    <td style='text-align: right;'><strong>" . number_format($devis->montant_ttc, 2, ',', ' ') . " €</strong></td>
                </tr>
            </table>

            " . ($devis->modalites_paiement ? "<p><strong>Modalités de paiement :</strong> {$devis->modalites_paiement}</p>" : "") . "
            " . ($devis->delai_realisation ? "<p><strong>Délai de réalisation :</strong> {$devis->delai_realisation} jours</p>" : "") . "
            " . ($devis->conditions_generales ? "<p><strong>Conditions générales :</strong><br>{$devis->conditions_generales}</p>" : "") . "
        </body>
        </html>";
    }

    /**
     * Générer HTML par défaut pour une facture (si la vue n'existe pas)
     */
    private function genererHtmlFactureDefaut(Facture $facture, array $entreprise): string
    {
        $clientNom = $facture->client_info['nom'] ?? $facture->chantier?->client?->name ?? 'Client inconnu';
        $clientAdresse = $facture->client_info['adresse'] ?? $facture->chantier?->client?->adresse ?? '';
        
        $lignesHtml = '';
        foreach ($facture->lignes as $ligne) {
            $lignesHtml .= "
                <tr>
                    <td>{$ligne->designation}</td>
                    <td style='text-align: center;'>{$ligne->quantite}</td>
                    <td style='text-align: center;'>{$ligne->unite}</td>
                    <td style='text-align: right;'>" . number_format($ligne->prix_unitaire_ht, 2, ',', ' ') . " €</td>
                    <td style='text-align: right;'>" . number_format($ligne->montant_ht, 2, ',', ' ') . " €</td>
                </tr>";
        }

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>Facture {$facture->numero}</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; }
                .header { border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
                .company { float: left; width: 50%; }
                .client { float: right; width: 50%; text-align: right; }
                .clear { clear: both; }
                .title { text-align: center; font-size: 18px; font-weight: bold; margin: 30px 0; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .totals { width: 300px; margin-left: auto; margin-top: 20px; }
                .totals td { border: 1px solid #333; }
                .total-final { font-weight: bold; background-color: #f0f0f0; }
                .urgent { color: red; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='header'>
                <div class='company'>
                    <strong>{$entreprise['nom']}</strong><br>
                    {$entreprise['adresse']}<br>
                    {$entreprise['ville']}<br>
                    Tél: {$entreprise['telephone']}<br>
                    Email: {$entreprise['email']}<br>
                    SIRET: {$entreprise['siret']}
                </div>
                <div class='client'>
                    <strong>FACTURE DESTINÉE À :</strong><br>
                    {$clientNom}<br>
                    {$clientAdresse}
                </div>
                <div class='clear'></div>
            </div>

            <div class='title'>FACTURE N° {$facture->numero}</div>

            <p><strong>Date d'émission :</strong> {$facture->date_emission->format('d/m/Y')}</p>
            <p><strong>Date d'échéance :</strong> {$facture->date_echeance->format('d/m/Y')}</p>
            
            " . ($facture->description ? "<p><strong>Description :</strong> {$facture->description}</p>" : "") . "

            <table>
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th>Quantité</th>
                        <th>Unité</th>
                        <th>Prix unitaire HT</th>
                        <th>Total HT</th>
                    </tr>
                </thead>
                <tbody>
                    {$lignesHtml}
                </tbody>
            </table>

            <table class='totals'>
                <tr>
                    <td>Total HT</td>
                    <td style='text-align: right;'>" . number_format($facture->montant_ht, 2, ',', ' ') . " €</td>
                </tr>
                <tr>
                    <td>TVA ({$facture->taux_tva}%)</td>
                    <td style='text-align: right;'>" . number_format($facture->montant_tva, 2, ',', ' ') . " €</td>
                </tr>
                <tr class='total-final'>
                    <td><strong>Total TTC</strong></td>
                    <td style='text-align: right;'><strong>" . number_format($facture->montant_ttc, 2, ',', ' ') . " €</strong></td>
                </tr>
                <tr>
                    <td>Montant payé</td>
                    <td style='text-align: right;'>" . number_format($facture->montant_paye, 2, ',', ' ') . " €</td>
                </tr>
                <tr class='" . ($facture->montant_restant > 0 ? 'urgent' : '') . "'>
                    <td><strong>Montant restant dû</strong></td>
                    <td style='text-align: right;'><strong>" . number_format($facture->montant_restant, 2, ',', ' ') . " €</strong></td>
                </tr>
            </table>

            " . ($facture->conditions_reglement ? "<p><strong>Conditions de règlement :</strong> {$facture->conditions_reglement}</p>" : "") . "
            
            <p><strong>En cas de retard de paiement, des pénalités de retard seront appliquées.</strong></p>
        </body>
        </html>";
    }

    /**
     * Formater un montant pour l'affichage
     */
    public function formaterMontant(float $montant): string
    {
        return number_format($montant, 2, ',', ' ') . ' €';
    }

    /**
     * Générer un nom de fichier sécurisé
     */
    public function genererNomFichier(string $type, string $numero): string
    {
        $timestamp = now()->format('Y-m-d');
        $filename = strtolower($type) . '_' . str_replace(['/', '\\', ' '], '_', $numero) . '_' . $timestamp;
        
        // Nettoyer le nom de fichier
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '', $filename);
        
        return $filename . '.pdf';
    }
}