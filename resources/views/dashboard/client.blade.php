@extends('layouts.app')

@section('title', 'Mon Espace Projet')
@section('page-title', 'Mon Espace Projet')
@section('page-subtitle', 'Suivez l\'avancement de vos projets BTP')

@section('content')
<div class="space-y-6" x-data="clientDashboard()">
    
    {{-- Header Compact - Style Admin --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Mon Espace Projet</h1>
            @if($mes_chantiers->count() === 0)
                <p class="text-slate-500 mt-1">Bonjour {{ Auth::user()->name }}, créez votre premier projet pour commencer</p>
            @elseif($mes_chantiers->where('statut', 'en_cours')->count() > 0)
                <p class="text-slate-500 mt-1">Bonjour {{ Auth::user()->name }}, vous avez {{ $mes_chantiers->where('statut', 'en_cours')->count() }} projet(s) en cours</p>
            @else
                <p class="text-slate-500 mt-1">Bonjour {{ Auth::user()->name }}, tous vos projets sont terminés !</p>
            @endif
        </div>
        
        {{-- Actions compactes à droite --}}
        <div class="flex items-center space-x-3">
            <button onclick="openDevisModal()" 
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                @if($mes_chantiers->count() === 0)
                    Démarrer mon premier projet
                @else
                    Nouveau projet
                @endif
            </button>
            
            {{-- Menu déroulant pour les autres actions --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="inline-flex items-center px-3 py-2 border border-slate-300 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-50 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Contacter mon équipe
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
                    
                    <a href="{{ route('messages.create') }}" 
                       class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Nouveau Message</div>
                            <div class="text-xs text-slate-500">Contacter votre commercial</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('chantiers.index') }}" 
                       class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Tous mes projets</div>
                            <div class="text-xs text-slate-500">Vue d'ensemble</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Section Attention Requise --}}
    @php
        $actions_requises = collect();
        $projets_en_retard = $mes_chantiers->filter(function($chantier) {
            return $chantier->date_fin_prevue && $chantier->date_fin_prevue->isPast() && $chantier->statut !== 'termine';
        });
        $messages_non_lus = isset($messages) ? $messages->where('lu', false)->count() : 0;
        $projets_termines_non_evalues = $mes_chantiers->where('statut', 'termine')->filter(function($chantier) {
            return !isset($chantier->note_satisfaction);
        });
        
        if($projets_en_retard->count() > 0) $actions_requises->push(['type' => 'retard', 'count' => $projets_en_retard->count()]);
        if($messages_non_lus > 0) $actions_requises->push(['type' => 'messages', 'count' => $messages_non_lus]);
        if($projets_termines_non_evalues->count() > 0) $actions_requises->push(['type' => 'evaluation', 'count' => $projets_termines_non_evalues->count()]);
    @endphp
    
    @if($actions_requises->count() > 0)
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-red-900">Action requise</h3>
                    <div class="text-red-700 text-sm mt-1">
                        @foreach($actions_requises as $action)
                            @if($action['type'] === 'retard')
                                • {{ $action['count'] }} projet(s) en retard nécessitent votre attention<br>
                            @elseif($action['type'] === 'messages')
                                • {{ $action['count'] }} nouveau(x) message(s) non lu(s)<br>
                            @elseif($action['type'] === 'evaluation')
                                • {{ $action['count'] }} projet(s) terminé(s) à évaluer<br>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="ml-auto">
                    <button onclick="voirToutesActionsRequises()" class="text-red-700 hover:text-red-900 text-sm font-medium px-4 py-2 border border-red-300 rounded-lg hover:bg-red-100 transition-colors duration-200">
                        Voir tout
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Statistiques Client - 4 Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Total Projets --}}
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 hover:shadow-medium transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">Total Projets</h3>
                    <p class="text-xs text-slate-400 mt-1">Mes projets</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <span class="text-white text-lg">🏗️</span>
                </div>
            </div>
            <div class="space-y-2">
                <div class="text-3xl font-bold text-indigo-600 counter" 
                     x-text="animatedStats.total_projets" 
                     data-target="{{ $mes_chantiers->count() }}">0</div>
                <div class="flex items-center text-sm text-emerald-600">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                    </svg>
                    <span>Projets créés</span>
                </div>
            </div>
        </div>

        {{-- En Cours --}}
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 hover:shadow-medium transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">En Cours</h3>
                    <p class="text-xs text-slate-400 mt-1">Actifs maintenant</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-full flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <span class="text-white text-lg">⚡</span>
                </div>
            </div>
            <div class="space-y-2">
                <div class="text-3xl font-bold text-blue-600 counter" 
                     x-text="animatedStats.en_cours" 
                     data-target="{{ $mes_chantiers->where('statut', 'en_cours')->count() }}">0</div>
                <div class="flex items-center text-sm text-slate-600">
                    @if($projets_en_retard->count() > 0)
                        <span class="text-red-500 font-medium">{{ $projets_en_retard->count() }} en retard</span>
                        <span class="text-slate-500 ml-1">• Attention requise</span>
                    @else
                        <div class="w-2 h-2 bg-emerald-400 rounded-full mr-2 animate-pulse"></div>
                        <span>Dans les temps</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Terminés --}}
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 hover:shadow-medium transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">Terminés</h3>
                    <p class="text-xs text-slate-400 mt-1">Projets réussis</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-full flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <span class="text-white text-lg">✅</span>
                </div>
            </div>
            <div class="space-y-2">
                <div class="text-3xl font-bold text-emerald-600 counter" 
                     x-text="animatedStats.termines" 
                     data-target="{{ $mes_chantiers->where('statut', 'termine')->count() }}">0</div>
                <div class="flex items-center text-sm text-emerald-600">
                    @if($mes_chantiers->where('statut', 'termine')->count() > 0)
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $mes_chantiers->where('statut', 'termine')->count() }} réussis</span>
                        @if($projets_termines_non_evalues->count() > 0)
                            <span class="text-amber-500 ml-1">• {{ $projets_termines_non_evalues->count() }} à évaluer</span>
                        @endif
                    @else
                        <span>Aucun projet terminé</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Avancement Moyen --}}
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 hover:shadow-medium transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">Avancement Moyen</h3>
                    <p class="text-xs text-slate-400 mt-1">Performance globale</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <span class="text-white text-lg">📊</span>
                </div>
            </div>
            <div class="space-y-3">
                @php
                    $avancement_moyen = $mes_chantiers->avg('avancement_global') ?? 0;
                @endphp
                <div class="flex items-end space-x-2">
                    <span class="text-3xl font-bold text-amber-600 counter" x-text="animatedStats.avancement_moyen" data-target="{{ round($avancement_moyen) }}">0</span>
                    <span class="text-lg font-semibold text-amber-600 mb-1">%</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-amber-500 to-orange-600 h-2 rounded-full transition-all duration-1000 ease-out" 
                         :style="`width: ${animatedStats.avancement_moyen}%`"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Layout Principal --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Mes Projets Récents --}}
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
                                <h3 class="text-lg font-semibold text-slate-900">Mes Projets</h3>
                                <p class="text-sm text-slate-500">Gérez et suivez vos projets en cours</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <select class="text-sm border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option>Tous les statuts</option>
                                <option>En cours</option>
                                <option>Planifiés</option>
                                <option>Terminés</option>
                            </select>
                            <a href="{{ route('chantiers.index') }}" 
                               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition-colors duration-200 flex items-center">
                                Voir tous
                                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                @if($mes_chantiers->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($mes_chantiers->take(5) as $chantier)
                        <div class="p-6 hover:bg-slate-50 transition-colors duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
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
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-slate-900 truncate">
                                            <a href="{{ route('chantiers.show', $chantier) }}" class="text-indigo-600 hover:text-indigo-800">
                                                {{ $chantier->titre }}
                                            </a>
                                        </p>
                                        <p class="text-sm text-slate-500 truncate">{{ $chantier->client->name ?? 'Client' }}</p>
                                        @if($chantier->date_fin_prevue)
                                            <p class="text-xs text-slate-400 mt-1">
                                                Échéance : {{ $chantier->date_fin_prevue->format('d/m/Y') }}
                                                @if($chantier->date_fin_prevue && $chantier->date_fin_prevue->isPast() && $chantier->statut !== 'termine')
                                                    <span class="text-red-500 font-medium">• En retard</span>
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-16 bg-slate-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all duration-300" 
                                                 style="width: {{ $chantier->avancement_global ?? 0 }}%"></div>
                                        </div>
                                        <span class="text-sm font-medium text-slate-900 w-8">{{ number_format($chantier->avancement_global ?? 0, 0) }}%</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('chantiers.show', $chantier) }}" 
                                           class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </a>
                                        @if($chantier->commercial)
                                            <button onclick="contacterCommercial({{ $chantier->commercial->id }})" 
                                                    class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-20 h-20 mx-auto mb-6 bg-slate-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun projet</h3>
                        <p class="text-slate-500 mb-6">Commencez par demander un devis pour votre premier projet.</p>
                        <button onclick="openDevisModal()" class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Créer mon premier projet
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            
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
                    {{-- Action principale contextuelle --}}
                    @if($mes_chantiers->count() === 0)
                        <button onclick="openDevisModal()" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            🚀 Démarrer mon premier projet
                        </button>
                    @else
                        <button onclick="openDevisModal()" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Nouveau projet
                        </button>
                    @endif
                    
                    {{-- Actions contextuelles --}}
                    @if($projets_en_retard->count() > 0)
                        <button onclick="voirProjetsEnRetard()" class="w-full bg-red-100 text-red-600 py-3 px-4 rounded-xl font-medium hover:bg-red-200 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            ⚠️ Voir projets en retard ({{ $projets_en_retard->count() }})
                        </button>
                    @endif
                    
                    @if($messages_non_lus > 0)
                        <a href="{{ route('messages.index') }}" class="w-full bg-cyan-100 text-cyan-600 py-3 px-4 rounded-xl font-medium hover:bg-cyan-200 transition-colors duration-200 block text-center">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            📧 Messages non lus ({{ $messages_non_lus }})
                        </a>
                    @endif
                    
                    @if($projets_termines_non_evalues->count() > 0)
                        <button onclick="evaluerProjets()" class="w-full bg-amber-100 text-amber-600 py-3 px-4 rounded-xl font-medium hover:bg-amber-200 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            ⭐ Évaluer projets terminés ({{ $projets_termines_non_evalues->count() }})
                        </button>
                    @endif
                    
                    {{-- Actions standard --}}
                    <a href="{{ route('messages.create') }}" class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors duration-200 group">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-200 transition-colors duration-200">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-900">Contacter mon équipe</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Communications Récentes --}}
            <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Communications</h3>
                        <p class="text-sm text-slate-500">Échanges avec votre équipe</p>
                    </div>
                </div>
                
                @if(isset($messages) && $messages->count() > 0)
                    <div class="space-y-4 max-h-64 overflow-y-auto">
                        @foreach($messages->take(3) as $message)
                            <div class="flex items-start space-x-3 p-4 border border-slate-200 rounded-xl hover:border-indigo-300 transition-colors duration-200">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-indigo-600">{{ substr($message->from->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-slate-900">{{ $message->from->name }}</p>
                                    <p class="text-sm text-slate-500 mt-1">{{ Str::limit($message->subject, 40) }}</p>
                                    <p class="text-xs text-slate-400 mt-2">{{ $message->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    @if(!$message->lu)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-600">
                                            Nouveau
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <a href="{{ route('messages.index') }}" 
                           class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                            Voir toutes les communications
                            <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 mx-auto mb-4 bg-cyan-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-semibold text-slate-900 mb-2">Aucune communication</h4>
                        <p class="text-xs text-slate-500">Vos échanges avec l'équipe apparaîtront ici</p>
                    </div>
                @endif
            </div>

            {{-- Mon Commercial --}}
            @php
                $commercialPrincipal = $mes_chantiers->first()?->commercial;
            @endphp
            @if($commercialPrincipal)
                <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Mon Commercial</h3>
                            <p class="text-sm text-slate-500">Votre interlocuteur privilégié</p>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-lg font-bold text-white">{{ substr($commercialPrincipal->name, 0, 2) }}</span>
                        </div>
                        <h4 class="font-bold text-slate-900">{{ $commercialPrincipal->name }}</h4>
                        <p class="text-slate-500 text-sm mb-4">Votre conseiller dédié</p>
                        
                        <div class="space-y-3">
                            @if($commercialPrincipal->telephone)
                                <a href="tel:{{ $commercialPrincipal->telephone }}" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 block">
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                    </svg>
                                    Appeler
                                </a>
                            @endif
                            <button onclick="contacterCommercial({{ $commercialPrincipal->id }})" class="w-full border border-slate-300 text-slate-700 py-3 px-4 rounded-xl font-medium hover:bg-slate-50 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                                Envoyer un message
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Notifications Récentes --}}
            <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Notifications</h3>
                        <p class="text-sm text-slate-500">Actualités de vos projets</p>
                    </div>
                </div>
                
                @if(isset($notifications) && $notifications->count() > 0)
                    <div class="space-y-4 max-h-64 overflow-y-auto">
                        @foreach($notifications->take(4) as $notification)
                            <div class="flex space-x-3 p-3 rounded-xl {{ !$notification->lu ? 'bg-indigo-50' : 'hover:bg-slate-50' }} transition-colors duration-200">
                                <div class="flex-shrink-0">
                                    @switch($notification->type ?? 'default')
                                        @case('etape_terminee')
                                            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                                <span class="text-emerald-500">✅</span>
                                            </div>
                                            @break
                                        @case('retard_detecte')
                                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                <span class="text-red-500">⚠️</span>
                                            </div>
                                            @break
                                        @case('nouveau_document')
                                            <div class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center">
                                                <span class="text-cyan-500">📄</span>
                                            </div>
                                            @break
                                        @case('nouveau_commentaire_commercial')
                                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                                <span class="text-indigo-500">💬</span>
                                            </div>
                                            @break
                                        @case('projet_demarre')
                                            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                                <span class="text-emerald-500">🚀</span>
                                            </div>
                                            @break
                                        @default
                                            <div class="w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                                    @endswitch
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-slate-900">{{ $notification->titre }}</p>
                                    <p class="text-sm text-slate-500 mt-1">{{ $notification->message }}</p>
                                    <div class="flex items-center justify-between mt-2">
                                        <p class="text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                                        @if(!$notification->lu)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-600">
                                                Nouveau
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('notifications.index') }}" 
                           class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                            Voir toutes les notifications
                            <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="mx-auto h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-2 text-sm text-slate-500">Aucune notification récente</p>
                    </div>
                @endif
            </div>

            {{-- Satisfaction --}}
            <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Votre Satisfaction</h3>
                        <p class="text-sm text-slate-500">Notre priorité</p>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="flex justify-center space-x-1 mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-6 h-6 {{ $i <= 4 ? 'text-amber-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="text-slate-600 text-sm mb-4">Note moyenne : 4.2/5</p>
                    <button onclick="laisserAvis()" class="w-full bg-gradient-to-r from-amber-500 to-orange-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-amber-600 hover:to-orange-700 transition-all duration-200">
                        Laisser un avis
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modales --}}

