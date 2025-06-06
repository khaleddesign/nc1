@extends('layouts.app')

@section('title', 'Dashboard Commercial')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-briefcase me-2"></i>Mes Chantiers</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="#" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouveau Chantier
        </a>
    </div>
</div>

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <div class="h3 mb-0">{{ $stats['total_chantiers'] }}</div>
                <div>Total Chantiers</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <div class="h3 mb-0">{{ $stats['en_cours'] }}</div>
                <div>En Cours</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <div class="h3 mb-0">{{ $stats['termines'] }}</div>
                <div>Terminés</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <div class="h3 mb-0">{{ number_format($stats['avancement_moyen'], 1) }}%</div>
                <div>Avancement Moyen</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Liste des chantiers -->
    <div class="col-md-8">
        <h5>Mes Chantiers</h5>
        @if($mes_chantiers->count() > 0)
            @foreach($mes_chantiers as $chantier)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title">{{ $chantier->titre }}</h5>
                            <p class="card-text text-muted">
                                <i class="fas fa-user me-1"></i>{{ $chantier->client->name }}
                                @if($chantier->date_fin_prevue)
                                    <br><i class="fas fa-calendar me-1"></i>{{ $chantier->date_fin_prevue->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-{{ $chantier->statut == 'en_cours' ? 'primary' : ($chantier->statut == 'termine' ? 'success' : 'secondary') }} mb-2">
                                {{ ucfirst(str_replace('_', ' ', $chantier->statut)) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="progress mb-2" style="height: 25px;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ $chantier->avancement_global }}%">
                            {{ number_format($chantier->avancement_global, 1) }}%
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Budget: {{ $chantier->budget ? number_format($chantier->budget, 0) . ' €' : 'Non défini' }}
                        </small>
                        <div>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                Voir détails
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-hammer fa-3x text-muted mb-3"></i>
                    <h5>Aucun chantier</h5>
                    <p class="text-muted">Vous n'avez encore aucun chantier assigné.</p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Notifications récentes -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-bell me-2"></i>Notifications Récentes
                </h6>
            </div>
            <div class="card-body">
                @if(isset($notifications) && $notifications->count() > 0)
                    @foreach($notifications as $notification)
                    <div class="border-bottom py-2">
                        <div class="fw-bold">{{ $notification->titre }}</div>
                        <small class="text-muted">{{ $notification->message }}</small>
                        <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted mb-0">Aucune notification.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection