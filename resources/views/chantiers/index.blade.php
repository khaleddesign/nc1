@extends('layouts.app')

@section('title', 'Chantiers')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- Header avec dégradé - Identique au dashboard -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18M6.75 9h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-3.75-16.5h3.75A2.25 2.25 0 0121 6.75v12a2.25 2.25 0 01-2.25 2.25h-3.75M16.5 7.5V21a.75.75 0 01-.75.75M6 7.5V21a.75.75 0 01-.75.75" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-6">
                            <h1 class="text-3xl font-bold text-white sm:text-4xl">
                                Gestion des Chantiers
                            </h1>
                            <p class="mt-2 text-blue-100 text-lg">
                                {{ $chantiers->total() }} projet(s) • Suivez et gérez tous vos projets en cours 🏗️
                            </p>
                        </div>
                    </div>
                </div>
                
                @can('create', App\Models\Chantier::class)
                    <div class="mt-6 flex space-x-3 md:mt-0 md:ml-4">
                        <a href="{{ route('chantiers.create') }}" 
                           class="inline-flex items-center px-6 py-3 border border-transparent rounded-full shadow-sm text-sm font-medium text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Nouveau chantier
                        </a>
                        <a href="{{ route('chantiers.calendrier') }}" 
                           class="inline-flex items-center px-6 py-3 border border-white/20 rounded-full shadow-sm text-sm font-medium text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                            </svg>
                            Calendrier
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filtres compacts et efficaces -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-8">
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    Recherche & Filtres
                </h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('chantiers.index') }}" class="flex flex-col sm:flex-row gap-4">
                    <!-- Recherche principale -->
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Rechercher par titre, description, client..."
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres essentiels -->
                    <div class="flex gap-3">
                        <select name="statut" class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous statuts</option>
                            <option value="planifie" {{ request('statut') === 'planifie' ? 'selected' : '' }}>📋 Planifié</option>
                            <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>🔄 En cours</option>
                            <option value="termine" {{ request('statut') === 'termine' ? 'selected' : '' }}>✅ Terminé</option>
                        </select>

                        @if($commerciaux->count() > 0)
                        <select name="commercial_id" class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous commerciaux</option>
                            @foreach($commerciaux as $commercial)
                                <option value="{{ $commercial->id }}" {{ request('commercial_id') == $commercial->id ? 'selected' : '' }}>
                                    {{ $commercial->name }}
                                </option>
                            @endforeach
                        </select>
                        @endif

                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 font-medium">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            Filtrer
                        </button>

                        @if(request()->hasAny(['search', 'statut', 'commercial_id', 'client_id']))
                            <a href="{{ route('chantiers.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 font-medium">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des chantiers optimisée -->
        <div class="space-y-4">
            @forelse($chantiers as $chantier)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <!-- Informations principales -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-3 mb-3">
                                    <!-- Statut visuel -->
                                    <div class="flex-shrink-0">
                                        @php
                                            $statusConfig = match($chantier->statut) {
                                                'planifie' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'dot' => 'bg-gray-400', 'icon' => '📋'],
                                                'en_cours' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'dot' => 'bg-blue-500', 'icon' => '🔄'],
                                                'termine' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'dot' => 'bg-green-500', 'icon' => '✅'],
                                                default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'dot' => 'bg-gray-400', 'icon' => '❓']
                                            };
                                        @endphp
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 rounded-full {{ $statusConfig['dot'] }} {{ $chantier->statut === 'en_cours' ? 'animate-pulse' : '' }}"></div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                                {{ $statusConfig['icon'] }} {{ $chantier->getStatutTexte() }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Alertes -->
                                    @if($chantier->isEnRetard())
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800">
                                            ⚠️ En retard
                                        </span>
                                    @endif
                                </div>

                                <!-- Titre et description -->
                                <div class="mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                        <a href="{{ route('chantiers.show', $chantier) }}" 
                                           class="hover:text-blue-600 transition-colors">
                                            {{ $chantier->titre }}
                                        </a>
                                    </h3>
                                    @if($chantier->description)
                                        <p class="text-sm text-gray-600 line-clamp-2">
                                            {{ $chantier->description }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Métadonnées en ligne -->
                                <div class="flex flex-wrap items-center gap-6 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                        </svg>
                                        <span class="font-medium text-gray-900">{{ $chantier->client->name }}</span>
                                    </div>

                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" />
                                        </svg>
                                        {{ $chantier->commercial->name }}
                                    </div>

                                    @if($chantier->date_debut && $chantier->date_fin_prevue)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                                            </svg>
                                            <span class="{{ $chantier->isEnRetard() ? 'text-red-600 font-medium' : '' }}">
                                                {{ $chantier->date_debut->format('d/m/Y') }} → {{ $chantier->date_fin_prevue->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    @endif

                                    @if($chantier->budget)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ number_format($chantier->budget, 0, ',', ' ') }} €
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Progression et actions -->
                            <div class="flex-shrink-0 ml-6">
                                <div class="text-right mb-4">
                                    <!-- Avancement -->
                                    <div class="mb-2">
                                        <div class="flex items-center justify-end space-x-2 mb-1">
                                            <span class="text-sm text-gray-500">Avancement</span>
                                            <span class="text-lg font-bold text-gray-900">{{ number_format($chantier->avancement_global ?? 0, 0) }}%</span>
                                        </div>
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            @php
                                                $progress = $chantier->avancement_global ?? 0;
                                                $progressColor = 'bg-gray-400';
                                                if ($progress >= 100) $progressColor = 'bg-green-500';
                                                elseif ($progress >= 75) $progressColor = 'bg-blue-500';
                                                elseif ($progress >= 50) $progressColor = 'bg-yellow-500';
                                                elseif ($progress >= 25) $progressColor = 'bg-orange-500';
                                                elseif ($progress > 0) $progressColor = 'bg-red-500';
                                            @endphp
                                            <div class="h-2 rounded-full transition-all duration-500 {{ $progressColor }}" 
                                                 style="width: {{ $progress }}%"></div>
                                        </div>
                                        @if($chantier->etapes->count() > 0)
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $chantier->etapes->where('terminee', true)->count() }}/{{ $chantier->etapes->count() }} étapes
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('chantiers.show', $chantier) }}" 
                                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Voir
                                    </a>
                                    
                                    @can('update', $chantier)
                                        <a href="{{ route('chantiers.edit', $chantier) }}" 
                                           class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                           title="Modifier">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- État vide -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="mx-auto h-20 w-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mb-6">
                        <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18M6.75 9h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-3.75-16.5h3.75A2.25 2.25 0 0121 6.75v12a2.25 2.25 0 01-2.25 2.25h-3.75M16.5 7.5V21a.75.75 0 01-.75.75M6 7.5V21a.75.75 0 01-.75.75" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        @if(request()->hasAny(['search', 'statut', 'commercial_id', 'client_id']))
                            Aucun chantier trouvé
                        @else
                            Aucun chantier pour le moment
                        @endif
                    </h3>
                    <p class="text-gray-500 mb-8">
                        @if(request()->hasAny(['search', 'statut', 'commercial_id', 'client_id']))
                            Aucun chantier ne correspond aux critères sélectionnés. Essayez de modifier vos filtres.
                        @else
                            Commencez par créer votre premier chantier pour démarrer la gestion de vos projets.
                        @endif
                    </p>
                    @can('create', App\Models\Chantier::class)
                        @unless(request()->hasAny(['search', 'statut', 'commercial_id', 'client_id']))
                            <a href="{{ route('chantiers.create') }}" 
                               class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-base font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Créer mon premier chantier
                            </a>
                        @else
                            <a href="{{ route('chantiers.index') }}" 
                               class="inline-flex items-center px-6 py-3 border border-blue-300 rounded-xl text-base font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Réinitialiser les filtres
                            </a>
                        @endunless
                    @endcan
                </div>
            @endforelse
        </div>

        <!-- Pagination moderne -->
        @if($chantiers->hasPages())
            <div class="mt-12 flex justify-center">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                    {{ $chantiers->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit pour les selects
    const selects = document.querySelectorAll('select[name="statut"], select[name="commercial_id"]');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Recherche avec debounce
    const searchInput = document.querySelector('input[name="search"]');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 500);
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth hover effects */
.hover\:shadow-md:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
</style>
@endpush