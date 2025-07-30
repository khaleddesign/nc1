@extends('layouts.app')

@section('title', 'Chantiers')

@section('content')
<div class="space-y-6" x-data="chantiersIndex()">
    
    {{-- Header avec actions --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Gestion des Chantiers</h1>
            <p class="text-slate-500 mt-1">{{ $chantiers->total() }} projet(s) • Pilotez vos chantiers avec excellence</p>
        </div>
        
        {{-- Actions --}}
        <div class="flex items-center space-x-3">
            @can('create', App\Models\Chantier::class)
                <a href="{{ route('chantiers.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nouveau Chantier
                </a>
            @endcan
            
            <a href="{{ route('chantiers.calendrier') }}" 
               class="inline-flex items-center px-3 py-2 border border-slate-300 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-50 transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                </svg>
                Calendrier
            </a>
        </div>
    </div>

    {{-- Statistiques rapides --}}
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
                <div class="text-3xl font-bold text-indigo-600">{{ $chantiers->total() }}</div>
                <div class="flex items-center text-sm text-emerald-600">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                    </svg>
                    <span>Cette semaine</span>
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
                <div class="text-3xl font-bold text-blue-600">{{ $chantiers->where('statut', 'en_cours')->count() }}</div>
                <div class="flex items-center text-sm text-slate-600">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full mr-2 animate-pulse"></div>
                    <span>En progression</span>
                </div>
            </div>
        </div>

        {{-- Planifiés --}}
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 hover:shadow-medium transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">Planifiés</h3>
                    <p class="text-xs text-slate-400 mt-1">À venir</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <span class="text-white text-lg">📋</span>
                </div>
            </div>
            <div class="space-y-2">
                <div class="text-3xl font-bold text-amber-600">{{ $chantiers->where('statut', 'planifie')->count() }}</div>
                <div class="flex items-center text-sm text-amber-600">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>En attente</span>
                </div>
            </div>
        </div>

        {{-- Terminés --}}
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-slate-100 hover:shadow-medium transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">Terminés</h3>
                    <p class="text-xs text-slate-400 mt-1">Succès confirmés</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-full flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <span class="text-white text-lg">✅</span>
                </div>
            </div>
            <div class="space-y-2">
                <div class="text-3xl font-bold text-emerald-600">{{ $chantiers->where('statut', 'termine')->count() }}</div>
                <div class="flex items-center text-sm text-emerald-600">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Livrés</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtres compacts --}}
    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-4">
        <form method="GET" action="{{ route('chantiers.index') }}" class="space-y-3">
            <div class="flex items-center space-x-3">
                {{-- Icône et titre compact --}}
                <div class="flex items-center space-x-2">
                    <div class="w-6 h-6 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-900">Recherche & Filtres</span>
                </div>

                {{-- Badges filtres actifs --}}
                @if(request()->hasAny(['search', 'statut', 'commercial_id', 'client_id']))
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            Filtres actifs
                        </span>
                        <a href="{{ route('chantiers.index') }}" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset
                        </a>
                    </div>
                @endif

                {{-- Compteur résultats --}}
                <div class="ml-auto text-xs text-slate-500">
                    {{ $chantiers->total() }} résultat(s)
                </div>
            </div>

            {{-- Champs de filtres en ligne --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">
                <!-- Recherche -->
                <div class="lg:col-span-5">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Rechercher titre, description, client..."
                               class="pl-10 block w-full px-3 py-2 border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white">
                    </div>
                </div>

                <!-- Statut -->
                <div class="lg:col-span-3">
                    <select name="statut" class="block w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white">
                        <option value="">Tous statuts</option>
                        <option value="planifie" {{ request('statut') === 'planifie' ? 'selected' : '' }}>📋 Planifié</option>
                        <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>⚡ En cours</option>
                        <option value="termine" {{ request('statut') === 'termine' ? 'selected' : '' }}>✅ Terminé</option>
                    </select>
                </div>

                <!-- Commercial -->
                @if($commerciaux->count() > 0)
                <div class="lg:col-span-3">
                    <select name="commercial_id" class="block w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white">
                        <option value="">Tous commerciaux</option>
                        @foreach($commerciaux as $commercial)
                            <option value="{{ $commercial->id }}" {{ request('commercial_id') == $commercial->id ? 'selected' : '' }}>
                                {{ $commercial->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Bouton Filtrer -->
                <div class="lg:col-span-1">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Liste des chantiers --}}
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
                        <h3 class="text-lg font-semibold text-slate-900">Liste des Chantiers</h3>
                        <p class="text-sm text-slate-500">Gérez tous vos projets</p>
                    </div>
                </div>
            </div>
        </div>
        
        @if($chantiers->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="text-left py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Chantier</th>
                            <th class="text-left py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Client</th>
                            <th class="text-left py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Commercial</th>
                            <th class="text-left py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Statut</th>
                            <th class="text-left py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Avancement</th>
                            <th class="text-left py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Budget</th>
                            <th class="text-right py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($chantiers as $chantier)
                        <tr class="hover:bg-slate-50 transition-colors duration-200">
                            <td class="py-4 px-6">
                                <div>
                                    <div class="flex items-center space-x-3">
                                        @php
                                            $statusConfig = match($chantier->statut) {
                                                'planifie' => ['gradient' => 'from-slate-500 to-slate-600', 'icon' => '📋'],
                                                'en_cours' => ['gradient' => 'from-blue-500 to-indigo-600', 'icon' => '⚡'],
                                                'termine' => ['gradient' => 'from-emerald-500 to-green-600', 'icon' => '✅'],
                                                default => ['gradient' => 'from-slate-500 to-slate-600', 'icon' => '❓']
                                            };
                                        @endphp
                                        <div class="w-10 h-10 bg-gradient-to-br {{ $statusConfig['gradient'] }} rounded-xl flex items-center justify-center text-white text-sm shadow-lg">
                                            {{ $statusConfig['icon'] }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900">{{ $chantier->titre }}</div>
                                            @if($chantier->description)
                                                <div class="text-sm text-slate-500 mt-1">{{ Str::limit($chantier->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-semibold mr-3">
                                        {{ substr($chantier->client->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="font-medium text-slate-900">{{ $chantier->client->name }}</span>
                                        @if($chantier->client->telephone)
                                            <div class="text-xs text-slate-500">{{ $chantier->client->telephone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-green-600 rounded-full flex items-center justify-center text-white text-sm font-semibold mr-3">
                                        {{ substr($chantier->commercial->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-slate-900">{{ $chantier->commercial->name }}</span>
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
                                @if($chantier->isEnRetard())
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">
                                            ⚠️ En retard
                                        </span>
                                    </div>
                                @endif
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
                                @if($chantier->etapes->count() > 0)
                                    <div class="text-xs text-slate-500 mt-1">
                                        {{ $chantier->etapes->where('terminee', true)->count() }}/{{ $chantier->etapes->count() }} étapes
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($chantier->budget)
                                    <div class="font-semibold text-slate-900">{{ number_format($chantier->budget, 0, ',', ' ') }} €</div>
                                @else
                                    <span class="text-slate-400 text-sm">Non défini</span>
                                @endif
                                @if($chantier->date_debut && $chantier->date_fin_prevue)
                                    <div class="text-xs text-slate-500 mt-1">
                                        {{ $chantier->date_debut->format('d/m/Y') }} → {{ $chantier->date_fin_prevue->format('d/m/Y') }}
                                    </div>
                                @endif
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
                                    @can('update', $chantier)
                                        <a href="{{ route('chantiers.edit', $chantier) }}" 
                                           class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </a>
                                    @endcan

                                    <!-- Bouton supprimer -->
                                    @if(Auth::user()->isAdmin())
                                        @can('delete', $chantier)
                                            <form method="POST" action="{{ route('chantiers.destroy', $chantier) }}" class="inline" 
                                                  onsubmit="return confirm('⚠️ ATTENTION : Cette action supprimera définitivement le chantier. Voulez-vous continuer ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    @else
                                        @can('softDelete', $chantier)
                                            <form method="POST" action="{{ route('chantiers.soft-delete', $chantier) }}" class="inline" 
                                                  onsubmit="return confirm('Voulez-vous masquer ce chantier de votre liste ? (Il restera visible pour l\'administrateur)')">
                                                @csrf
                                                <button type="submit" 
                                                        class="p-2 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    @endif
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
                <h3 class="text-lg font-semibold text-slate-900 mb-2">
                    @if(request()->hasAny(['search', 'statut', 'commercial_id', 'client_id']))
                        Aucun chantier trouvé
                    @else
                        Aucun chantier pour le moment
                    @endif
                </h3>
                <p class="text-slate-500 mb-6">
                    @if(request()->hasAny(['search', 'statut', 'commercial_id', 'client_id']))
                        Aucun chantier ne correspond aux critères sélectionnés. Essayez de modifier vos filtres.
                    @else
                        Commencez par créer votre premier chantier pour démarrer la gestion de vos projets.
                    @endif
                </p>
                @can('create', App\Models\Chantier::class)
                    @unless(request()->hasAny(['search', 'statut', 'commercial_id', 'client_id']))
                        <a href="{{ route('chantiers.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Créer mon premier chantier
                        </a>
                    @else
                        <a href="{{ route('chantiers.index') }}" 
                           class="inline-flex items-center px-6 py-3 border border-indigo-300 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 font-semibold rounded-xl transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Réinitialiser les filtres
                        </a>
                    @endunless
                @endcan
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    @if($chantiers->hasPages())
        <div class="bg-white rounded-2xl shadow-soft border border-slate-100 p-6">
            <div class="flex items-center justify-between">
                <div class="text-sm text-slate-500">
                    Affichage de {{ $chantiers->firstItem() }} à {{ $chantiers->lastItem() }} sur {{ $chantiers->total() }} résultats
                </div>
                <div class="flex items-center space-x-2">
                    {{ $chantiers->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function chantiersIndex() {
    return {
        init() {
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
                    }, 800);
                });
            }
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

.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

@media (max-width: 768px) {
    .overflow-x-auto table {
        min-width: 800px;
    }
    
    .grid-cols-2.md\:grid-cols-4 {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .lg\:grid-cols-4 {
        grid-template-columns: repeat(1, 1fr);
    }
    
    .lg\:col-span-2 {
        grid-column: span 1;
    }
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
                case 'n':
                    e.preventDefault();
                    window.location.href = '{{ route("chantiers.create") }}';
                    break;
                case 'k':
                    e.preventDefault();
                    document.querySelector('input[name="search"]').focus();
                    break;
            }
        }
    });
});
</script>
@endpush