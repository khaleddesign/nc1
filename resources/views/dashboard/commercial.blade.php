@extends('layouts.app')

@section('title', 'Dashboard Commercial')
@section('page-title', 'Dashboard Commercial')
@section('page-subtitle', 'Vos chantiers et devis en cours')

@section('content')
<div class="space-y-6" x-data="commercialDashboard()">
    
    {{-- Header Compact - Style Admin --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dashboard Commercial</h1>
            <p class="text-slate-500 mt-1">Vos chantiers et devis en cours</p>
        </div>
        
        {{-- Actions compactes à droite --}}
        <div class="flex items-center space-x-3">
            <a href="{{ route('devis.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nouveau Devis
            </a>
            
            {{-- Menu déroulant pour les autres actions --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="inline-flex items-center px-3 py-2 border border-slate-300 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-50 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                    </svg>
                    Plus d'actions
                    <svg class="w-4 h-4 ml-1 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.away="open = false"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-strong border border-slate-200 py-2 z-10">
                    
                    <a href="{{ route('chantiers.create') }}" 
                       class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Nouveau Chantier</div>
                            <div class="text-xs text-slate-500">Créer un projet</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('chantiers.calendrier') }}" 
                       class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Voir Calendrier</div>
                            <div class="text-xs text-slate-500">Planning des chantiers</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiques Commercial - 4 Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Mes Chantiers --}}
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 hover:shadow-medium transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">Mes Chantiers</h3>
                    <p class="text-xs text-slate-400 mt-1">Total assignés</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <span class="text-white text-lg">🏗️</span>
                </div>
            </div>
            <div class="space-y-2">
                <div class="text-3xl font-bold text-indigo-600 counter" 
                     x-text="animatedStats.total_chantiers" 
                     data-target="{{ $stats['total_chantiers'] ?? 0 }}">0</div>
                <div class="flex items-center text-sm text-emerald-600">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                    </svg>
                    <span>+{{ $stats['en_cours'] ?? 0 }} actifs</span>
                </div>
            </div>
        </div>

        {{-- Devis En Attente --}}
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 hover:shadow-medium transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">Devis En Attente</h3>
                    <p class="text-xs text-slate-400 mt-1">À traiter</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <span class="text-white text-lg">📄</span>
                </div>
            </div>
            <div class="space-y-2">
                <div class="text-3xl font-bold text-amber-600 counter" 
                     x-text="animatedStats.devis_en_attente" 
                     data-target="{{ $mes_devis->where('statut', 'envoye')->count() }}">0</div>
                <div class="flex items-center text-sm text-slate-600">
                    <div class="w-2 h-2 bg-amber-400 rounded-full mr-2 animate-pulse"></div>
                    <span>En cours de traitement</span>
                </div>
            </div>
        </div>

        {{-- CA Mensuel --}}
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 hover:shadow-medium transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">CA Mensuel</h3>
                    <p class="text-xs text-slate-400 mt-1">Chiffre d'affaires</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-full flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <span class="text-white text-lg">💰</span>
                </div>
            </div>
            <div class="space-y-2">
                <div class="text-3xl font-bold text-emerald-600 counter" 
                     x-text="formatCurrency(animatedStats.ca_mensuel)" 
                     data-target="{{ $mes_devis->where('statut', 'accepte')->sum('montant_ttc') }}">0€</div>
                <div class="flex items-center text-sm text-emerald-600">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Validé ce mois</span>
                </div>
            </div>
        </div>

        {{-- Taux de Conversion --}}
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 hover:shadow-medium transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">Taux Conversion</h3>
                    <p class="text-xs text-slate-400 mt-1">Devis → Chantiers</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <span class="text-white text-lg">📊</span>
                </div>
            </div>
            <div class="space-y-3">
                @php
                    $devisEnvoyes = $mes_devis->whereIn('statut', ['envoye', 'accepte'])->count();
                    $devisAcceptes = $mes_devis->where('statut', 'accepte')->count();
                    $tauxConversion = $devisEnvoyes > 0 ? ($devisAcceptes / $devisEnvoyes) * 100 : 0;
                @endphp
                <div class="flex items-end space-x-2">
                    <span class="text-3xl font-bold text-cyan-600 counter" x-text="animatedStats.taux_conversion" data-target="{{ round($tauxConversion) }}">0</span>
                    <span class="text-lg font-semibold text-cyan-600 mb-1">%</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-cyan-500 to-blue-600 h-2 rounded-full transition-all duration-1000 ease-out" 
                         :style="`width: ${animatedStats.taux_conversion}%`"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Layout Principal --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Mes Chantiers Récents --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">Mes Chantiers Récents</h3>
                                <p class="text-sm text-slate-500">Derniers projets assignés</p>
                            </div>
                        </div>
                        <a href="{{ route('chantiers.index') }}" 
                           class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition-colors duration-200 flex items-center">
                            Voir tous
                            <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                @if(isset($mes_chantiers) && $mes_chantiers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="text-left py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Chantier</th>
                                    <th class="text-left py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Client</th>
                                    <th class="text-left py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Statut</th>
                                    <th class="text-left py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Avancement</th>
                                    <th class="text-right py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($mes_chantiers->take(5) as $chantier)
                                <tr class="hover:bg-slate-50 transition-colors duration-200">
                                    <td class="py-4 px-6">
                                        <div>
                                            <div class="font-semibold text-slate-900">{{ $chantier->titre }}</div>
                                            @if($chantier->description)
                                                <div class="text-sm text-slate-500 mt-1">{{ Str::limit($chantier->description, 40) }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-semibold mr-3">
                                                {{ substr($chantier->client->name, 0, 1) }}
                                            </div>
                                            <span class="font-medium text-slate-900">{{ $chantier->client->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @php
                                            $statusConfig = [
                                                'planifie' => ['class' => 'bg-slate-100 text-slate-700 border-slate-200', 'text' => 'Planifié'],
                                                'en_cours' => ['class' => 'bg-blue-100 text-blue-700 border-blue-200', 'text' => 'En cours'],
                                                'termine' => ['class' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'text' => 'Terminé'],
                                            ];
                                            $config = $statusConfig[$chantier->statut] ?? ['class' => 'bg-slate-100 text-slate-700 border-slate-200', 'text' => 'Inconnu'];
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $config['class'] }}">
                                            {{ $config['text'] }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-1">
                                                <div class="w-full bg-slate-200 rounded-full h-2">
                                                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all duration-300" 
                                                         style="width: {{ $chantier->avancement_global ?? 0 }}%"></div>
                                                </div>
                                            </div>
                                            <span class="text-sm font-semibold text-slate-700 min-w-[3rem] text-right">{{ number_format($chantier->avancement_global ?? 0, 0) }}%</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('chantiers.show', $chantier) }}" 
                                               class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('chantiers.edit', $chantier) }}" 
                                               class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-20 h-20 mx-auto mb-6 bg-slate-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun chantier assigné</h3>
                        <p class="text-slate-500 mb-6">Créez votre premier chantier pour commencer.</p>
                        <a href="{{ route('chantiers.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Créer un chantier
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            
            {{-- Mes Devis En Attente --}}
            <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Mes Devis En Attente</h3>
                        <p class="text-sm text-slate-500">Réponses client</p>
                    </div>
                </div>
                
                @php
                    $devisEnAttente = $mes_devis->where('statut', 'envoye');
                @endphp
                
                @if($devisEnAttente->count() > 0)
                    <div class="space-y-4 max-h-64 overflow-y-auto">
                        @foreach($devisEnAttente->take(3) as $devis)
                            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl hover:bg-amber-100 transition-colors duration-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-slate-900 text-sm">{{ $devis->chantier->client->name }}</h4>
                                    <span class="text-xs font-medium text-amber-700 bg-amber-200 px-2 py-1 rounded-full">En attente</span>
                                </div>
                                <p class="text-sm text-slate-600 mb-2">{{ Str::limit($devis->titre, 30) }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-slate-900">{{ number_format($devis->montant_ttc, 0, ',', ' ') }}€</span>
                                    <span class="text-xs text-slate-500">{{ $devis->date_envoi->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <a href="{{ route('devis.index', ['filter' => 'envoye']) }}" 
                           class="text-amber-600 hover:text-amber-800 text-sm font-medium flex items-center">
                            Voir tous les devis en attente
                            <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 mx-auto mb-4 bg-amber-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-semibold text-slate-900 mb-2">Aucun devis en attente</h4>
                        <p class="text-xs text-slate-500">Tous vos devis ont été traités</p>
                    </div>
                @endif
            </div>

            {{-- Objectifs du Mois --}}
            <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M9.497 14.25v-2.25m5.007 2.25v-2.25m0-2.25v.375c0 .621-.504 1.125-1.125 1.125h-3.75a1.125 1.125 0 01-1.125-1.125v-.375m9 0a2.625 2.625 0 00-2.625-2.625h-4.75a2.625 2.625 0 00-2.625 2.625v-.375A1.125 1.125 0 018.25 8.625h7.5A1.125 1.125 0 0117 9.75v.375z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Objectifs du Mois</h3>
                        <p class="text-sm text-slate-500">Progression actuelle</p>
                    </div>
                </div>
                
                <div class="space-y-6">
                    {{-- Objectif CA --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-700">Chiffre d'affaires</span>
                            <span class="text-sm font-bold text-slate-900">{{ number_format($mes_devis->where('statut', 'accepte')->sum('montant_ttc'), 0, ',', ' ') }}€ / 50 000€</span>
                        </div>
                        @php
                            $objectifCA = 50000;
                            $caActuel = $mes_devis->where('statut', 'accepte')->sum('montant_ttc');
                            $progressCA = min(($caActuel / $objectifCA) * 100, 100);
                        @endphp
                        <div class="w-full bg-slate-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-emerald-500 to-green-600 h-3 rounded-full transition-all duration-1000" 
                                 style="width: {{ $progressCA }}%"></div>
                        </div>
                        <div class="text-xs text-slate-500 mt-1">{{ number_format($progressCA, 1) }}% de l'objectif</div>
                    </div>
                    
                    {{-- Objectif Devis --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-700">Devis acceptés</span>
                            <span class="text-sm font-bold text-slate-900">{{ $mes_devis->where('statut', 'accepte')->count() }} / 10</span>
                        </div>
                        @php
                            $objectifDevis = 10;
                            $devisActuels = $mes_devis->where('statut', 'accepte')->count();
                            $progressDevis = min(($devisActuels / $objectifDevis) * 100, 100);
                        @endphp
                        <div class="w-full bg-slate-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 h-3 rounded-full transition-all duration-1000" 
                                 style="width: {{ $progressDevis }}%"></div>
                        </div>
                        <div class="text-xs text-slate-500 mt-1">{{ number_format($progressDevis, 1) }}% de l'objectif</div>
                    </div>
                </div>
            </div>

            {{-- Actions Rapides --}}
            <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Actions Rapides</h3>
                        <p class="text-sm text-slate-500">Raccourcis utiles</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('chantiers.index') }}" 
                       class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors duration-200 group">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors duration-200">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-900">Tous mes chantiers</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                    
                    <a href="{{ route('devis.index') }}" 
                       class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors duration-200 group">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center group-hover:bg-amber-200 transition-colors duration-200">
                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-900">Gérer mes devis</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>

                    <a href="{{ route('chantiers.calendrier') }}" 
                       class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors duration-200 group">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center group-hover:bg-cyan-200 transition-colors duration-200">
                                <svg class="w-4 h-4 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-900">Voir le planning</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Chantiers en Retard --}}
            @php
                $chantiersEnRetard = $mes_chantiers->filter(function($c) {
                    return $c->date_fin_prevue && $c->date_fin_prevue->isPast() && $c->statut !== 'termine';
                });
            @endphp
            @if($chantiersEnRetard->count() > 0)
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-red-900">Attention</h3>
                        <p class="text-sm text-red-700">{{ $chantiersEnRetard->count() }} chantier(s) en retard</p>
                    </div>
                </div>
                
                <div class="space-y-3 max-h-32 overflow-y-auto">
                    @foreach($chantiersEnRetard->take(3) as $chantier)
                        <div class="flex items-center justify-between p-3 bg-white border border-red-200 rounded-lg">
                            <div>
                                <a href="{{ route('chantiers.show', $chantier) }}" 
                                   class="text-sm font-semibold text-red-900 hover:text-red-700 transition-colors duration-200">
                                    {{ Str::limit($chantier->titre, 20) }}
                                </a>
                                <div class="text-xs text-red-600 mt-1">
                                    {{ $chantier->date_fin_prevue->diffForHumans() }}
                                </div>
                            </div>
                            <span class="text-xs font-medium text-red-700 bg-red-200 px-2 py-1 rounded-full">Retard</span>
                        </div>
                    @endforeach
                </div>
                
                @if($chantiersEnRetard->count() > 3)
                    <div class="mt-4 pt-4 border-t border-red-200">
                        <a href="{{ route('chantiers.index', ['filter' => 'en_retard']) }}" 
                           class="text-red-700 hover:text-red-900 text-sm font-medium flex items-center">
                            Voir tous les chantiers en retard
                            <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function commercialDashboard() {
    return {
        animatedStats: {
            total_chantiers: 0,
            devis_en_attente: 0,
            ca_mensuel: 0,
            taux_conversion: 0
        },
        
        targetStats: {
            total_chantiers: {{ $stats['total_chantiers'] ?? 0 }},
            devis_en_attente: {{ $mes_devis->where('statut', 'envoye')->count() }},
            ca_mensuel: {{ $mes_devis->where('statut', 'accepte')->sum('montant_ttc') }},
            taux_conversion: {{ $devisEnvoyes > 0 ? round(($devisAcceptes / $devisEnvoyes) * 100) : 0 }}
        },
        
        init() {
            this.animateCounters();
        },
        
        animateCounters() {
            const duration = 1500;
            const fps = 60;
            const totalFrames = (duration / 1000) * fps;
            
            Object.keys(this.targetStats).forEach(key => {
                let currentFrame = 0;
                const targetValue = this.targetStats[key];
                
                const animate = () => {
                    const progress = currentFrame / totalFrames;
                    const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                    
                    this.animatedStats[key] = Math.floor(targetValue * easeOutQuart);
                    
                    if (currentFrame < totalFrames) {
                        currentFrame++;
                        requestAnimationFrame(animate);
                    } else {
                        this.animatedStats[key] = targetValue;
                    }
                };
                
                requestAnimationFrame(animate);
            });
        },
        
        formatCurrency(value) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR',
                maximumFractionDigits: 0
            }).format(value);
        }
    }
}
</script>
@endsection

