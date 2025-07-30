@extends('layouts.app')

@section('title', 'Tableau de bord Administrateur')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Vue d\'ensemble de votre activité BTP')

@section('content')
<div class="space-y-6" x-data="adminDashboard()">
    
    {{-- Actions Rapides - Version Optimisée --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Tableau de bord</h1>
            <p class="text-slate-500 mt-1">Vue d'ensemble de votre activité BTP</p>
        </div>
        
        {{-- Actions compactes à droite --}}
        <div class="flex items-center space-x-3">
            <a href="{{ route('chantiers.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nouveau Chantier
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
                    
                    <a href="{{ route('admin.users.create') }}" 
                       class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Nouvel Utilisateur</div>
                            <div class="text-xs text-slate-500">Ajouter un client ou commercial</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.statistics') }}" 
                       class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Statistiques Avancées</div>
                            <div class="text-xs text-slate-500">Analyses et rapports détaillés</div>
                        </div>
                    </a>
                    
                    <div class="border-t border-slate-100 my-2"></div>
                    
                    <a href="{{ route('admin.users') }}" 
                       class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors duration-200">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Gérer Utilisateurs</div>
                            <div class="text-xs text-slate-500">Liste et administration</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiques Principales --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Total Chantiers --}}
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 hover:shadow-medium transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">Total Chantiers</h3>
                    <p class="text-xs text-slate-400 mt-1">Tous projets</p>
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
                    <span>+{{ $stats['chantiers_actifs'] ?? 0 }} actifs</span>
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
                     x-text="animatedStats.chantiers_actifs" 
                     data-target="{{ $stats['chantiers_actifs'] ?? 0 }}">0</div>
                <div class="flex items-center text-sm text-slate-600">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full mr-2 animate-pulse"></div>
                    <span>En progression</span>
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
                     x-text="animatedStats.chantiers_termines" 
                     data-target="{{ $stats['chantiers_termines'] ?? 0 }}">0</div>
                <div class="flex items-center text-sm text-emerald-600">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Succès confirmés</span>
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
                <div class="flex items-end space-x-2">
                    <span class="text-3xl font-bold text-amber-600 counter" x-text="animatedStats.avancement_moyen" data-target="{{ round($stats['avancement_moyen'] ?? 0) }}">0</span>
                    <span class="text-lg font-semibold text-amber-600 mb-1">%</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-amber-500 to-orange-600 h-2 rounded-full transition-all duration-1000 ease-out" 
                         :style="`width: ${animatedStats.avancement_moyen}%`"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section Principale --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Chantiers Récents --}}
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
                                <h3 class="text-lg font-semibold text-slate-900">Chantiers Récents</h3>
                                <p class="text-sm text-slate-500">Derniers projets créés</p>
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
                
                @if(isset($chantiers_recents) && $chantiers_recents->count() > 0)
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
                                @foreach($chantiers_recents as $chantier)
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-3.75-16.5h3.75A2.25 2.25 0 0121 6.75v12a2.25 2.25 0 01-2.25 2.25h-3.75M16.5 7.5V21a.75.75 0 01-.75.75M6 7.5V21a.75.75 0 01-.75.75" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun chantier</h3>
                        <p class="text-slate-500 mb-6">Commencez par créer un nouveau chantier pour voir l'activité ici.</p>
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
            
            {{-- Répartition Utilisateurs --}}
            <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Utilisateurs</h3>
                        <p class="text-sm text-slate-500">Répartition par rôle</p>
                    </div>
                </div>
                
                @php
                    $clientsCount = \App\Models\User::where('role', 'client')->count();
                    $commerciauxCount = \App\Models\User::where('role', 'commercial')->count();
                    $adminsCount = \App\Models\User::where('role', 'admin')->count();
                    $totalUsers = $clientsCount + $commerciauxCount + $adminsCount;
                @endphp
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-indigo-500 rounded-full"></div>
                            <span class="text-sm font-medium text-slate-700">Clients</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-20 bg-slate-200 rounded-full h-2">
                                <div class="bg-indigo-500 h-2 rounded-full transition-all duration-1000" 
                                     style="width: {{ $totalUsers > 0 ? ($clientsCount / $totalUsers) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-lg font-bold text-slate-900 min-w-[2rem] text-right">{{ $clientsCount }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                            <span class="text-sm font-medium text-slate-700">Commerciaux</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-20 bg-slate-200 rounded-full h-2">
                                <div class="bg-amber-500 h-2 rounded-full transition-all duration-1000" 
                                     style="width: {{ $totalUsers > 0 ? ($commerciauxCount / $totalUsers) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-lg font-bold text-slate-900 min-w-[2rem] text-right">{{ $commerciauxCount }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-sm font-medium text-slate-700">Admins</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-20 bg-slate-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full transition-all duration-1000" 
                                     style="width: {{ $totalUsers > 0 ? ($adminsCount / $totalUsers) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-lg font-bold text-slate-900 min-w-[2rem] text-right">{{ $adminsCount }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-slate-900">{{ $totalUsers }}</div>
                        <div class="text-sm text-slate-500">Total utilisateurs</div>
                    </div>
                </div>
            </div>

            {{-- Activité Récente --}}
            <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Activité Récente</h3>
                        <p class="text-sm text-slate-500">Dernières actions système</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors duration-200">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-900">Nouveau chantier créé</p>
                            <p class="text-xs text-slate-500 mt-1">{{ auth()->user()->name }} - Il y a 2 heures</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors duration-200">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-900">Utilisateur ajouté</p>
                            <p class="text-xs text-slate-500 mt-1">Nouveau client - Il y a 4 heures</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors duration-200">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-900">Chantier mis à jour</p>
                            <p class="text-xs text-slate-500 mt-1">Avancement 85% - Hier</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alertes --}}
            @if(isset($stats['chantiers_en_retard']) && $stats['chantiers_en_retard'] > 0)
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-red-900">Attention</h3>
                        <p class="text-sm text-red-700">{{ $stats['chantiers_en_retard'] }} chantier(s) en retard</p>
                    </div>
                </div>
                <a href="{{ route('chantiers.index', ['filter' => 'en_retard']) }}" 
                   class="inline-flex items-center text-sm font-semibold text-red-700 hover:text-red-900 transition-colors duration-200">
                    Voir les chantiers en retard
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>
            @endif

            {{-- Raccourcis --}}
            <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Raccourcis</h3>
                        <p class="text-sm text-slate-500">Actions fréquentes</p>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <a href="{{ route('chantiers.index') }}" 
                       class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors duration-200 group">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors duration-200">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-900">Tous les chantiers</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                    
                    <a href="{{ route('admin.users') }}" 
                       class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors duration-200 group">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-200 transition-colors duration-200">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-900">Gérer utilisateurs</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function adminDashboard() {
    return {
        animatedStats: {
            total_chantiers: 0,
            chantiers_actifs: 0,
            chantiers_termines: 0,
            avancement_moyen: 0
        },
        
        targetStats: {
            total_chantiers: {{ $stats['total_chantiers'] ?? 0 }},
            chantiers_actifs: {{ $stats['chantiers_actifs'] ?? 0 }},
            chantiers_termines: {{ $stats['chantiers_termines'] ?? 0 }},
            avancement_moyen: {{ round($stats['avancement_moyen'] ?? 0) }}
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

.group:hover .group-hover\:bg-indigo-500 {
    background-color: rgb(99 102 241);
}

@media (max-width: 768px) {
    .grid-cols-2.md\:grid-cols-4 {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .lg\:col-span-2 {
        grid-column: span 1;
    }
    
    .p-8 {
        padding: 1.5rem;
    }
    
    .p-6 {
        padding: 1rem;
    }
}

@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

*:focus-visible {
    outline: 2px solid rgb(99 102 241);
    outline-offset: 2px;
    border-radius: 4px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 'n':
                    e.preventDefault();
                    window.location.href = '{{ route("chantiers.create") }}';
                    break;
                case 'u':
                    e.preventDefault();
                    window.location.href = '{{ route("admin.users") }}';
                    break;
            }
        }
    });
});
</script>
@endpush