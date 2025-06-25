<?php
namespace Tests\Unit\Services;

use App\Models\Devis;
use App\Models\Facture;
use App\Models\Ligne;
use App\Services\PdfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdfServiceTest extends TestCase
{
    use RefreshDatabase;

    private PdfService $pdfService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pdfService = new PdfService();
    }

    public function test_generation_pdf_devis()
    {
        $devis = Devis::factory()->create([
            'titre' => 'Devis PDF Test',
            'montant_ht' => 1000,
            'montant_ttc' => 1200
        ]);

        Ligne::factory()->pourDevis($devis)->create([
            'designation' => 'Service test',
            'quantite' => 10,
            'prix_unitaire' => 100
        ]);

        $pdf = $this->pdfService->genererDevis($devis);

        $this->assertNotNull($pdf);
        $this->assertStringContainsString('Devis PDF Test', $pdf);
        $this->assertStringContainsString('1 000,00', $pdf);
    }

    public function test_generation_pdf_facture()
    {
        $facture = Facture::factory()->create([
            'titre' => 'Facture PDF Test',
            'montant_ht' => 1500,
            'montant_ttc' => 1800
        ]);

        Ligne::factory()->pourFacture($facture)->create([
            'designation' => 'Service facturé',
            'quantite' => 15,
            'prix_unitaire' => 100
        ]);

        $pdf = $this->pdfService->genererFacture($facture);

        $this->assertNotNull($pdf);
        $this->assertStringContainsString('Facture PDF Test', $pdf);
        $this->assertStringContainsString('1 500,00', $pdf);
    }

    public function test_pdf_contient_informations_entreprise()
    {
        config([
            'app.company_name' => 'Test Entreprise BTP',
            'app.company_address' => '123 Rue du Test',
            'app.company_siret' => '12345678901234'
        ]);

        $devis = Devis::factory()->create();
        $pdf = $this->pdfService->genererDevis($devis);

        $this->assertStringContainsString('Test Entreprise BTP', $pdf);
        $this->assertStringContainsString('123 Rue du Test', $pdf);
        $this->assertStringContainsString('12345678901234', $pdf);
    }
}

// Commandes artisan pour les tests
/*
// Créer un fichier .env.testing
cp .env.example .env.testing

// Configurer la base de données de test
echo "DB_CONNECTION=sqlite" >> .env.testing
echo "DB_DATABASE=:memory:" >> .env.testing

// Lancer les tests
php artisan test

// Tests avec couverture
php artisan test --coverage

// Tests spécifiques
php artisan test --filter DevisWorkflowTest
php artisan test tests/Feature/
php artisan test tests/Unit/

// Tests en parallèle (plus rapide)
php artisan test --parallel

// Tests avec rapport détaillé
php artisan test --verbose
*/

// Makefile pour automatiser les tests
/*
.PHONY: test test-unit test-feature test-coverage setup-test

setup-test:
	cp .env.example .env.testing
	echo "DB_CONNECTION=sqlite" >> .env.testing
	echo "DB_DATABASE=:memory:" >> .env.testing
	php artisan key:generate --env=testing

test:
	php artisan test

test-unit:
	php artisan test tests/Unit/

test-feature:
	php artisan test tests/Feature/

test-coverage:
	php artisan test --coverage-html coverage/

test-parallel:
	php artisan test --parallel

lint:
	./vendor/bin/pint
	./vendor/bin/phpstan analyse

ci: lint test
*/