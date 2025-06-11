@extends('layouts.app')

@section('title', 'Chantiers')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- Header avec dégradé -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18M6.75 9h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-3.75-16.5h3.75A2.25 2.25 0 0121 6.75v12a2.25 2.25 0 01-2.25 2.25h-3.75M16.5 7.5V21a.75.75 0 01-.75.75M6 7.5V21a.75.75 0 01-.75.75" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-6">
                            <h1 class="text-3xl font-bold text-white sm:text-4xl">
                                Gestion des Chantiers
                            </h1>
                            <p class="mt-2 text-blue-100 text-lg">
                                Suivez et gérez tous vos projets en cours 🏗️
                            </p>
                        </div>
                    </div>
                </div>
                
                @can('create', App\Models\Chantier::class)
                    <div class="mt-6 flex space-x-3 md:mt-0 md:ml-4">
                        <a href="{{ route('chantiers.create') }}" 
                           class="inline-flex items-center px-6 py-3 border border-transparent rounded-full shadow-sm text-sm font-medium text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Nouveau chantier
                        </a>
                        <a href="{{ route('chantiers.calendrier') }}" 
                           class="inline-flex items-center px-6 py-3 border border-white/20 rounded-full shadow-sm text-sm font-medium text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                            </svg>
                            Calendrier
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistiques avec animations -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <!-- Total Chantiers -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium uppercase tracking-wide">Total Chantiers</p>
                            <p class="text-4xl font-bold text-white mt-2 counter" data-target="{{ $stats['total'] }}">0</p>
                            <div class="flex items-center mt-2">
                                <svg class="h-4 w-4 text-green-300 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                </svg>
                                <span class="text-green-200 text-sm">Tous projets</span>
                            </div>
                        </div>
                        <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18M6.75 9h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-3.75-16.5h3.75A2.25 2.25 0 0121 6.75v12a2.25 2.25 0 01-2.25 2.25h-3.75M16.5 7.5V21a.75.75 0 01-.75.75M6 7.5V21a.75.75 0 01-.75.75" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Planifiés -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-gray-500 to-gray-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-100 text-sm font-medium uppercase tracking-wide">Planifiés</p>
                            <p class="text-4xl font-bold text-white mt-2 counter" data-target="{{ $stats['planifies'] }}">0</p>
                            <div class="flex items-center mt-2">
                                <div class="h-2 w-2 bg-gray-300 rounded-full mr-2"></div>
                                <span class="text-gray-200 text-sm">À démarrer</span>
                            </div>
                        </div>
                        <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- En cours -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-amber-100 text-sm font-medium uppercase tracking-wide">En Cours</p>
                            <p class="text-4xl font-bold text-white mt-2 counter" data-target="{{ $stats['en_cours'] }}">0</p>
                            <div class="flex items-center mt-2">
                                <div class="h-2 w-2 bg-green-300 rounded-full mr-2 animate-pulse"></div>
                                <span class="text-amber-200 text-sm">Actifs</span>
                            </div>
                        </div>
                        <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="h-8 w-8 text-white animate-spin" style="animation-duration: 3s;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terminés -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-emerald-100 text-sm font-medium uppercase tracking-wide">Terminés</p>
                            <p class="text-4xl font-bold text-white mt-2 counter" data-target="{{ $stats['termines'] }}">0</p>
                            <div class="flex items-center mt-2">
                                <svg class="h-4 w-4 text-green-300 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-emerald-200 text-sm">Réussis</span>
                            </div>
                        </div>
                        <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et recherche modernisés -->
        <div class="bg-white rounded-2xl shadow-xl mb-8">
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    Filtres & Recherche
                </h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('chantiers.index') }}" x-data="{ hasFilters: false }" x-init="hasFilters = {{ request()->hasAny(['search', 'statut', 'commercial_id', 'client_id']) ? 'true' : 'false' }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <!-- Recherche -->
                        <div>
                            <label for="search" class="form-label">Recherche</label>
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       id="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Titre, description, client..."
                                       class="form-input pl-10"
                                       x-on:input="hasFilters = true">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div>
                            <label for="statut" class="form-label">Statut</label>
                            <select name="statut" id="statut" class="form-select" x-on:change="hasFilters = true">
                                <option value="">Tous les statuts</option>
                                <option value="planifie" {{ request('statut') === 'planifie' ? 'selected' : '' }}>📋 Planifié</option>
                                <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>🚧 En cours</option>
                                <option value="termine" {{ request('statut') === 'termine' ? 'selected' : '' }}>✅ Terminé</option>
                            </select>
                        </div>

                        @if($commerciaux->count() > 0)
                        <div>
                            <label for="commercial_id" class="form-label">Commercial</label>
                            <select name="commercial_id" id="commercial_id" class="form-select" x-on:change="hasFilters = true">
                                <option value="">Tous les commerciaux</option>
                                @foreach($commerciaux as $commercial)
                                    <option value="{{ $commercial->id }}" {{ request('commercial_id') == $commercial->id ? 'selected' : '' }}>
                                        {{ $commercial->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        @if($clients->count() > 0)
                        <div>
                            <label for="client_id" class="form-label">Client</label>
                            <select name="client_id" id="client_id" class="form-select" x-on:change="hasFilters = true">
                                <option value="">Tous les clients</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                        <div class="flex space-x-3">
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                                Filtrer
                            </button>
                            <a href="{{ route('chantiers.index') }}" 
                               class="btn-outline"
                               x-show="hasFilters">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Réinitialiser
                            </a>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-500">
                                {{ $chantiers->total() }} chantier(s) trouvé(s)
                            </span>
                            <a href="{{ route('chantiers.export', request()->query()) }}" class="btn-outline btn-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Exporter
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Grille des chantiers modernisée -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($chantiers as $chantier)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transform hover:scale-105 transition-all duration-300 chantier-card" 
                     x-data="{ 
                         progress: {{ $chantier->avancement_global }},
                         isHovered: false 
                     }"
                     @mouseenter="isHovered = true"
                     @mouseleave="isHovered = false">
                    
                    <!-- Header de la carte avec dégradé selon le statut -->
                    <div class="relative h-24 {{ match($chantier->statut) {
                        'planifie' => 'bg-gradient-to-r from-gray-400 to-gray-500',
                        'en_cours' => 'bg-gradient-to-r from-blue-500 to-blue-600',
                        'termine' => 'bg-gradient-to-r from-green-500 to-green-600',
                        default => 'bg-gradient-to-r from-gray-400 to-gray-500'
                    } }}">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                        <div class="relative p-4 h-full flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20 text-white">
                                        {{ $chantier->getStatutTexte() }}
                                    </span>
                                    @if($chantier->isEnRetard())
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-500 text-white animate-pulse">
                                            ⚠️ Retard
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    <div class="w-full bg-white/20 rounded-full h-2">
                                        <div class="bg-white h-2 rounded-full transition-all duration-1000 ease-out" 
                                             :style="`width: ${progress}%`"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center">
                                    @if($chantier->statut === 'termine')
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($chantier->statut === 'en_cours')
                                        <svg class="h-6 w-6 text-white animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                                        </svg>
                                    @else
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu de la carte -->
                    <div class="p-6">
                        <!-- Titre et description -->
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-1">
                                <a href="{{ route('chantiers.show', $chantier) }}" 
                                   class="hover:text-blue-600 transition-colors duration-200">
                                    {{ $chantier->titre }}
                                </a>
                            </h3>
                            @if($chantier->description)
                                <p class="text-gray-600 text-sm line-clamp-2">
                                    {{ $chantier->description }}
                                </p>
                            @endif
                        </div>

                        <!-- Informations principales -->
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-gray-900 font-medium">{{ $chantier->client->name }}</p>
                                    <p class="text-gray-500 text-xs">Client</p>
                                </div>
                            </div>

                            <div class="flex items-center text-sm">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-gray-900 font-medium">{{ $chantier->commercial->name }}</p>
                                    <p class="text-gray-500 text-xs">Commercial</p>
                                </div>
                            </div>

                            @if($chantier->date_debut && $chantier->date_fin_prevue)
                                <div class="flex items-center text-sm">
                                    <div class="flex-shrink-0 mr-3">
                                        <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-gray-900 font-medium">
                                            {{ $chantier->date_debut->format('d/m/Y') }} 
                                            <span class="mx-1">→</span> 
                                            <span class="{{ $chantier->isEnRetard() ? 'text-red-600 font-bold' : '' }}">
                                                {{ $chantier->date_fin_prevue->format('d/m/Y') }}
                                            </span>
                                        </p>
                                        <p class="text-gray-500 text-xs">
                                            @php
                                                $duration = $chantier->date_debut->diffInDays($chantier->date_fin_prevue);
                                            @endphp
                                            Durée: {{ $duration }} jour(s)
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if($chantier->budget)
                                <div class="flex items-center text-sm">
                                    <div class="flex-shrink-0 mr-3">
                                        <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-gray-900 font-medium">{{ number_format($chantier->budget, 0, ',', ' ') }} €</p>
                                        <p class="text-gray-500 text-xs">Budget total</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Progression et métadata -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Avancement global</span>
                                <span class="text-lg font-bold text-gray-900" x-text="`${progress}%`"></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="h-3 rounded-full transition-all duration-1000 ease-out {{ $chantier->getProgressBarColor() }}" 
                                     :style="`width: ${progress}%`"></div>
                            </div>
                            @if($chantier->etapes->count() > 0)
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>{{ $chantier->etapes->where('terminee', true)->count() }}/{{ $chantier->etapes->count() }} étapes</span>
                                    <span>{{ $chantier->etapes->where('terminee', false)->count() }} restantes</span>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <a href="{{ route('chantiers.show', $chantier) }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-xl transition-all duration-200 transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Voir détails
                            </a>
                            
                            @can('update', $chantier)
                                <div class="flex space-x-2">
                                    <a href="{{ route('chantiers.edit', $chantier) }}" 
                                       class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200" 
                                       title="Modifier">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>
                                    @can('delete', $chantier)
                                        <form method="POST" action="{{ route('chantiers.destroy', $chantier) }}" 
                                              class="inline-block"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce chantier ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200" 
                                                    title="Supprimer">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            @empty
                <!-- État vide modernisé -->
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                        <div class="mx-auto h-24 w-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mb-6">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18M6.75 9h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-3.75-16.5h3.75A2.25 2.25 0 0121 6.75v12a2.25 2.25 0 01-2.25 2.25h-3.75M16.5 7.5V21a.75.75 0 01-.75.75M6 7.5V21a.75.75 0 01-.75.75" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">
                            @if(request()->hasAny(['search', 'statut', 'commercial_id', 'client_id']))
                                Aucun résultat trouvé
                            @else
                                Aucun chantier pour le moment
                            @endif
                        </h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">
                            @if(request()->hasAny(['search', 'statut', 'commercial_id', 'client_id']))
                                Aucun chantier ne correspond aux critères de recherche sélectionnés. Essayez de modifier vos filtres.
                            @else
                                Commencez par créer votre premier chantier pour démarrer la gestion de vos projets.
                            @endif
                        </p>
                        @can('create', App\Models\Chantier::class)
                            @unless(request()->hasAny(['search', 'statut', 'commercial_id', 'client_id']))
                                <a href="{{ route('chantiers.create') }}" 
                                   class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-base font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    Créer mon premier chantier
                                </a>
                            @else
                                <a href="{{ route('chantiers.index') }}" 
                                   class="inline-flex items-center px-6 py-3 border border-blue-300 rounded-xl text-base font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    Réinitialiser les filtres
                                </a>
                            @endunless
                        @endcan
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination modernisée -->
        @if($chantiers->hasPages())
            <div class="mt-12 flex justify-center">
                <div class="bg-white rounded-2xl shadow-xl p-4">
                    {{ $chantiers->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = parseInt(counter.dataset.target) || 0;
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    counter.textContent = target;
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.floor(current);
                }
            }, 20);
        });
    }

    // Déclencher l'animation au chargement
    setTimeout(animateCounters, 500);

    // Auto-submit du formulaire de recherche après saisie
    const searchInput = document.getElementById('search');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 800);
        });
    }
    
    // Auto-submit pour les selects
    const selects = document.querySelectorAll('select[name="statut"], select[name="commercial_id"], select[name="client_id"]');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Animation des cartes au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationDelay = `${Math.random() * 0.3}s`;
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, observerOptions);

    // Observer toutes les cartes
    document.querySelectorAll('.chantier-card').forEach(card => {
        observer.observe(card);
    });

    // Effet de parallaxe léger sur le header
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const header = document.querySelector('.bg-gradient-to-r.from-blue-600');
        if (header) {
            header.style.transform = `translateY(${scrolled * 0.1}px)`;
        }
    });
});

