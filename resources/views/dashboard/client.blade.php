@extends('layouts.app')

@section('title', 'Mon Espace Projet')

@push('styles')
<style>
    .glass-effect {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }
    
    .project-card {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .project-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .project-card:hover::before {
        left: 100%;
    }
    
    .project-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .progress-ring {
        transform: rotate(-90deg);
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        height: 100%;
        width: 2px;
        background: linear-gradient(to bottom, #3b82f6, #10b981);
    }
    
    .floating-animation {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .pulse-glow {
        animation: pulse-glow 2s infinite;
    }
    
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 5px rgba(59, 130, 246, 0.5); }
        50% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.8), 0 0 30px rgba(59, 130, 246, 0.6); }
    }
    
    .notification-dot {
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
        40%, 43% { transform: translate3d(0, -8px, 0); }
        70% { transform: translate3d(0, -4px, 0); }
        90% { transform: translate3d(0, -2px, 0); }
    }
</style>
@endpush

@section('content')
<div class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen">
    <!-- Header avec design moderne -->
    <header class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 shadow-2xl overflow-hidden">
        <!-- Éléments décoratifs d'arrière-plan -->
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute top-0 left-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-x-48 -translate-y-48 floating-animation"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full translate-x-32 translate-y-32 floating-animation" style="animation-delay: 1s;"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <!-- Avatar avec effet de bordure animée -->
                    <div class="relative">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-r from-pink-500 to-violet-500 p-1 pulse-glow">
                            <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                                <span class="text-2xl font-bold text-gray-700">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        @if($notifications->where('lu', false)->count() > 0)
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-400 rounded-full border-4 border-white notification-dot"></div>
                        @endif
                    </div>
                    
                    <div>
                        <h1 class="text-4xl font-bold text-white mb-2">
                            Bonjour, {{ Auth::user()->name }} ! 👋
                        </h1>
                        <p class="text-blue-100 text-lg">
                            Bienvenue sur votre espace projet personnalisé
                        </p>
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="bg-white/20 px-3 py-1 rounded-full text-sm text-white">
                                <i class="fas fa-crown mr-1"></i>Client {{ Auth::user()->role === 'client' ? 'Premium' : ucfirst(Auth::user()->role) }}
                            </span>
                            <span class="bg-white/20 px-3 py-1 rounded-full text-sm text-white">
                                <i class="fas fa-star mr-1"></i>{{ $mes_chantiers->count() }} projet{{ $mes_chantiers->count() > 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Notifications et actions rapides -->
                <div class="flex items-center space-x-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-3 bg-white/20 rounded-full text-white hover:bg-white/30 transition-all duration-200 relative">
                            <i class="fas fa-bell text-xl"></i>
                            @if($notifications->where('lu', false)->count() > 0)
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                    {{ $notifications->where('lu', false)->count() }}
                                </span>
                            @endif
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl overflow-hidden z-50">
                            <div class="p-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
                                <h3 class="font-semibold">Notifications récentes</h3>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                @forelse($notifications->take(5) as $notification)
                                    <div class="p-4 border-b hover:bg-gray-50 cursor-pointer {{ !$notification->lu ? 'bg-blue-50' : '' }}">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-2 h-2 {{ !$notification->lu ? 'bg-blue-500' : 'bg-gray-300' }} rounded-full mt-2"></div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $notification->titre }}</p>
                                                <p class="text-xs text-gray-500">{{ Str::limit($notification->message, 50) }}</p>
                                                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-gray-500">
                                        <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                        <p class="text-sm">Aucune notification</p>
                                    </div>
                                @endforelse
                            </div>
                            @if($notifications->count() > 0)
                                <div class="p-3 bg-gray-50 text-center">
                                    <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                        Voir toutes les notifications
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <button onclick="openDevisModal()" class="px-6 py-3 bg-white text-blue-600 rounded-full font-medium hover:bg-blue-50 transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i>Nouveau Projet
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Statistiques rapides avec design moderne -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Statistiques générées dynamiquement -->
            @php
                $stats = [
                    [
                        'icon' => 'fas fa-home',
                        'value' => $mes_chantiers->count(),
                        'label' => 'Projets Totaux',
                        'gradient' => 'from-blue-500 to-cyan-500'
                    ],
                    [
                        'icon' => 'fas fa-check-circle',
                        'value' => $mes_chantiers->where('statut', 'termine')->count(),
                        'label' => 'Terminés',
                        'gradient' => 'from-green-500 to-emerald-500'
                    ],
                    [
                        'icon' => 'fas fa-tools',
                        'value' => $mes_chantiers->where('statut', 'en_cours')->count(),
                        'label' => 'En Cours',
                        'gradient' => 'from-orange-500 to-red-500'
                    ],
                    [
                        'icon' => 'fas fa-percentage',
                        'value' => number_format($mes_chantiers->avg('avancement_global') ?? 0, 0),
                        'label' => 'Avancement Moyen',
                        'gradient' => 'from-purple-500 to-pink-500'
                    ]
                ];
            @endphp
            
            @foreach($stats as $stat)
                <div class="glass-effect rounded-2xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r {{ $stat['gradient'] }} rounded-full flex items-center justify-center">
                        <i class="{{ $stat['icon'] }} text-2xl text-white"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $stat['value'] }}{{ $stat['label'] === 'Avancement Moyen' ? '%' : '' }}</h3>
                    <p class="text-gray-600">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Contenu principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Projets en cours (2 colonnes) -->
            <div class="xl:col-span-2 space-y-6">
                <h2 class="text-2xl font-bold gradient-text mb-6">Mes Projets</h2>
                
                @forelse($mes_chantiers as $chantier)
                    <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="p-6">
                            <!-- En-tête du projet -->
                            <div class="flex items-start justify-between mb-6">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                        @switch(strtolower($chantier->titre))
                                            @case(str_contains(strtolower($chantier->titre), 'cuisine'))
                                                <i class="fas fa-kitchen-set text-2xl text-white"></i>
                                                @break
                                            @case(str_contains(strtolower($chantier->titre), 'salle de bain'))
                                                <i class="fas fa-bath text-2xl text-white"></i>
                                                @break
                                            @case(str_contains(strtolower($chantier->titre), 'extension'))
                                                <i class="fas fa-hammer text-2xl text-white"></i>
                                                @break
                                            @default
                                                <i class="fas fa-home text-2xl text-white"></i>
                                        @endswitch
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">{{ $chantier->titre }}</h3>
                                        <p class="text-gray-600">{{ Str::limit($chantier->description ?? 'Projet en cours', 50) }}</p>
                                        <div class="flex items-center mt-1 space-x-4">
                                            @if($chantier->date_debut)
                                                <span class="text-sm text-gray-500">
                                                    <i class="fas fa-calendar mr-1"></i>Début: {{ $chantier->date_debut->format('d/m/Y') }}
                                                </span>
                                            @endif
                                            @if($chantier->budget)
                                                <span class="text-sm text-green-600">
                                                    <i class="fas fa-euro-sign mr-1"></i>{{ number_format($chantier->budget, 0, ',', ' ') }}€
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    @php
                                        $badgeClasses = [
                                            'planifie' => 'bg-gray-100 text-gray-800',
                                            'en_cours' => 'bg-blue-100 text-blue-800',
                                            'termine' => 'bg-green-100 text-green-800'
                                        ];
                                        $statutTexte = [
                                            'planifie' => 'Planifié',
                                            'en_cours' => 'En cours',
                                            'termine' => 'Terminé'
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 {{ $badgeClasses[$chantier->statut] ?? 'bg-gray-100 text-gray-800' }} rounded-full text-sm font-medium">
                                        {{ $statutTexte[$chantier->statut] ?? 'Inconnu' }}
                                    </span>
                                    <div class="relative" x-data="{ menuOpen: false }">
                                        <button @click="menuOpen = !menuOpen" class="text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div x-show="menuOpen" @click.away="menuOpen = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10">
                                            <a href="{{ route('chantiers.show', $chantier) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-eye mr-2"></i>Voir détails
                                            </a>
                                            <button onclick="contacterCommercial({{ $chantier->commercial->id }})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-phone mr-2"></i>Contacter commercial
                                            </button>
                                            @if($chantier->documents->count() > 0)
                                                <button onclick="voirDocuments({{ $chantier->id }})" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <i class="fas fa-file mr-2"></i>Documents ({{ $chantier->documents->count() }})
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Avancement avec cercle de progression -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700">Avancement global</span>
                                        <span class="text-sm font-bold text-blue-600">{{ number_format($chantier->avancement_global, 0) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full relative overflow-hidden transition-all duration-1000" 
                                             style="width: {{ $chantier->avancement_global }}%" 
                                             data-progress="{{ $chantier->avancement_global }}">
                                            <div class="absolute inset-0 bg-white opacity-30 animate-pulse"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-6 relative">
                                    <svg class="w-16 h-16 progress-ring" viewBox="0 0 36 36">
                                        <path class="text-gray-200" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                        <path class="text-blue-500" stroke="currentColor" stroke-width="3" fill="none" 
                                              stroke-dasharray="{{ $chantier->avancement_global }}, 100" 
                                              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-sm font-bold text-gray-700">{{ number_format($chantier->avancement_global, 0) }}%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Timeline des étapes -->
                            @if($chantier->etapes->count() > 0)
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Étapes du projet</h4>
                                    <div class="timeline relative pl-8 space-y-4">
                                        @foreach($chantier->etapes->take(4) as $etape)
                                            <div class="flex items-center space-x-3">
                                                @if($etape->terminee)
                                                    <div class="w-4 h-4 bg-green-500 rounded-full relative z-10 border-2 border-white"></div>
                                                    <div class="flex-1 bg-green-50 rounded-lg p-3">
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-sm font-medium text-green-800">{{ $etape->nom }}</span>
                                                            <span class="text-xs text-green-600">{{ number_format($etape->pourcentage, 0) }}%</span>
                                                        </div>
                                                    </div>
                                                @elseif($etape->pourcentage > 0)
                                                    <div class="w-4 h-4 bg-blue-500 rounded-full relative z-10 border-2 border-white pulse-glow"></div>
                                                    <div class="flex-1 bg-blue-50 rounded-lg p-3">
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-sm font-medium text-blue-800">{{ $etape->nom }}</span>
                                                            <span class="text-xs text-blue-600">{{ number_format($etape->pourcentage, 0) }}%</span>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="w-4 h-4 bg-gray-300 rounded-full relative z-10 border-2 border-white"></div>
                                                    <div class="flex-1 bg-gray-50 rounded-lg p-3">
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-sm font-medium text-gray-600">{{ $etape->nom }}</span>
                                                            <span class="text-xs text-gray-500">{{ number_format($etape->pourcentage, 0) }}%</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                        
                                        @if($chantier->etapes->count() > 4)
                                            <div class="text-center">
                                                <button onclick="voirToutesEtapes({{ $chantier->id }})" class="text-sm text-blue-600 hover:text-blue-800">
                                                    + {{ $chantier->etapes->count() - 4 }} autres étapes
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Messages de statut spéciaux -->
                            @if($chantier->statut == 'termine')
                                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-start space-x-3">
                                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                        <div>
                                            <h6 class="font-semibold text-green-800">Projet terminé avec succès !</h6>
                                            <p class="text-green-700 text-sm mt-1">Nous espérons que vous êtes satisfait du résultat. N'hésitez pas à nous contacter pour vos futurs projets.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($chantier->isEnRetard())
                                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex items-start space-x-3">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mt-1"></i>
                                        <div>
                                            <h6 class="font-semibold text-yellow-800">Projet en retard</h6>
                                            <p class="text-yellow-700 text-sm mt-1">Le chantier accuse un retard. Votre commercial vous contactera prochainement pour vous informer de la nouvelle planification.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Actions -->
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('chantiers.show', $chantier) }}" class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-xl font-medium hover:bg-blue-700 transition-colors text-center">
                                    <i class="fas fa-eye mr-2"></i>Voir Détails
                                </a>
                                <button onclick="contacterCommercial({{ $chantier->commercial->id }})" class="flex-1 bg-gray-100 text-gray-700 py-3 px-4 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                                    <i class="fas fa-phone mr-2"></i>Contacter
                                </button>
                                @if($chantier->documents->count() > 0)
                                    <button onclick="voirDocuments({{ $chantier->id }})" class="bg-gray-100 text-gray-700 py-3 px-4 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                                        <i class="fas fa-download"></i>
                                    </button>
                                @endif
                                @if($chantier->statut == 'termine')
                                    <button onclick="noterProjet({{ $chantier->id }})" class="bg-yellow-100 text-yellow-700 py-3 px-4 rounded-xl font-medium hover:bg-yellow-200 transition-colors">
                                        <i class="fas fa-star"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-hard-hat text-3xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun projet en cours</h3>
                        <p class="text-gray-600 mb-6">Vous n'avez pas encore de projets. Contactez notre équipe pour démarrer votre premier projet.</p>
                        <button onclick="openDevisModal()" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-3 rounded-xl font-medium hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>Demander un Devis
                        </button>
                    </div>
                @endforelse
            </div>
            
            <!-- Sidebar (1 colonne) -->
            <div class="space-y-6">
                <!-- Commercial attitré -->
                @php
                    $commercialPrincipal = $mes_chantiers->first()?->commercial;
                @endphp
                @if($commercialPrincipal)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Votre Commercial</h3>
                        <div class="text-center">
                            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">{{ substr($commercialPrincipal->name, 0, 2) }}</span>
                            </div>
                            <h4 class="font-bold text-gray-900">{{ $commercialPrincipal->name }}</h4>
                            <p class="text-gray-600 text-sm mb-4">Commercial Expert</p>
                            
                            <div class="space-y-3">
                                @if($commercialPrincipal->telephone)
                                    <a href="tel:{{ $commercialPrincipal->telephone }}" class="block w-full bg-blue-600 text-white py-3 rounded-xl font-medium hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-phone mr-2"></i>{{ $commercialPrincipal->telephone }}
                                    </a>
                                @endif
                                @if($commercialPrincipal->email)
                                    <a href="mailto:{{ $commercialPrincipal->email }}" class="block w-full bg-gray-100 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                                        <i class="fas fa-envelope mr-2"></i>Envoyer un Email
                                    </a>
                                @endif
                                <button onclick="planifierRdv({{ $commercialPrincipal->id }})" class="w-full bg-green-100 text-green-700 py-3 rounded-xl font-medium hover:bg-green-200 transition-colors">
                                    <i class="fas fa-calendar mr-2"></i>Planifier un RDV
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Activité récente -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Activité Récente</h3>
                    <div class="space-y-4">
                        @forelse($notifications->take(3) as $notification)
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 {{ !$notification->lu ? 'bg-blue-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                                    @switch($notification->type)
                                        @case('etape_terminee')
                                            <i class="fas fa-check text-green-600 text-sm"></i>
                                            @break
                                        @case('nouveau_document')
                                            <i class="fas fa-file text-blue-600 text-sm"></i>
                                            @break
                                        @case('nouveau_commentaire_commercial')
                                            <i class="fas fa-comment text-purple-600 text-sm"></i>
                                            @break
                                        @default
                                            <i class="fas fa-bell text-gray-600 text-sm"></i>
                                    @endswitch
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $notification->titre }}</p>
                                    <p class="text-xs text-gray-600">{{ Str::limit($notification->message, 40) }}</p>
                                    <p class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-bell-slash text-2xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500">Aucune activité récente</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Actions rapides -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Actions Rapides</h3>
                    <div class="space-y-3">
                        <button onclick="openDevisModal()" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-xl font-medium hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>Nouveau Projet
                        </button>
                        <button onclick="demanderDevis()" class="w-full bg-gradient-to-r from-green-500 to-teal-600 text-white py-3 rounded-xl font-medium hover:from-green-600 hover:to-teal-700 transition-all duration-200">
                            <i class="fas fa-calculator mr-2"></i>Demander un Devis
                        </button>
                        <button onclick="ouvrirSupport()" class="w-full bg-gradient-to-r from-orange-500 to-red-600 text-white py-3 rounded-xl font-medium hover:from-orange-600 hover:to-red-700 transition-all duration-200">
                            <i class="fas fa-headset mr-2"></i>Support Client
                        </button>
                    </div>
                </div>
                
                <!-- Satisfaction client -->
                <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-bold mb-2">Votre Satisfaction</h3>
                    <p class="text-sm opacity-90 mb-4">Notez votre expérience globale</p>
                    <div class="flex justify-center space-x-1 mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-2xl {{ $i <= 4 ? '' : 'opacity-50' }}"></i>
                        @endfor
                    </div>
                    <button onclick="laisserAvis()" class="w-full bg-white text-orange-600 py-2 rounded-lg font-medium hover:bg-orange-50 transition-colors">
                        Laisser un Avis
                    </button>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Chat widget flottant -->
    <div class="fixed bottom-6 right-6 z-50" x-data="{ chatOpen: false }">
        <button @click="chatOpen = !chatOpen" class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full shadow-lg flex items-center justify-center text-white hover:from-blue-600 hover:to-purple-700 transform hover:scale-110 transition-all duration-200 pulse-glow">
            <i class="fas fa-comments text-xl" x-show="!chatOpen"></i>
            <i class="fas fa-times text-xl" x-show="chatOpen"></i>
        </button>
        
        <div x-show="chatOpen" x-transition class="absolute bottom-20 right-0 w-80 bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-4 text-white">
                <h3 class="font-bold">Chat Support</h3>
                @if($commercialPrincipal)
                    <p class="text-sm opacity-90">{{ $commercialPrincipal->name }} est en ligne</p>
                @else
                    <p class="text-sm opacity-90">Support client disponible</p>
                @endif
            </div>
            <div class="h-64 p-4 overflow-y-auto bg-gray-50">
                <div class="space-y-3">
                    <div class="flex items-start space-x-2">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm">
                            {{ $commercialPrincipal ? substr($commercialPrincipal->name, 0, 1) : 'S' }}
                        </div>
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-sm">Bonjour {{ Auth::user()->name }} ! Comment puis-je vous aider aujourd'hui ?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t">
                <div class="flex space-x-2">
                    <input type="text" placeholder="Tapez votre message..." class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de demande de devis -->
    <div x-data="{ devisOpen: false }" @devis-modal.window="devisOpen = true">
        <div x-show="devisOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" x-transition>
            <div @click.away="devisOpen = false" class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold">Demande de Devis</h2>
                        <button @click="devisOpen = false" class="text-white hover:text-gray-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <p class="mt-2 opacity-90">Décrivez-nous votre projet, nous vous recontacterons rapidement</p>
                </div>
                
                <form action="#" method="POST" class="p-6 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type de projet</label>
                            <select name="type_projet" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Sélectionner...</option>
                                <option value="cuisine">Cuisine</option>
                                <option value="salle_bain">Salle de bain</option>
                                <option value="extension">Extension</option>
                                <option value="renovation">Rénovation complète</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Budget estimé</label>
                            <select name="budget_estime" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Sélectionner...</option>
                                <option value="moins_10k">Moins de 10 000€</option>
                                <option value="10k_25k">10 000€ - 25 000€</option>
                                <option value="25k_50k">25 000€ - 50 000€</option>
                                <option value="50k_100k">50 000€ - 100 000€</option>
                                <option value="plus_100k">Plus de 100 000€</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description du projet</label>
                        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Décrivez votre projet en détail..." required></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date souhaitée de début</label>
                            <input type="date" name="date_debut_souhaitee" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Délai préféré</label>
                            <select name="delai_prefere" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="flexible">Flexible</option>
                                <option value="moins_1_mois">Moins de 1 mois</option>
                                <option value="1_3_mois">1-3 mois</option>
                                <option value="3_6_mois">3-6 mois</option>
                                <option value="plus_6_mois">Plus de 6 mois</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button type="button" @click="devisOpen = false" class="flex-1 border border-gray-300 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-xl font-medium hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                            Envoyer la Demande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Variables globales
let currentCommercialId = null;
let currentChantierId = null;

// Animation du compteur de progression
function animateProgress() {
    const progressBars = document.querySelectorAll('[data-progress]');
    progressBars.forEach(bar => {
        const targetWidth = bar.dataset.progress;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = targetWidth + '%';
        }, 500);
    });
}

