<?php
// config/entreprise.php

return [
    /*
    |--------------------------------------------------------------------------
    | Informations de l'entreprise
    |--------------------------------------------------------------------------
    */
    
    'nom' => env('ENTREPRISE_NOM', 'Mon Entreprise'),
    'adresse' => env('ENTREPRISE_ADRESSE', '123 Rue de l\'Exemple'),
    'ville' => env('ENTREPRISE_VILLE', '75000 Paris'),
    'telephone' => env('ENTREPRISE_TELEPHONE', '01 23 45 67 89'),
    'email' => env('ENTREPRISE_EMAIL', 'contact@monentreprise.fr'),
    'site_web' => env('ENTREPRISE_SITE_WEB', 'www.monentreprise.fr'),
    
    /*
    |--------------------------------------------------------------------------
    | Informations légales
    |--------------------------------------------------------------------------
    */
    
    'siret' => env('ENTREPRISE_SIRET', '12345678901234'),
    'numero_tva' => env('ENTREPRISE_TVA', 'FR12345678901'),
    'code_ape' => env('ENTREPRISE_CODE_APE', '7022Z'),
    'capital' => env('ENTREPRISE_CAPITAL', '10000'),
    
    /*
    |--------------------------------------------------------------------------
    | Branding
    |--------------------------------------------------------------------------
    */
    
    'logo' => env('ENTREPRISE_LOGO', null),
    'couleur_primaire' => env('ENTREPRISE_COULEUR', '#3b82f6'),
    
    /*
    |--------------------------------------------------------------------------
    | Facturation électronique
    |--------------------------------------------------------------------------
    */
    
    'facturation_electronique' => [
        'active' => env('FACTURATION_ELECTRONIQUE_ACTIVE', true),
        'format_defaut' => env('FACTURATION_ELECTRONIQUE_FORMAT', 'json'),
        'archivage_obligatoire' => env('FACTURATION_ELECTRONIQUE_ARCHIVAGE', true),
    ],
];