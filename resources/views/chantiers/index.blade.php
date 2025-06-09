{{-- Exemple de migration de chantiers/index.blade.php vers Tailwind --}}
@extends('layouts.app')

@section('title', 'Liste des chantiers')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
            <svg class="inline-block w-6 h-6 mr-2 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18M6.75 9h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-3.75-16.5h3.75A2.25 2.25 0 0121 6.75v12a2.25 2.25 0 01-2.25 2.25h-3.75M16.5 7.5V21a.75.75 0 01-.75.75M6 7.5V21a.75.75 0 01-.75.75" />
            </svg>
            Gestion des Chantiers
        </h1>
        
        @if(Auth::user()->isAdmin() || Auth::user()->isCommercial())
            <a href="{{ route('chantiers.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nouveau Chantier
            </a>
        @endif
    </div>

    <!-- Filtres et recherche -->
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('chantiers.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" 
                           name="search" 
                           class="form-input" 
                           placeholder="Rechercher..." 
                           value="{{ request('search') }}">
                </div>
                <div>
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="planifie" {{ request('statut') == 'planifie' ? 'selected' : '' }}>Planifié</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        Filtrer
                    </button>
                    <a href="{{ route('chantiers.index') }}" class="btn btn-outline">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des chantiers -->
    @if($chantiers->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            @foreach($chantiers as $chantier)
                <div class="chantier-card {{ $chantier->isEnRetard() ? 'border-l-4 border-danger-400' : '' }}">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <h5 class="text-lg font-semibold text-gray-900">
                                <svg class="inline-block w-5 h-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18M6.75 9h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-3.75-16.5h3.75A2.25 2.25 0 0121 6.75v12a2.25 2.25 0 01-2.25 2.25h-3.75M16.5 7.5V21a.75.75 0 01-.75.75M6 7.5V21a.75.75 0 01-.75.75" />
                                </svg>
                                {{ $chantier->titre }}
                            </h5>
                            <span class="badge {{ $chantier->getStatutBadgeClass() }}">
                                {{ $chantier->getStatutTexte() }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $chantiers->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun chantier trouvé</h3>
            <p class="text-gray-500 mb-4">
                @if(Auth::user()->isAdmin() || Auth::user()->isCommercial())
                    Créez votre premier chantier pour commencer.
                @else
                    Aucun chantier n'est disponible pour le moment.
                @endif
            </p>
            @if(Auth::user()->isAdmin() || Auth::user()->isCommercial())
                <a href="{{ route('chantiers.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Créer le premier chantier
                </a>
            @endif
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
// Recherche en temps réel (optionnel)
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    let searchTimeout;
    
    searchInput?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            // Auto-submit après 500ms de pause
            this.form.submit();
        }, 500);
    });
});
</script>
@endsection    <div class="card-body">
                        <p class="text-gray-600 mb-4">{{ Str::limit($chantier->description, 100) }}</p>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <small class="block text-gray-500 font-medium">Client</small>
                                <strong class="text-gray-900">{{ $chantier->client->name }}</strong>
                            </div>
                            <div>
                                <small class="block text-gray-500 font-medium">Commercial</small>
                                <strong class="text-gray-900">{{ $chantier->commercial->name }}</strong>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <small class="block text-gray-500 font-medium">Date début</small>
                                <span class="text-gray-900">{{ $chantier->date_debut ? $chantier->date_debut->format('d/m/Y') : 'Non définie' }}</span>
                            </div>
                            <div>
                                <small class="block text-gray-500 font-medium">Date fin prévue</small>
                                <span class="text-gray-900">{{ $chantier->date_fin_prevue ? $chantier->date_fin_prevue->format('d/m/Y') : 'Non définie' }}</span>
                                @if($chantier->isEnRetard())
                                    <svg class="inline-block w-4 h-4 ml-1 text-danger-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </div>
                        </div>

                        @if($chantier->budget)
                            <div class="mb-4">
                                <small class="block text-gray-500 font-medium">Budget</small>
                                <strong class="text-gray-900">{{ number_format($chantier->budget, 2, ',', ' ') }} €</strong>
                            </div>
                        @endif

                        <!-- Barre de progression -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <small class="text-gray-500 font-medium">Avancement global</small>
                                <span class="badge badge-info">{{ number_format($chantier->avancement_global, 0) }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar {{ $chantier->avancement_global == 100 ? 'progress-bar-success' : 'progress-bar-primary' }}" 
                                     style="width: {{ $chantier->avancement_global }}%">
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-between items-center">
                            <div class="flex gap-2">
                                <span class="badge badge-secondary">
                                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3-7.5H21m-4.5 0H21m-4.5 0c0-.621-.504-1.125-1.125-1.125M3.375 7.5h1.5c.621 0 1.125.504 1.125 1.125m-2.625 0v12c0 .621.504 1.125 1.125 1.125m-1.125 0h1.5m-1.125 0c-.621 0-1.125-.504-1.125-1.125M18 12v6c0 .621-.504 1.125-1.125 1.125M15.75 12v6c0 .621-.504 1.125-1.125 1.125m-8.625-1.125v-1.5c0-.621.504-1.125 1.125-1.125h2.25s.621.504 1.125 1.125" />
                                    </svg>
                                    {{ $chantier->etapes->count() }} étapes
                                </span>
                                <span class="badge badge-secondary">
                                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                                    </svg>
                                    {{ $chantier->documents->count() }} documents
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('chantiers.show', $chantier) }}" class="btn btn-primary btn-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Voir
                                </a>
                                @if(Auth::user()->isAdmin() || (Auth::user()->isCommercial() && $chantier->commercial_id == Auth::id()))
                                    <a href="{{ route('chantiers.edit', $chantier) }}" class="btn btn-outline btn-sm">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>