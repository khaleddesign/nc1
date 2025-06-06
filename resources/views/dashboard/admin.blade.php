@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-tachometer-alt me-2"></i>Dashboard Administrateur</h1>
            <h2>Bonjour {{ Auth::user()->name }} !</h2>
            <p class="text-muted">Vue d'ensemble de tous les chantiers</p>
        </div>
    </div>
    
    <!-- Statistiques globales -->
    <!-- ... (pas de changement ici) ... -->

    <div class="row">
        <!-- Chantiers récents -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Chantiers Récents
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Chantier</th>
                                    <th>Client</th>
                                    <th>Commercial</th>
                                    <th>Statut</th>
                                    <th>Avancement</th>
                                    <th>Actions</th> <!-- Ajout colonne Actions -->
                                </tr>
                            </thead>
                            <tbody>
                                <!-- EXEMPLE boucle Laravel -->
                                @foreach ($chantiers as $chantier)
                                <tr>
                                    <td>
                                        <strong>{{ $chantier->titre }}</strong>
                                        <br><small class="text-muted">{{ $chantier->description }}</small>
                                    </td>
                                    <td>{{ $chantier->client->name ?? '' }}</td>
                                    <td>{{ $chantier->commercial->name ?? '' }}</td>
                                    <td>
                                        @if($chantier->statut === 'en_cours')
                                            <span class="badge bg-primary">En cours</span>
                                        @elseif($chantier->statut === 'planifie')
                                            <span class="badge bg-secondary">Planifié</span>
                                        @elseif($chantier->statut === 'termine')
                                            <span class="badge bg-success">Terminé</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $chantier->statut === 'termine' ? 'bg-success' : ($chantier->statut === 'planifie' ? 'bg-secondary' : '') }}"
                                                 role="progressbar" style="width: {{ $chantier->avancement_global ?? 0 }}%">
                                                {{ $chantier->avancement_global ?? 0 }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('chantiers.show', $chantier) }}" class="btn btn-outline-primary btn-sm">
                                            Voir détails
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                <!-- /EXEMPLE boucle -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Alertes et actions rapides -->
        <div class="col-md-4">
            <!-- ... (alertes inchangées) ... -->
            
            <!-- Actions rapides admin -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>Actions Rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('chantiers.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i> Nouveau Chantier
                        </a>
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-users me-2"></i> Gérer Utilisateurs
                        </a>
                        <a href="{{ route('admin.statistics') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-chart-bar me-2"></i> Statistiques Détaillées
                        </a>
                        <!-- À remplacer par la bonne route si besoin -->
                        <a href="#" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-file-export me-2"></i> Export Données
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- ... (reste inchangé) ... -->
        </div>
    </div>
    
    <!-- ... (reste inchangé) ... -->
</div>
@endsection