// Fonction pour ouvrir le modal de devis
function openDevisModal() {
    window.dispatchEvent(new CustomEvent('devis-modal'));
}

// Contacter le commercial
function contacterCommercial(commercialId) {
    currentCommercialId = commercialId;
    
    // Afficher un modal de contact ou rediriger
    if (confirm('Voulez-vous appeler votre commercial maintenant ?')) {
        // Ici vous pouvez ajouter la logique d'appel
        showNotification('Mise en relation avec votre commercial...', 'info');
    }
}

// Voir les documents d'un chantier
function voirDocuments(chantierId) {
    window.location.href = `/chantiers/${chantierId}#documents`;
}

// Voir toutes les étapes
function voirToutesEtapes(chantierId) {
    window.location.href = `/chantiers/${chantierId}#etapes`;
}

// Noter un projet
function noterProjet(chantierId) {
    currentChantierId = chantierId;
    // Ouvrir un modal de notation
    showNotification('Fonction de notation en développement', 'info');
}

// Planifier un RDV
function planifierRdv(commercialId) {
    showNotification('Redirection vers le calendrier de rendez-vous...', 'info');
    // Ici vous pouvez intégrer un système de prise de RDV
}

// Demander un devis
function demanderDevis() {
    openDevisModal();
}

// Ouvrir le support
function ouvrirSupport() {
    showNotification('Ouverture du chat support...', 'info');
}

