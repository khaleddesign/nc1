@extends('layouts.app')

@section('title', 'Statistiques')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-chart-bar me-2"></i>Statistiques Détaillées</h1>
            <p class="text-muted">Analyse complète de l'activité</p>
        </div>
    </div>
    
    <!-- Statistiques générales -->
    <div class="row mt-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-1">Utilisateurs par rôle</h6>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Clients</span>
                            <strong>{{ $stats['users_by_role']['client'] ?? 0 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Commerciaux</span>
                            <strong>{{ $stats['users_by_role']['commercial'] ?? 0 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Admins</span>
                            <strong>{{ $stats['users_by_role']['admin'] ?? 0 }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-1">Chantiers par statut</h6>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Planifiés</span>
                            <strong>{{ $stats['chantiers_by_status']['planifie'] ?? 0 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>En cours</span>
                            <strong>{{ $stats['chantiers_by_status']['en_cours'] ?? 0 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Terminés</span>
                            <strong>{{ $stats['chantiers_by_status']['termine'] ?? 0 }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-1">Avancement Global</h6>
                    <div class="display-4 text-center mt-3">
                        {{ number_format($stats['average_progress'], 1) }}%
                    </div>
                    <div class="text-center">
                        <small>Moyenne tous chantiers</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-1">Budget Total</h6>
                    <div class="display-6 text-center mt-3">
                        {{ number_format(\App\Models\Chantier::sum('budget'), 0, ',', ' ') }} €
                    </div>
                    <div class="text-center">
                        <small>Tous chantiers confondus</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Graphiques -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>Évolution mensuelle des chantiers
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Répartition par statut
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top 5 -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-trophy me-2"></i>Top 5 Commerciaux
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $topCommerciaux = \App\Models\User::where('role', 'commercial')
                            ->withCount('chantiersCommercial')
                            ->orderBy('chantiers_commercial_count', 'desc')
                            ->take(5)
                            ->get();
                    @endphp
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Commercial</th>
                                    <th>Nombre de chantiers</th>
                                    <th>En cours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCommerciaux as $commercial)
                                    <tr>
                                        <td>{{ $commercial->name }}</td>
                                        <td>{{ $commercial->chantiers_commercial_count }}</td>
                                        <td>{{ $commercial->chantiersCommercial->where('statut', 'en_cours')->count() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-coins me-2"></i>Top 5 Chantiers (Budget)
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $topChantiers = \App\Models\Chantier::orderBy('budget', 'desc')
                            ->take(5)
                            ->get();
                    @endphp
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Chantier</th>
                                    <th>Client</th>
                                    <th>Budget</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topChantiers as $chantier)
                                    <tr>
                                        <td>
                                            <a href="{{ route('chantiers.show', $chantier) }}">
                                                {{ Str::limit($chantier->titre, 30) }}
                                            </a>
                                        </td>
                                        <td>{{ $chantier->client->name }}</td>
                                        <td>{{ number_format($chantier->budget, 0, ',', ' ') }} €</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Export -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-download me-2"></i>Export des données
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <button class="btn btn-outline-success w-100" onclick="exportData('excel')">
                        <i class="fas fa-file-excel me-2"></i>Export Excel
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-danger w-100" onclick="exportData('pdf')">
                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-primary w-100" onclick="exportData('csv')">
                        <i class="fas fa-file-csv me-2"></i>Export CSV
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-secondary w-100" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique mensuel
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const monthlyData = @json($stats['chantiers_by_month'] ?? []);
const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];

new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: Object.keys(monthlyData).map(m => months[parseInt(m) - 1]),
        datasets: [{
            label: 'Nouveaux chantiers',
            data: Object.values(monthlyData),
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
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

// Graphique camembert
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusData = @json($stats['chantiers_by_status'] ?? []);

new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Planifiés', 'En cours', 'Terminés'],
        datasets: [{
            data: [
                statusData['planifie'] ?? 0,
                statusData['en_cours'] ?? 0,
                statusData['termine'] ?? 0
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

// Export des données
function exportData(format) {
    // À implémenter : export réel des données
    alert('Export ' + format + ' - Fonctionnalité à implémenter');
}
</script>
@endsection