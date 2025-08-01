{{-- resources/views/devis/prospects.blade.php --}}
@extends('layouts.app')

@section('title', 'Devis Prospects')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        <svg class="w-6 h-6 inline mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        🎯 Gestion des Prospects
                    </h1>
                    {{-- Statistiques compactes --}}
                    <div class="mt-2 flex flex-wrap items-center gap-6 text-sm">
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-purple-600 mr-1">{{ $stats['total'] }}</span>
                            <span class="text-gray-600">Total</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-lg font-semibold text-gray-500 mr-1">{{ $stats['brouillon'] }}</span>
                            <span class="text-gray-600">Brouillons</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-lg font-semibold text-yellow-600 mr-1">{{ $stats['negocie'] }}</span>
                            <span class="text-gray-600">En négociation</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-lg font-semibold text-green-600 mr-1">{{ $stats['accepte'] }}</span>
                            <span class="text-gray-600">Acceptés</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-lg font-semibold text-blue-600 mr-1">{{ $stats['convertibles'] }}</span>
                            <span class="text-gray-600">Convertibles</span>
                        </div>
                    </div>
                </div>
                @can('commercial-or-admin')
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('devis.create') }}" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Nouveau Prospect
                    </a>
                </div>
                @endcan
            </div>
        </div>
    </div>

    {{-- Filtres et recherche --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('devis.prospects') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Recherche --}}
                    <div>
                        <label for="search" class="form-label">Rechercher</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Nom, email, titre..." class="form-input">
                    </div>

                    {{-- Statut prospect --}}
                    <div>
                        <label for="statut_prospect" class="form-label">Statut</label>
                        <select name="statut_prospect" id="statut_prospect" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="brouillon" {{ request('statut_prospect') === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                            <option value="envoye" {{ request('statut_prospect') === 'envoye' ? 'selected' : '' }}>Envoyé</option>
                            <option value="negocie" {{ request('statut_prospect') === 'negocie' ? 'selected' : '' }}>En négociation</option>
                            <option value="accepte" {{ request('statut_prospect') === 'accepte' ? 'selected' : '' }}>Accepté</option>
                            <option value="refuse" {{ request('statut_prospect') === 'refuse' ? 'selected' : '' }}>Refusé</option>
                            <option value="converti" {{ request('statut_prospect') === 'converti' ? 'selected' : '' }}>Converti</option>
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

                    {{-- Filtre rapide convertibles --}}
                    <div>
                        <label for="convertibles" class="form-label">Filtres rapides</label>
                        <select name="filter" class="form-select" onchange="this.form.submit()">
                            <option value="">Tous</option>
                            <option value="convertibles" {{ request('filter') === 'convertibles' ? 'selected' : '' }}>
                                ✅ Convertibles en chantier
                            </option>
                            <option value="en_negociation" {{ request('filter') === 'en_negociation' ? 'selected' : '' }}>
                                🔄 En négociation
                            </option>
                            <option value="expires_bientot" {{ request('filter') === 'expires_bientot' ? 'selected' : '' }}>
                                ⏰ Expire bientôt
                            </option>
                        </select>
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
                        <a href="{{ route('devis.prospects') }}" class="btn btn-outline">Réinitialiser</a>
                    </div>
                    
                    {{-- Actions en lot --}}
                    <div class="flex items-center space-x-2">
                        <button type="button" class="btn btn-outline text-sm" onclick="exportSelection()">
                            📊 Exporter la sélection
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Liste des prospects --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @if($devis->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="form-checkbox" id="select-all">
                                </th>
                                <th>Prospect</th>
                                <th>Client</th>
                                <th>Commercial</th>
                                <th>Montant TTC</th>
                                <th>Statut</th>
                                <th>Validité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devis as $devisItem)
                                <tr class="hover:bg-gray-50">
                                    <td>
                                        <input type="checkbox" class="form-checkbox prospect-checkbox" value="{{ $devisItem->id }}">
                                    </td>
                                    <td>
                                        <div>
                                            <div class="flex items-center">
                                                <span class="font-mono text-sm font-medium text-purple-600 mr-2">
                                                    {{ $devisItem->numero }}
                                                </span>
                                                <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">
                                                    🎯 PROSPECT
                                                </span>
                                            </div>
                                            <div class="font-medium text-gray-900 mt-1">{{ Str::limit($devisItem->titre, 30) }}</div>
                                            @if($devisItem->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($devisItem->description, 40) }}</div>
                                            @endif
                                            
                                            {{-- Indicateur de négociation --}}
                                            @if($devisItem->historique_negociation && count($devisItem->historique_negociation) > 0)
                                                <div class="text-xs text-orange-600 mt-1">
                                                    🔄 {{ count($devisItem->historique_negociation) }} version(s)
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900">{{ $devisItem->client_info['nom'] ?? 'N/A' }}</div>
                                            <div class="text-gray-500">{{ $devisItem->client_info['email'] ?? 'N/A' }}</div>
                                            @if($devisItem->client_info['telephone'] ?? null)
                                                <div class="text-gray-500">{{ $devisItem->client_info['telephone'] }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-sm text-gray-900">{{ $devisItem->commercial->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="font-medium text-gray-900">{{ number_format($devisItem->montant_ttc ?? 0, 2, ',', ' ') }} €</span>
                                        <div class="text-xs text-gray-500">HT: {{ number_format($devisItem->montant_ht ?? 0, 2, ',', ' ') }} €</div>
                                    </td>
                                    <td>
                                        @php
                                            $colors = [
                                                'brouillon' => 'bg-gray-100 text-gray-800',
                                                'envoye' => 'bg-blue-100 text-blue-800',
                                                'negocie' => 'bg-yellow-100 text-yellow-800',
                                                'accepte' => 'bg-green-100 text-green-800',
                                                'refuse' => 'bg-red-100 text-red-800',
                                                'converti' => 'bg-purple-100 text-purple-800'
                                            ];
                                        @endphp
                                        <span class="badge {{ $colors[$devisItem->statut_prospect] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $devisItem->statut_prospect)) }}
                                        </span>
                                        
                                        {{-- Indicateur convertible --}}
                                        @if($devisItem->peutEtreConverti())
                                            <div class="text-xs text-green-600 font-medium mt-1">
                                                ✅ Convertible
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-sm text-gray-500">{{ $devisItem->date_validite->format('d/m/Y') }}</span>
                                        @if($devisItem->date_validite->isPast() && !in_array($devisItem->statut_prospect, ['accepte', 'refuse', 'converti']))
                                            <div class="text-xs text-red-600">⏰ Expiré</div>
                                        @elseif($devisItem->date_validite->diffInDays(now()) <= 3 && !in_array($devisItem->statut_prospect, ['accepte', 'refuse', 'converti']))
                                            <div class="text-xs text-orange-600">⚠️ Expire bientôt</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex items-center space-x-2">
                                            {{-- Voir --}}
                                            <a href="{{ route('devis.show', $devisItem) }}" 
                                               class="text-blue-600 hover:text-blue-800" title="Voir le prospect">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </a>
                                            
                                            {{-- Actions spécifiques aux prospects --}}
                                            @can('update', $devisItem)
                                                {{-- Convertir en chantier --}}
                                                @if($devisItem->peutEtreConverti())
                                                    <a href="{{ route('devis.convert-to-chantier', $devisItem) }}" 
                                                       class="text-green-600 hover:text-green-800" title="Convertir en chantier">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                        </svg>
                                                    </a>
                                                @endif
                                                
                                                {{-- Historique négociation --}}
                                                @if($devisItem->historique_negociation)
                                                    <a href="{{ route('devis.historique-negociation', $devisItem) }}" 
                                                       class="text-orange-600 hover:text-orange-800" title="Historique négociation">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </a>
                                                @endif
                                            @endcan
                                            
                                            {{-- Menu actions --}}
                                            <div x-data="{ open: false }" class="relative">
                                                <button @click="open = !open" 
                                                        class="text-gray-600 hover:text-gray-900 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                    </svg>
                                                </button>
                                                <div x-show="open" 
                                                     @click.away="open = false"
                                                     x-transition
                                                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                                    <div class="py-1">
                                                        @can('update', $devisItem)
                                                            @if(in_array($devisItem->statut_prospect, ['brouillon', 'negocie']))
                                                                <a href="#" class="dropdown-item">📧 Envoyer au client</a>
                                                                <a href="#" class="dropdown-item">✏️ Modifier</a>
                                                                <a href="#" class="dropdown-item">🔄 Ajouter version</a>
                                                            @endif
                                                            
                                                            <a href="#" class="dropdown-item">📋 Dupliquer</a>
                                                            
                                                            @if($devisItem->peutEtreConverti())
                                                                <div class="border-t border-gray-100 my-1"></div>
                                                                <a href="{{ route('devis.convert-to-chantier', $devisItem) }}" 
                                                                   class="dropdown-item text-green-700">
                                                                    ✅ Convertir en chantier
                                                                </a>
                                                            @endif
                                                        @endcan
                                                        
                                                        <div class="border-t border-gray-100 my-1"></div>
                                                        <a href="#" class="dropdown-item">📄 PDF</a>
                                                        
                                                        @can('delete', $devisItem)
                                                            <div class="border-t border-gray-100 my-1"></div>
                                                            <a href="#" class="dropdown-item text-red-700">🗑️ Supprimer</a>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun prospect trouvé</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouveau devis prospect.</p>
                    @can('commercial-or-admin')
                        <div class="mt-6">
                            <a href="{{ route('devis.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                🎯 Créer un prospect
                            </a>
                        </div>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Scripts pour interactions --}}
<script>
// Sélection multiple
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.prospect-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Export sélection
function exportSelection() {
    const selected = [];
    document.querySelectorAll('.prospect-checkbox:checked').forEach(checkbox => {
        selected.push(checkbox.value);
    });
    
    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un prospect à exporter.');
        return;
    }
    
    // TODO: Implémenter l'export
    console.log('Export prospects:', selected);
    alert(`Export de ${selected.length} prospect(s) en cours...`);
}

// Actions rapides au clavier
document.addEventListener('keydown', function(e) {
    // Ctrl+A pour sélectionner tout
    if (e.ctrlKey && e.key === 'a') {
        e.preventDefault();
        document.getElementById('select-all').checked = true;
        document.getElementById('select-all').dispatchEvent(new Event('change'));
    }
    
    // Échap pour désélectionner
    if (e.key === 'Escape') {
        document.getElementById('select-all').checked = false;
        document.getElementById('select-all').dispatchEvent(new Event('change'));
    }
});
</script>
@endsection 