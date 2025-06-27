@extends('layouts.app')

@section('title', 'Performance Commerciale')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="performanceData()">
    
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
                        <span class="ml-1 text-gray-900 font-medium">Performance Commerciale</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <div class="mt-4">
            <h1 class="text-3xl font-bold text-gray-900">Performance de l'Équipe Commerciale</h1>
            <p class="mt-2 text-gray-600">Analyse des performances individuelles et comparatives</p>
        </div>
    </div>

    <!-- KPIs Globaux -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">CA Équipe</p>
                    <p class="text-3xl font-bold" x-text="formatMoney(kpis.ca_total)">{{ number_format($classement_commerciaux->sum('ca_realise'), 0, ',', ' ') }} €</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Commerciaux Actifs</p>
                    <p class="text-3xl font-bold">{{ $classement_commerciaux->count() }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">CA Moyen</p>
                    <p class="text-3xl font-bold" x-text="formatMoney(kpis.ca_moyen)">{{ number_format($classement_commerciaux->avg('ca_realise'), 0, ',', ' ') }} €</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Délai Moyen Paiement</p>
                    <p class="text-3xl font-bold">{{ round($classement_commerciaux->avg('delai_moyen_paiement'), 0) }} <span class="text-lg">jours</span></p>
                </div>
                <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques de performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Graphique en barres des CA -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">CA par Commercial</h3>
            <div class="h-80">
                <canvas id="caBarChart"></canvas>
            </div>
        </div>

        <!-- Graphique radar des performances -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Analyse Multi-Critères</h3>
            <div class="h-80">
                <canvas id="radarChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Classement détaillé -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Classement Détaillé</h3>
            <div class="flex space-x-2">
                <button @click="sortBy = 'ca_realise'; sortOrder = 'desc'" 
                        :class="sortBy === 'ca_realise' ? 'btn-primary' : 'btn-outline'"
                        class="btn btn-sm">CA</button>
                <button @click="sortBy = 'nb_factures'; sortOrder = 'desc'" 
                        :class="sortBy === 'nb_factures' ? 'btn-primary' : 'btn-outline'"
                        class="btn btn-sm">Volume</button>
                <button @click="sortBy = 'delai_moyen_paiement'; sortOrder = 'asc'" 
                        :class="sortBy === 'delai_moyen_paiement' ? 'btn-primary' : 'btn-outline'"
                        class="btn btn-sm">Délais</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rang
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Commercial
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            @click="sortTable('ca_realise')">
                            CA Réalisé
                            <span x-show="sortBy === 'ca_realise'" x-text="sortOrder === 'desc' ? '↓' : '↑'"></span>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            @click="sortTable('nb_factures')">
                            Nb Factures
                            <span x-show="sortBy === 'nb_factures'" x-text="sortOrder === 'desc' ? '↓' : '↑'"></span>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Panier Moyen
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            @click="sortTable('delai_moyen_paiement')">
                            Délai Moyen
                            <span x-show="sortBy === 'delai_moyen_paiement'" x-text="sortOrder === 'desc' ? '↓' : '↑'"></span>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Performance
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="(commercial, index) in sortedCommerciaux" :key="commercial.name">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full text-white text-sm font-bold"
                                     :class="getRankColor(index)">
                                    <span x-text="index + 1"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700" x-text="commercial.name.charAt(0)"></span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900" x-text="commercial.name"></div>
                                        <div class="text-sm text-gray-500" x-text="getRole(commercial)">Commercial</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900" x-text="formatMoney(commercial.ca_realise)"></div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-blue-600 h-2 rounded-full" 
                                         :style="`width: ${(commercial.ca_realise / Math.max(...sortedCommerciaux.map(c => c.ca_realise))) * 100}%`"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900" x-text="commercial.nb_factures"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900" x-text="formatMoney(commercial.ca_realise / Math.max(commercial.nb_factures, 1))"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-900" x-text="Math.round(commercial.delai_moyen_paiement || 0) + ' jours'"></span>
                                    <div class="ml-2" :class="getDelaiColor(commercial.delai_moyen_paiement)">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="h-2 rounded-full" 
                                             :style="`width: ${getPerformanceScore(commercial)}%; background-color: ${getPerformanceColor(commercial)}`"></div>
                                    </div>
                                    <span class="text-xs text-gray-600" x-text="getPerformanceScore(commercial) + '%'"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button @click="viewDetails(commercial)" class="text-blue-600 hover:text-blue-900 mr-3">Détails</button>
                                <button @click="sendMessage(commercial)" class="text-green-600 hover:text-green-900">Message</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Objectifs vs Réalisé -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Objectifs vs Réalisé</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="commercial in commerciaux_data.slice(0, 6)" :key="commercial.name">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-900" x-text="commercial.name"></h4>
                            <span class="text-xs text-gray-500">Mensuel</span>
                        </div>
                        
                        <!-- Objectif vs Réalisé -->
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Objectif:</span>
                                <span class="font-medium" x-text="formatMoney(getObjectif(commercial))"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Réalisé:</span>
                                <span class="font-medium" x-text="formatMoney(commercial.ca_realise)"></span>
                            </div>
                            
                            <!-- Barre de progression -->
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="h-3 rounded-full transition-all duration-300"
                                     :style="`width: ${Math.min((commercial.ca_realise / getObjectif(commercial)) * 100, 100)}%; background-color: ${getObjectifColor(commercial)}`"></div>
                            </div>
                            
                            <!-- Pourcentage -->
                            <div class="flex justify-between text-xs">
                                <span :class="getObjectifTextColor(commercial)" 
                                      x-text="Math.round((commercial.ca_realise / getObjectif(commercial)) * 100) + '%'"></span>
                                <span class="text-gray-500" x-text="getObjectifStatus(commercial)"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function performanceData() {
    return {
        sortBy: 'ca_realise',
        sortOrder: 'desc',
        
        // Données
        commerciaux_data: @json($classement_commerciaux),
        
        kpis: {
            ca_total: {{ $classement_commerciaux->sum('ca_realise') }},
            ca_moyen: {{ $classement_commerciaux->avg('ca_realise') }},
            nb_commerciaux: {{ $classement_commerciaux->count() }}
        },

        init() {
            this.initCharts();
        },

        get sortedCommerciaux() {
            return [...this.commerciaux_data].sort((a, b) => {
                const aVal = a[this.sortBy] || 0;
                const bVal = b[this.sortBy] || 0;
                return this.sortOrder === 'desc' ? bVal - aVal : aVal - bVal;
            });
        },

        initCharts() {
            // Graphique en barres CA
            const caCtx = document.getElementById('caBarChart').getContext('2d');
            new Chart(caCtx, {
                type: 'bar',
                data: {
                    labels: this.commerciaux_data.map(c => c.name),
                    datasets: [{
                        label: 'CA Réalisé',
                        data: this.commerciaux_data.map(c => c.ca_realise),
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
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

            // Graphique radar
            const radarCtx = document.getElementById('radarChart').getContext('2d');
            const topCommerciaux = this.commerciaux_data.slice(0, 3);
            
            new Chart(radarCtx, {
                type: 'radar',
                data: {
                    labels: ['CA', 'Volume', 'Délais', 'Régularité', 'Conversion'],
                    datasets: topCommerciaux.map((commercial, index) => ({
                        label: commercial.name,
                        data: [
                            this.normalizeValue(commercial.ca_realise, 'ca'),
                            this.normalizeValue(commercial.nb_factures, 'volume'),
                            this.normalizeValue(30 - (commercial.delai_moyen_paiement || 30), 'delai'),
                            Math.random() * 100, // Données simulées
                            Math.random() * 100  // Données simulées
                        ],
                        backgroundColor: `rgba(${this.getColorByIndex(index)}, 0.2)`,
                        borderColor: `rgba(${this.getColorByIndex(index)}, 1)`,
                        borderWidth: 2
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        },

        normalizeValue(value, type) {
            const max = Math.max(...this.commerciaux_data.map(c => {
                switch(type) {
                    case 'ca': return c.ca_realise;
                    case 'volume': return c.nb_factures;
                    case 'delai': return 30 - (c.delai_moyen_paiement || 30);
                    default: return value;
                }
            }));
            return (value / max) * 100;
        },

        getColorByIndex(index) {
            const colors = ['59, 130, 246', '139, 92, 246', '239, 68, 68'];
            return colors[index] || '156, 163, 175';
        },

        sortTable(column) {
            if (this.sortBy === column) {
                this.sortOrder = this.sortOrder === 'desc' ? 'asc' : 'desc';
            } else {
                this.sortBy = column;
                this.sortOrder = column === 'delai_moyen_paiement' ? 'asc' : 'desc';
            }
        },

        getRankColor(index) {
            if (index === 0) return 'bg-yellow-500';
            if (index === 1) return 'bg-gray-400';
            if (index === 2) return 'bg-yellow-600';
            return 'bg-blue-500';
        },

        getDelaiColor(delai) {
            if (delai <= 15) return 'text-green-500';
            if (delai <= 30) return 'text-yellow-500';
            return 'text-red-500';
        },

        getPerformanceScore(commercial) {
            // Score basé sur CA, volume et délais
            const caScore = (commercial.ca_realise / Math.max(...this.commerciaux_data.map(c => c.ca_realise))) * 40;
            const volumeScore = (commercial.nb_factures / Math.max(...this.commerciaux_data.map(c => c.nb_factures))) * 30;
            const delaiScore = Math.max(0, (45 - (commercial.delai_moyen_paiement || 30)) / 45) * 30;
            
            return Math.round(caScore + volumeScore + delaiScore);
        },

        getPerformanceColor(commercial) {
            const score = this.getPerformanceScore(commercial);
            if (score >= 80) return '#10B981';
            if (score >= 60) return '#F59E0B';
            return '#EF4444';
        },

        getObjectif(commercial) {
            // Objectif simulé basé sur la moyenne
            return Math.round(this.kpis.ca_moyen * 1.2);
        },

        getObjectifColor(commercial) {
            const ratio = commercial.ca_realise / this.getObjectif(commercial);
            if (ratio >= 1) return '#10B981';
            if (ratio >= 0.8) return '#F59E0B';
            return '#EF4444';
        },

        getObjectifTextColor(commercial) {
            const ratio = commercial.ca_realise / this.getObjectif(commercial);
            if (ratio >= 1) return 'text-green-600';
            if (ratio >= 0.8) return 'text-yellow-600';
            return 'text-red-600';
        },

        getObjectifStatus(commercial) {
            const ratio = commercial.ca_realise / this.getObjectif(commercial);
            if (ratio >= 1) return 'Objectif atteint';
            if (ratio >= 0.8) return 'En bonne voie';
            return 'À améliorer';
        },

        getRole(commercial) {
            return 'Commercial';
        },

        formatMoney(amount) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount || 0);
        },

        viewDetails(commercial) {
            // Ouvrir modal avec détails du commercial
            alert(`Détails pour ${commercial.name}\n\nCA: ${this.formatMoney(commercial.ca_realise)}\nFactures: ${commercial.nb_factures}\nDélai moyen: ${Math.round(commercial.delai_moyen_paiement || 0)} jours`);
        },

        sendMessage(commercial) {
            // Rediriger vers la messagerie
            window.location.href = `/messages/create?to=${commercial.name}`;
        }
    }
}
</script>
@endpush