// Fonction utilitaire pour copier dans le presse-papiers
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Lien copié dans le presse-papiers !', 'success');
    });
}

// Fonction de toast pour les notifications
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 max-w-sm p-4 rounded-xl shadow-lg transform transition-all duration-300 translate-x-full`;
    
    const bgColors = {
        'info': 'bg-blue-500 text-white',
        'success': 'bg-green-500 text-white',
        'warning': 'bg-yellow-500 text-white',
        'error': 'bg-red-500 text-white'
    };
    
    toast.classList.add(...(bgColors[type] || bgColors.info).split(' '));
    toast.innerHTML = `
        <div class="flex items-center">
            <span class="flex-1">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animation d'entrée
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Auto-suppression
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 4000);
}
</script>
@endpush

@push('styles')
<style>
/* Styles pour les cartes avec effet de survol */
.chantier-card {
    transition: all 0.3s ease;
}

.chantier-card:hover {
    transform: translateY(-8px) scale(1.02);
}

/* Animation de text truncation */
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Animation personnalisée pour l'entrée */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out both;
}

/* Effet de parallaxe fluide */
.parallax-header {
    transition: transform 0.1s ease-out;
}

/* Styles pour les barres de progression personnalisées */
.progress-bar {
    transition: width 1s ease-in-out;
}

/* Effet de survol sur les actions */
.action-hover {
    transition: all 0.2s ease;
}

.action-hover:hover {
    transform: scale(1.1);
}

/* Responsive design amélioré */
@media (max-width: 640px) {
    .chantier-card {
        margin-bottom: 1rem;
    }
    
    .chantier-card:hover {
        transform: none;
    }
}

/* Loading skeleton (pour les états de chargement futurs) */
.skeleton {
    background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
    background-size: 200% 100%;
    animation: shimmer 1.5s ease-in-out infinite;
}

@keyframes shimmer {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

/* Styles d'impression optimisés */
@media print {
    .no-print {
        display: none !important;
    }
    
    .chantier-card {
        break-inside: avoid;
        box-shadow: none !important;
        border: 1px solid #e5e7eb;
    }
    
    .bg-gradient-to-r,
    .bg-gradient-to-br {
        background: #f8fafc !important;
        color: #1f2937 !important;
    }
}
</style>
@endpush