{{-- Modal de demande de devis --}}
<div x-data="{ devisOpen: false }" @devis-modal.window="devisOpen = true">
    <div x-show="devisOpen" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" x-transition>
        <div @click.away="devisOpen = false" class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold">Demande de Devis</h2>
                    <button @click="devisOpen = false" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="mt-2 opacity-90">Décrivez-nous votre projet, nous vous recontacterons rapidement</p>
            </div>
            
            <form action="{{ route('messages.create') }}" method="GET" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type de projet</label>
                        <select name="type_projet" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Sélectionner...</option>
                            <option value="cuisine">Cuisine</option>
                            <option value="salle_bain">Salle de bain</option>
                            <option value="extension">Extension</option>
                            <option value="renovation">Rénovation complète</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Budget estimé</label>
                        <select name="budget_estime" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Sélectionner...</option>
                            <option value="moins_10k">Moins de 10 000€</option>
                            <option value="10k_25k">10 000€ - 25 000€</option>
                            <option value="25k_50k">25 000€ - 50 000€</option>
                            <option value="50k_100k">50 000€ - 100 000€</option>
                            <option value="plus_100k">Plus de 100 000€</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description du projet</label>
                    <textarea name="subject" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Décrivez votre projet en détail..." required></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date souhaitée de début</label>
                        <input type="date" name="date_debut_souhaitee" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Délai préféré</label>
                        <select name="delai_prefere" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="flexible">Flexible</option>
                            <option value="moins_1_mois">Moins de 1 mois</option>
                            <option value="1_3_mois">1-3 mois</option>
                            <option value="3_6_mois">3-6 mois</option>
                            <option value="plus_6_mois">Plus de 6 mois</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex space-x-4">
                    <button type="button" @click="devisOpen = false" class="flex-1 border border-gray-300 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-3 rounded-xl font-medium hover:from-indigo-600 hover:to-purple-700 transition-all duration-200">
                        Envoyer la Demande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Notation Projet --}}
