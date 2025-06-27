@extends('layouts.app')

@section('title', 'Tous les Devis')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header avec statistiques --}}
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        <svg class="w-8 h-8 inline mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621 0 1.125-.504 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        Gestion des Devis
                    </h1>
                    <div class="mt-2 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <span class="font-medium">{{ $stats['total'] }}</span> devis au total
                        </div>
                    </div>
                </div>
                @can('commercial-or-admin')
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('chantiers.index') }}" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Nouveau Devis
                    </a>
                </div>
                @endcan
            </div>

            {{-- Statistiques rapides --}}
            <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-5">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-blue-700">{{ $stats['total'] }}</div>
                    <div class="text-sm text-blue-600">Total</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-700">{{ $stats['brouillon'] }}</div>
                    <div class="text-sm text-gray-600">Brouillons</div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-yellow-700">{{ $stats['envoye'] }}</div>
                    <div class="text-sm text-yellow-600">Envoyés</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-700">{{ $stats['accepte'] }}</div>
                    <div class="text-sm text-green-600">Acceptés</div>
                </div>
                <div class="bg-red-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-red-700">{{ $stats['refuse'] }}</div>
                    <div class="text-sm text-red-600">Refusés</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtres et recherche --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('devis.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Recherche --}}
                    <div>
                        <label for="search" class="form-label">Rechercher</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Numéro, titre, client..." class="form-input">
                    </div>

                    {{-- Statut --}}
                    <div>
                        <label for="statut" class="form-label">Statut</label>
                        <select name="statut" id="statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="brouillon" {{ request('statut') === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                            <option value="envoye" {{ request('statut') === 'envoye' ? 'selected' : '' }}>Envoyé</option>
                            <option value="accepte" {{ request('statut') === 'accepte' ? 'selected' : '' }}>Accepté</option>
                            <option value="refuse" {{ request('statut') === 'refuse' ? 'selected' : '' }}>Refusé</option>
                        </select>
                    </div>

                    {{-- Commercial (admin seulement) --}}
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

                    {{-- Période --}}
                    <div>
                        <label for="date_debut" class="form-label">Période</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="form-input text-xs">
                            <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="form-input text-xs">
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
                    </div>
                    
                    {{-- Tri --}}
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Trier par :</span>
                        <select name="sort" onchange="this.form.submit()" class="form-select text-sm">
                            <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date création</option>
                            <option value="numero" {{ request('sort') === 'numero' ? 'selected' : '' }}>Numéro</option>
                            <option value="titre" {{ request('sort') === 'titre' ? 'selected' : '' }}>Titre</option>
                            <option value="montant_ttc" {{ request('sort') === 'montant_ttc' ? 'selected' : '' }}>Montant</option>
                        </select>
                        <select name="direction" onchange="this.form.submit()" class="form-select text-sm">
                            <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>↓ Desc</option>
                            <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>↑ Asc</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        {{-- Liste des devis --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @if($devis->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Numéro</th>
                                <th>Titre</th>
                                <th>Chantier</th>
                                <th>Client</th>
                                <th>Commercial</th>
                                <th>Montant TTC</th>
                                <th>Statut</th>
                                <th>Date création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devis as $devisItem)
                                <tr class="hover:bg-gray-50">
                                    <td>
                                        <span class="font-mono text-sm font-medium text-blue-600">
                                            {{ $devisItem->numero }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="font-medium text-gray-900">{{ Str::limit($devisItem->titre, 30) }}</div>
                                        @if($devisItem->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($devisItem->description, 40) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('chantiers.show', $devisItem->chantier) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ Str::limit($devisItem->chantier->titre, 25) }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900">{{ $devisItem->chantier->client->name }}</div>
                                            <div class="text-gray-500">{{ $devisItem->chantier->client->email }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-sm text-gray-900">{{ $devisItem->commercial->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="font-medium text-gray-900">{{ number_format($devisItem->montant_ttc ?? 0, 2, ',', ' ') }} €</span>
                                    </td>
                                    <td>
                                        @php
                                            $colors = [
                                                'brouillon' => 'bg-gray-100 text-gray-800',
                                                'envoye' => 'bg-yellow-100 text-yellow-800',
                                                'accepte' => 'bg-green-100 text-green-800',
                                                'refuse' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="badge {{ $colors[$devisItem->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($devisItem->statut) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-sm text-gray-500">{{ $devisItem->created_at->format('d/m/Y') }}</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('devis.show', $devisItem) }}" 
                                               class="text-blue-600 hover:text-blue-800" title="Voir">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('chantiers.devis.show', [$devisItem->chantier, $devisItem]) }}" 
                                               class="text-green-600 hover:text-green-800" title="Voir dans le chantier">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                                                </svg>
                                            </a>
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
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouveau devis dans un chantier.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection