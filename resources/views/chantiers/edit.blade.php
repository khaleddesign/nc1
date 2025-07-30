@extends('layouts.app')

@section('title', 'Modifier le chantier')

@section('content')
<div class="min-h-screen bg-slate-50" x-data="chantierEditData()">
    <!-- Container principal avec padding responsive -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        
        <!-- Breadcrumb moderne -->
        <nav class="flex items-center space-x-2 text-sm mb-8" aria-label="Breadcrumb">
            <a href="{{ route('chantiers.index') }}" 
               class="text-slate-600 hover:text-indigo-600 font-medium transition-colors duration-200">
                Chantiers
            </a>
            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
            <a href="{{ route('chantiers.show', $chantier) }}" 
               class="text-slate-600 hover:text-indigo-600 font-medium transition-colors duration-200">
                {{ Str::limit($chantier->titre, 30) }}
            </a>
            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
            <span class="text-slate-900 font-semibold">Modifier</span>
        </nav>

        <!-- En-tête avec gradient moderne -->
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 rounded-3xl shadow-xl mb-8">
            <!-- Effets de fond -->
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
            
            <!-- Contenu -->
            <div class="relative px-8 py-10 sm:px-12">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-16 w-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center ring-1 ring-white/30">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-8">
                        <h1 class="text-4xl font-bold text-white mb-2">
                            Modifier le chantier
                        </h1>
                        <p class="text-indigo-100 text-lg font-medium">
                            {{ $chantier->titre }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire dans une grille responsive -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- Colonne principale - Formulaire -->
            <div class="xl:col-span-2">
                <div class="card">
                    <form method="POST" action="{{ route('chantiers.update', $chantier) }}" id="editChantierForm">
                        @csrf
                        @method('PUT')

                        <div class="card-body space-y-12">
                            
                            <!-- Section 1: Informations générales -->
                            <div class="space-y-6">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-semibold text-slate-900">Informations générales</h2>
                                        <p class="text-slate-600 text-sm">Titre, description et détails du chantier</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-6">
                                    <!-- Titre -->
                                    <div>
                                        <label for="titre" class="form-label required">
                                            Titre du chantier
                                        </label>
                                        <input type="text" 
                                               class="form-input @error('titre') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                               id="titre" 
                                               name="titre" 
                                               value="{{ old('titre', $chantier->titre) }}" 
                                               required
                                               autofocus
                                               x-model="formData.titre">
                                        @error('titre')
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-textarea @error('description') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                                  id="description" 
                                                  name="description" 
                                                  rows="4"
                                                  placeholder="Décrivez le projet en détail..."
                                                  x-model="formData.description">{{ old('description', $chantier->description) }}</textarea>
                                        @error('description')
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Intervenants -->
                            <div class="space-y-6">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-semibold text-slate-900">Intervenants</h2>
                                        <p class="text-slate-600 text-sm">Client et commercial responsable</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Client -->
                                    <div>
                                        <label for="client_id" class="form-label required">Client</label>
                                        <select class="form-select @error('client_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                                id="client_id" 
                                                name="client_id" 
                                                required
                                                x-model="formData.client_id"
                                                @change="updateClientInfo()">
                                            <option value="">Sélectionner un client</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" 
                                                        {{ old('client_id', $chantier->client_id) == $client->id ? 'selected' : '' }}
                                                        data-phone="{{ $client->telephone }}"
                                                        data-address="{{ $client->adresse }}">
                                                    {{ $client->name }}
                                                    @if($client->telephone)
                                                        - {{ $client->telephone }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('client_id')
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                        
                                        <!-- Info client -->
                                        <div x-show="clientInfo.visible" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 transform scale-95"
                                             x-transition:enter-end="opacity-100 transform scale-100"
                                             class="mt-3">
                                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                                                <div class="space-y-2">
                                                    <div x-show="clientInfo.phone" class="flex items-center text-sm text-blue-800">
                                                        <svg class="h-4 w-4 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                                        </svg>
                                                        <span x-text="clientInfo.phone"></span>
                                                    </div>
                                                    <div x-show="clientInfo.address" class="flex items-start text-sm text-blue-800">
                                                        <svg class="h-4 w-4 mr-2 mt-0.5 text-blue-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                                        </svg>
                                                        <span x-text="clientInfo.address"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Commercial -->
                                    <div>
                                        <label for="commercial_id" class="form-label required">Commercial responsable</label>
                                        <select class="form-select @error('commercial_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                                id="commercial_id" 
                                                name="commercial_id" 
                                                required
                                                x-model="formData.commercial_id">
                                            <option value="">Sélectionner un commercial</option>
                                            @foreach($commerciaux as $commercial)
                                                <option value="{{ $commercial->id }}" 
                                                        {{ old('commercial_id', $chantier->commercial_id) == $commercial->id ? 'selected' : '' }}>
                                                    {{ $commercial->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('commercial_id')
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Planning et Budget -->
                            <div class="space-y-6">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-xl bg-amber-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-semibold text-slate-900">Planning et Budget</h2>
                                        <p class="text-slate-600 text-sm">Dates de réalisation et informations financières</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <!-- Statut -->
                                    <div>
                                        <label for="statut" class="form-label required">Statut du chantier</label>
                                        <select class="form-select @error('statut') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                                id="statut" 
                                                name="statut" 
                                                required
                                                x-model="formData.statut">
                                            <option value="planifie" {{ old('statut', $chantier->statut) == 'planifie' ? 'selected' : '' }}>
                                                📋 Planifié
                                            </option>
                                            <option value="en_cours" {{ old('statut', $chantier->statut) == 'en_cours' ? 'selected' : '' }}>
                                                🚧 En cours
                                            </option>
                                            <option value="termine" {{ old('statut', $chantier->statut) == 'termine' ? 'selected' : '' }}>
                                                ✅ Terminé
                                            </option>
                                        </select>
                                        @error('statut')
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Budget -->
                                    <div>
                                        <label for="budget" class="form-label">Budget (€)</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <span class="text-slate-500 text-sm font-medium">€</span>
                                            </div>
                                            <input type="number" 
                                                   class="form-input pl-10 @error('budget') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                                   id="budget" 
                                                   name="budget" 
                                                   value="{{ old('budget', $chantier->budget) }}" 
                                                   step="0.01" 
                                                   min="0"
                                                   placeholder="0.00"
                                                   x-model="formData.budget">
                                        </div>
                                        @error('budget')
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Avancement actuel -->
                                    <div>
                                        <label class="form-label">Avancement actuel</label>
                                        <div class="mt-2">
                                            <div class="flex items-center justify-between text-sm mb-3">
                                                <span class="text-slate-600 font-medium">Progression</span>
                                                <span class="font-bold text-slate-900 bg-slate-100 px-2 py-1 rounded-lg">
                                                    {{ number_format($chantier->avancement_global, 1) }}%
                                                </span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar {{ $chantier->getProgressBarColor() }}" 
                                                     style="width: {{ $chantier->avancement_global }}%"></div>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-2 flex items-center">
                                                <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                                                </svg>
                                                Calculé automatiquement selon les étapes
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 4: Dates -->
                            <div class="space-y-6">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-xl bg-purple-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-semibold text-slate-900">Dates importantes</h2>
                                        <p class="text-slate-600 text-sm">Planning prévisionnel et réel</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <!-- Date début -->
                                    <div>
                                        <label for="date_debut" class="form-label">Date de début</label>
                                        <input type="date" 
                                               class="form-input @error('date_debut') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                               id="date_debut" 
                                               name="date_debut" 
                                               value="{{ old('date_debut', optional($chantier->date_debut)->format('Y-m-d')) }}"
                                               x-model="formData.date_debut"
                                               @change="validateDates()">
                                        @error('date_debut')
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Date fin prévue -->
                                    <div>
                                        <label for="date_fin_prevue" class="form-label">Date de fin prévue</label>
                                        <input type="date" 
                                               class="form-input @error('date_fin_prevue') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                               id="date_fin_prevue" 
                                               name="date_fin_prevue" 
                                               value="{{ old('date_fin_prevue', optional($chantier->date_fin_prevue)->format('Y-m-d')) }}"
                                               x-model="formData.date_fin_prevue"
                                               @change="validateDates()">
                                        @error('date_fin_prevue')
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                        @if($chantier->isEnRetard())
                                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                <div class="flex items-center text-sm text-red-800">
                                                    <svg class="h-4 w-4 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                                    </svg>
                                                    Ce chantier est en retard
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Date fin effective -->
                                    <div>
                                        <label for="date_fin_effective" class="form-label">Date de fin effective</label>
                                        <input type="date" 
                                               class="form-input @error('date_fin_effective') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                               id="date_fin_effective" 
                                               name="date_fin_effective" 
                                               value="{{ old('date_fin_effective', optional($chantier->date_fin_effective)->format('Y-m-d')) }}"
                                               x-model="formData.date_fin_effective"
                                               @change="suggestStatusOnCompletion()">
                                        @error('date_fin_effective')
                                            <div class="form-error">{{ $message }}</div>
                                        @enderror
                                        <p class="text-xs text-slate-500 mt-2 flex items-center">
                                            <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                            </svg>
                                            À renseigner une fois le chantier terminé
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 5: Notes -->
                            <div class="space-y-6">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-semibold text-slate-900">Notes internes</h2>
                                        <p class="text-slate-600 text-sm">Informations complémentaires visibles par l'équipe</p>
                                    </div>
                                </div>

                                <div>
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-textarea @error('notes') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                              id="notes" 
                                              name="notes" 
                                              rows="4" 
                                              placeholder="Notes techniques, contraintes, points d'attention..."
                                              x-model="formData.notes">{{ old('notes', $chantier->notes) }}</textarea>
                                    @error('notes')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                    <p class="text-xs text-slate-500 mt-3 flex items-center bg-slate-50 rounded-lg p-3">
                                        <svg class="h-4 w-4 mr-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Ces notes sont visibles par l'équipe interne uniquement
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Footer avec boutons -->
                        <div class="card-footer bg-slate-50/80 backdrop-blur-sm">
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
                                <!-- Boutons de navigation -->
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('chantiers.show', $chantier) }}" class="btn-outline">
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                                        </svg>
                                        Retour au chantier
                                    </a>
                                    <a href="{{ route('chantiers.index') }}" class="btn-outline">
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                        Liste des chantiers
                                    </a>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="flex flex-wrap gap-3">
                                    <button type="button" 
                                            @click="openPreviewModal()" 
                                            class="btn-outline">
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Aperçu des modifications
                                    </button>
                                    <button type="submit" 
                                            class="btn-primary"
                                            :disabled="isSubmitting"
                                            x-bind:class="{ 'opacity-75 cursor-not-allowed': isSubmitting }">
                                        <svg x-show="!isSubmitting" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
                                        </svg>
                                        <svg x-show="isSubmitting" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span x-text="isSubmitting ? 'Enregistrement...' : 'Enregistrer les modifications'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Colonne droite - Informations complémentaires -->
            <div class="xl:col-span-1 space-y-6">
                
                <!-- Informations du chantier -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-slate-900 flex items-center">
                            <svg class="h-5 w-5 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                            Informations du chantier
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                <span class="text-sm text-slate-600 font-medium">Créé le</span>
                                <span class="text-sm font-semibold text-slate-900">{{ $chantier->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                <span class="text-sm text-slate-600 font-medium">Dernière modification</span>
                                <span class="text-sm font-semibold text-slate-900">{{ $chantier->updated_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                <span class="text-sm text-slate-600 font-medium">Étapes définies</span>
                                <span class="text-sm font-semibold text-slate-900 bg-indigo-100 text-indigo-800 px-2 py-1 rounded-lg">
                                    {{ $chantier->etapes->count() }} étape(s)
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100">
                                <span class="text-sm text-slate-600 font-medium">Documents</span>
                                <span class="text-sm font-semibold text-slate-900 bg-emerald-100 text-emerald-800 px-2 py-1 rounded-lg">
                                    {{ $chantier->documents->count() }} fichier(s)
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm text-slate-600 font-medium">Commentaires</span>
                                <span class="text-sm font-semibold text-slate-900 bg-amber-100 text-amber-800 px-2 py-1 rounded-lg">
                                    {{ $chantier->commentaires->count() }} message(s)
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-slate-900 flex items-center">
                            <svg class="h-5 w-5 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                            Actions rapides
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3">
                            <a href="{{ route('chantiers.show', $chantier) }}" 
                               class="w-full flex items-center justify-center px-4 py-3 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 hover-lift">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Voir le chantier
                            </a>
                            
                            @can('update', $chantier)
                                @if($chantier->etapes->count() === 0)
                                    <button @click="suggestSteps()" 
                                            class="w-full flex items-center justify-center px-4 py-3 border-2 border-indigo-200 rounded-xl text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 hover:border-indigo-300 transition-all duration-200 hover-lift">
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        Ajouter des étapes
                                    </button>
                                @endif
                            @endcan
                            
                            <button @click="window.print()" 
                                    class="w-full flex items-center justify-center px-4 py-3 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 hover-lift">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231a1.125 1.125 0 01-1.12-1.227L6.34 18m11.32 0H6.34m11.32 0H17.66M6.34 18H6.14" />
                                </svg>
                                Imprimer la fiche
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Progression visuelle -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-slate-900 flex items-center">
                            <svg class="h-5 w-5 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            État du projet
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <!-- Statut actuel -->
                            <div class="text-center">
                                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold 
                                    @if($chantier->statut === 'planifie') bg-slate-100 text-slate-800
                                    @elseif($chantier->statut === 'en_cours') bg-blue-100 text-blue-800
                                    @else bg-emerald-100 text-emerald-800 @endif">
                                    @if($chantier->statut === 'planifie') 📋 Planifié
                                    @elseif($chantier->statut === 'en_cours') 🚧 En cours
                                    @else ✅ Terminé @endif
                                </div>
                            </div>

                            <!-- Progression circulaire -->
                            <div class="flex justify-center">
                                <div class="relative w-24 h-24">
                                    <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 36 36">
                                        <path class="text-slate-200" stroke="currentColor" stroke-width="3" fill="none" 
                                              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        <path class="text-indigo-600" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"
                                              stroke-dasharray="{{ $chantier->avancement_global }}, 100"
                                              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-lg font-bold text-slate-900">{{ number_format($chantier->avancement_global, 0) }}%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Métriques -->
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div class="bg-slate-50 rounded-lg p-3">
                                    <div class="text-lg font-bold text-slate-900">{{ $chantier->etapes->count() }}</div>
                                    <div class="text-xs text-slate-600">Étapes</div>
                                </div>
                                <div class="bg-slate-50 rounded-lg p-3">
                                    <div class="text-lg font-bold text-slate-900">{{ $chantier->etapes->where('statut', 'termine')->count() }}</div>
                                    <div class="text-xs text-slate-600">Terminées</div>
                                </div>
                                <div class="bg-slate-50 rounded-lg p-3">
                                    <div class="text-lg font-bold text-slate-900">{{ $chantier->documents->count() }}</div>
                                    <div class="text-xs text-slate-600">Docs</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'aperçu des modifications -->
    <div x-show="showPreviewModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        
        <!-- Overlay -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="closePreviewModal()"></div>
        
        <!-- Modal -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div x-show="showPreviewModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-slate-200">
                    <h3 class="text-xl font-semibold text-slate-900">Aperçu des modifications</h3>
                    <button @click="closePreviewModal()" 
                            class="text-slate-400 hover:text-slate-600 transition-colors p-2 hover:bg-slate-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Content -->
                <div class="p-6 overflow-y-auto max-h-96">
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <h4 class="font-semibold text-slate-900 flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    Titre du chantier
                                </h4>
                                <p class="text-slate-600" x-text="formData.titre || 'Non défini'"></p>
                            </div>
                            <div class="space-y-3">
                                <h4 class="font-semibold text-slate-900 flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                    </svg>
                                    Statut
                                </h4>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                      :class="{
                                          'bg-slate-100 text-slate-800': formData.statut === 'planifie',
                                          'bg-blue-100 text-blue-800': formData.statut === 'en_cours',
                                          'bg-emerald-100 text-emerald-800': formData.statut === 'termine'
                                      }">
                                    <span x-show="formData.statut === 'planifie'">📋 Planifié</span>
                                    <span x-show="formData.statut === 'en_cours'">🚧 En cours</span>
                                    <span x-show="formData.statut === 'termine'">✅ Terminé</span>
                                </span>
                            </div>
                        </div>
                        
                        <div class="space-y-3" x-show="formData.description">
                            <h4 class="font-semibold text-slate-900 flex items-center">
                                <svg class="h-4 w-4 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.25a2.25 2.25 0 00-2.25 2.25v13.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.5a2.25 2.25 0 00-2.25-2.25z" />
                                </svg>
                                Description
                            </h4>
                            <p class="text-slate-600 whitespace-pre-wrap" x-text="formData.description"></p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <h4 class="font-semibold text-slate-900 flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                    Client
                                </h4>
                                <p class="text-slate-600" x-text="getClientName()"></p>
                            </div>
                            <div class="space-y-3">
                                <h4 class="font-semibold text-slate-900 flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v3.5c0 .621-.504 1.125-1.125 1.125H12M15.75 9h3.75M15.75 12h3.75m-3.75 3h3.75" />
                                    </svg>
                                    Commercial responsable
                                </h4>
                                <p class="text-slate-600" x-text="getCommercialName()"></p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3" x-show="formData.budget">
                                <h4 class="font-semibold text-slate-900 flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Budget
                                </h4>
                                <p class="text-slate-600" x-text="formatBudget()"></p>
                            </div>
                            <div class="space-y-3" x-show="formData.date_debut || formData.date_fin_prevue">
                                <h4 class="font-semibold text-slate-900 flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                                    </svg>
                                    Planning
                                </h4>
                                <div class="text-slate-600 space-y-1">
                                    <div x-show="formData.date_debut" x-text="'Début : ' + formatDate(formData.date_debut)"></div>
                                    <div x-show="formData.date_fin_prevue" x-text="'Fin prévue : ' + formatDate(formData.date_fin_prevue)"></div>
                                    <div x-show="formData.date_fin_effective" class="font-semibold text-emerald-600" x-text="'Terminé le : ' + formatDate(formData.date_fin_effective)"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3" x-show="formData.notes">
                            <h4 class="font-semibold text-slate-900 flex items-center">
                                <svg class="h-4 w-4 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                </svg>
                                Notes internes
                            </h4>
                            <p class="text-slate-600 whitespace-pre-wrap" x-text="formData.notes"></p>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="flex flex-col sm:flex-row sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 p-6 border-t border-slate-200 bg-slate-50">
                    <button @click="closePreviewModal()" class="btn-outline">
                        Fermer
                    </button>
                    <button @click="submitFormFromPreview()" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Confirmer les modifications
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast notifications -->
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform translate-x-full"
         class="fixed top-4 right-4 z-50 max-w-sm w-full pointer-events-auto"
         style="display: none;">
        <div class="rounded-xl shadow-strong overflow-hidden"
             :class="{
                 'bg-blue-500': toast.type === 'info',
                 'bg-emerald-500': toast.type === 'success',
                 'bg-amber-500': toast.type === 'warning',
                 'bg-red-500': toast.type === 'error'
             }">
            <div class="p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg x-show="toast.type === 'success'" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg x-show="toast.type === 'error'" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <svg x-show="toast.type === 'warning'" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-3.75 4.5h7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg x-show="toast.type === 'info'" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium text-white" x-text="toast.message"></p>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        <button @click="hideToast()" class="inline-flex text-white hover:text-gray-200 focus:outline-none">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Alpine.js data
function chantierEditData() {
    return {
        // État du formulaire
        formData: {
            titre: @json(old('titre', $chantier->titre)),
            description: @json(old('description', $chantier->description)),
            client_id: @json(old('client_id', $chantier->client_id)),
            commercial_id: @json(old('commercial_id', $chantier->commercial_id)),
            statut: @json(old('statut', $chantier->statut)),
            budget: @json(old('budget', $chantier->budget)),
            date_debut: @json(old('date_debut', optional($chantier->date_debut)->format('Y-m-d'))),
            date_fin_prevue: @json(old('date_fin_prevue', optional($chantier->date_fin_prevue)->format('Y-m-d'))),
            date_fin_effective: @json(old('date_fin_effective', optional($chantier->date_fin_effective)->format('Y-m-d'))),
            notes: @json(old('notes', $chantier->notes))
        },
        
        // Données de référence
        clients: @json($clients->map(function($client) {
            return [
                'id' => $client->id,
                'name' => $client->name,
                'telephone' => $client->telephone,
                'adresse' => $client->adresse
            ];
        })),
        
        commerciaux: @json($commerciaux->map(function($commercial) {
            return [
                'id' => $commercial->id,
                'name' => $commercial->name
            ];
        })),
        
        // État de l'interface
        showPreviewModal: false,
        isSubmitting: false,
        
        // Informations client
        clientInfo: {
            visible: false,
            phone: '',
            address: ''
        },
        
        // Toast notifications
        toast: {
            show: false,
            message: '',
            type: 'info'
        },
        
        // Méthodes
        init() {
            // Déclencher l'affichage des infos client si un client est sélectionné
            if (this.formData.client_id) {
                this.updateClientInfo();
            }
            
            // Validation des dates au chargement
            this.validateDates();
            
            // Gestion de la soumission du formulaire
            this.$refs.form?.addEventListener('submit', (e) => {
                if (!this.validateForm()) {
                    e.preventDefault();
                    return;
                }
                this.isSubmitting = true;
            });
        },
        
        updateClientInfo() {
            const client = this.clients.find(c => c.id == this.formData.client_id);
            if (client) {
                this.clientInfo.phone = client.telephone || '';
                this.clientInfo.address = client.adresse || '';
                this.clientInfo.visible = !!(client.telephone || client.adresse);
            } else {
                this.clientInfo.visible = false;
            }
        },
        
        validateDates() {
            if (this.formData.date_debut && this.formData.date_fin_prevue) {
                if (this.formData.date_fin_prevue < this.formData.date_debut) {
                    this.formData.date_fin_prevue = this.formData.date_debut;
                    this.showToast('Date de fin ajustée automatiquement', 'info');
                }
            }
            
            if (this.formData.date_fin_effective && this.formData.date_debut) {
                if (this.formData.date_fin_effective < this.formData.date_debut) {
                    this.showToast('La date de fin effective ne peut pas être antérieure à la date de début', 'warning');
                }
            }
        },
        
        suggestStatusOnCompletion() {
            if (this.formData.date_fin_effective && this.formData.statut !== 'termine') {
                if (confirm('Une date de fin effective est renseignée. Voulez-vous marquer le chantier comme terminé ?')) {
                    this.formData.statut = 'termine';
                }
            }
        },
        
        validateForm() {
            if (!this.formData.titre || this.formData.titre.trim().length < 3) {
                this.showToast('Le titre doit contenir au moins 3 caractères', 'error');
                this.$refs.titre?.focus();
                return false;
            }
            
            if (!this.formData.client_id) {
                this.showToast('Veuillez sélectionner un client', 'error');
                this.$refs.client_id?.focus();
                return false;
            }
            
            if (!this.formData.commercial_id) {
                this.showToast('Veuillez sélectionner un commercial', 'error');
                this.$refs.commercial_id?.focus();
                return false;
            }
            
            return true;
        },
        
        openPreviewModal() {
            this.showPreviewModal = true;
            document.body.style.overflow = 'hidden';
        },
        
        closePreviewModal() {
            this.showPreviewModal = false;
            document.body.style.overflow = 'auto';
        },
        
        submitFormFromPreview() {
            this.closePreviewModal();
            this.$refs.form.submit();
        },
        
        suggestSteps() {
            const titre = this.formData.titre.toLowerCase();
            const description = this.formData.description.toLowerCase();
            
            let suggestions = [];
            
            if (titre.includes('cuisine') || description.includes('cuisine')) {
                suggestions = [
                    'Démolition existant',
                    'Gros œuvre et cloisons',
                    'Électricité',
                    'Plomberie',
                    'Carrelage et faïence',
                    'Peinture',
                    'Installation mobilier',
                    'Finitions et nettoyage'
                ];
            } else if (titre.includes('salle de bain') || description.includes('salle de bain')) {
                suggestions = [
                    'Démolition ancienne salle de bain',
                    'Modification plomberie',
                    'Électricité et éclairage',
                    'Étanchéité',
                    'Carrelage mural et sol',
                    'Installation sanitaires',
                    'Peinture et finitions'
                ];
            } else if (titre.includes('extension') || description.includes('extension')) {
                suggestions = [
                    'Préparation du terrain',
                    'Fondations',
                    'Gros œuvre',
                    'Charpente et couverture',
                    'Clos et couvert',
                    'Second œuvre',
                    'Finitions'
                ];
            } else {
                suggestions = [
                    'Préparation et études',
                    'Démarrage des travaux',
                    'Phase principale',
                    'Contrôles et ajustements',
                    'Finitions',
                    'Livraison'
                ];
            }
            
            if (confirm('Voulez-vous ajouter des étapes types pour ce chantier ?\n\nÉtapes suggérées :\n' + suggestions.join('\n'))) {
                window.location.href = `{{ route('chantiers.show', $chantier) }}#etapes`;
            }
        },
        
        // Méthodes d'affichage pour la modal d'aperçu
        getClientName() {
            const client = this.clients.find(c => c.id == this.formData.client_id);
            return client ? client.name : 'Non sélectionné';
        },
        
        getCommercialName() {
            const commercial = this.commerciaux.find(c => c.id == this.formData.commercial_id);
            return commercial ? commercial.name : 'Non sélectionné';
        },
        
        formatBudget() {
            if (!this.formData.budget) return 'Non défini';
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR'
            }).format(this.formData.budget);
        },
        
        formatDate(dateStr) {
            if (!dateStr) return '';
            return new Date(dateStr).toLocaleDateString('fr-FR');
        },
        
        // Système de toast
        showToast(message, type = 'info') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.show = true;
            
            setTimeout(() => {
                this.hideToast();
            }, 4000);
        },
        
        hideToast() {
            this.toast.show = false;
        }
    }
}

// Gestion des raccourcis clavier
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        // Fermer les modals avec Échap
        Alpine.store('chantierEdit')?.closePreviewModal();
    }
});
</script>
@endpush

@push('styles')
<style>
.required::after {
    content: ' *';
    color: #ef4444;
    font-weight: bold;
}

/* Améliorations print */
@media print {
    .no-print {
        display: none !important;
    }
    
    .bg-gradient-to-br {
        background: #1e40af !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
    }
    
    .shadow-xl, .shadow-strong {
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }
    
    .card {
        break-inside: avoid;
        page-break-inside: avoid;
    }
}

/* Animations personnalisées */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out;
}

/* Amélioration de l'hover sur les cards */
.hover-lift {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Styles pour la progression circulaire */
.text-indigo-600 {
    transition: stroke-dasharray 0.6s ease-in-out;
}

/* Amélioration des focus states */
.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    transform: translateY(-1px);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1), 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Responsive amélioré pour les boutons */
@media (max-width: 640px) {
    .btn {
        @apply w-full justify-center;
    }
    
    .mobile-stack {
        @apply flex-col space-y-3 space-x-0;
    }
}
</style>
@endpush