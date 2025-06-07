@extends('layouts.app')

@section('title', 'Dashboard Commercial')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-briefcase me-2"></i>Dashboard Commercial</h1>
            <h2>Bonjour {{ Auth::user()->name }} !</h2>
            <p class="text-muted">Gérez vos chantiers et suivez leur progression</p>
        </div>
    </div>
    
    <!-- Statistiques dynamiques -->
    <div class="row mt-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Chantiers</h6>
                            <h2 class="mb-0">{{ $stats['total_chantiers'] }}</h2>
                        </div>
                        <div class="opacity-75">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">En Cours</h6>
                            <h2 class="mb-0">{{ $stats['en_cours'] }}</h2>
                        </div>
                        <div class="opacity-75">
                            <i class="fas fa-hard-hat fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Terminés</h6>
                            <h2 class="mb-0">{{ $stats['termines'] }}</h2>
                        </div>
                        <div class="opacity-75">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Avancement Moyen</h6>
                            <h2 class="mb-0">{{ number_format($stats['avancement_moyen'], 1) }}%</h2>
                        </div>
                        <div class="opacity-75">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Liste des chantiers -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Mes Chantiers
                    </h5>
                    <a href="{{ route('chantiers.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Nouveau
                    </a>
                </div>
                <div class="card-body">
                    @if($mes_chantiers->count() > 0)
                        @foreach($mes_chantiers as $chantier)
                            <div class="card mb-3 {{ $chantier->isEnRetard() ? 'border-danger' : '' }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="card-title">
                                                <a href="{{ route('chantiers.show', $chantier) }}" 
                                                   class="text-decoration-none text-dark">
                                                    {{ $chantier->titre }}
                                                </a>
                                            </h5>
                                            <p class="card-text text-muted">
                                                <i class="fas fa-user me-1"></i>{{ $chantier->client->name }}
                                                @if($chantier->client->telephone)
                                                    - <i class="fas fa-phone me-1"></i>{{ $chantier->client->telephone }}
                                                @endif
                                                @if($chantier->date_fin_prevue)
                                                    <br><i class="fas fa-calendar me-1"></i>
                                                    Fin prévue : {{ $chantier->date_fin_prevue->format('d/m/Y') }}
                                                    @if($chantier->isEnRetard())
                                                        <span class="text-danger">
                                                            ({{ $chantier->date_fin_prevue->diffForHumans() }})
                                                        </span>
                                                    @endif
                                                @endif
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge {{ $chantier->getStatutBadgeClass() }} mb-2">
                                                {{ $chantier->getStatutTexte() }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Barre de progression -->
                                    <div class="progress mb-2" style="height: 25px;">
                                        <div class="progress-bar {{ $chantier->avancement_global == 100 ? 'bg-success' : '' }}" 
                                             role="progressbar" 
                                             style="width: {{ $chantier->avancement_global }}%">
                                            {{ number_format($chantier->avancement_global, 0) }}%
                                        </div>
                                    </div>
                                    
                                    <!-- Informations et actions -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            @if($chantier->budget)
                                                Budget: {{ number_format($chantier->budget, 0, ',', ' ') }} €
                                            @endif
                                            @if($chantier->etapes->count() > 0)
                                                | {{ $chantier->etapes->count() }} étapes
                                            @endif
                                            @if($chantier->documents->count() > 0)
                                                | {{ $chantier->documents->count() }} documents
                                            @endif
                                        </small>
                                        <div>
                                            <a href="{{ route('chantiers.show', $chantier) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>Voir
                                            </a>
                                            <a href="{{ route('chantiers.edit', $chantier) }}" 
                                               class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit me-1"></i>Modifier
                                            </a>
                                            <a href="{{ route('chantiers.etapes', $chantier) }}" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-tasks me-1"></i>Étapes
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5>Aucun chantier assigné</h5>
                            <p class="text-muted">Créez votre premier chantier pour commencer</p>
                            <a href="{{ route('chantiers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Créer un chantier
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Panneau latéral -->
        <div class="col-lg-4">
            <!-- Notifications récentes -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bell me-2"></i>Notifications Récentes
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($notifications) && $notifications->count() > 0)
                        @foreach($notifications as $notification)
                            <div class="border-bottom py-2">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $notification->titre }}</strong>
                                    <form method="POST" action="{{ route('notifications.read', $notification) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link btn-sm p-0">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                </div>
                                <small class="text-muted">{{ $notification->message }}</small>
                                <div class="small text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary">
                                Voir toutes les notifications
                            </a>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Aucune nouvelle notification</p>
                    @endif
                </div>
            </div>
            
            <!-- Actions rapides -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Actions Rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('chantiers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouveau Chantier
                        </a>
                        <a href="{{ route('chantiers.calendrier') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-calendar me-2"></i>Voir le Calendrier
                        </a>
                        <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#quickUploadModal">
                            <i class="fas fa-upload me-2"></i>Upload Rapide
                        </button>
                        <a href="#" class="btn btn-outline-success">
                            <i class="fas fa-file-pdf me-2"></i>Générer Rapport
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Chantiers en retard -->
            @php
                $chantiersEnRetard = $mes_chantiers->filter(function($c) {
                    return $c->isEnRetard();
                });
            @endphp
            @if($chantiersEnRetard->count() > 0)
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>Chantiers en Retard
                        </h6>
                    </div>
                    <div class="card-body">
                        @foreach($chantiersEnRetard as $chantier)
                            <div class="mb-2">
                                <a href="{{ route('chantiers.show', $chantier) }}" 
                                   class="text-decoration-none">
                                    {{ $chantier->titre }}
                                </a>
                                <br>
                                <small class="text-danger">
                                    {{ $chantier->date_fin_prevue->diffForHumans() }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Upload Rapide -->
<div class="modal fade" id="quickUploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Rapide de Documents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickUploadForm">
                    <div class="mb-3">
                        <label class="form-label">Sélectionner le chantier</label>
                        <select class="form-select" name="chantier_id" required>
                            <option value="">Choisir un chantier...</option>
                            @foreach($mes_chantiers as $chantier)
                                <option value="{{ $chantier->id }}">{{ $chantier->titre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fichiers</label>
                        <input type="file" class="form-control" name="fichiers[]" multiple required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="submitQuickUpload()">
                    <i class="fas fa-upload me-1"></i>Uploader
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function submitQuickUpload() {
    const form = document.getElementById('quickUploadForm');
    const chantierId = form.querySelector('[name="chantier_id"]').value;
    
    if (chantierId) {
        form.action = `/chantiers/${chantierId}/documents`;
        form.method = 'POST';
        form.enctype = 'multipart/form-data';
        
        // Ajouter le token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        // Ajouter le type
        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type';
        typeInput.value = 'document';
        form.appendChild(typeInput);
        
        form.submit();
    }
}
</script>
@endsection