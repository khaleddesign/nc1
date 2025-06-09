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

    // Configuration UI avec classes Tailwind CSS
    'ui' => [
        // Classes pour les alertes
        'alerts' => [
            'success' => 'bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md mb-4',
            'error' => 'bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md mb-4',
            'warning' => 'bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-md mb-4',
            'info' => 'bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-md mb-4',
        ],

        // Classes pour les badges
        'badges' => [
            'primary' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800',
            'secondary' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800',
            'success' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800',
            'danger' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800',
            'warning' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800',
            'info' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800',
        ],

        // Classes pour les boutons
        'buttons' => [
            'primary' => 'inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150',
            'secondary' => 'inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150',
            'success' => 'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150',
            'danger' => 'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150',
            'warning' => 'inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150',
            'outline' => 'inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:border-blue-500 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150',
        ],

        // Classes pour les cartes
        'cards' => [
            'default' => 'bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200',
            'elevated' => 'bg-white overflow-hidden shadow-md rounded-lg border border-gray-200',
            'outlined' => 'bg-white overflow-hidden border-2 border-gray-200 rounded-lg',
            'header' => 'px-6 py-4 bg-gray-50 border-b border-gray-200',
            'body' => 'p-6',
            'footer' => 'px-6 py-4 bg-gray-50 border-t border-gray-200',
        ],

        // Classes pour les formulaires
        'forms' => [
            'input' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm',
            'input_error' => 'block w-full rounded-md border-red-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm text-red-900 placeholder-red-300',
            'textarea' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm',
            'select' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm',
            'checkbox' => 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded',
            'radio' => 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300',
            'label' => 'block text-sm font-medium text-gray-700 mb-2',
            'label_required' => 'block text-sm font-medium text-gray-700 mb-2 after:content-["*"] after:text-red-500 after:ml-1',
            'error' => 'mt-1 text-sm text-red-600',
            'help' => 'mt-1 text-sm text-gray-500',
        ],

        // Classes pour les tableaux
        'tables' => [
            'container' => 'overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg',
            'table' => 'min-w-full divide-y divide-gray-300',
            'thead' => 'bg-gray-50',
            'th' => 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider',
            'tbody' => 'bg-white divide-y divide-gray-200',
            'td' => 'px-6 py-4 whitespace-nowrap text-sm text-gray-900',
            'row_hover' => 'hover:bg-gray-50 cursor-pointer',
            'row_selected' => 'bg-blue-50',
            'row_striped' => 'odd:bg-white even:bg-gray-50',
        ],

        // Classes pour la navigation
        'navigation' => [
            'navbar' => 'bg-white shadow-sm border-b border-gray-200',
            'navbar_container' => 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8',
            'navbar_content' => 'flex justify-between h-16',
            'nav_link_active' => 'inline-flex items-center px-1 pt-1 border-b-2 border-blue-500 text-sm font-medium text-gray-900',
            'nav_link_inactive' => 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-150',
            'breadcrumb' => 'flex items-center space-x-2 text-sm text-gray-500 mb-6',
            'breadcrumb_item' => 'hover:text-gray-700 transition-colors duration-150',
            'breadcrumb_separator' => 'text-gray-400',
        ],

        // Classes pour les modales
        'modals' => [
            'overlay' => 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50',
            'container' => 'flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0',
            'content' => 'inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full',
            'header' => 'bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4',
            'title' => 'text-lg leading-6 font-medium text-gray-900',
            'body' => 'bg-white px-4 pt-5 pb-4 sm:p-6',
            'footer' => 'bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3',
        ],

        // Classes pour les barres de progression
        'progress' => [
            'container' => 'w-full bg-gray-200 rounded-full h-2.5',
            'bar' => 'h-2.5 rounded-full transition-all duration-300 ease-in-out',
            'bar_blue' => 'bg-blue-500',
            'bar_green' => 'bg-green-500',
            'bar_yellow' => 'bg-yellow-500',
            'bar_red' => 'bg-red-500',
            'bar_gray' => 'bg-gray-400',
        ],

        // Classes pour la pagination
        'pagination' => [
            'container' => 'flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6',
            'info' => 'flex flex-1 justify-between sm:hidden',
            'nav' => 'hidden sm:flex sm:flex-1 sm:items-center sm:justify-between',
            'list' => 'isolate inline-flex -space-x-px rounded-md shadow-sm',
            'link' => 'relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 focus:z-20',
            'link_active' => 'relative inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-500 focus:z-20',
            'link_disabled' => 'relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-300 cursor-not-allowed',
        ],

        // Classes pour les dropdowns
        'dropdowns' => [
            'menu' => 'absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none',
            'item' => 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150',
            'item_active' => 'block px-4 py-2 text-sm text-gray-900 bg-gray-100',
            'divider' => 'border-t border-gray-100',
        ],

        // Classes pour les notifications/toasts
        'toasts' => [
            'container' => 'fixed top-4 right-4 z-50 space-y-2',
            'toast' => 'max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto border-l-4 p-4 animate-slide-down',
            'toast_success' => 'border-green-400',
            'toast_error' => 'border-red-400',
            'toast_warning' => 'border-yellow-400',
            'toast_info' => 'border-blue-400',
            'title' => 'text-sm font-medium text-gray-900',
            'message' => 'mt-1 text-sm text-gray-500',
        ],

        // Classes spécifiques aux chantiers
        'chantiers' => [
            'card' => 'bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-200',
            'status_indicator' => 'w-3 h-3 rounded-full',
            'status_planifie' => 'bg-gray-400',
            'status_en_cours' => 'bg-blue-500',
            'status_termine' => 'bg-green-500',
            'progress_container' => 'w-full bg-gray-200 rounded-full h-2',
            'progress_bar' => 'h-2 rounded-full transition-all duration-300',
            'urgent' => 'border-l-4 border-red-500 bg-red-50',
        ],

        // Classes pour les étapes
        'etapes' => [
            'container' => 'space-y-3',
            'item' => 'border-l-4 pl-4 py-3 rounded-r-md transition-colors duration-150',
            'item_completed' => 'border-green-400 bg-green-50',
            'item_in_progress' => 'border-blue-400 bg-blue-50',
            'item_pending' => 'border-gray-300 bg-gray-50',
            'item_overdue' => 'border-red-400 bg-red-50',
            'title' => 'font-medium text-gray-900',
            'description' => 'text-sm text-gray-600 mt-1',
            'progress' => 'mt-2 flex items-center space-x-2',
            'progress_text' => 'text-xs text-gray-500',
        ],

        // Classes pour les documents
        'documents' => [
            'grid' => 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4',
            'item' => 'bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow duration-150',
            'icon' => 'w-8 h-8 text-gray-400 mb-2',
            'name' => 'text-sm font-medium text-gray-900 truncate',
            'meta' => 'text-xs text-gray-500 mt-1',
            'actions' => 'mt-3 flex space-x-2',
        ],

        // Classes utilitaires
        'utilities' => [
            'loading' => 'opacity-50 pointer-events-none',
            'required' => 'after:content-["*"] after:text-red-500 after:ml-1',
            'text_truncate' => 'truncate',
            'text_ellipsis' => 'overflow-hidden text-ellipsis whitespace-nowrap',
            'sr_only' => 'sr-only',
            'divider' => 'border-t border-gray-200 my-6',
            'spacer' => 'flex-1',
        ],

        // Classes responsive
        'responsive' => [
            'hide_mobile' => 'hidden sm:block',
            'hide_desktop' => 'block sm:hidden',
            'full_mobile' => 'w-full sm:w-auto',
            'stack_mobile' => 'flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2',
        ],
    ],

    // Configuration des icônes (Heroicons)
    'icons' => [
        'chantier' => [
            'planifie' => 'clock',
            'en_cours' => 'play',
            'termine' => 'check-circle',
        ],
        'actions' => [
            'add' => 'plus',
            'edit' => 'pencil',
            'delete' => 'trash',
            'view' => 'eye',
            'download' => 'arrow-down-tray',
            'upload' => 'arrow-up-tray',
            'search' => 'magnifying-glass',
            'filter' => 'funnel',
            'sort' => 'bars-3-bottom-left',
        ],
        'navigation' => [
            'dashboard' => 'home',
            'chantiers' => 'building-office-2',
            'users' => 'users',
            'notifications' => 'bell',
            'settings' => 'cog-6-tooth',
            'logout' => 'arrow-right-on-rectangle',
        ],
        'status' => [
            'success' => 'check-circle',
            'error' => 'x-circle',
            'warning' => 'exclamation-triangle',
            'info' => 'information-circle',
        ],
    ],

    // Configuration des animations
    'animations' => [
        'enabled' => env('ANIMATIONS_ENABLED', true),
        'duration' => [
            'fast' => '150ms',
            'normal' => '300ms',
            'slow' => '500ms',
        ],
        'easing' => [
            'ease_in_out' => 'cubic-bezier(0.4, 0, 0.2, 1)',
            'ease_out' => 'cubic-bezier(0, 0, 0.2, 1)',
            'ease_in' => 'cubic-bezier(0.4, 0, 1, 1)',
        ],
    ],
];