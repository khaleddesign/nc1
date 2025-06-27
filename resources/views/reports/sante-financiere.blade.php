@extends('layouts.app')

@section('title', 'Santé Financière')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="santeFinanciereData()">
    
    <!-- Header -->
    <div class="mb-8">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('reports.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                        📊 Reporting
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-900 font-medium">Santé Financière</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <div class="mt-4">
            <h1 class="text-3xl font-bold text-gray-900">Analyse de la Santé Financière</h1>
            <p class="mt-2 text-gray-600">Suivi des encaissements, impayés et trésorerie</p>
        </div>
    </div>

    <!-- Alertes importantes -->
    <div class="mb-8 space-y-4">
        @if($impayees->montant_en_retard > 0)
        <div class="bg-red-50 border-l-4 border-red-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Factures en retard détectées</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>{{ number_format($impayees->montant_en_retard, 0, ',', ' ') }} € de factures sont en retard de paiement.</p>
                    </div>
                    <div class="mt-4">
                        <button @click="showRetardDetails = true" class="btn btn-sm btn-danger">
                            Voir les détails
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($dso > 45)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">DSO élevé</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Votre délai moyen de paiement ({{ $dso }} jours) dépasse les standards recommandés (30-45 jours).</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- KPIs financiers -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Encaissé</p>
                    <p class="text-3xl font-bold" x-text="formatMoney(kpis.total_encaisse)">{{ number_format($total_encaisse, 0, ',', ' ') }} €</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Impayés</p>
                    <p class="text-3xl font-bold" x-text="formatMoney(kpis.montant_impaye)">{{ number_format($impayees->montant_total, 0, ',', ' ') }} €</p>
                    <p class="text-red-200 text-sm">{{ $impayees->nb_factures }} factures</p>
                </div>
                <div class="bg-red-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">DSO</p>
                    <p class="text-3xl font-bold">{{ $dso }} <span class="text-lg">jours</span></p>
                    <p class="text-purple-200 text-sm">Délai moyen paiement</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Taux de Recouvrement</p>
                    <p class="text-3xl font-bold">{{ round(($total_encaisse / max($total_encaisse + $impayees->montant_total, 1)) * 100, 1) }}%</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Évolution des encaissements -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Évolution des Encaissements</h3>
            <div class="h-80">
                <canvas id="encaissementsChart"></canvas>
            </div>
        </div>

        <!-- Répartition des modes de paiement -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Modes de Paiement</h3>
            <div class="h-80">
                <canvas id="paiementModesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Analyse des retards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Répartition par ancienneté -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Ancienneté des Impayés</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">0-30 jours</span>
                    <div class="flex items-center">
                        <span class="text-sm font-semibold text-gray-900 mr-2">{{ number_format($anciennete['0_30'] ?? 0, 0, ',', ' ') }} €</span>
                        <div class="w-16 bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ min(($anciennete['0_30'] ?? 0) / max($impayees->montant_total, 1) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">30-60 jours</span>
                    <div class="flex items-center">
                        <span class="text-sm font-semibold text-gray-900 mr-2">{{ number_format($anciennete['30_60'] ?? 0, 0, ',', ' ') }} €</span>
                        <div class="w-16 bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ min(($anciennete['30_60'] ?? 0) / max($impayees->montant_total, 1) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">60+ jours</span>
                    <div class="flex items-center">
                        <span class="text-sm font-semibold text-gray-900 mr-2">{{ number_format($anciennete['60_plus'] ?? 0, 0, ',', ' ') }} €</span>
                        <div class="w-16 bg-gray-200 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: {{ min(($anciennete['60_plus'] ?? 0) / max($impayees->montant_total, 1) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prévisionnel trésorerie -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Prévisionnel 30 jours</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Encaissements prévus</span>
                    <span class="text-lg font-semibold text-green-600">{{ number_format($previsionnel['encaissements'] ?? 0, 0, ',', ' ') }} €</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Factures à émettre</span>
                    <span class="text-lg font-semibold text-blue-600">{{ number_format($previsionnel['a_facturer'] ?? 0, 0, ',', ' ') }} €</span>
                </div>
                
                <div class="border-t pt-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Flux net prévu</span>
                        <span class="text-xl font-bold text-gray-900">{{ number_format(($previsionnel['encaissements'] ?? 0) + ($previsionnel['a_facturer'] ?? 0), 0, ',', ' ') }} €</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions recommandées -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Actions Recommandées</h3>
            <div class="space-y-3">
                @if($impayees->montant_en_retard > 0)
                <div class="flex items-start p-3 bg-red-50 rounded-lg">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-red-800">Relancer les impayés</p>
                        <p class="text-sm text-red-700">{{ $impayees->nb_factures }} factures nécessitent une relance</p>
                    </div>
                </div>
                @endif
                
                @if($dso > 45)
                <div class="flex items-start p-3 bg-yellow-50 rounded-lg">
                    <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">Améliorer les délais</p>
                        <p class="text-sm text-yellow-700">Revoir les conditions de paiement</p>
                    </div>
                </div>
                @endif
                
                <div class="flex items-start p-3 bg-blue-50 rounded-lg">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Automatiser les relances</p>
                        <p class="text-sm text-blue-700">Mettre en place un système automatique</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des factures critiques -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Factures Critiques</h3>
            <div class="flex space-x-2">
                <button @click="filterCritique = 'retard'" 
                        :class="filterCritique === 'retard' ? 'btn-danger' : 'btn-outline'"
                        class="btn btn-sm">En retard</button>
                <button @click="filterCritique = 'echues'" 
                        :class="filterCritique === 'echues' ? 'btn-warning' : 'btn-outline'"
                        class="btn btn-sm">Échues</button>
                <button @click="filterCritique = 'all'" 
                        :class="filterCritique === 'all' ? 'btn-primary' : 'btn-outline'"
                        class="btn btn-sm">Toutes</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Facture
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Client
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Montant
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Échéance
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Retard
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Relances
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Exemple de données simulées -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">F-2024-001</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Pierre Bernard</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">15 000 €</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">15/05/2024</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                45 jours
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">2 relances</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900 mr-3">Relancer</button>
                            <button class="text-green-600 hover:text-green-900">Appeler</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function santeFinanciereData() {
    return {
        filterCritique: 'retard',
        showRetardDetails: false,
        
        // Données KPIs
        kpis: {
            total_encaisse: {{ $total_encaisse }},
            montant_impaye: {{ $impayees->montant_total }},
            dso: {{ $dso }}
        },
        
        // Données pour graphiques
        encaissements_data: @json($encaissements),

        init() {
            this.initCharts();
        },

        initCharts() {
            // Graphique des modes de paiement
            const paiementModesCtx = document.getElementById('paiementModesChart').getContext('2d');
            new Chart(paiementModesCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Virement', 'Chèque', 'Carte Bancaire', 'Espèces', 'Autre'],
                    datasets: [{
                        data: [45, 25, 20, 5, 5], // Données simulées
                        backgroundColor: [
                            '#3B82F6',
                            '#8B5CF6', 
                            '#10B981',
                            '#F59E0B',
                            '#EF4444'
                        ]
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
        },

        formatMoney(amount) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount || 0);
        }
    }
}
</script>
@endpush Graphique des encaissements
            const encaissementsCtx = document.getElementById('encaissementsChart').getContext('2d');
            new Chart(encaissementsCtx, {
                type: 'line',
                data: {
                    labels: this.encaissements_data.map(item => item.mois),
                    datasets: [{
                        label: 'Encaissements',
                        data: this.encaissements_data.map(item => item.montant),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 3,
                        fill: true,
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
                                callback: function(value) {
                                    return new Intl.NumberFormat('fr-FR', {
                                        style: 'currency',
                                        currency: 'EUR',
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    }).format(value);
                                }
                            }
                        }
                    }
                }
            });

            //