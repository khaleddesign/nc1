@extends('layouts.app')

@section('title', 'Rapport Chiffre d\'Affaires')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="chiffreAffairesData()">
    
    <!-- Header avec navigation -->
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
                        <span class="ml-1 text-gray-900 font-medium">Chiffre d'Affaires</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <div class="mt-4">
            <h1 class="text-3xl font-bold text-gray-900">Analyse du Chiffre d'Affaires</h1>
            <p class="mt-2 text-gray-600">Évolution, tendances et performance par commercial</p>
        </div>
    </div>

    <!-- Filtres avancés -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Filtres d'Analyse</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="form-label">Date début</label>
                    <input type="date" x-model="filters.date_debut" @change="loadData()" class="form-input">
                </div>
                <div>
                    <label class="form-label">Date fin</label>
                    <input type="date" x-model="filters.date_fin" @change="loadData()" class="form-input">
                </div>
                <div>
                    <label class="form-label">Commercial</label>
                    <select x-model="filters.commercial_id" @change="loadData()" class="form-select">
                        <option value="">Tous les commerciaux</option>
                        @foreach(\App\Models\User::where('role', 'commercial')->get() as $commercial)
                            <option value="{{ $commercial->id }}">{{ $commercial->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Période d'agrégation</label>
                    <select x-model="filters.periode" @change="loadData()" class="form-select">
                        <option value="mensuel">Mensuel</option>
                        <option value="trimestriel">Trimestriel</option>
                        <option value="annuel">Annuel</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button @click="exportReport()" class="btn btn-primary w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs Résumé -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">CA Total</dt>
                        <dd class="text-lg font-semibold text-gray-900" x-text="formatMoney(summary.total)">{{ number_format($ca_mensuel->sum('montant'), 0, ',', ' ') }} €</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Moyenne Mensuelle</dt>
                        <dd class="text-lg font-semibold text-gray-900" x-text="formatMoney(summary.moyenne)">{{ number_format($ca_mensuel->avg('montant'), 0, ',', ' ') }} €</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Nb Commerciaux</dt>
                        <dd class="text-lg font-semibold text-gray-900" x-text="summary.nb_commerciaux">{{ $ca_par_commercial->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Évolution</dt>
                        <dd class="text-lg font-semibold" 
                            :class="summary.evolution >= 0 ? 'text-green-600' : 'text-red-600'"
                            x-text="(summary.evolution >= 0 ? '+' : '') + summary.evolution + '%'">
                            @php 
                                $evolution = $ca_mensuel->count() > 1 ? 
                                    round((($ca_mensuel->last()->montant - $ca_mensuel->first()->montant) / $ca_mensuel->first()->montant) * 100, 1) : 0;
                            @endphp
                            {{ $evolution >= 0 ? '+' : '' }}{{ $evolution }}%
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Évolution temporelle -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Évolution du CA</h3>
                <div class="flex space-x-2">
                    <button @click="chartType = 'line'" 
                            :class="chartType === 'line' ? 'btn-primary' : 'btn-outline'"
                            class="btn btn-sm">Ligne</button>
                    <button @click="chartType = 'bar'" 
                            :class="chartType === 'bar' ? 'btn-primary' : 'btn-outline'"
                            class="btn btn-sm">Barres</button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="evolutionChart"></canvas>
            </div>
        </div>

        <!-- Répartition par commercial -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Répartition par Commercial</h3>
            <div class="h-80">
                <canvas id="commercialChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tableau détaillé par commercial -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Performance par Commercial</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Commercial
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            CA Réalisé
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nb Factures
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Panier Moyen
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Part de Marché
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Performance
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($ca_par_commercial as $index => $commercial)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-bold">{{ $index + 1 }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $commercial->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ number_format($commercial->montant, 0, ',', ' ') }} €</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $commercial->nb_factures }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($commercial->montant / max($commercial->nb_factures, 1), 0, ',', ' ') }} €</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php $part = round(($commercial->montant / $ca_par_commercial->sum('montant')) * 100, 1); @endphp
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($part, 100) }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600">{{ $part }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($index === 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    🏆 Top Performer
                                </span>
                            @elseif($index < 3)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ⭐ Excellent
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    👍 Bon
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Loading overlay -->
    <div x-show="loading" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700">Chargement des données...</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function chiffreAffairesData() {
    return {
        loading: false,
        chartType: 'line',
        evolutionChart: null,
        commercialChart: null,
        
        // Données initiales
        evolution_data: @json($ca_mensuel),
        commercial_data: @json($ca_par_commercial),
        
        summary: {
            total: {{ $ca_mensuel->sum('montant') }},
            moyenne: {{ $ca_mensuel->avg('montant') }},
            nb_commerciaux: {{ $ca_par_commercial->count() }},
            evolution: {{ $ca_mensuel->count() > 1 ? round((($ca_mensuel->last()->montant - $ca_mensuel->first()->montant) / $ca_mensuel->first()->montant) * 100, 1) : 0 }}
        },
        
        filters: {
            date_debut: '{{ $filters["date_debut"] }}',
            date_fin: '{{ $filters["date_fin"] }}',
            commercial_id: '{{ $filters["commercial_id"] ?? "" }}',
            periode: '{{ $filters["periode"] }}'
        },

        init() {
            this.initCharts();
        },

        initCharts() {
            // Graphique d'évolution
            const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
            this.evolutionChart = new Chart(evolutionCtx, {
                type: this.chartType,
                data: {
                    labels: this.evolution_data.map(item => item.periode),
                    datasets: [{
                        label: 'Chiffre d\'Affaires',
                        data: this.evolution_data.map(item => item.montant),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: this.chartType === 'line' ? 'rgba(59, 130, 246, 0.1)' : 'rgba(59, 130, 246, 0.8)',
                        borderWidth: 3,
                        fill: this.chartType === 'line',
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

            // Graphique commerciaux (donut)
            const commercialCtx = document.getElementById('commercialChart').getContext('2d');
            this.commercialChart = new Chart(commercialCtx, {
                type: 'doughnut',
                data: {
                    labels: this.commercial_data.map(item => item.name),
                    datasets: [{
                        data: this.commercial_data.map(item => item.montant),
                        backgroundColor: [
                            '#3B82F6', '#8B5CF6', '#EF4444', '#10B981', '#F59E0B',
                            '#6366F1', '#EC4899', '#14B8A6', '#F97316', '#84CC16'
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

        updateChartType() {
            this.evolutionChart.destroy();
            this.initCharts();
        },

        async loadData() {
            this.loading = true;
            try {
                const response = await fetch('/reports/api-data?' + new URLSearchParams({
                    ...this.filters,
                    type: 'ca_detail'
                }), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.updateData(data);
                }
            } catch (error) {
                console.error('Erreur lors du chargement des données:', error);
                this.showToast('Erreur lors du chargement des données', 'error');
            } finally {
                this.loading = false;
            }
        },

        updateData(data) {
            if (data.evolution) {
                this.evolution_data = data.evolution;
                this.updateCharts();
            }
            if (data.commercial) {
                this.commercial_data = data.commercial;
                this.updateCharts();
            }
            if (data.summary) {
                this.summary = data.summary;
            }
        },

        updateCharts() {
            // Mise à jour graphique évolution
            if (this.evolutionChart) {
                this.evolutionChart.data.labels = this.evolution_data.map(item => item.periode);
                this.evolutionChart.data.datasets[0].data = this.evolution_data.map(item => item.montant);
                this.evolutionChart.update();
            }

            // Mise à jour graphique commerciaux
            if (this.commercialChart) {
                this.commercialChart.data.labels = this.commercial_data.map(item => item.name);
                this.commercialChart.data.datasets[0].data = this.commercial_data.map(item => item.montant);
                this.commercialChart.update();
            }
        },

        formatMoney(amount) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount || 0);
        },

        async exportReport() {
            this.loading = true;
            try {
                const params = new URLSearchParams({...this.filters, type: 'ca'});
                window.open(`/reports/export-pdf?${params}`, '_blank');
            } catch (error) {
                this.showToast('Erreur lors de l\'export', 'error');
            } finally {
                this.loading = false;
            }
        },

        showToast(message, type = 'info') {
            if (typeof window.showToast === 'function') {
                window.showToast(message, type);
            }
        }
    }
}

// Watcher pour le type de graphique
document.addEventListener('alpine:init', () => {
    Alpine.effect(() => {
        const data = Alpine.store('chiffreAffaires');
        if (data && data.chartType) {
            data.updateChartType();
        }
    });
});
</script>
@endpush