@push('styles')
<style>
.shadow-soft {
    box-shadow: 0 4px 24px rgba(15, 23, 42, 0.08);
}

.shadow-medium {
    box-shadow: 0 8px 32px rgba(15, 23, 42, 0.12);
}

.shadow-strong {
    box-shadow: 0 16px 48px rgba(15, 23, 42, 0.16);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Animation des cartes */
.group:hover {
    transform: translateY(-2px);
}

/* Responsive amélioré */
@media (max-width: 768px) {
    .grid-cols-2.md\:grid-cols-4 {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .lg\:col-span-2 {
        grid-column: span 1;
    }
    
    .p-6 {
        padding: 1rem;
    }
}

/* Focus states pour l'accessibilité */
*:focus-visible {
    outline: 2px solid rgb(99 102 241);
    outline-offset: 2px;
    border-radius: 4px;
}

/* Animation de pulse pour les indicateurs */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Raccourcis clavier
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 'd':
                    e.preventDefault();
                    window.location.href = '{{ route("devis.create") }}';
                    break;
                case 'c':
                    e.preventDefault();
                    window.location.href = '{{ route("chantiers.create") }}';
                    break;
                case 'p':
                    e.preventDefault();
                    window.location.href = '{{ route("chantiers.calendrier") }}';
                    break;
            }
        }
    });
    
    // Auto-refresh des statistiques toutes les 5 minutes
    setInterval(function() {
        // À implémenter : fetch des nouvelles données
        console.log('Auto-refresh des statistiques...');
    }, 300000);
});
</script>
@endpush