<div id="notationModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="fermerModal('notationModal')"></div>
        
        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Noter ce projet</h3>
                <button onclick="fermerModal('notationModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="notationForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Note globale</label>
                    <div class="flex justify-center">
                        <div class="star-rating flex space-x-1" data-rating="0">
                            <svg class="w-8 h-8 text-gray-300 cursor-pointer hover:text-amber-400 transition-colors" data-value="1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="w-8 h-8 text-gray-300 cursor-pointer hover:text-amber-400 transition-colors" data-value="2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="w-8 h-8 text-gray-300 cursor-pointer hover:text-amber-400 transition-colors" data-value="3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="w-8 h-8 text-gray-300 cursor-pointer hover:text-amber-400 transition-colors" data-value="4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="w-8 h-8 text-gray-300 cursor-pointer hover:text-amber-400 transition-colors" data-value="5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire (optionnel)</label>
                    <textarea class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" rows="3" placeholder="Partagez votre expérience..."></textarea>
                </div>
            </form>
            <div class="flex space-x-3 mt-6">
                <button onclick="fermerModal('notationModal')" class="flex-1 border border-gray-300 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <button onclick="soumettreNotation()" class="flex-1 bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-3 rounded-xl font-medium hover:from-indigo-600 hover:to-purple-700 transition-all duration-200">
                    Envoyer
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Documents --}}
<div id="documentsModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="fermerModal('documentsModal')"></div>
        
        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Documents du projet</h3>
                <button onclick="fermerModal('documentsModal')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="documentsListe" class="space-y-4">
                <!-- Contenu chargé dynamiquement -->
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-slate-900 mb-2">Aucun document</h4>
                    <p class="text-slate-500">Les documents de votre projet apparaîtront ici.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Variables globales
