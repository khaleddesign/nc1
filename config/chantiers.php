<?php
// config/chantiers.php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration de l'application Gestion Chantiers
    |--------------------------------------------------------------------------
    */

    // Informations de l'entreprise
    'company' => [
        'name' => env('COMPANY_NAME', 'Gestion Chantiers'),
        'email' => env('COMPANY_EMAIL', 'contact@chantiers.com'),
        'phone' => env('COMPANY_PHONE', '01 23 45 67 89'),
        'address' => env('COMPANY_ADDRESS', '123 rue de la Construction, 75001 Paris'),
        'website' => env('COMPANY_WEBSITE', 'https://www.chantiers.com'),
    ],

    // Paramètres des chantiers
    'chantiers' => [
        // Statuts possibles
        'statuts' => [
            'planifie' => 'Planifié',
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
        ],
        
        // Types de documents autorisés
        'document_types' => [
            'image' => 'Image/Photo',
            'document' => 'Document',
            'plan' => 'Plan',
            'facture' => 'Facture',
            'autre' => 'Autre',
        ],
        
        // Extensions de fichiers autorisées
        'allowed_extensions' => [
            'jpg', 'jpeg', 'png', 'gif', 'pdf', 
            'doc', 'docx', 'xls', 'xlsx', 'txt'
        ],
        
        // Taille maximale des fichiers (en MB)
        'max_file_size' => 10,
        
        // Nombre maximum de fichiers par upload
        'max_files_per_upload' => 10,
    ],

    // Paramètres des notifications
    'notifications' => [
        // Types de notifications
        'types' => [
            'nouveau_chantier' => 'Nouveau chantier',
            'changement_statut' => 'Changement de statut',
            'nouvelle_etape' => 'Nouvelle étape',
            'etape_terminee' => 'Étape terminée',
            'nouveau_document' => 'Nouveau document',
            'nouveau_commentaire_client' => 'Nouveau commentaire client',
            'nouveau_commentaire_commercial' => 'Réponse du commercial',
            'chantier_retard' => 'Chantier en retard',
        ],
        
        // Durée de vie des notifications (en jours)
        'retention_days' => 90,
        
        // Notifications par email
        'email_enabled' => env('NOTIFICATIONS_EMAIL_ENABLED', true),
    ],

    // Paramètres de pagination
    'pagination' => [
        'chantiers_per_page' => 10,
        'users_per_page' => 20,
        'notifications_per_page' => 20,
        'documents_per_page' => 25,
    ],

    // Paramètres de sécurité
    'security' => [
        // Durée de session (en minutes)
        'session_lifetime' => 120,
        
        // Tentatives de connexion autorisées
        'max_login_attempts' => 5,
        
        // Durée du blocage après échec (en minutes)
        'lockout_duration' => 15,
    ],

    // Paramètres d'export
    'export' => [
        // Formats disponibles
        'formats' => ['excel', 'pdf', 'csv'],
        
        // Limite de lignes par export
        'max_rows' => 10000,
    ],

    // Messages personnalisés
    'messages' => [
        'welcome' => 'Bienvenue sur la plateforme de gestion de chantiers',
        'project_created' => 'Votre projet a été créé avec succès',
        'project_updated' => 'Votre projet a été mis à jour',
        'step_completed' => 'Félicitations ! L\'étape a été terminée',
    ],
];