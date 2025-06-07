@extends('layouts.app')

@section('title', 'Dashboard Administrateur')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-tachometer-alt me-2"></i>Dashboard Administrateur</h1>
            <h2>Bonjour {{ Auth::user()->name }} !</h2>
            <p class="text-muted">Vue d'ensemble de tous les chantiers</p>
        </div>
    </div>
    
    <!-- Statistiques globales -->
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
                            <h2 class="mb-0">{{ $stats['chantiers_en_cours'] }}</h2>
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
                            <h2 class="mb-0">{{ $stats['chantiers_termines'] }}</h2>
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
        <!-- Chantiers récents -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Chantiers Récents
                    </h5>
                </div>
                <div class="card-body">
                    @if($chantiers_recents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Chantier</th>
                                        <th>Client</th>
                                        <th>Commercial</th>
                                        <th>Statut</th>
                                        <th>Avancement</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($chantiers_recents as $chantier)
                                    <tr>
                                        <td>
                                            <strong>{{ $chantier->titre }}</strong>
                                            @if($chantier->description)
                                                <br><small class="text-muted">{{ Str::limit($chantier->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $chantier->client->name }}</td>
                                        <td>{{ $chantier->commercial->name }}</td>
                                        <td>
                                            <span class="badge {{ $chantier->getStatutBadgeClass() }}">
                                                {{ $chantier->getStatutTexte() }}
                                            </span>
                                            @if($chantier->isEnRetard())
                                                <br><small class="text-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> En retard
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar {{ $chantier->avancement_global == 100 ? 'bg-success' : '' }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $chantier->avancement_global }}%">
                                                    {{ number_format($chantier->avancement_global, 0) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('chantiers.show', $chantier) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('chantiers.edit', $chantier) }}" 
                                               class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Aucun chantier récent</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Panneau latéral -->
        <div class="col-lg-4">
            <!-- Chantiers en retard -->
            @if($chantiers_retard->count() > 0)
            <div class="card mb-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Chantiers en Retard
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($chantiers_retard as $chantier)
                        <div class="mb-3">
                            <a href="{{ route('chantiers.show', $chantier) }}" 
                               class="text-decoration-none text-dark">
                                <strong>{{ $chantier->titre }}</strong>
                            </a>
                            <br>
                            <small class="text-muted">
                                Client: {{ $chantier->client->name }}<br>
                                Fin prévue: {{ $chantier->date_fin_prevue->format('d/m/Y') }}
                                <span class="text-danger">
                                    ({{ $chantier->date_fin_prevue->diffForHumans() }})
                                </span>
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Actions rapides -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Actions Rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('chantiers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nouveau Chantier
                        </a>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus me-2"></i>Ajouter Utilisateur
                        </a>
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-users me-2"></i>Gérer Utilisateurs
                        </a>
                        <a href="{{ route('admin.statistics') }}" class="btn btn-outline-info">
                            <i class="fas fa-chart-bar me-2"></i>Statistiques Détaillées
                        </a>
                        <a href="{{ route('chantiers.calendrier') }}" class="btn btn-outline-success">
                            <i class="fas fa-calendar me-2"></i>Calendrier
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Statistiques utilisateurs -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-users me-2"></i>Utilisateurs
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="mb-0">{{ $stats['total_clients'] }}</h4>
                            <small class="text-muted">Clients</small>
                        </div>
                        <div class="col-4">
                            <h4 class="mb-0">{{ $stats['total_commerciaux'] }}</h4>
                            <small class="text-muted">Commerciaux</small>
                        </div>
                        <div class="col-4">
                            <h4 class="mb-0">{{ \App\Models\User::where('role', 'admin')->count() }}</h4>
                            <small class="text-muted">Admins</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Graphiques (optionnel) -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Répartition par Statut
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Chantiers par Mois
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique de répartition par statut
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Planifiés', 'En cours', 'Terminés'],
        datasets: [{
            data: [
                {{ $chantiers->where('statut', 'planifie')->count() }},
                {{ $chantiers->where('statut', 'en_cours')->count() }},
                {{ $chantiers->where('statut', 'termine')->count() }}
            ],
            backgroundColor: ['#6c757d', '#ffc107', '#28a745']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Graphique des chantiers par mois
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const monthData = @json($chantiers->groupBy(function($c) { 
    return $c->created_at->format('Y-m'); 
})->map->count());

new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: Object.keys(monthData).slice(-6), // 6 derniers mois
        datasets: [{
            label: 'Nombre de chantiers',
            data: Object.values(monthData).slice(-6),
            backgroundColor: '#007bff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
@endsection