let currentChantierId = null;

// Système d'animation des statistiques
function clientDashboard() {
    return {
        animatedStats: {
            total_projets: 0,
            en_cours: 0,
            termines: 0,
            avancement_moyen: 0
        },
        
        init() {
            this.animateCounters();
        },
        
        animateCounters() {
            const targets = {
                total_projets: {{ $mes_chantiers->count() }},
                en_cours: {{ $mes_chantiers->where('statut', 'en_cours')->count() }},
                termines: {{ $mes_chantiers->where('statut', 'termine')->count() }},
                avancement_moyen: {{ round($mes_chantiers->avg('avancement_global') ?? 0) }}
            };
            
            // Animation progressive des compteurs
            Object.keys(targets).forEach(key => {
                let start = 0;
                const end = targets[key];
                const duration = 2000;
                const increment = end / (duration / 16);
                
                const timer = setInterval(() => {
                    start += increment;
                    if (start >= end) {
                        this.animatedStats[key] = end;
                        clearInterval(timer);
                    } else {
                        this.animatedStats[key] = Math.floor(start);
                    }
                }, 16);
            });
        }
    }
}

// Fonction pour ouvrir le modal de devis
function openDevisModal() {
    window.dispatchEvent(new CustomEvent('devis-modal'));
}

// Contacter le commercial
function contacterCommercial(commercialId) {
    window.location.href = `{{ route('messages.create') }}?to=${commercialId}`;
}

