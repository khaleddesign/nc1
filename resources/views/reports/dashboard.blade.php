@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50" x-data="reportsData()">
    
    <!-- Header avec filtres redesigné -->
    <div class="relative overflow-hidden">
        <!-- Background pattern -->
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600"></div>
        <div class="absolute inset-0 bg-black/10"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="text-white">
                    <h1 class="text-4xl font-bold tracking-tight flex items-center gap-3">
                        <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                            📊
                        </div>
                        Analytics Dashboard
                    </h1>
                    <p class="mt-2 text-blue-100 text-lg">
                        Pilotez vos performances en temps réel
                    </p>
                </div>
                
                <!-- Filtres rapides modernisés -->
                <div class="mt-6 lg:mt-0 flex flex-wrap gap-3">
                    <button @click="setPeriod('month')" 
                            :class="period === 'month' ? 'bg-white text-blue-600' : 'bg-white/20 text-white hover:bg-white/30'"
                            class="px-4 py-2 rounded-xl font-medium transition-all duration-200 backdrop-blur-sm">
                        Ce mois
                    </button>
                    <button @click="setPeriod('quarter')" 
                            :class="period === 'quarter' ? 'bg-white text-blue-600' : 'bg-white/20 text-white hover:bg-white/30'"
                            class="px-4 py-2 rounded-xl font-medium transition-all duration-200 backdrop-blur-sm">
                        Trimestre
                    </button>
                    <button @click="setPeriod('year')" 
                            :class="period === 'year' ? 'bg-white text-blue-600' : 'bg-white/20 text-white hover:bg-white/30'"
                            class="px-4 py-2 rounded-xl font-medium transition-all duration-200 backdrop-blur-sm">
                        Année
                    </button>
                    <button @click="showFilters = !showFilters" 
                            class="px-4 py-2 bg-white/20 text-white hover:bg-white/30 rounded-xl font-medium transition-all duration-200 backdrop-blur-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                        </svg>
                        Filtres
                    </button>
                </div>
            </div>
            
            <!-- Filtres avancés -->
            <div x-show="showFilters" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="mt-6 p-6 bg-white/20 backdrop-blur-md rounded-2xl border border-white/20">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white">Date début</label>
                        <input type="date" x-model="filters.date_debut" @change="loadData()" 
                               class="w-full px-4 py-3 bg-white/90 rounded-xl border-0 focus:ring-2 focus:ring-white/50 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white">Date fin</label>
                        <input type="date" x-model="filters.date_fin" @change="loadData()" 
                               class="w-full px-4 py-3 bg-white/90 rounded-xl border-0 focus:ring-2 focus:ring-white/50 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white">Commercial</label>
                        <select x-model="filters.commercial_id" @change="loadData()" 
                                class="w-full px-4 py-3 bg-white/90 rounded-xl border-0 focus:ring-2 focus:ring-white/50 transition-all">
                            <option value="">Tous les commerciaux</option>
                            @foreach(\App\Models\User::where('role', 'commercial')->get() as $commercial)
                                <option value="{{ $commercial->id }}">{{ $commercial->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-white">Période</label>
                        <select x-model="filters.periode" @change="loadData()" 
                                class="w-full px-4 py-3 bg-white/90 rounded-xl border-0 focus:ring-2 focus:ring-white/50 transition-all">
                            <option value="mensuel">Vue mensuelle</option>
                            <option value="trimestriel">Vue trimestrielle</option>
                            <option value="annuel">Vue annuelle</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 -mt-4 relative z-10">
        
        <!-- KPIs principaux redesignés -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Chiffre d'affaires -->
            <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-blue-600"></div>
                <div class="relative p-6 text-white">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="p-2 bg-white/20 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                </div>
                                <p class="text-blue-100 text-sm font-medium">Chiffre d'Affaires</p>
                            </div>
                            <p class="text-3xl font-bold mb-2" x-text="formatMoney(kpis.ca_total)">{{ number_format($kpis['ca_total'], 0, ',', ' ') }} €</p>
                            <div class="flex items-center gap-1">
                                <div :class="kpis.evolution_ca >= 0 ? 'bg-green-500/20 text-green-200' : 'bg-red-500/20 text-red-200'" 
                                     class="flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              :d="kpis.evolution_ca >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6'"/>
                                    </svg>
                                    <span x-text="Math.abs(kpis.evolution_ca) + '%'">{{ abs($kpis['evolution_ca']) }}%</span>
                                </div>
                                <span class="text-blue-200 text-xs">vs période précédente</span>
                            </div>
                        </div>
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Taux de conversion -->
            <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-green-600"></div>
                <div class="relative p-6 text-white">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="p-2 bg-white/20 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <p class="text-green-100 text-sm font-medium">Taux de Conversion</p>
                            </div>
                            <p class="text-3xl font-bold mb-2" x-text="kpis.taux_conversion + '%'">{{ $kpis['taux_conversion'] }}%</p>
                            <p class="text-green-200 text-sm">
                                <span x-text="kpis.nb_devis_acceptes">{{ $kpis['nb_devis_acceptes'] }}</span> / 
                                <span x-text="kpis.nb_devis_envoyes">{{ $kpis['nb_devis_envoyes'] }}</span> devis acceptés
                            </p>
                        </div>
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- DSO -->
            <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-violet-600"></div>
                <div class="relative p-6 text-white">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="p-2 bg-white/20 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-purple-100 text-sm font-medium">Délai Moyen (DSO)</p>
                            </div>
                            <p class="text-3xl font-bold mb-2">
                                <span x-text="kpis.dso">{{ $kpis['dso'] }}</span>
                                <span class="text-lg">jours</span>
                            </p>
                            <p class="text-purple-200 text-sm">Days Sales Outstanding</p>
                        </div>
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- CA en attente -->
            <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500 to-orange-600"></div>
                <div class="relative p-6 text-white">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="p-2 bg-white/20 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <p class="text-orange-100 text-sm font-medium">CA en Attente</p>
                            </div>
                            <p class="text-3xl font-bold mb-2" x-text="formatMoney(kpis.ca_en_attente)">{{ number_format($kpis['ca_en_attente'], 0, ',', ' ') }} €</p>
                            <p class="text-orange-200 text-sm">Factures impayées</p>
                        </div>
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques principaux -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
            <!-- Évolution du CA (2/3 de largeur) -->
            <div class="xl:col-span-2 bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Évolution du Chiffre d'Affaires</h3>
                        <p class="text-gray-500 text-sm">Tendance sur la période sélectionnée</p>
                    </div>
                    <div class="flex bg-gray-100 rounded-xl p-1">
                        <button @click="chartPeriod = 'mensuel'" 
                                :class="chartPeriod === 'mensuel' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-600 hover:text-gray-900'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                            Mensuel
                        </button>
                        <button @click="chartPeriod = 'trimestriel'" 
                                :class="chartPeriod === 'trimestriel' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-600 hover:text-gray-900'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                            Trimestriel
                        </button>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="caChart"></canvas>
                </div>
            </div>

            <!-- Pipeline commercial (1/3 de largeur) -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Pipeline Commercial</h3>
                    <p class="text-gray-500 text-sm">Suivi des opportunités</p>
                </div>
                <div class="space-y-4">
                    <div class="group p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl hover:from-gray-100 hover:to-gray-200 transition-all duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">Devis brouillon</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900" x-text="formatMoney(pipeline.devis_brouillon)">
                                {{ number_format($pipeline['devis_brouillon'], 0, ',', ' ') }} €
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1">
                            <div class="bg-gray-400 h-1 rounded-full" style="width: 20%"></div>
                        </div>
                    </div>
                    
                    <div class="group p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">Devis envoyés</span>
                            </div>
                            <span class="text-lg font-bold text-blue-700" x-text="formatMoney(pipeline.devis_envoyes)">
                                {{ number_format($pipeline['devis_envoyes'], 0, ',', ' ') }} €
                            </span>
                        </div>
                        <div class="w-full bg-blue-200 rounded-full h-1">
                            <div class="bg-blue-500 h-1 rounded-full" style="width: 60%"></div>
                        </div>
                    </div>
                    
                    <div class="group p-4 bg-gradient-to-r from-emerald-50 to-green-100 rounded-xl hover:from-emerald-100 hover:to-green-200 transition-all duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">Devis acceptés</span>
                            </div>
                            <span class="text-lg font-bold text-emerald-700" x-text="formatMoney(pipeline.devis_acceptes)">
                                {{ number_format($pipeline['devis_acceptes'], 0, ',', ' ') }} €
                            </span>
                        </div>
                        <div class="w-full bg-emerald-200 rounded-full h-1">
                            <div class="bg-emerald-500 h-1 rounded-full" style="width: 80%"></div>
                        </div>
                    </div>
                    
                    <div class="group p-4 bg-gradient-to-r from-amber-50 to-orange-100 rounded-xl hover:from-amber-100 hover:to-orange-200 transition-all duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">Factures en attente</span>
                            </div>
                            <span class="text-lg font-bold text-amber-700" x-text="formatMoney(pipeline.factures_en_attente)">
                                {{ number_format($pipeline['factures_en_attente'], 0, ',', ' ') }} €
                            </span>
                        </div>
                        <div class="w-full bg-amber-200 rounded-full h-1">
                            <div class="bg-amber-500 h-1 rounded-full" style="width: 40%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance et tunnel de conversion -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Top commerciaux -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Top Commerciaux</h3>
                        <p class="text-gray-500 text-sm">Classement par CA réalisé</p>
                    </div>
                    <a href="{{ route('reports.performance-commerciale') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-xl text-sm font-medium transition-colors duration-200">
                        Voir détail
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="space-y-3">
                    @foreach($performance_commerciale['classement']->take(5) as $index => $commercial)
                    <div class="group flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition-all duration-200">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">{{ $index + 1 }}</span>
                                </div>
                                @if($index === 0)
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-yellow-400 rounded-full flex items-center justify-center">
                                    <span class="text-yellow-900 text-xs">👑</span>
                                </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $commercial->name }}</p>
                                <p class="text-sm text-gray-500">{{ $commercial->nb_factures }} factures</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900">{{ number_format($commercial->ca_realise, 0, ',', ' ') }} €</p>
                            <p class="text-sm text-gray-500">CA réalisé</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tunnel de conversion moderne -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Tunnel de Conversion</h3>
                    <p class="text-gray-500 text-sm">Processus commercial étape par étape</p>
                </div>
                <div class="space-y-6">
                    <!-- Étape 1 -->
                    <div class="relative">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-700">Devis créés</span>
                            </div>
                            <span class="font-bold text-gray-900" x-text="taux_conversion.total_devis">{{ $taux_conversion['total_devis'] }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>

                    <!-- Étape 2 -->
                    <div class="relative">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-700">Devis envoyés</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-gray-900" x-text="taux_conversion.devis_envoyes">{{ $taux_conversion['devis_envoyes'] }}</span>
                                <span class="text-sm text-indigo-600 ml-2 font-medium">{{ $taux_conversion['taux_envoi'] }}%</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2 rounded-full" style="width: {{ $taux_conversion['taux_envoi'] }}%"></div>
                        </div>
                    </div>

                    <!-- Étape 3 -->
                    <div class="relative">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-700">Devis acceptés</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-gray-900" x-text="taux_conversion.devis_acceptes">{{ $taux_conversion['devis_acceptes'] }}</span>
                                <span class="text-sm text-emerald-600 ml-2 font-medium">{{ $taux_conversion['taux_acceptation'] }}%</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-emerald-500 to-green-600 h-2 rounded-full" style="width: {{ $taux_conversion['taux_acceptation'] }}%"></div>
                        </div>
                    </div>

                    <!-- Étape 4 -->
                    <div class="relative">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-700">Devis facturés</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-gray-900" x-text="taux_conversion.devis_factures">{{ $taux_conversion['devis_factures'] }}</span>
                                <span class="text-sm text-green-600 ml-2 font-medium">{{ $taux_conversion['taux_facturation'] }}%</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-2 rounded-full" style="width: {{ $taux_conversion['taux_facturation'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides modernisées -->
        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-1">Actions Rapides</h3>
                <p class="text-gray-500 text-sm">Accédez rapidement aux rapports détaillés</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('reports.chiffre-affaires') }}" 
                   class="group relative overflow-hidden p-6 border-2 border-gray-100 hover:border-blue-200 rounded-2xl transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Rapport CA</h4>
                                <p class="text-sm text-gray-500">Analyse détaillée des revenus</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>

                <a href="{{ route('reports.performance-commerciale') }}" 
                   class="group relative overflow-hidden p-6 border-2 border-gray-100 hover:border-emerald-200 rounded-2xl transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">Performance</h4>
                                <p class="text-sm text-gray-500">Équipe commerciale</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>

                <button @click="exportReport('dashboard')" 
                        class="group relative overflow-hidden p-6 border-2 border-gray-100 hover:border-purple-200 rounded-2xl transition-all duration-300 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-gradient-to-r from-purple-500 to-violet-600 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 group-hover:text-purple-600 transition-colors">Export PDF</h4>
                                <p class="text-sm text-gray-500">Rapport complet</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 group-hover:translate-x-1 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading overlay modernisé -->
<div x-show="loading" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     class="fixed inset-0 bg-black/20 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 shadow-2xl border border-gray-100">
        <div class="flex items-center gap-4">
            <div class="relative">
                <div class="w-8 h-8 border-4 border-blue-200 rounded-full animate-spin"></div>
                <div class="absolute top-0 left-0 w-8 h-8 border-4 border-transparent border-t-blue-600 rounded-full animate-spin"></div>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">Chargement en cours</h3>
                <p class="text-sm text-gray-500">Mise à jour des données...</p>
            </div>
        </div>
    </div>
</div>

<!-- Notification toast (optionnel) -->
<div x-data="{ show: false, message: '', type: 'info' }" 
     @toast.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 3000)"
     x-show="show"
     x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white rounded-2xl shadow-lg border border-gray-200 p-4">
    <div class="flex items-center gap-3">
        <div :class="{
            'bg-blue-100 text-blue-600': type === 'info',
            'bg-green-100 text-green-600': type === 'success',
            'bg-red-100 text-red-600': type === 'error',
            'bg-amber-100 text-amber-600': type === 'warning'
        }" class="p-2 rounded-lg">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <p class="text-gray-900 font-medium" x-text="message"></p>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function reportsData() {
    return {
        loading: false,
        showFilters: false,
        period: 'month',
        chartPeriod: 'mensuel',
        caChart: null,
        
        // Données initiales depuis le serveur
        kpis: @json($kpis),
        pipeline: @json($pipeline),
        taux_conversion: @json($taux_conversion),
        chiffre_affaires: @json($chiffre_affaires),
        
        filters: {
            date_debut: '{{ $filters["date_debut"] }}',
            date_fin: '{{ $filters["date_fin"] }}',
            commercial_id: '{{ $filters["commercial_id"] ?? "" }}',
            periode: '{{ $filters["periode"] }}'
        },

        init() {
            this.initCharts();
            
            // Auto-refresh toutes les 5 minutes
            setInterval(() => {
                this.loadData(false);
            }, 300000);
        },

        setPeriod(period) {
            this.period = period;
            const now = new Date();
            
            switch(period) {
                case 'month':
                    this.filters.date_debut = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
                    this.filters.date_fin = now.toISOString().split('T')[0];
                    break;
                case 'quarter':
                    const quarter = Math.floor(now.getMonth() / 3);
                    this.filters.date_debut = new Date(now.getFullYear(), quarter * 3, 1).toISOString().split('T')[0];
                    this.filters.date_fin = now.toISOString().split('T')[0];
                    break;
                case 'year':
                    this.filters.date_debut = new Date(now.getFullYear(), 0, 1).toISOString().split('T')[0];
                    this.filters.date_fin = now.toISOString().split('T')[0];
                    break;
            }
            
            this.loadData();
        },

        async loadData(showLoading = true) {
            if (showLoading) this.loading = true;
            
            try {
                const response = await fetch('/reports/api-data?' + new URLSearchParams(this.filters), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.updateData(data);
                    this.showToast('Données mises à jour', 'success');
                }
            } catch (error) {
                console.error('Erreur lors du chargement des données:', error);
                this.showToast('Erreur lors du chargement des données', 'error');
            } finally {
                this.loading = false;
            }
        },

        updateData(data) {
            if (data.kpis) this.kpis = data.kpis;
            if (data.pipeline) this.pipeline = data.pipeline;
            if (data.taux_conversion) this.taux_conversion = data.taux_conversion;
            if (data.chiffre_affaires) {
                this.chiffre_affaires = data.chiffre_affaires;
                this.updateCharts();
            }
        },

        initCharts() {
            // Graphique CA avec design moderne
            const caCtx = document.getElementById('caChart').getContext('2d');
            
            // Créer un gradient
            const gradient = caCtx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.05)');
            
            this.caChart = new Chart(caCtx, {
                type: 'line',
                data: {
                    labels: this.chiffre_affaires.evolution.map(item => item.periode),
                    datasets: [{
                        label: 'Chiffre d\'Affaires',
                        data: this.chiffre_affaires.evolution.map(item => item.montant),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: 'rgb(37, 99, 235)',
                        pointHoverBorderColor: 'white',
                        pointHoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.95)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            cornerRadius: 12,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return new Intl.NumberFormat('fr-FR', {
                                        style: 'currency',
                                        currency: 'EUR',
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    }).format(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: '#6B7280',
                                font: {
                                    weight: '500'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#F3F4F6',
                                drawBorder: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: '#6B7280',
                                font: {
                                    weight: '500'
                                },
                                callback: function(value) {
                                    return new Intl.NumberFormat('fr-FR', {
                                        style: 'currency',
                                        currency: 'EUR',
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0,
                                        notation: value >= 1000000 ? 'compact' : 'standard'
                                    }).format(value);
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    elements: {
                        line: {
                            borderCapStyle: 'round',
                            borderJoinStyle: 'round'
                        }
                    }
                }
            });
        },

        updateCharts() {
            if (this.caChart) {
                this.caChart.data.labels = this.chiffre_affaires.evolution.map(item => item.periode);
                this.caChart.data.datasets[0].data = this.chiffre_affaires.evolution.map(item => item.montant);
                this.caChart.update('active');
            }
        },

        formatMoney(amount) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
                notation: amount >= 1000000 ? 'compact' : 'standard'
            }).format(amount || 0);
        },

        async exportReport(type) {
            this.loading = true;
            try {
                const params = new URLSearchParams({...this.filters, type});
                window.open(`/reports/export-pdf?${params}`, '_blank');
                this.showToast('Export en cours...', 'info');
            } catch (error) {
                this.showToast('Erreur lors de l\'export', 'error');
            } finally {
                this.loading = false;
            }
        },

        showToast(message, type = 'info') {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: { message, type }
            }));
        }
    }
}
</script>
@endpush