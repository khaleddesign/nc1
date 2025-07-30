@extends('layouts.app')

@section('title', $chantier->titre)

@section('content')
<div class="min-h-screen bg-slate-50">
    <!-- Zone principale avec margin pour sidebar -->
    <main class="flex-1 ml-0 lg:ml-280">
        <!-- Header collant moderne -->
        <header class="sticky top-0 bg-white/80 backdrop-blur-lg shadow-soft z-10 px-6 py-3 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <!-- Breadcrumb moderne -->
                <nav class="flex items-center space-x-2 text-sm">
                    <a href="{{ route('chantiers.index') }}" 
                       class="text-slate-500 hover:text-indigo-600 transition-colors duration-200 font-medium">
                        Chantiers
                    </a>
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-slate-900 font-semibold">{{ $chantier->titre }}</span>
                </nav>

                <!-- Actions header -->
                <div class="flex items-center space-x-3">
                    <!-- Bouton Message moderne -->
                    <button onclick="openModal('modal-message')" 
                            class="btn-outline-primary btn-sm hover-lift">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Message
                    </button>

                    @can('update', $chantier)
                        <a href="{{ route('chantiers.edit', $chantier) }}" 
                           class="btn-primary btn-sm hover-lift">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Modifier
                        </a>
                    @endcan
                </div>
            </div>
        </header>

        <!-- Contenu principal avec espacements optimisés -->
        <div class="p-6 space-y-6">
            <!-- En-tête du chantier - Card moderne compacte -->
            <div class="card card-elevated animate-fade-in-up">
                <!-- Header avec gradient -->
                <div class="card-header">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-3">
                                <!-- Icône du chantier avec gradient -->
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-glow">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h4M9 7h6m-6 4h6m-6 4h6" />
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-slate-900 gradient-text">{{ $chantier->titre }}</h1>
                                    <p class="text-slate-600 text-sm mt-1">Créé le {{ $chantier->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            <!-- Badges de statut modernes -->
                            <div class="flex items-center gap-3">
                                <span class="chantier-status-{{ strtolower(str_replace(' ', '-', $chantier->getStatutTexte())) }}">
                                    <div class="w-2 h-2 rounded-full bg-current"></div>
                                    {{ $chantier->getStatutTexte() }}
                                </span>
                                @if ($chantier->isEnRetard())
                                    <span class="badge-danger animate-pulse">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                        En retard
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations principales en grid moderne compacte -->
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <!-- Client -->
                        <div class="group">
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-indigo-50 transition-all duration-200 hover-lift">
                                <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Client</h3>
                                    <p class="text-sm font-bold text-slate-900">{{ $chantier->client->name }}</p>
                                    @if ($chantier->client->telephone)
                                        <p class="text-xs text-slate-500">{{ $chantier->client->telephone }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Commercial -->
                        <div class="group">
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-purple-50 transition-all duration-200 hover-lift">
                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6M8 8v6a2 2 0 002 2h4a2 2 0 002-2V8M8 8V6a2 2 0 012-2h4a2 2 0 012 2v2" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Commercial</h3>
                                    <p class="text-sm font-bold text-slate-900">{{ $chantier->commercial->name }}</p>
                                    @if ($chantier->commercial->telephone)
                                        <p class="text-xs text-slate-500">{{ $chantier->commercial->telephone }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Dates -->
                        @if ($chantier->date_debut)
                            <div class="group">
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 transition-all duration-200 hover-lift">
                                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Dates</h3>
                                        <p class="text-sm font-bold text-slate-900">{{ $chantier->date_debut->format('d/m/Y') }}</p>
                                        @if ($chantier->date_fin_prevue)
                                            <p class="text-xs {{ $chantier->getRetardClass() }}">
                                                au {{ $chantier->date_fin_prevue->format('d/m/Y') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Budget -->
                        @if ($chantier->budget)
                            <div class="group">
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-amber-50 transition-all duration-200 hover-lift">
                                    <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Budget</h3>
                                        <p class="text-sm font-bold text-slate-900">{{ number_format($chantier->budget, 0, ',', ' ') }} €</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Description compacte -->
                    @if ($chantier->description)
                        <div class="p-4 bg-gradient-to-r from-slate-50 to-indigo-50 rounded-xl border border-slate-200 mb-6">
                            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Description</h3>
                            <p class="text-slate-700 text-sm leading-relaxed">{{ $chantier->description }}</p>
                        </div>
                    @endif

                    <!-- Barre de progression moderne compacte -->
                    <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-soft">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-sm font-semibold text-slate-900">Avancement global</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-bold gradient-text">{{ number_format($chantier->avancement_global, 1) }}%</span>
                                <div class="w-6 h-6 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar-primary" style="width: {{ $chantier->avancement_global }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Photos moderne compacte -->
            <div class="card card-elevated animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-slate-900">Photos du projet</h2>
                            <span class="badge-primary" id="photos-count">{{ $chantier->photos_count ?? 0 }}</span>
                        </div>
                        <button onclick="openModal('modal-upload-photos')" class="btn-primary btn-sm hover-lift">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Ajouter
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div id="galerie-photos">
                        @if ($chantier->hasPhotos())
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Photo principale -->
                                <div class="md:col-span-2 relative group cursor-pointer hover-lift" onclick="ouvrirLightbox(0)">
                                    <img src="{{ asset($chantier->photo_couverture->thumbnail_url) }}" 
                                         alt="Photo principale"
                                         class="w-full h-48 object-cover rounded-xl shadow-medium group-hover:shadow-strong transition-all duration-300">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Mini-photos en grille -->
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach ($chantier->photos_recentes->skip(1)->take(3) as $index => $photo)
                                        <div class="relative group cursor-pointer hover-lift" onclick="ouvrirLightbox({{ $index + 1 }})">
                                            <img src="{{ asset($photo->thumbnail_url) }}" 
                                                 alt="Photo {{ $index + 2 }}"
                                                 class="w-full h-20 object-cover rounded-lg shadow-soft group-hover:shadow-medium transition-all duration-300">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 rounded-lg"></div>
                                        </div>
                                    @endforeach

                                    @if ($chantier->photos_count > 4)
                                        <div class="relative cursor-pointer bg-gradient-to-br from-slate-700 to-slate-900 h-20 rounded-lg flex items-center justify-center text-white font-bold text-sm hover-lift hover-glow"
                                             onclick="voirToutesPhotos()">
                                            +{{ $chantier->photos_count - 4 }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-slate-900 mb-2">Aucune photo</h3>
                                <p class="text-slate-500 text-sm mb-4">Commencez par ajouter des photos de ce chantier.</p>
                                <button onclick="openModal('modal-upload-photos')" class="btn-primary btn-sm hover-lift">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Ajouter des photos
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Grid principal : Étapes + Sidebar avec espacement optimisé -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Colonne principale : Étapes + Commentaires -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Étapes modernes compactes -->
                    <div class="card card-elevated animate-fade-in-up" style="animation-delay: 0.2s">
                        <div class="card-header">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h2 class="text-lg font-semibold text-slate-900">Étapes du projet</h2>
                                </div>
                                @can('update', $chantier)
                                    <button onclick="openModal('modal-nouvelle-etape')" class="btn-primary btn-sm hover-lift">
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Ajouter
                                    </button>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body">
                            @forelse($chantier->etapes as $etape)
                                <div class="etape-item-{{ $etape->terminee ? 'completed' : ($etape->pourcentage > 0 ? 'in-progress' : 'pending') }} mb-4 last:mb-0 p-4 rounded-xl transition-all duration-300 hover-lift">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <!-- Icône de statut -->
                                                <div class="w-6 h-6 rounded-full flex items-center justify-center
                                                    {{ $etape->terminee ? 'bg-emerald-100 text-emerald-600' : ($etape->pourcentage > 0 ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-400') }}">
                                                    @if($etape->terminee)
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    @elseif($etape->pourcentage > 0)
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <h4 class="text-base font-semibold text-slate-900">{{ $etape->nom }}</h4>
                                            </div>

                                            @if ($etape->description)
                                                <p class="text-slate-600 text-sm mb-3 ml-9">{{ $etape->description }}</p>
                                            @endif

                                            <!-- Dates et informations compactes -->
                                            <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 ml-9 mb-3">
                                                @if ($etape->date_debut)
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        <span>{{ $etape->date_debut->format('d/m/Y') }}</span>
                                                    </div>
                                                @endif
                                                @if ($etape->date_fin_prevue)
                                                    <div class="flex items-center gap-1 {{ $etape->isEnRetard() ? 'text-red-600 font-semibold' : '' }}">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span>{{ $etape->date_fin_prevue->format('d/m/Y') }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Barre de progression moderne compacte -->
                                            <div class="ml-9">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-xs font-medium text-slate-700">Progression</span>
                                                    <span class="text-xs font-bold text-indigo-600">{{ $etape->pourcentage }}%</span>
                                                </div>
                                                <div class="progress h-2">
                                                    <div class="progress-bar-primary h-2" style="width: {{ $etape->pourcentage }}%"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Actions compactes -->
                                        @can('update', $chantier)
                                            <div class="flex items-center gap-1 ml-4">
                                                <button class="btn-outline btn-sm hover-lift p-1"
                                                    onclick="modifierProgressionEtape({{ $etape->id }}, {{ $etape->pourcentage }})"
                                                    title="Modifier progression">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                    </svg>
                                                </button>

                                                <button class="btn-outline btn-sm hover-lift p-1"
                                                    onclick="modifierEtape({{ $etape->toJson() }})"
                                                    title="Modifier l'étape">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </button>

                                                <form method="POST" action="{{ route('etapes.destroy', [$chantier, $etape]) }}" class="inline"
                                                    onsubmit="return confirm('Supprimer cette étape : {{ $etape->nom }} ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-outline btn-sm text-red-600 border-red-200 hover:bg-red-50 hover-lift p-1" title="Supprimer">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-slate-900 mb-2">Aucune étape</h3>
                                    <p class="text-slate-500 text-sm mb-4">Commencez par ajouter une première étape à ce chantier.</p>
                                    @can('update', $chantier)
                                        <button onclick="openModal('modal-nouvelle-etape')" class="btn-primary btn-sm hover-lift">
                                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Ajouter une étape
                                        </button>
                                    @endcan
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Commentaires modernes compacts -->
                    <div class="card card-elevated animate-fade-in-up" style="animation-delay: 0.3s">
                        <div class="card-header">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-slate-900">Commentaires</h2>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Formulaire nouveau commentaire compact -->
                            <form method="POST" action="{{ route('commentaires.store', $chantier) }}" class="mb-6">
                                @csrf
                                <div class="space-y-3">
                                    <textarea name="contenu" id="contenu" rows="3" class="form-textarea"
                                        placeholder="Ajouter un commentaire..." required></textarea>
                                    <div class="flex justify-end">
                                        <button type="submit" class="btn-primary btn-sm hover-lift">
                                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                            Publier
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Liste des commentaires compacte -->
                            <div class="space-y-4">
                                @forelse($chantier->commentaires as $commentaire)
                                    <div class="flex gap-3 p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors duration-200">
                                        <!-- Avatar compact -->
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-soft">
                                                <span class="text-xs font-semibold text-white">
                                                    {{ substr($commentaire->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Contenu compact -->
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="font-semibold text-slate-900 text-sm">{{ $commentaire->user->name }}</h4>
                                                <span class="badge-{{ $commentaire->user->role === 'client' ? 'primary' : 'warning' }} text-xs">
                                                    {{ ucfirst($commentaire->user->role) }}
                                                </span>
                                                <span class="text-xs text-slate-500">{{ $commentaire->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-slate-700 text-sm leading-relaxed">{{ $commentaire->contenu }}</p>

                                            @if (Auth::id() === $commentaire->user_id || Auth::user()->isAdmin())
                                                <div class="mt-2">
                                                    <form method="POST" action="{{ route('commentaires.destroy', $commentaire) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-xs text-red-600 hover:text-red-800 transition-colors"
                                                            onclick="return confirm('Supprimer ce commentaire ?')">
                                                            Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                        </div>
                                        <p class="text-slate-500 text-sm italic">Aucun commentaire pour le moment.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar moderne compacte -->
                <div class="space-y-6">
                    <!-- Documents compacts -->
                    <div class="card card-elevated animate-fade-in-up" style="animation-delay: 0.4s">
                        <div class="card-header">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h2 class="text-base font-semibold text-slate-900">Documents</h2>
                                </div>
                                @can('update', $chantier)
                                    <button onclick="openModal('modal-upload-document')" class="btn-primary btn-sm hover-lift">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        Upload
                                    </button>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body">
                            @forelse($chantier->documents as $document)
                                <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50 transition-colors duration-200 border-b border-slate-100 last:border-b-0">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-lg bg-slate-100 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                @if ($document->isImage())
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                @endif
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-slate-900">{{ $document->nom_original }}</p>
                                            <p class="text-xs text-slate-500">{{ $document->getTailleFormatee() }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('documents.download', $document) }}"
                                            class="p-1 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded transition-all duration-200" 
                                            title="Télécharger">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </a>
                                        @can('update', $chantier)
                                            <form method="POST" action="{{ route('documents.destroy', $document) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-all duration-200" 
                                                    title="Supprimer" onclick="return confirm('Supprimer ce document ?')">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-xs text-slate-500">Aucun document</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Notes compactes -->
                    @if ($chantier->notes)
                        <div class="card card-elevated animate-fade-in-up" style="animation-delay: 0.5s">
                            <div class="card-header">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                    <h2 class="text-base font-semibold text-slate-900">Notes</h2>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-wrap">{{ $chantier->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modales modernes compactes -->
@can('update', $chantier)
    <!-- Modal - Nouvelle étape -->
    <div id="modal-nouvelle-etape" class="modal-overlay hidden" onclick="if(event.target === this) closeModal('modal-nouvelle-etape')">
        <div class="modal-content max-w-md">
            <div class="modal-header">
                <h3 class="text-lg font-semibold text-slate-900">Nouvelle étape</h3>
                <button onclick="closeModal('modal-nouvelle-etape')" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('etapes.store', $chantier) }}">
                @csrf
                <div class="modal-body space-y-4">
                    <div>
                        <label for="nom" class="form-label">Nom de l'étape *</label>
                        <input type="text" name="nom" id="nom" class="form-input" required>
                    </div>

                    <div>
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="3" class="form-textarea"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="ordre" class="form-label">Ordre *</label>
                            <input type="number" name="ordre" id="ordre" value="{{ $chantier->etapes->count() + 1 }}" 
                                class="form-input" required min="1">
                        </div>
                        <div>
                            <label for="pourcentage" class="form-label">Avancement (%)</label>
                            <input type="number" name="pourcentage" id="pourcentage" value="0" 
                                class="form-input" min="0" max="100">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="date_debut" class="form-label">Date début</label>
                            <input type="date" name="date_debut" id="date_debut" class="form-input">
                        </div>
                        <div>
                            <label for="date_fin_prevue" class="form-label">Date fin prévue</label>
                            <input type="date" name="date_fin_prevue" id="date_fin_prevue" class="form-input">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeModal('modal-nouvelle-etape')" class="btn-outline">Annuler</button>
                    <button type="submit" class="btn-primary">Créer l'étape</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal - Upload document -->
    <div id="modal-upload-document" class="modal-overlay hidden" onclick="if(event.target === this) closeModal('modal-upload-document')">
        <div class="modal-content max-w-md">
            <div class="modal-header">
                <h3 class="text-lg font-semibold text-slate-900">Ajouter des documents</h3>
                <button onclick="closeModal('modal-upload-document')" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('documents.store', $chantier) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body space-y-4">
                    <div>
                        <label for="fichiers" class="form-label">Fichiers *</label>
                        <input type="file" name="fichiers[]" id="fichiers" class="form-input" multiple required
                            accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx">
                        <p class="form-help">Formats acceptés : JPG, PNG, PDF, DOC, XLS (max 10MB par fichier)</p>
                    </div>

                    <div>
                        <label for="type" class="form-label">Type de document</label>
                        <select name="type" id="type" class="form-select">
                            <option value="document">Document général</option>
                            <option value="image">Image/Photo</option>
                            <option value="plan">Plan</option>
                            <option value="facture">Facture</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <div>
                        <label for="description_doc" class="form-label">Description</label>
                        <textarea name="description" id="description_doc" rows="3" class="form-textarea"
                            placeholder="Description optionnelle des documents..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeModal('modal-upload-document')" class="btn-outline">Annuler</button>
                    <button type="submit" class="btn-primary">Uploader</button>
                </div>
            </form>
        </div>
    </div>
@endcan

<!-- Modal - Upload photos -->
<div id="modal-upload-photos" class="modal-overlay hidden" onclick="if(event.target === this) closeModal('modal-upload-photos')">
    <div class="modal-content max-w-lg">
        <div class="modal-header">
            <h3 class="text-lg font-semibold text-slate-900">Ajouter des photos</h3>
            <button onclick="closeModal('modal-upload-photos')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="form-upload-photos" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="chantier_id" value="{{ $chantier->id }}">

            <div class="modal-body space-y-4">
                <div>
                    <label for="photos" class="form-label">Photos *</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-indigo-400 hover:bg-indigo-50 transition-all duration-200"
                        id="drop-zone">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-slate-600">
                                <label for="photos"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Choisir des fichiers</span>
                                    <input id="photos" name="photos[]" type="file" multiple accept="image/*" class="sr-only">
                                </label>
                                <p class="pl-1">ou glisser-déposer</p>
                            </div>
                            <p class="text-xs text-slate-500">PNG, JPG, GIF jusqu'à 10MB par fichier</p>
                        </div>
                    </div>
                </div>

                <!-- Prévisualisation -->
                <div id="preview-photos" class="hidden">
                    <label class="form-label">Aperçu des photos sélectionnées</label>
                    <div id="preview-grid" class="grid grid-cols-3 gap-2 mt-2"></div>
                </div>

                <!-- Barre de progression -->
                <div id="upload-progress" class="hidden">
                    <div class="flex items-center justify-between text-sm text-slate-600 mb-2">
                        <span>Upload en cours...</span>
                        <span id="progress-text">0%</span>
                    </div>
                    <div class="progress">
                        <div id="progress-bar" class="progress-bar-primary" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-upload-photos')" class="btn-outline" id="btn-cancel">Annuler</button>
                <button type="submit" class="btn-primary" id="btn-upload" disabled>
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Uploader <span id="file-count">(0)</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal - Message -->
<div id="modal-message" class="modal-overlay hidden" onclick="if(event.target === this) closeModal('modal-message')">
    <div class="modal-content max-w-lg">
        <div class="modal-header">
            <h3 class="text-lg font-semibold text-slate-900">Envoyer un message</h3>
            <button onclick="closeModal('modal-message')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('messages.store') }}">
            @csrf
            <input type="hidden" name="chantier_id" value="{{ $chantier->id }}">
            <input type="hidden" name="recipient_id"
                value="{{ Auth::user()->isClient() ? $chantier->commercial->id : $chantier->client->id }}">

            <div class="modal-body space-y-4">
                <div>
                    <label class="form-label">Destinataire</label>
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                <span class="text-sm font-semibold text-white">
                                    {{ substr(Auth::user()->isClient() ? $chantier->commercial->name : $chantier->client->name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">
                                    {{ Auth::user()->isClient() ? $chantier->commercial->name : $chantier->client->name }}
                                </p>
                                <p class="text-sm text-slate-500">
                                    {{ Auth::user()->isClient() ? 'Commercial' : 'Client' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="subject" class="form-label">Sujet *</label>
                    <input type="text" name="subject" id="subject" class="form-input"
                        value="À propos du chantier : {{ $chantier->titre }}" required>
                </div>

                <div>
                    <label for="body" class="form-label">Message *</label>
                    <textarea name="body" id="body" rows="6" class="form-textarea" 
                        placeholder="Votre message..." required></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-message')" class="btn-outline">Annuler</button>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Envoyer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal - Modifier Étape -->
<div id="modal-modifier-etape" class="modal-overlay hidden" onclick="if(event.target === this) closeModal('modal-modifier-etape')">
    <div class="modal-content max-w-2xl">
        <div class="modal-header">
            <h3 class="text-lg font-semibold text-slate-900">Modifier l'étape</h3>
            <button onclick="closeModal('modal-modifier-etape')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" id="form-modifier-etape">
            @csrf
            @method('PUT')
            <div class="modal-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_nom" class="form-label">Nom de l'étape *</label>
                        <input type="text" name="nom" id="edit_nom" class="form-input" required>
                    </div>
                    <div>
                        <label for="edit_ordre" class="form-label">Ordre *</label>
                        <input type="number" name="ordre" id="edit_ordre" class="form-input" required min="1">
                    </div>
                </div>

                <div>
                    <label for="edit_description" class="form-label">Description</label>
                    <textarea name="description" id="edit_description" rows="3" class="form-textarea"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="edit_date_debut" class="form-label">Date début</label>
                        <input type="date" name="date_debut" id="edit_date_debut" class="form-input">
                    </div>
                    <div>
                        <label for="edit_date_fin_prevue" class="form-label">Date fin prévue</label>
                        <input type="date" name="date_fin_prevue" id="edit_date_fin_prevue" class="form-input">
                    </div>
                    <div>
                        <label for="edit_date_fin_effective" class="form-label">Date fin effective</label>
                        <input type="date" name="date_fin_effective" id="edit_date_fin_effective" class="form-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_pourcentage" class="form-label">Pourcentage d'avancement</label>
                        <input type="range" class="w-full h-3 bg-slate-200 rounded-lg appearance-none cursor-pointer"
                            name="pourcentage" id="edit_pourcentage" min="0" max="100" step="5"
                            oninput="document.getElementById('edit_pourcentage_value').textContent = this.value + '%'">
                        <div class="text-center mt-3">
                            <span id="edit_pourcentage_value" class="badge-primary text-lg font-bold">0%</span>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex items-center h-5">
                            <input class="form-checkbox" type="checkbox" name="terminee" id="edit_terminee" value="1">
                        </div>
                        <div class="ml-3">
                            <label for="edit_terminee" class="font-semibold text-slate-700">
                                Marquer comme terminée
                            </label>
                            <p class="text-slate-500 text-sm">Cela mettra automatiquement la progression à 100%</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="edit_notes" class="form-label">Notes</label>
                    <textarea name="notes" id="edit_notes" rows="2" class="form-textarea"
                        placeholder="Notes additionnelles..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-modifier-etape')" class="btn-outline">Annuler</button>
                <button type="submit" class="btn-primary">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal - Progression Rapide -->
<div id="modal-progression-etape" class="modal-overlay hidden" onclick="if(event.target === this) closeModal('modal-progression-etape')">
    <div class="modal-content max-w-md">
        <div class="modal-header">
            <h3 class="text-lg font-semibold text-slate-900">Modifier l'avancement</h3>
            <button onclick="closeModal('modal-progression-etape')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" id="form-progression-etape">
            @csrf
            @method('PUT')
            <div class="modal-body space-y-4">
                <div>
                    <label for="quick_pourcentage" class="form-label">Pourcentage d'avancement</label>
                    <input type="range" class="w-full h-4 bg-slate-200 rounded-lg appearance-none cursor-pointer"
                        name="pourcentage" id="quick_pourcentage" min="0" max="100" step="5"
                        oninput="document.getElementById('quick_pourcentage_value').textContent = this.value + '%'">
                    <div class="text-center mt-4">
                        <span id="quick_pourcentage_value" class="badge-primary text-2xl font-bold">0%</span>
                    </div>
                </div>

                <div class="grid grid-cols-5 gap-2">
                    <button type="button" class="btn-outline btn-sm" onclick="setProgression(0)">0%</button>
                    <button type="button" class="btn-outline btn-sm" onclick="setProgression(25)">25%</button>
                    <button type="button" class="btn-outline btn-sm" onclick="setProgression(50)">50%</button>
                    <button type="button" class="btn-outline btn-sm" onclick="setProgression(75)">75%</button>
                    <button type="button" class="btn-success btn-sm" onclick="setProgression(100)">100%</button>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-progression-etape')" class="btn-outline">Annuler</button>
                <button type="submit" class="btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Variables globales pour les étapes
let etapeEnCours = null;

// Fonction pour modifier une étape
function modifierEtape(etapeData) {
    console.log('Modification étape:', etapeData);
    etapeEnCours = etapeData;

    const form = document.getElementById('form-modifier-etape');
    form.action = "/chantiers/{{ $chantier->id }}/etapes/" + etapeData.id;

    // Remplir les champs
    document.getElementById('edit_nom').value = etapeData.nom || '';
    document.getElementById('edit_ordre').value = etapeData.ordre || '';
    document.getElementById('edit_description').value = etapeData.description || '';
    document.getElementById('edit_date_debut').value = etapeData.date_debut || '';
    document.getElementById('edit_date_fin_prevue').value = etapeData.date_fin_prevue || '';
    document.getElementById('edit_date_fin_effective').value = etapeData.date_fin_effective || '';
    document.getElementById('edit_pourcentage').value = etapeData.pourcentage || 0;
    document.getElementById('edit_terminee').checked = etapeData.terminee || false;
    document.getElementById('edit_notes').value = etapeData.notes || '';

    // Mettre à jour l'affichage du pourcentage
    document.getElementById('edit_pourcentage_value').textContent = (etapeData.pourcentage || 0) + '%';

    openModal('modal-modifier-etape');
}

// Fonction pour modifier rapidement la progression
function modifierProgressionEtape(etapeId, currentProgress) {
    etapeEnCours = { id: etapeId };

    const form = document.getElementById('form-progression-etape');
    form.action = "/chantiers/{{ $chantier->id }}/etapes/" + etapeId + "/progress";

    document.getElementById('quick_pourcentage').value = currentProgress;
    document.getElementById('quick_pourcentage_value').textContent = currentProgress + '%';

    openModal('modal-progression-etape');
}

// Fonction pour définir un pourcentage rapide
function setProgression(value) {
    document.getElementById('quick_pourcentage').value = value;
    document.getElementById('quick_pourcentage_value').textContent = value + '%';
}

// Variables globales pour la galerie
let photosData = [];
let currentPhotoIndex = 0;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    initializePhotoGallery();
    initializeExistingModals();
});

function initializeExistingModals() {
    // Fermer modal avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal-overlay:not(.hidden)');
            modals.forEach(modal => {
                modal.classList.add('hidden');
            });
            document.body.classList.remove('overflow-hidden');
        }
    });

    // Événement pour checkbox "terminée"
    document.getElementById('edit_terminee')?.addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('edit_pourcentage').value = 100;
            document.getElementById('edit_pourcentage_value').textContent = '100%';
        }
    });
}

function initializePhotoGallery() {
    chargerPhotos();
}

async function chargerPhotos() {
    const chantierId = "{{ $chantier->id }}";
    try {
        const response = await fetch(`/api/v2/chantiers/${chantierId}/photos`);
        const data = await response.json();
        if (data.success) {
            photosData = data.photos;
            mettreAJourGalerie();
        }
    } catch (error) {
        console.error('Erreur chargement photos:', error);
    }
}

function mettreAJourGalerie() {
    const photosCount = document.getElementById('photos-count');
    if (photosCount) {
        photosCount.textContent = photosData.length;
    }
}

function ouvrirLightbox(index) {
    currentPhotoIndex = index;
    // Logique lightbox simplifiée pour l'exemple
    console.log('Lightbox photo index:', index);
}

function voirToutesPhotos() {
    openModal('modal-toutes-photos');
}

console.log('Chantier show optimisé chargé avec succès');
</script>
@endpush
@endsection