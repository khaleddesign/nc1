@extends('layouts.app')

@section('title', 'Gestion des Devis')
@section('page-title', 'Gestion des Devis')
@section('page-subtitle', 'Vue d\'ensemble prospects et chantiers')

@section('content')
<div class="min-h-screen bg-slate-50" x-data="devisManager()" x-init="init()">
    
    {{-- 📊 HEADER AVEC STATISTIQUES TEMPS RÉEL --}}
    <div class="bg-white border-b border-slate-200 shadow-sm mb-8">
        <div class="px-6 py-8">
            <div class="max-w-7xl mx-auto">
                {{-- En-tête principal --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 gradient-text">
                            <svg class="w-8 h-8 inline mr-3 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125-1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            Gestion des Devis
                        </h1>
                        <p class="text-slate-600 mt-2">Pilotage unifié prospects et chantiers</p>
                    </div>
                    
                    {{-- 🆕 BOUTON CRÉATION ADAPTATIF --}}
                    @can('commercial-or-admin')
                    <div class="mt-4 sm:mt-0">
                        <div class="relative inline-block text-left" x-data="{ createOpen: false }">
                            <button @click="createOpen = !createOpen" 
                                    class="btn btn-primary hover-lift inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Nouveau Devis
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="createOpen" 
                                 @click.away="createOpen = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-lg border border-slate-200 py-2 z-50">
                                
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <h3 class="text-sm font-semibold text-slate-900">Type de devis</h3>
                                </div>
                                
                                <div class="py-2">
                                    <a href="{{ route('devis.create', ['type' => 'prospect']) }}" 
                                       class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                                        <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center mr-3">
                                            <span class="text-white text-lg">🎯</span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-semibold text-slate-900">Nouveau Prospect</div>
                                            <div class="text-xs text-slate-500">Créer un devis prospect sans chantier</div>
                                        </div>
                                    </a>
                                    
                                    @if(isset($chantiers) && $chantiers->count() > 0)
                                        <div class="border-t border-slate-100 my-2"></div>
                                        <div class="px-4 py-2">
                                            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Devis pour chantier existant</div>
                                        </div>
                                        @foreach($chantiers->take(3) as $chantier)
                                            <a href="{{ route('devis.create', ['chantier_id' => $chantier->id]) }}" 
                                               class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center mr-3">
                                                    <span class="text-white text-lg">🏗️</span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="font-semibold text-slate-900 truncate">{{ Str::limit($chantier->titre, 20) }}</div>
                                                    <div class="text-xs text-slate-500 truncate">{{ $chantier->client->name ?? 'Client non défini' }}</div>
                                                </div>
                                            </a>
                                        @endforeach
                                        @if($chantiers->count() > 3)
                                            <a href="{{ route('devis.create') }}" 
                                               class="block px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-50 text-center font-medium">
                                                Voir tous les chantiers...
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                </div>

                {{-- 📊 STATISTIQUES INTERACTIVES --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div class="card hover:shadow-lg transition-shadow duration-300 bg-gradient-to-br from-indigo-500 to-purple-600 text-white p-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</div>
                                <div class="text-sm opacity-90">Total</div>
                            </div>
                            <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="card hover:shadow-lg transition-shadow duration-300 bg-gradient-to-br from-orange-400 to-red-500 text-white p-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xl font-bold">{{ $stats['prospects'] ?? 0 }}</div>
                                <div class="text-sm opacity-90">🎯 Prospects</div>
                            </div>
                            <div class="text-2xl">🎯</div>
                        </div>
                    </div>
                    
                    <div class="card hover:shadow-lg transition-shadow duration-300 bg-gradient-to-br from-emerald-400 to-green-600 text-white p-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xl font-bold">{{ $stats['chantiers'] ?? 0 }}</div>
                                <div class="text-sm opacity-90">🏗️ Chantiers</div>
                            </div>
                            <div class="text-2xl">🏗️</div>
                        </div>
                    </div>
                    
                    <div class="card hover:shadow-lg transition-shadow duration-300 bg-gradient-to-br from-amber-400 to-orange-500 text-white p-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-lg font-bold">{{ $stats['envoye'] ?? 0 }}</div>
                                <div class="text-sm opacity-90">📤 Envoyés</div>
                            </div>
                            <div class="text-2xl">📤</div>
                        </div>
                    </div>
                    
                    <div class="card hover:shadow-lg transition-shadow duration-300 bg-gradient-to-br from-purple-400 to-pink-500 text-white p-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-lg font-bold">{{ $stats['convertibles'] ?? 0 }}</div>
                                <div class="text-sm opacity-90">⚡ Convertibles</div>
                            </div>
                            <div class="text-2xl">⚡</div>
                        </div>
                    </div>
                    
                    <div class="card hover:shadow-lg transition-shadow duration-300 bg-gradient-to-br from-slate-400 to-slate-600 text-white p-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-lg font-bold">{{ number_format($stats['montant_total'] ?? 0, 0) }}€</div>
                                <div class="text-sm opacity-90">💰 CA Total</div>
                            </div>
                            <div class="text-2xl">💰</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔍 FILTRES INTELLIGENTS --}}
    <div class="max-w-7xl mx-auto px-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form method="GET" action="{{ route('devis.index') }}" class="space-y-6" @submit="loading = true">
                {{-- Filtres principaux --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    {{-- Type --}}
                    <div>
                        <label for="type" class="block text-sm font-medium text-slate-700 mb-1">Type</label>
                        <select name="type" id="type" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" x-model="filters.type">
                            <option value="">🔍 Tous</option>
                            <option value="prospects" {{ request('type') === 'prospects' ? 'selected' : '' }}>🎯 Prospects</option>
                            <option value="chantiers" {{ request('type') === 'chantiers' ? 'selected' : '' }}>🏗️ Chantiers</option>
                        </select>
                    </div>

                    {{-- Chantier --}}
                    <div>
                        <label for="chantier_id" class="block text-sm font-medium text-slate-700 mb-1">Chantier</label>
                        <select name="chantier_id" id="chantier_id" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Tous les chantiers</option>
                            @if(isset($chantiers))
                                @foreach($chantiers as $chantier)
                                    <option value="{{ $chantier->id }}" {{ request('chantier_id') == $chantier->id ? 'selected' : '' }}>
                                        {{ Str::limit($chantier->titre, 30) }} - {{ $chantier->client->name ?? 'Client non défini' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Statut --}}
                    <div>
                        <label for="statut" class="block text-sm font-medium text-slate-700 mb-1">Statut</label>
                        <select name="statut" id="statut" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Tous les statuts</option>
                            <option value="prospect_brouillon" {{ request('statut') === 'prospect_brouillon' ? 'selected' : '' }}>📝 Brouillon</option>
                            <option value="prospect_envoye" {{ request('statut') === 'prospect_envoye' ? 'selected' : '' }}>📤 Envoyé</option>
                            <option value="prospect_negocie" {{ request('statut') === 'prospect_negocie' ? 'selected' : '' }}>🔄 Négociation</option>
                            <option value="prospect_accepte" {{ request('statut') === 'prospect_accepte' ? 'selected' : '' }}>✅ Accepté</option>
                            <option value="chantier_valide" {{ request('statut') === 'chantier_valide' ? 'selected' : '' }}>🏗️ Validé</option>
                            <option value="facturable" {{ request('statut') === 'facturable' ? 'selected' : '' }}>💰 Facturable</option>
                            <option value="facture" {{ request('statut') === 'facture' ? 'selected' : '' }}>🧾 Facturé</option>
                        </select>
                    </div>

                    {{-- Commercial (admin seulement) --}}
                    @if(Auth::user()->isAdmin() && isset($commerciaux) && $commerciaux->count() > 0)
                    <div>
                        <label for="commercial_id" class="block text-sm font-medium text-slate-700 mb-1">Commercial</label>
                        <select name="commercial_id" id="commercial_id" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Tous les commerciaux</option>
                            @foreach($commerciaux as $commercial)
                                <option value="{{ $commercial->id }}" {{ request('commercial_id') == $commercial->id ? 'selected' : '' }}>
                                    {{ $commercial->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- Recherche --}}
                    <div>
                        <label for="search" class="block text-sm font-medium text-slate-700 mb-1">Rechercher</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Numéro, titre, client..." 
                               class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                {{-- Filtres avancés (collapsible) --}}
                <div x-show="showAdvanced" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-6 border-t border-slate-200">
                    
                    {{-- Période --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Période</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" name="date_debut" value="{{ request('date_debut') }}" 
                                   class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Du">
                            <input type="date" name="date_fin" value="{{ request('date_fin') }}" 
                                   class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Au">
                        </div>
                    </div>

                    {{-- Montant --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Montant TTC</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="montant_min" value="{{ request('montant_min') }}" 
                                   placeholder="Min €" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" step="0.01">
                            <input type="number" name="montant_max" value="{{ request('montant_max') }}" 
                                   placeholder="Max €" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" step="0.01">
                        </div>
                    </div>

                    {{-- Tri --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Trier par</label>
                        <div class="grid grid-cols-2 gap-2">
                            <select name="sort" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date création</option>
                                <option value="numero" {{ request('sort') === 'numero' ? 'selected' : '' }}>Numéro</option>
                                <option value="titre" {{ request('sort') === 'titre' ? 'selected' : '' }}>Titre</option>
                                <option value="montant_ttc" {{ request('sort') === 'montant_ttc' ? 'selected' : '' }}>Montant</option>
                            </select>
                            <select name="direction" class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>↓ Desc</option>
                                <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>↑ Asc</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Actions de filtrage --}}
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex flex-wrap gap-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200" :class="{ 'opacity-50 cursor-not-allowed': loading }">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            <span x-show="!loading">Filtrer</span>
                            <span x-show="loading">Recherche...</span>
                        </button>
                        
                        <a href="{{ route('devis.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Réinitialiser
                        </a>
                        
                        <button type="button" @click="showAdvanced = !showAdvanced" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2 transition-transform duration-200" :class="{ 'rotate-180': showAdvanced }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                            <span x-text="showAdvanced ? 'Moins de filtres' : 'Plus de filtres'"></span>
                        </button>
                    </div>
                    
                    {{-- Indicateurs actifs --}}
                    <div class="flex items-center gap-2 text-sm text-slate-500">
                        @if(request()->hasAny(['type', 'statut', 'search', 'chantier_id', 'commercial_id']))
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                                </svg>
                                Filtres actifs
                            </span>
                        @endif
                        
                        <span>{{ $devis->total() ?? 0 }} résultat(s)</span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- 📋 LISTE UNIFIÉE RESPONSIVE --}}
    <div class="max-w-7xl mx-auto px-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden" x-show="!loading || {{ $devis->count() ?? 0 }} > 0">
            @if(isset($devis) && $devis->count() > 0)
                {{-- Vue desktop/tablet --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    Type / Numéro
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    Titre / Client
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    Chantier
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    Commercial
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    Montant TTC
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @foreach($devis as $devisItem)
                                <tr class="hover:bg-slate-50 transition-colors duration-200 group">
                                    {{-- Type / Numéro --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center font-semibold text-lg
                                                        {{ $devisItem->statut->isProspect() ? 'bg-gradient-to-br from-orange-400 to-red-500 text-white' : 'bg-gradient-to-br from-emerald-400 to-green-600 text-white' }}">
                                                {{ $devisItem->statut->isProspect() ? '🎯' : '🏗️' }}
                                            </div>
                                            <div>
                                                <div class="font-mono text-sm font-semibold text-indigo-600">
                                                    {{ $devisItem->numero }}
                                                </div>
                                                <div class="text-xs text-slate-500">
                                                    {{ $devisItem->statut->isProspect() ? 'Prospect' : 'Chantier' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Titre / Client --}}
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="font-semibold text-slate-900 group-hover:text-indigo-600 transition-colors duration-200">
                                                {{ Str::limit($devisItem->titre, 35) }}
                                            </div>
                                            <div class="text-sm text-slate-500 flex items-center mt-1">
                                                <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                @if($devisItem->statut->isProspect())
                                                    {{ $devisItem->client_nom ?? 'Client non défini' }}
                                                @else
                                                    {{ $devisItem->chantier->client->name ?? 'Client non défini' }}
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Chantier --}}
                                    <td class="px-6 py-4">
                                        @if($devisItem->chantier)
                                            <a href="{{ route('chantiers.show', $devisItem->chantier) }}" 
                                               class="text-emerald-600 hover:text-emerald-800 font-medium transition-colors duration-200 hover:underline">
                                                {{ Str::limit($devisItem->chantier->titre, 25) }}
                                            </a>
                                        @else
                                            <span class="text-slate-400 italic text-sm">Prospect autonome</span>
                                        @endif
                                    </td>

                                    {{-- Commercial --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center mr-2">
                                                <span class="text-xs font-semibold text-indigo-700">
                                                    {{ substr($devisItem->commercial->name ?? 'N', 0, 1) }}
                                                </span>
                                            </div>
                                            <span class="text-sm text-slate-900">{{ $devisItem->commercial->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>

                                    {{-- Montant --}}
                                    <td class="px-6 py-4">
                                        <div class="text-right">
                                            <div class="font-semibold text-slate-900">
                                                {{ number_format($devisItem->montant_ttc ?? 0, 2, ',', ' ') }} €
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                HT: {{ number_format($devisItem->montant_ht ?? 0, 2, ',', ' ') }} €
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Statut --}}
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $devisItem->statut->badgeClass() }}">
                                            {{ $devisItem->statut->label() }}
                                        </span>
                                    </td>

                                    {{-- Date --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            <div class="text-slate-900 font-medium">{{ $devisItem->created_at->format('d/m/Y') }}</div>
                                            <div class="text-slate-500">{{ $devisItem->created_at->format('H:i') }}</div>
                                        </div>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            {{-- Voir --}}
                                            <a href="{{ route('devis.show', $devisItem) }}" 
                                               class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200"
                                               title="Voir le devis">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </a>

                                            {{-- Actions spécifiques selon statut --}}
                                            @if($devisItem->statut->isProspect() && $devisItem->statut->value === 'prospect_accepte')
                                                <a href="{{ route('devis.convert-form', $devisItem) }}" 
                                                   class="p-2 text-slate-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all duration-200"
                                                   title="Convertir en chantier">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                    </svg>
                                                </a>
                                            @endif

                                            @if($devisItem->chantier)
                                                <a href="{{ route('chantiers.show', $devisItem->chantier) }}" 
                                                   class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all duration-200"
                                                   title="Voir le chantier">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                </a>
                                            @endif

                                            {{-- PDF --}}
                                            @if($devisItem->statut->value !== 'prospect_brouillon')
                                                @if($devisItem->chantier)
                                                    <a href="{{ route('chantiers.devis.pdf', [$devisItem->chantier, $devisItem]) }}" 
                                                       target="_blank"
                                                       class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                       title="Télécharger PDF">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <a href="{{ route('devis.pdf', $devisItem) }}" 
                                                       target="_blank"
                                                       class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                       title="Télécharger PDF">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </a>
                                                @endif
                                            @endif

                                            {{-- Menu actions --}}
                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open" 
                                                        class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                    </svg>
                                                </button>

                                                <div x-show="open" 
                                                     @click.away="open = false"
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0 scale-95"
                                                     x-transition:enter-end="opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="opacity-100 scale-100"
                                                     x-transition:leave-end="opacity-0 scale-95"
                                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 py-1 z-50">
                                                    
                                                    @can('commercial-or-admin')
                                                        @if(in_array($devisItem->statut->value, ['prospect_brouillon', 'prospect_envoye', 'chantier_valide']))
                                                            @if($devisItem->chantier)
                                                                <a href="{{ route('chantiers.devis.edit', [$devisItem->chantier, $devisItem]) }}" 
                                                                   class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                                                                    <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                    </svg>
                                                                    Modifier
                                                                </a>
                                                            @else
                                                                <a href="{{ route('devis.edit', $devisItem) }}" 
                                                                   class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                                                                    <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                    </svg>
                                                                    Modifier
                                                                </a>
                                                            @endif
                                                        @endif

                                                        @if($devisItem->statut->isProspect() && $devisItem->statut->value === 'prospect_negocie')
                                                            <a href="{{ route('devis.negotiation-history', $devisItem) }}" 
                                                               class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                                                                <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                Historique négociation
                                                            </a>
                                                        @endif

                                                        @if($devisItem->chantier)
                                                            <form action="{{ route('chantiers.devis.dupliquer', [$devisItem->chantier, $devisItem]) }}" 
                                                                  method="POST" class="inline w-full">
                                                                @csrf
                                                                <button type="submit" 
                                                                        onclick="return confirm('Créer une copie de ce devis ?')"
                                                                        class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200 w-full text-left">
                                                                    <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                                    </svg>
                                                                    Dupliquer
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('devis.dupliquer', $devisItem) }}" 
                                                                  method="POST" class="inline w-full">
                                                                @csrf
                                                                <button type="submit" 
                                                                        onclick="return confirm('Créer une copie de ce devis ?')"
                                                                        class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200 w-full text-left">
                                                                    <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                                    </svg>
                                                                    Dupliquer
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Vue mobile --}}
                <div class="md:hidden divide-y divide-slate-200">
                    @foreach($devis as $devisItem)
                        <div class="p-4 hover:bg-slate-50 transition-colors duration-200">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center font-semibold text-lg
                                                {{ $devisItem->statut->isProspect() ? 'bg-gradient-to-br from-orange-400 to-red-500 text-white' : 'bg-gradient-to-br from-emerald-400 to-green-600 text-white' }}">
                                        {{ $devisItem->statut->isProspect() ? '🎯' : '🏗️' }}
                                    </div>
                                    <div>
                                        <div class="font-mono text-sm font-semibold text-indigo-600">
                                            {{ $devisItem->numero }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $devisItem->statut->isProspect() ? 'Prospect' : 'Chantier' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $devisItem->statut->badgeClass() }}">
                                    {{ $devisItem->statut->label() }}
                                </span>
                            </div>

                            <div class="space-y-2">
                                <div>
                                    <div class="font-semibold text-slate-900">{{ $devisItem->titre }}</div>
                                    <div class="text-sm text-slate-500">
                                        @if($devisItem->statut->isProspect())
                                            {{ $devisItem->client_nom ?? 'Client non défini' }}
                                        @else
                                            {{ $devisItem->chantier->client->name ?? 'Client non défini' }}
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="text-lg font-bold text-slate-900">
                                        {{ number_format($devisItem->montant_ttc ?? 0, 0, ',', ' ') }} €
                                    </div>
                                    <div class="text-sm text-slate-500">
                                        {{ $devisItem->created_at->format('d/m/Y') }}
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-2">
                                    <div class="text-sm text-slate-500">
                                        {{ $devisItem->commercial->name ?? 'N/A' }}
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('devis.show', $devisItem) }}" 
                                           class="inline-flex items-center px-2.5 py-1.5 border border-slate-300 text-xs font-medium rounded text-slate-700 bg-white hover:bg-slate-50">
                                            Voir
                                        </a>
                                        
                                        @if($devisItem->statut->isProspect() && $devisItem->statut->value === 'prospect_accepte')
                                            <a href="{{ route('devis.convert-form', $devisItem) }}" 
                                               class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700">
                                                Convertir
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $devis->appends(request()->query())->links() }}
                </div>

            @else
                {{-- État vide --}}
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">
                        @if(request()->hasAny(['type', 'statut', 'search', 'chantier_id']))
                            Aucun devis trouvé
                        @else
                            Aucun devis pour le moment
                        @endif
                    </h3>
                    
                    <p class="text-slate-500 mb-8 max-w-md mx-auto">
                        @if(request()->hasAny(['type', 'statut', 'search', 'chantier_id']))
                            Aucun devis ne correspond à vos critères de recherche. Essayez de modifier les filtres.
                        @else
                            Commencez par créer votre premier devis prospect ou pour un chantier existant.
                        @endif
                    </p>
                    
                    @can('commercial-or-admin')
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('devis.create', ['type' => 'prospect']) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                <span class="text-lg mr-2">🎯</span>
                                Créer un prospect
                            </a>
                            
                            @if(isset($chantiers) && $chantiers->count() > 0)
                                <a href="{{ route('devis.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                    <span class="text-lg mr-2">🏗️</span>
                                    Devis pour chantier
                                </a>
                            @endif
                        </div>
                    @endcan
                </div>
            @endif
        </div>

        {{-- État de chargement --}}
        <div x-show="loading && !{{ isset($devis) && $devis->count() > 0 ? 'true' : 'false' }}" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="bg-white rounded-lg shadow-sm p-16 text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-4"></div>
            <p class="text-slate-600">Recherche en cours...</p>
        </div>
    </div>
</div>

{{-- Scripts Alpine.js --}}
<script>
function devisManager() {
    return {
        // États
        loading: false,
        showAdvanced: false,
        filters: {
            type: '{{ request("type") }}',
            statut: '{{ request("statut") }}',
            chantier_id: '{{ request("chantier_id") }}'
        },
        
        // Initialisation
        init() {
            // Auto-hide loading après 3 secondes max
            setTimeout(() => {
                this.loading = false;
            }, 3000);
            
            // Gestion de l'état des filtres avancés
            const hasAdvancedFilters = {{ request()->hasAny(['date_debut', 'date_fin', 'montant_min', 'montant_max', 'sort']) ? 'true' : 'false' }};
            if (hasAdvancedFilters) {
                this.showAdvanced = true;
            }
        },
        
        // Méthodes utilitaires
        formatMoney(amount) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(amount || 0);
        },
        
        // Gestion des actions
        confirmAction(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }
    }
}

