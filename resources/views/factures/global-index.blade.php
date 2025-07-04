@extends('layouts.app')

@section('title', 'Toutes les Factures')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header avec statistiques --}}
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        <svg class="w-8 h-8 inline mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M16.5 18.75h-9A2.25 2.25 0 015.25 16.5v-10.5A2.25 2.25 0 017.5 3.75h1.5m0 0h6m-6 0v1.5m6-1.5v1.5m6 1.5v10.5a2.25 2.25 0 01-2.25 2.25H13.5m-6-0h6m-6 3h6" />
                        </svg>
                        Gestion des Factures
                    </h1>
                    <div class="mt-2 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <span class="font-medium">{{ $stats['total'] }}</span> factures au total
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <span class="font-medium">{{ number_format($stats['montant_total'], 2, ',', ' ') }} €</span> de chiffre d'affaires
                        </div>
                    </div>
                </div>
                @can('commercial-or-admin')
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <button onclick="openModal('modal-selection-chantier')" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Nouvelle Facture
                    </button>
                </div>
                @endcan
            </div>

            {{-- Statistiques rapides --}}
            <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-5">
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-700">{{ $stats['total'] }}</div>
                    <div class="text-sm text-green-600">Total</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-700">{{ $stats['brouillon'] }}</div>
                    <div class="text-sm text-gray-600">Brouillons</div>
                </div>
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-blue-700">{{ $stats['envoyee'] }}</div>
                    <div class="text-sm text-blue-600">Envoyées</div>
                </div>
                <div class="bg-emerald-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-emerald-700">{{ $stats['payee'] }}</div>
                    <div class="text-sm text-emerald-600">Payées</div>
                </div>
                <div class="bg-red-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-red-700">{{ $stats['en_retard'] }}</div>
                    <div class="text-sm text-red-600">En retard</div>
                </div>
            </div>

            {{-- Indicateurs financiers --}}
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="bg-gradient-to-r from-green-400 to-emerald-500 rounded-lg p-4 text-white">
                    <div class="text-lg font-bold">{{ number_format($stats['montant_paye'], 2, ',', ' ') }} €</div>
                    <div class="text-sm opacity-90">Montant payé</div>
                </div>
                <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg p-4 text-white">
                    <div class="text-lg font-bold">{{ number_format($stats['montant_impaye'], 2, ',', ' ') }} €</div>
                    <div class="text-sm opacity-90">En attente de paiement</div>
                </div>
                <div class="bg-gradient-to-r from-blue-400 to-purple-500 rounded-lg p-4 text-white">
                    <div class="text-lg font-bold">{{ number_format($stats['montant_total'], 2, ',', ' ') }} €</div>
                    <div class="text-sm opacity-90">Chiffre d'affaires total</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtres et recherche --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('factures.index') }}" class="space-y-4">
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
                            <option value="envoyee" {{ request('statut') === 'envoyee' ? 'selected' : '' }}>Envoyée</option>
                            <option value="payee" {{ request('statut') === 'payee' ? 'selected' : '' }}>Payée</option>
                            <option value="annulee" {{ request('statut') === 'annulee' ? 'selected' : '' }}>Annulée</option>
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

                {{-- Filtres avancés --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Montant</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="montant_min" value="{{ request('montant_min') }}" 
                                   placeholder="Min" class="form-input text-xs" step="0.01">
                            <input type="number" name="montant_max" value="{{ request('montant_max') }}" 
                                   placeholder="Max" class="form-input text-xs" step="0.01">
                        </div>
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center">
                            <input type="checkbox" name="en_retard" value="1" {{ request('en_retard') ? 'checked' : '' }} 
                                   class="form-checkbox">
                            <span class="ml-2 text-sm text-gray-700">Factures en retard</span>
                        </label>
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
                        <a href="{{ route('factures.index') }}" class="btn btn-outline">Réinitialiser</a>
                    </div>
                    
                    {{-- Tri --}}
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Trier par :</span>
                        <select name="sort" onchange="this.form.submit()" class="form-select text-sm">
                            <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date création</option>
                            <option value="numero" {{ request('sort') === 'numero' ? 'selected' : '' }}>Numéro</option>
                            <option value="titre" {{ request('sort') === 'titre' ? 'selected' : '' }}>Titre</option>
                            <option value="montant_ttc" {{ request('sort') === 'montant_ttc' ? 'selected' : '' }}>Montant</option>
                            <option value="date_echeance" {{ request('sort') === 'date_echeance' ? 'selected' : '' }}>Échéance</option>
                        </select>
                        <select name="direction" onchange="this.form.submit()" class="form-select text-sm">
                            <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>↓ Desc</option>
                            <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>↑ Asc</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        {{-- Liste des factures --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @if($factures->count() > 0)
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
                                <th>Échéance</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($factures as $facture)
                                <tr class="hover:bg-gray-50 {{ $facture->date_echeance < now() && !in_array($facture->statut, ['payee', 'annulee']) ? 'bg-red-50' : '' }}">
                                    <td>
                                        <span class="font-mono text-sm font-medium text-green-600">
                                            {{ $facture->numero }}
                                        </span>
                                        @if($facture->devis)
                                            <div class="text-xs text-gray-500">
                                                Devis {{ $facture->devis->numero }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="font-medium text-gray-900">{{ Str::limit($facture->titre, 30) }}</div>
                                        @if($facture->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($facture->description, 40) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('chantiers.show', $facture->chantier) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ Str::limit($facture->chantier->titre, 25) }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900">{{ $facture->chantier->client->name }}</div>
                                            <div class="text-gray-500">{{ $facture->chantier->client->email }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-sm text-gray-900">{{ $facture->commercial->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="font-medium text-gray-900">{{ number_format($facture->montant_ttc ?? 0, 2, ',', ' ') }} €</span>
                                        @if($facture->montant_paye > 0 && $facture->statut !== 'payee')
                                            <div class="text-xs text-blue-600">
                                                {{ number_format($facture->montant_paye, 2, ',', ' ') }} € payés
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900">{{ $facture->date_echeance->format('d/m/Y') }}</div>
                                            @if($facture->date_echeance < now() && !in_array($facture->statut, ['payee', 'annulee']))
                                                <div class="text-red-600 text-xs font-medium">
                                                    {{ $facture->date_echeance->diffForHumans() }}
                                                </div>
                                            @else
                                                <div class="text-gray-500 text-xs">
                                                    {{ $facture->date_echeance->diffForHumans() }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $colors = [
                                                'brouillon' => 'bg-gray-100 text-gray-800',
                                                'envoyee' => 'bg-blue-100 text-blue-800',
                                                'payee' => 'bg-green-100 text-green-800',
                                                'payee_partiel' => 'bg-yellow-100 text-yellow-800',
                                                'en_retard' => 'bg-red-100 text-red-800',
                                                'annulee' => 'bg-gray-100 text-gray-800'
                                            ];
                                            $statutDisplay = $facture->statut;
                                            if ($facture->date_echeance < now() && !in_array($facture->statut, ['payee', 'annulee'])) {
                                                $statutDisplay = 'en_retard';
                                            }
                                        @endphp
                                        <span class="badge {{ $colors[$statutDisplay] ?? 'bg-gray-100 text-gray-800' }}">
                                            @if($statutDisplay === 'en_retard')
                                                En retard
                                            @else
                                                {{ ucfirst(str_replace('_', ' ', $facture->statut)) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('factures.show', $facture) }}" 
                                               class="text-blue-600 hover:text-blue-800" title="Voir">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('chantiers.factures.show', [$facture->chantier, $facture]) }}" 
                                               class="text-green-600 hover:text-green-800" title="Voir dans le chantier">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                                                </svg>
                                            </a>
                                            @if($facture->statut !== 'brouillon')
                                                <a href="{{ route('chantiers.factures.pdf', [$facture->chantier, $facture]) }}" 
                                                   class="text-purple-600 hover:text-purple-800" title="Télécharger PDF" target="_blank">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $factures->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune facture trouvée</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer une nouvelle facture dans un chantier.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal de sélection de chantier --}}
    <div id="modal-selection-chantier" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Créer une facture</h3>
                    <button onclick="closeModal('modal-selection-chantier')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <p class="text-sm text-gray-600 mb-4">
                    Pour créer une facture, vous devez d'abord sélectionner un chantier :
                </p>
                
                <div class="space-y-3 max-h-60 overflow-y-auto">
                    @php
                        // Récupérer les chantiers de l'utilisateur
                        $mesChantiers = Auth::user()->isAdmin() 
                            ? \App\Models\Chantier::with('client')->where('statut', '!=', 'termine')->latest()->take(20)->get()
                            : Auth::user()->chantiersCommercial()->with('client')->where('statut', '!=', 'termine')->latest()->take(20)->get();
                    @endphp
                    
                    @forelse($mesChantiers as $chantier)
                        <a href="{{ route('chantiers.factures.create', $chantier) }}" 
                           class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <div>
                                <div class="font-medium text-gray-900">{{ $chantier->titre }}</div>
                                <div class="text-sm text-gray-500">{{ $chantier->client->name }}</div>
                            </div>
                            <div class="flex items-center">
                                <span class="text-xs px-2 py-1 bg-{{ $chantier->statut === 'en_cours' ? 'blue' : 'gray' }}-100 text-{{ $chantier->statut === 'en_cours' ? 'blue' : 'gray' }}-800 rounded-full">
                                    {{ ucfirst($chantier->statut) }}
                                </span>
                                <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-6">
                            <p class="text-gray-500">Aucun chantier actif trouvé.</p>
                            <a href="{{ route('chantiers.create') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                Créer un nouveau chantier
                            </a>
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-6 flex justify-between">
                    <a href="{{ route('chantiers.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        Voir tous les chantiers
                    </a>
                    <button onclick="closeModal('modal-selection-chantier')" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Fermer avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.fixed.inset-0:not(.hidden)');
            modals.forEach(modal => {
                modal.classList.add('hidden');
            });
            document.body.classList.remove('overflow-hidden');
        }
    });
    </script>
</div>
@endsection