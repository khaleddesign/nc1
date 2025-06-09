@extends('layouts.app')

@section('title', 'Statistiques')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
            <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
            Statistiques Détaillées
        </h1>
        <p class="text-gray-500 mt-1">Analyse complète de l'activité</p>
    </div>
    
    <!-- Statistiques générales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card bg-primary-600 text-white">
            <div class="card-body">
                <h6 class="text-white/90 text-xs uppercase font-medium tracking-wider mb-3">Utilisateurs par rôle</h6>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-white/90">Clients</span>
                        <span class="font-bold text-white">{{ $stats['users_by_role']['client'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/90">Commerciaux</span>
                        <span class="font-bold text-white">{{ $stats['users_by_role']['commercial'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/90">Admins</span>
                        <span class="font-bold text-white">{{ $stats['users_by_role']['admin'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card bg-success-600 text-white">
            <div class="card-body">
                <h6 class="text-white/90 text-xs uppercase font-medium tracking-wider mb-3">Chantiers par statut</h6>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-white/90">Planifiés</span>
                        <span class="font-bold text-white">{{ $stats['chantiers_by_status']['planifie'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/90">En cours</span>
                        <span class="font-bold text-white">{{ $stats['chantiers_by_status']['en_cours'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/90">Terminés</span>
                        <span class="font-bold text-white">{{ $stats['chantiers_by_status']['termine'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card bg-warning-500 text-white">
            <div class="card-body">
                <h6 class="text-white/90 text-xs uppercase font-medium tracking-wider mb-3">Avancement Global</h6>
                <div class="text-4xl font-bold text-center mt-4 mb-2">
                    {{ number_format($stats['average_progress'], 1) }}%
                </div>
                <div class="text-center">
                    <small class="text-white/80">Moyenne tous chantiers</small>
                </div>
            </div>
        </div>
        
        <div class="card bg-blue-500 text-white">
            <div class="card-body">
                <h6 class="text-white/90 text-xs uppercase font-medium tracking-wider mb-3">Budget Total</h6>
                <div class="text-2xl font-bold text-center mt-4 mb-2">
                    {{ number_format(\App\Models\Chantier::sum('budget'), 0, ',', ' ') }} €
                </div>
                <div class="text-center">
                    <small class="text-white/80">Tous chantiers confondus</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                        Évolution mensuelle des chantiers
                    </h5>
                </div>
                <div class="card-body">
                    <div class="h-75">
                        <canvas id="monthlyChart" class="w-full"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-1">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                        Répartition par statut
                    </h5>
                </div>
                <div class="card-body">
                    <div class="h-75">
                        <canvas id="statusChart" class="w-full"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top 5 -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="card">
            <div class="card-header">
                <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-warning-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236S7.5 4.5 12 4.5s6.75.264 6.75.264-2.244-.264-6.75-.264c-4.506 0-6.75.264-6.75.264zm16.5 13.5a2.25 2.25 0 00-2.25-2.25h-3.75a2.25 2.25 0 00-2.25 2.25v1.5a2.25 2.25 0 002.25 2.25h3.75a2.25 2.25 0 002.25-2.25v-1.5z" />
                    </svg>
                    Top 5 Commerciaux
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
                <div class="overflow-x-auto">
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
        
        <div class="card">
            <div class="card-header">
                <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-success-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12s-1.536.219-2.121.659c-1.172.879-1.172 2.303 0 3.182l.879.659zm-1.415-2.828l-.879-.659c-1.171-.879-3.07-.879-4.242 0-1.172.879-1.172 2.303 0 3.182C6.536 11.781 7.268 12 8 12s1.536-.219 2.121-.659c1.172-.879 1.172-2.303 0-3.182l-.879-.659z" />
                    </svg>
                    Top 5 Chantiers (Budget)
                </h5>
            </div>
            <div class="card-body">
                @php
                    $topChantiers = \App\Models\Chantier::orderBy('budget', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                <div class="overflow-x-auto">
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
                                        <a href="{{ route('chantiers.show', $chantier) }}" class="text-primary-600 hover:text-primary-800">
                                            {{ Str::limit($chantier->titre, 30) }}
                                        </a>
                                    </td>
                                    <td>{{ $chantier->client->name }}</td>
                                    <td class="font-medium">{{ number_format($chantier->budget, 0, ',', ' ') }} €</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Export -->
    <div class="card">
        <div class="card-header">
            <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export des données
            </h5>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <button class="btn btn-success w-full" onclick="exportData('excel')">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 18l-3-3m0 0l-3 3m3-3v-6" />
                    </svg>
                    Export Excel
                </button>
                <button class="btn btn-danger w-full" onclick="exportData('pdf')">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 18l-3-3m0 0l-3 3m3-3v-6" />
                    </svg>
                    Export PDF
                </button>
                <button class="btn btn-primary w-full" onclick="exportData('csv')">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 18l-3-3m0 0l-3 3m3-3v-6" />
                    </svg>
                    Export CSV
                </button>
                <button class="btn btn-secondary w-full" onclick="window.print()">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                    </svg>
                    Imprimer
                </button>
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
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37, 99, 235, 0.1)',
            tension: 0.4,
            borderWidth: 2,
            pointBackgroundColor: '#2563eb',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 4,
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
                    stepSize: 1,
                    color: '#6b7280',
                },
                grid: {
                    color: '#f3f4f6',
                }
            },
            x: {
                ticks: {
                    color: '#6b7280',
                },
                grid: {
                    color: '#f3f4f6',
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
            backgroundColor: ['#6b7280', '#f59e0b', '#22c55e'],
            borderWidth: 2,
            borderColor: '#ffffff',
            hoverBorderWidth: 3,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#374151',
                    padding: 20,
                    usePointStyle: true,
                }
            }
        },
        cutout: '60%',
    }
});

// Export des données
function exportData(format) {
    // À implémenter : export réel des données
    alert('Export ' + format + ' - Fonctionnalité à implémenter');
}
</script>
@endsection