// Voir les documents d'un chantier
function voirDocuments(chantierId) {
    currentChantierId = chantierId;
    ouvrirModal('documentsModal');
    // Charger les documents via AJAX si nécessaire
}

// Voir toutes les étapes
function voirToutesEtapes(chantierId) {
    window.location.href = `/chantiers/${chantierId}#etapes`;
}

// Voir tous les projets en retard
function voirProjetsEnRetard() {
    window.location.href = '{{ route("chantiers.index") }}?filter=en_retard';
}

// Évaluer les projets terminés
function evaluerProjets() {
    window.location.href = '{{ route("chantiers.index") }}?filter=a_evaluer';
}

// Voir toutes les actions requises
function voirToutesActionsRequises() {
    window.location.href = '{{ route("chantiers.index") }}?filter=actions_requises';
}

// Fonctions utilitaires pour les modales
function ouvrirModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function fermerModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Noter un projet
function noterProjet(chantierId) {
    currentChantierId = chantierId;
    ouvrirModal('notationModal');
}

// Laisser un avis
function laisserAvis() {
    showNotification('Redirection vers la page d\'avis...', 'info');
    // Implémenter la redirection vers la page d'avis
}

// Système de notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 bg-white rounded-xl shadow-strong p-4 border-l-4 ${
        type === 'success' ? 'border-emerald-500' : 
        type === 'warning' ? 'border-amber-500' : 
        type === 'error' ? 'border-red-500' : 'border-indigo-500'
    } transform translate-x-full transition-transform duration-300`;
    
    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 ${
                    type === 'success' ? 'text-emerald-500' : 
                    type === 'warning' ? 'text-amber-500' : 
                    type === 'error' ? 'text-red-500' : 'text-indigo-500'
                }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    ${type === 'success' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />' :
                        type === 'warning' ?
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />' :
                        type === 'error' ?
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />' :
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />'
                    }
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-slate-900">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animation d'entrée
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto-suppression après 5 secondes
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Système de notation par étoiles
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating svg[data-value]');
    let currentRating = 0;
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            currentRating = parseInt(this.dataset.value);
            updateStars(currentRating);
        });
        
        star.addEventListener('mouseover', function() {
            updateStars(parseInt(this.dataset.value));
        });
    });
    
    if (document.querySelector('.star-rating')) {
        document.querySelector('.star-rating').addEventListener('mouseleave', function() {
            updateStars(currentRating);
        });
    }
    
    function updateStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-amber-400');
            } else {
                star.classList.remove('text-amber-400');
                star.classList.add('text-gray-300');
            }
        });
        if (document.querySelector('.star-rating')) {
            document.querySelector('.star-rating').dataset.rating = rating;
        }
    }
    
    // Fermeture des modales avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            fermerModal('notationModal');
            fermerModal('documentsModal');
        }
    });
});

// Soumettre la notation
function soumettreNotation() {
    const ratingElement = document.querySelector('.star-rating');
    const rating = ratingElement ? ratingElement.dataset.rating : 0;
    const commentaire = document.querySelector('#notationForm textarea').value;
    
    if (rating == 0) {
        showNotification('Veuillez donner une note', 'warning');
        return;
    }
    
    // Ici, vous pouvez envoyer les données au serveur
    // fetch('/api/notations', { method: 'POST', body: JSON.stringify({...}) })
    
    showNotification('Merci pour votre évaluation !', 'success');
    fermerModal('notationModal');
}

console.log('Dashboard Client Modernisé chargé avec succès ! 🎉');
</script>
@endpush