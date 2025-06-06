@extends('layouts.app')

@section('title', 'Liste des chantiers')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-hard-hat me-2"></i>Gestion des Chantiers</h1>
        
        @if(Auth::user()->isAdmin() || Auth::user()->isCommercial())
            <a href="{{ route('chantiers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nouveau Chantier
            </a>
        @endif
    </div>

    <!-- Filtres et recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('chantiers.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="planifie" {{ request('statut') == 'planifie' ? 'selected' : '' }}>Planifié</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search me-2"></i>Filtrer
                    </button>
                    <a href="{{ route('chantiers.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-2"></i>Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des chantiers -->
    @if($chantiers->count() > 0)
        <div class="row">
            @foreach($chantiers as $chantier)
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 {{ $chantier->isEnRetard() ? 'border-danger' : '' }}">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-building me-2"></i>{{ $chantier->titre }}
                            </h5>
                            <span class="badge {{ $chantier->getStatutBadgeClass() }}">
                                {{ $chantier->getStatutTexte() }}
                            </span>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ Str::limit($chantier->description, 100) }}</p>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Client</small>
                                    <strong>{{ $chantier->client->name }}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Commercial</small>
                                    <strong>{{ $chantier->commercial->name }}</strong>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Date début</small>
                                    {{ $chantier->date_debut ? $chantier->date_debut->format('d/m/Y') : 'Non définie' }}
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Date fin prévue</small>
                                    {{ $chantier->date_fin_prevue ? $chantier->date_fin_prevue->format('d/m/Y') : 'Non définie' }}
                                    @if($chantier->isEnRetard())
                                        <span class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($chantier->budget)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Budget</small>
                                    <strong>{{ number_format($chantier->budget, 2, ',', ' ') }} €</strong>
                                </div>
                            @endif

                            <!-- Barre de progression -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">Avancement global</small>
                                    <span class="badge bg-info">{{ number_format($chantier->avancement_global, 0) }}%</span>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar {{ $chantier->avancement_global == 100 ? 'bg-success' : '' }}" 
                                         role="progressbar" 
                                         style="width: {{ $chantier->avancement_global }}%">
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-secondary me-1">
                                        <i class="fas fa-tasks"></i> {{ $chantier->etapes->count() }} étapes
                                    </span>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-folder"></i> {{ $chantier->documents->count() }} documents
                                    </span>
                                </div>
                                <div>
                                    <a href="{{ route('chantiers.show', $chantier) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i>Voir
                                    </a>
                                    @if(Auth::user()->isAdmin() || (Auth::user()->isCommercial() && $chantier->commercial_id == Auth::id()))
                                        <a href="{{ route('chantiers.edit', $chantier) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $chantiers->links() }}
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            Aucun chantier trouvé.
            @if(Auth::user()->isAdmin() || Auth::user()->isCommercial())
                <a href="{{ route('chantiers.create') }}" class="alert-link">Créer le premier chantier</a>
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
            this.form.submit();
        }, 500);
    });
});
</script>
@endsection