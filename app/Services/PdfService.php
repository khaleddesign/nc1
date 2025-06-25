<?php

namespace App\Services;

use App\Models\Devis;
use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    /**
     * Générer le PDF d'un devis
     */
    public function genererDevisPdf(Devis $devis): string
    {
        $data = [
            'devis' => $devis,
            'entreprise' => [
                'nom' => config('app.name', 'Gestion Laravel'),
                'adresse' => env('COMPANY_ADDRESS', '123 Rue Example'),
                'ville' => env('COMPANY_CITY', 'Paris'),
                'code_postal' => env('COMPANY_POSTAL_CODE', '75001'),
                'telephone' => env('COMPANY_PHONE', '01 23 45 67 89'),
                'email' => env('COMPANY_EMAIL', 'contact@entreprise.com'),
                'siret' => env('COMPANY_SIRET', '123 456 789 00012'),
                'tva' => env('COMPANY_TVA', 'FR12345678901'),
            ]
        ];

        $pdf = Pdf::loadView('pdf.devis', $data);
        
        return $pdf->output();
    }

    /**
     * Générer le PDF d'une facture
     */
    public function genererFacturePdf(Facture $facture): string
    {
        $data = [
            'facture' => $facture,
            'entreprise' => [
                'nom' => config('app.name', 'Gestion Laravel'),
                'adresse' => env('COMPANY_ADDRESS', '123 Rue Example'),
                'ville' => env('COMPANY_CITY', 'Paris'),
                'code_postal' => env('COMPANY_POSTAL_CODE', '75001'),
                'telephone' => env('COMPANY_PHONE', '01 23 45 67 89'),
                'email' => env('COMPANY_EMAIL', 'contact@entreprise.com'),
                'siret' => env('COMPANY_SIRET', '123 456 789 00012'),
                'tva' => env('COMPANY_TVA', 'FR12345678901'),
            ]
        ];

        $pdf = Pdf::loadView('pdf.facture', $data);
        
        return $pdf->output();
    }

    /**
     * Générer un PDF simple avec du HTML
     */
    public function genererPdfDepuisHtml(string $html, array $options = []): string
    {
        $pdf = Pdf::loadHTML($html);
        
        // Options par défaut
        $defaultOptions = [
            'format' => 'A4',
            'orientation' => 'portrait',
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        if (isset($options['format'])) {
            $pdf->setPaper($options['format'], $options['orientation']);
        }
        
        return $pdf->output();
    }
}