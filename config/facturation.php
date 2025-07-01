<?php

return [
    'entreprise' => [
        'nom' => env('COMPANY_NAME', 'Votre Entreprise'),
        'siret' => env('COMPANY_SIRET'),
        'tva_numero' => env('COMPANY_VAT'),
        'adresse' => env('COMPANY_ADDRESS'),
        'ville' => env('COMPANY_CITY'),
        'code_postal' => env('COMPANY_POSTAL_CODE'),
        'telephone' => env('COMPANY_PHONE'),
        'email' => env('COMPANY_EMAIL'),
    ],
    
    'facturation_electronique' => [
        'active' => env('FACTURATION_ELECTRONIQUE_ACTIVE', false),
        'format_defaut' => env('FACTURATION_FORMAT', 'json'), // json, xml
        'plateforme_url' => env('PLATEFORME_DEMATERIALISATION_URL'),
        'api_key' => env('PLATEFORME_API_KEY'),
    ],
    
    'numerotation' => [
        'prefixe_facture' => env('PREFIXE_FACTURE', 'F'),
        'prefixe_devis' => env('PREFIXE_DEVIS', 'DEV'),
        'chronologique_obligatoire' => env('NUMEROTATION_CHRONOLOGIQUE', true),
    ]
];