// Auto-soumission des filtres avec délai
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Auto-submit si plus de 2 caractères
                if (this.value.length > 2 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 500);
        });
    }
    
    // Gestion des filtres select
    const selectFilters = document.querySelectorAll('select[name="type"], select[name="statut"], select[name="chantier_id"], select[name="commercial_id"]');
    selectFilters.forEach(select => {
        select.addEventListener('change', function() {
            // Auto-submit quand on change un filtre principal
            this.form.submit();
        });
    });
});
</script>

{{-- Styles additionnels --}}
<style>
/* Gradient text */
.gradient-text {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Hover lift effect */
.hover-lift {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(15, 23, 42, 0.12);
}

/* Loading button state */
.btn-loading {
    position: relative;
    pointer-events: none;
}

.btn-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 16px;
    height: 16px;
    margin: -8px 0 0 -8px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Card hover effects */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-1px);
}

/* Custom scrollbar */
.overflow-x-auto::-webkit-scrollbar {
    height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #94a3b8;
    border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}

/* Status badge styling */
.badge {
    @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
}

/* Responsive table improvements */
@media (max-width: 768px) {
    .table-responsive {
        -webkit-overflow-scrolling: touch;
    }
}

/* Animation delays for statistics cards */
.stats-card-1 { animation-delay: 0ms; }
.stats-card-2 { animation-delay: 100ms; }
.stats-card-3 { animation-delay: 200ms; }
.stats-card-4 { animation-delay: 300ms; }
.stats-card-5 { animation-delay: 400ms; }
.stats-card-6 { animation-delay: 500ms; }

