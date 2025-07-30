@extends('layouts.app')

@section('title', 'Gestion des Devis')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- 🎯 HEADER AVEC STATISTIQUES EN TEMPS RÉEL --}}
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-3xl font-bold leading-7 text-gray-900 sm:truncate">
                        <svg class="w-8 h-8 inline mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        Gestion des Devis
                    </h1>
                    
                    {{-- 📊 STATISTIQUES COMPACTES --}}
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-6 gap-4">
                        <div class="bg-blue-50 rounded-lg p-3">
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                            <div class="text-sm text-blue-700">Total</div>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-3">
                            <div class="text-xl font-semibold text-orange-600">{{ $stats['prospects'] }}</div>
                            <div class="text-sm text-orange-700">🎯 Prospects</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-3">
                            <div class="text-xl font-semibold text-green-600">{{ $stats['chantiers'] }}</div>
                            <div class="text-sm text-green-700">🏗️ Chantiers</div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-3">
                            <div class="text-lg font-semibold text-yellow-600">{{ $stats['envoye'] }}</div>
                            <div class="text-sm text-yellow-700">📤 Envoyés</div>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-3">
                            <div class="text-lg font-semibold text-purple-600">{{ $stats['convertibles'] }}</div>
                            <div class="text-sm text-purple-700">⚡ Convertibles</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-lg font-semibold text-gray-600">{{ number_format($stats['montant_total'], 0) }}€</div>
                            <div class="text-sm text-gray-700">💰 CA Total</div>
                        </div>
                    </div>
                </div>
                
                {{-- 🆕 BOUTON CRÉATION ADAPTATIF --}}
                @can('commercial-or-admin')
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <div class="relative inline-block text-left" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Nouveau Devis
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                            <div class="py-1">
                                <a href="{{ route('devis.create', ['type' => 'prospect']) }}" 
                                   class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    🎯 Nouveau Prospect
                                </a>
                                @if($chantiers->count() > 0)
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <div class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">Devis pour chantier existant</div>
                                    @foreach($chantiers->take(5) as $chantier)
                                        <a href="{{ route('devis.create', ['chantier_id' => $chantier->id]) }}" 
                                           class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            <div>
                                                <div class="font-medium">{{ Str::limit($chantier->titre, 25) }}</div>
                                                <div class="text-xs text-gray-500">{{ $chantier->client->name }}</div>
                                            </div>
                                        </a>
                                    @endforeach
                                    @if($chantiers->count() > 5)
                                        <a href="{{ route('devis.create') }}" 
                                           class="block px-4 py-2 text-sm text-blue-600 hover:bg-blue-50">
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
        </div>
    </div>

    {{-- 🔍 FILTRES INTELLIGENTS --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('devis.index') }}" class="space-y-4" x-data="filtresDevis()">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    {{-- 🎯 Filtre Type (Prospect/Chantier) --}}
                    <div>
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-select" x-model="filters.type" @change="updateFilters">
                            <option value="">🔍 Tous</option>
                            <option value="prospects" {{ request('type') === 'prospects' ? 'selected' : '' }}>🎯 Prospects</option>
                            <option value="chantiers" {{ request('type') === 'chantiers' ? 'selected' : '' }}>🏗️ Chantiers</option>
                        </select>
                    </div>

                    {{-- 🏗️ Filtre Chantier spécifique --}}
                    <div>
                        <label for="chantier_id" class="form-label">Chantier</label>
                        <select name="chantier_id" id="chantier_id" class="form-select">
                            <option value="">Tous les chantiers</option>
                            @foreach($chantiers as $chantier)
                                <option value="{{ $chantier->id }}" {{ request('chantier_id') == $chantier->id ? 'selected' : '' }}>
                                    {{ Str::limit($chantier->titre, 30) }} - {{ $chantier->client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 📊 Filtre Statut --}}
                    <div>
                        <label for="statut" class="form-label">Statut</label>
                        <select name="statut" id="statut" class="form-select">
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

                    {{-- 👤 Filtre Commercial (admin seulement) --}}
                    @if(Auth::user()->isAdmin() && $commerciaux->count() > 0)
                    <div>
                        <label for="commercial_id" class="form-label">Commercial</label>
                        <select name="commercial_id" id="commercial_id" class="form-select">
                            <option value="">Tous les commerciaux</option>
                            @foreach($commerciaux as $commercial)
                                <option value="{{ $commercial->id }}" {{ request('commercial_id') == $commercial->id ? 'selected' : '' }}>
                                    {{ $commercial->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- 🔍 Recherche --}}
                    <div>
                        <label for="search" class="form-label">Rechercher</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Numéro, titre, client..." class="form-input">
                    </div>
                </div>

                {{-- Filtres avancés --}}
                <div x-show="showAdvanced" x-collapse class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-200">
                    {{-- 📅 Période --}}
                    <div>
                        <label class="form-label">Période</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="form-input text-sm" placeholder="Du">
                            <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="form-input text-sm" placeholder="Au">
                        </div>
                    </div>

                    {{-- 💰 Montant --}}
                    <div>
                        <label class="form-label">Montant TTC</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="montant_min" value="{{ request('montant_min') }}" 
                                   placeholder="Min €" class="form-input text-sm" step="0.01">
                            <input type="number" name="montant_max" value="{{ request('montant_max') }}" 
                                   placeholder="Max €" class="form-input text-sm" step="0.01">
                        </div>
                    </div>

                    {{-- 📈 Tri --}}
                    <div>
                        <label class="form-label">Trier par</label>
                        <div class="grid grid-cols-2 gap-2">
                            <select name="sort" class="form-select text-sm">
                                <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date création</option>
                                <option value="numero" {{ request('sort') === 'numero' ? 'selected' : '' }}>Numéro</option>
                                <option value="titre" {{ request('sort') === 'titre' ? 'selected' : '' }}>Titre</option>
                                <option value="montant_ttc" {{ request('sort') === 'montant_ttc' ? 'selected' : '' }}>Montant</option>
                            </select>
                            <select name="direction" class="form-select text-sm">
                                <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>↓ Desc</option>
                                <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>↑ Asc</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex space-x-2">
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            Filtrer
                        </button>
                        <a href="{{ route('devis.index') }}" class="btn btn-outline">Réinitialiser</a>
                        <button type="button" @click="showAdvanced = !showAdvanced" class="btn btn-outline">
                            <span x-text="showAdvanced ? 'Moins de filtres' : 'Plus de filtres'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- 📋 LISTE UNIFIÉE --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @if($devis->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Type / Numéro</th>
                                <th>Titre / Client</th>
                                <th>Chantier</th>
                                <th>Commercial</th>
                                <th>Montant TTC</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devis as $devisItem)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td>
                                        {{-- 🎯 Indicateur de type --}}
                                        <div class="flex items-center space-x-2">
                                            <span class="text-lg">{{ $devisItem->isProspect() ? '🎯' : '🏗️' }}</span>
                                            <div>
                                                <div class="font-mono text-sm font-medium text-blue-600">
                                                    {{ $devisItem->numero }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $devisItem->isProspect() ? 'Prospect' : 'Chantier' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ Str::limit($devisItem->titre, 30) }}</div>
                                            <div class="text-sm text-gray-500">
                                                @if($devisItem->isProspect())
                                                    {{ $devisItem->client_nom }}
                                                @else
                                                    {{ $devisItem->chantier->client->name }}
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($devisItem->chantier)
                                            <a href="{{ route('chantiers.show', $devisItem->chantier) }}" 
                                               class="text-blue-600 hover:text-blue-800 font-medium">
                                                {{ Str::limit($devisItem->chantier->titre, 25) }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">Prospect</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-sm text-gray-900">{{ $devisItem->commercial->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <div class="text-right">
                                            <div class="font-medium text-gray-900">{{ number_format($devisItem->montant_ttc ?? 0, 2, ',', ' ') }} €</div>
                                            <div class="text-xs text-gray-500">HT: {{ number_format($devisItem->montant_ht ?? 0, 2, ',', ' ') }} €</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="{{ $devisItem->getStatutBadgeClass() }}">
                                            {{ $devisItem->getStatutIcon() }} {{ $devisItem->getStatutTexte() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-sm">
                                            <div class="text-gray-900">{{ $devisItem->created_at->format('d/m/Y') }}</div>
                                            <div class="text-gray-500">{{ $devisItem->created_at->format('H:i') }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center space-x-2">
                                            {{-- 👁️ Voir --}}
                                            <a href="{{ route('devis.show', $devisItem) }}" 
                                               class="text-blue-600 hover:text-blue-800" title="Voir">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </a>

                                            {{-- ⚡ Actions spécifiques au type --}}
                                            @if($devisItem->isProspect())
                                                {{-- Actions prospects --}}
                                                @if($devisItem->peutEtreConverti())
                                                    <a href="{{ route('devis.convert-form', $devisItem) }}" 
                                                       class="text-purple-600 hover:text-purple-800" title="Convertir en chantier">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                        </svg>
                                                    </a>
                                                @endif
                                            @else
                                                {{-- Actions chantiers --}}
                                                @if($devisItem->chantier)
                                                    <a href="{{ route('chantiers.show', $devisItem->chantier) }}" 
                                                       class="text-green-600 hover:text-green-800" title="Voir le chantier">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                        </svg>
                                                    </a>
                                                @endif
                                            @endif

                                            {{-- 📄 PDF --}}
                                            @if($devisItem->chantier)
                                                <a href="{{ route('chantiers.devis.pdf', [$devisItem->chantier, $devisItem]) }}" 
                                                   target="_blank" class="text-red-600 hover:text-red-800" title="PDF">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </a>
                                            @endif

                                            {{-- ⚙️ Menu d'actions --}}
                                            <div class="relative inline-block text-left" x-data="{ open: false }">
                                                <button @click="open = !open" 
                                                        class="text-gray-400 hover:text-gray-600">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                    </svg>
                                                </button>

                                                <div x-show="open" 
                                                     @click.away="open = false"
                                                     x-transition
                                                     class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                                                    <div class="py-1">
                                                        @can('commercial-or-admin')
                                                            @if($devisItem->peutEtreModifie())
                                                                @if($devisItem->chantier)
                                                                    <a href="{{ route('chantiers.devis.edit', [$devisItem->chantier, $devisItem]) }}" 
                                                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                        Modifier
                                                                    </a>
                                                                @endif
                                                            @endif

                                                            @if($devisItem->isProspect() && $devisItem->statut->value === 'prospect_negocie')
                                                                <a href="{{ route('devis.negotiation-history', $devisItem) }}" 
                                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                    Historique négociation
                                                                </a>
                                                            @endif
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $devis->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun devis trouvé</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(request()->hasAny(['type', 'statut', 'search', 'chantier_id']))
                            Aucun devis ne correspond à vos critères de recherche.
                        @else
                            Commencez par créer un nouveau devis.
                        @endif
                    </p>
                    @can('commercial-or-admin')
                        <div class="mt-6">
                            <a href="{{ route('devis.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Créer un devis
                            </a>
                        </div>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function filtresDevis() {
    return {
        showAdvanced: false,
        filters: {
            type: '{{ request("type") }}',
            statut: '{{ request("statut") }}',
            chantier_id: '{{ request("chantier_id") }}'
        },
        
        updateFilters() {
            // Logique pour mise à jour dynamique des filtres si nécessaire
        }
    }
}
</script>
@endsection