// Laisser un avis
function laisserAvis() {
    showNotification('Redirection vers la page d\'avis...', 'info');
}

// Système de notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 bg-white rounded-lg shadow-lg p-4 border-l-4 ${
        type === 'success' ? 'border-green-500' : 
        type === 'warning' ? 'border-yellow-500' : 
        type === 'error' ? 'border-red-500' : 'border-blue-500'
    } transform translate-x-full transition-transform duration-300`;
    
    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <i class="fas ${
                    type === 'success' ? 'fa-check-circle text-green-500' : 
                    type === 'warning' ? 'fa-exclamation-triangle text-yellow-500' : 
                    type === 'error' ? 'fa-times-circle text-red-500' : 'fa-info-circle text-blue-500'
                }"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animation d'entrée
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto-suppression après 5 secondes
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Initialisation des animations au chargement
document.addEventListener('DOMContentLoaded', function() {
    animateProgress();
    
    // Animation des cartes au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observer toutes les cartes de projet
    document.querySelectorAll('.project-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease-out';
        observer.observe(card);
    });
    
    // Simulation de notifications en temps réel
    setTimeout(() => {
        @if($mes_chantiers->where('statut', 'en_cours')->count() > 0)
            showNotification('Nouvelle étape terminée sur votre projet !', 'success');
        @endif
    }, 3000);
});

// Mise à jour des données en temps réel (simulation)
function updateProjectProgress() {
    // Vérification des mises à jour via AJAX
    fetch('/api/dashboard/progress')
        .then(response => response.json())
        .then(data => {
            if (data.updates) {
                data.updates.forEach(update => {
                    showNotification(update.message, update.type);
                });
            }
        })
        .catch(error => {
            console.log('Erreur lors de la vérification des mises à jour:', error);
        });
}

// Vérification des mises à jour toutes les 30 secondes
setInterval(updateProjectProgress, 30000);

// Effet de parallaxe sur les éléments flottants
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const parallaxElements = document.querySelectorAll('.floating-animation');
    
    parallaxElements.forEach((element, index) => {
        const speed = 0.1 + (index * 0.05);
        element.style.transform = `translateY(${scrolled * speed}px)`;
    });
});

// Gestion des formulaires AJAX
document.addEventListener('submit', function(e) {
    const form = e.target;
    
    // Formulaire de demande de devis
    if (form.querySelector('select[name="type_projet"]')) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch('{{ route("devis.store") ?? "#" }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Demande de devis envoyée avec succès !', 'success');
                form.reset();
                // Fermer le modal
                document.querySelector('[x-data]').__x.$data.devisOpen = false;
            } else {
                showNotification('Erreur lors de l\'envoi de la demande', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur lors de l\'envoi de la demande', 'error');
        });
    }
});

// Auto-refresh des notifications
function refreshNotifications() {
    fetch('/api/notifications/count')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.notification-badge');
            if (badge && data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'flex';
            } else if (badge) {
                badge.style.display = 'none';
            }
        })
        .catch(error => {
            console.log('Erreur lors du rafraîchissement des notifications:', error);
        });
}

// Rafraîchir les notifications toutes les minutes
setInterval(refreshNotifications, 60000);

console.log('Dashboard Client Personnalisé chargé avec succès ! 🎉');
</script>
@endpush