/* Fade in animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
}

/* Focus improvements */
.focus\:ring-indigo-500:focus {
    --tw-ring-color: rgb(99 102 241 / 0.5);
}

/* Dropdown improvements */
.dropdown-menu {
    @apply absolute mt-2 bg-white rounded-lg shadow-lg border border-slate-200 py-1 z-50;
    min-width: 200px;
}

.dropdown-item {
    @apply flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200;
}

/* Button improvements */
.btn {
    @apply inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200;
}

.btn-primary {
    @apply border-transparent text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500;
}

.btn-outline {
    @apply border-slate-300 text-slate-700 bg-white hover:bg-slate-50 focus:ring-indigo-500;
}

.btn-ghost {
    @apply border-transparent text-slate-700 bg-transparent hover:bg-slate-100 focus:ring-indigo-500;
}

.btn-sm {
    @apply px-2.5 py-1.5 text-xs;
}

/* Form improvements */
.form-label {
    @apply block text-sm font-medium text-slate-700 mb-1;
}

.form-input, .form-select {
    @apply w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200;
}

.form-input:focus, .form-select:focus {
    @apply ring-2 ring-indigo-500 border-indigo-500;
}

/* Table improvements */
.table {
    @apply min-w-full divide-y divide-slate-200;
}

.table th {
    @apply px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider bg-slate-50;
}

.table td {
    @apply px-6 py-4 whitespace-nowrap text-sm text-slate-900;
}

/* Mobile responsive improvements */
@media (max-width: 640px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
    
    .filter-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .mobile-card {
        padding: 1rem;
        margin-bottom: 0.5rem;
    }
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    .print-friendly {
        background: white !important;
        color: black !important;
    }
}
</style>
@endsection