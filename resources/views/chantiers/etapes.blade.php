@extends('layouts.app')

@section('title', 'Gestion des étapes - ' . $chantier->titre)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- Header avec dégradé -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune étape définie</h3>
                                <p class="mt-2 text-gray-500">
                                    @can('update', $chantier)
                                        Utilisez le formulaire ci-dessus pour ajouter des étapes à ce chantier.
                                    @else
                                        Les étapes seront ajoutées prochainement par l'équipe.
                                    @endcan
                                </p>
                                @can('update', $chantier)
                                    <div class="mt-6">
                                        <button onclick="document.querySelector('input[name=nom]').focus()" 
                                                class="btn-primary">
                                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                            Ajouter une étape
                                        </button>
                                    </div>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Informations du chantier -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="h-6 w-6 text-indigo-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                            Informations du chantier
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Client</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $chantier->client->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Commercial</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $chantier->commercial->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Statut</dt>
                            <dd class="mt-1">
                                <span class="{{ $chantier->getStatutBadgeClass() }}">
                                    {{ $chantier->getStatutTexte() }}
                                </span>
                            </dd>
                        </div>
                        @if($chantier->budget)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Budget</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-medium">{{ number_format($chantier->budget, 0, ',', ' ') }} €</dd>
                            </div>
                        @endif
                        @if($chantier->date_debut && $chantier->date_fin_prevue)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Période</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $chantier->date_debut->format('d/m/Y') }} → 
                                    <span class="{{ $chantier->isEnRetard() ? 'text-red-600 font-medium' : '' }}">
                                        {{ $chantier->date_fin_prevue->format('d/m/Y') }}
                                    </span>
                                </dd>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                            Actions rapides
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('chantiers.show', $chantier) }}" 
                               class="w-full flex items-center justify-center px-4 py-3 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Voir le chantier
                            </a>
                            
                            @can('update', $chantier)
                                <a href="{{ route('chantiers.edit', $chantier) }}" 
                                   class="w-full flex items-center justify-center px-4 py-3 border border-blue-300 rounded-xl text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    Modifier le chantier
                                </a>
                            @endcan
                            
                            <button onclick="window.print()" 
                                    class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231a1.125 1.125 0 01-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-10.326 0c-1.069.16-1.837 1.094-1.837 2.175v6.294a2.25 2.25 0 002.25 2.25h1.091m-1.091 0L5.65 18m11.318 0l.227-2.262" />
                                </svg>
                                Imprimer la liste
                            </button>
                            
                            <a href="{{ route('chantiers.index') }}" 
                               class="w-full flex items-center justify-center px-4 py-3 border border-purple-300 rounded-xl text-sm font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                                Tous les chantiers
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Progression détaillée -->
                @if($chantier->etapes->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="h-6 w-6 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            Progression détaillée
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($chantier->etapes->sortBy('ordre')->take(5) as $etape)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center flex-1">
                                        <div class="flex-shrink-0 mr-3">
                                            @if($etape->terminee)
                                                <div class="h-6 w-6 rounded-full bg-green-500 flex items-center justify-center">
                                                    <svg class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="h-6 w-6 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                                    <div class="h-2 w-2 rounded-full bg-gray-300"></div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $etape->nom }}</p>
                                            <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-1.5 rounded-full transition-all duration-300" 
                                                     style="width: {{ $etape->pourcentage }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-900">{{ number_format($etape->pourcentage, 0) }}%</span>
                                </div>
                            @endforeach
                            
                            @if($chantier->etapes->count() > 5)
                                <div class="text-center pt-2">
                                    <span class="text-sm text-gray-500">et {{ $chantier->etapes->count() - 5 }} autres étapes...</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Edition Étape -->
<div id="editEtapeModal" 
     class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" 
     x-data="{ open: false }"
     x-show="open"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-96 overflow-y-auto"
             @click.away="closeEditModal()">
            <form method="POST" id="editEtapeForm" x-data="{ submitting: false }">
                @csrf
                @method('PUT')
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            Modifier l'étape
                        </h3>
                        <button type="button" 
                                onclick="closeEditModal()" 
                                class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="form-label">Nom de l'étape <span class="text-red-500">*</span></label>
                            <input type="text" class="form-input" name="nom" id="edit_nom" required>
                        </div>
                        <div>
                            <label class="form-label">Ordre <span class="text-red-500">*</span></label>
                            <input type="number" class="form-input" name="ordre" id="edit_ordre" min="1" required>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="form-label">Description</label>
                        <textarea class="form-input" name="description" id="edit_description" rows="3"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="form-label">Date début</label>
                            <input type="date" class="form-input" name="date_debut" id="edit_date_debut">
                        </div>
                        <div>
                            <label class="form-label">Date fin prévue</label>
                            <input type="date" class="form-input" name="date_fin_prevue" id="edit_date_fin_prevue">
                        </div>
                        <div>
                            <label class="form-label">Date fin effective</label>
                            <input type="date" class="form-input" name="date_fin_effective" id="edit_date_fin_effective">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="form-label">Pourcentage d'avancement</label>
                            <input type="range" 
                                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" 
                                   name="pourcentage" 
                                   id="edit_pourcentage" 
                                   min="0" 
                                   max="100" 
                                   step="5" 
                                   oninput="updateProgressValue(this.value)">
                            <div class="text-center mt-2">
                                <span id="progressValue" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">0%</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="flex items-center h-5">
                                <input class="form-checkbox" type="checkbox" name="terminee" id="edit_terminee" value="1">
                            </div>
                            <div class="ml-3">
                                <label for="edit_terminee" class="font-medium text-gray-700">
                                    Marquer comme terminée
                                </label>
                                <p class="text-gray-500 text-sm">Cela mettra automatiquement la progression à 100%</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="form-label">Notes</label>
                        <textarea class="form-input" name="notes" id="edit_notes" rows="2" placeholder="Notes additionnelles..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                    <button type="button" 
                            onclick="closeEditModal()" 
                            class="btn-outline">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="btn-primary"
                            x-bind:disabled="submitting"
                            @click="submitting = true">
                        <span x-show="!submitting">Enregistrer</span>
                        <span x-show="submitting" style="display: none;">Enregistrement...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Progression Rapide -->
<div id="progressModal" 
     class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" 
     x-data="{ open: false }"
     x-show="open"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full"
             @click.away="closeProgressModal()">
            <form method="POST" id="progressForm" x-data="{ submitting: false }">
                @csrf
                @method('PUT')
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="h-6 w-6 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m0 0L9 8.25m0 0l1.5-2.25M9 8.25l1.5 2.25m0-3l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Modifier l'avancement
                        </h3>
                        <button type="button" 
                                onclick="closeProgressModal()" 
                                class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="mb-6">
                        <label class="form-label">Pourcentage d'avancement</label>
                        <input type="range" 
                               class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer" 
                               name="pourcentage" 
                               id="quick_pourcentage" 
                               min="0" 
                               max="100" 
                               step="5"
                               oninput="updateQuickProgressValue(this.value)">
                        <div class="text-center mt-3">
                            <span id="quickProgressValue" class="inline-flex items-center px-4 py-2 rounded-full text-lg font-bold bg-blue-100 text-blue-800">0%</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-5 gap-2 mb-6">
                        <button type="button" class="btn-outline btn-sm set-progress" data-value="0">0%</button>
                        <button type="button" class="btn-outline btn-sm set-progress" data-value="25">25%</button>
                        <button type="button" class="btn-outline btn-sm set-progress" data-value="50">50%</button>
                        <button type="button" class="btn-outline btn-sm set-progress" data-value="75">75%</button>
                        <button type="button" class="btn-outline btn-sm set-progress bg-green-50 border-green-200 text-green-700 hover:bg-green-100" data-value="100">100%</button>
                    </div>
                </div>
                
                <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                    <button type="button" 
                            onclick="closeProgressModal()" 
                            class="btn-outline">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="btn-primary"
                            x-bind:disabled="submitting"
                            @click="submitting = true">
                        <span x-show="!submitting">Enregistrer</span>
                        <span x-show="submitting" style="display: none;">Enregistrement...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = parseInt(counter.dataset.target) || 0;
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    counter.textContent = target;
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.floor(current);
                }
            }, 20);
        });
    }

    // Animation des barres de progression
    function animateProgressBars() {
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach(bar => {
            setTimeout(() => {
                const width = bar.dataset.width;
                if (width) {
                    bar.style.width = width;
                }
            }, 500);
        });
    }

    // Déclencher les animations au chargement
    setTimeout(() => {
        animateCounters();
        animateProgressBars();
    }, 300);

    // Edition d'une étape
    window.editEtape = function(etapeData) {
        const form = document.getElementById('editEtapeForm');
        form.action = `/chantiers/{{ $chantier->id }}/etapes/${etapeData.id}`;
        
        document.getElementById('edit_nom').value = etapeData.nom || '';
        document.getElementById('edit_ordre').value = etapeData.ordre || '';
        document.getElementById('edit_description').value = etapeData.description || '';
        document.getElementById('edit_date_debut').value = etapeData.date_debut ? etapeData.date_debut.split(' ')[0] : '';
        document.getElementById('edit_date_fin_prevue').value = etapeData.date_fin_prevue ? etapeData.date_fin_prevue.split(' ')[0] : '';
        document.getElementById('edit_date_fin_effective').value = etapeData.date_fin_effective ? etapeData.date_fin_effective.split(' ')[0] : '';
        document.getElementById('edit_pourcentage').value = etapeData.pourcentage || 0;
        document.getElementById('edit_terminee').checked = etapeData.terminee || false;
        document.getElementById('edit_notes').value = etapeData.notes || '';
        
        updateProgressValue(etapeData.pourcentage || 0);
        showEditModal();
    };

    // Modification rapide de la progression
    window.updateProgress = function(etapeId, currentProgress) {
        const form = document.getElementById('progressForm');
        form.action = `/chantiers/{{ $chantier->id }}/etapes/${etapeId}/progress`;
        
        document.getElementById('quick_pourcentage').value = currentProgress;
        updateQuickProgressValue(currentProgress);
        
        showProgressModal();
    };

    // Gestionnaires d'événements pour les boutons
    document.querySelectorAll('.edit-etape').forEach(btn => {
        btn.addEventListener('click', function() {
            const etape = JSON.parse(this.dataset.etape);
            editEtape(etape);
        });
    });

    document.querySelectorAll('.update-progress').forEach(btn => {
        btn.addEventListener('click', function() {
            const etapeId = this.dataset.etapeId;
            const current = this.dataset.current;
            updateProgress(etapeId, current);
        });
    });

    // Boutons de progression rapide
    document.querySelectorAll('.set-progress').forEach(btn => {
        btn.addEventListener('click', function() {
            const value = this.dataset.value;
            document.getElementById('quick_pourcentage').value = value;
            updateQuickProgressValue(value);
        });
    });

    // Mise à jour de l'affichage de la progression
    document.getElementById('quick_pourcentage')?.addEventListener('input', function() {
        updateQuickProgressValue(this.value);
    });

    // Si terminée est cochée, mettre la progression à 100%
    document.getElementById('edit_terminee')?.addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('edit_pourcentage').value = 100;
            updateProgressValue(100);
        }
    });

    // Validation des dates
    const dateDebut = document.getElementById('edit_date_debut');
    const dateFin = document.getElementById('edit_date_fin_prevue');
    
    dateDebut?.addEventListener('change', function() {
        if (dateFin && dateFin.value && dateFin.value < this.value) {
            dateFin.value = this.value;
            showToast('Date de fin ajustée automatiquement', 'info');
        }
    });
});

// Fonctions pour les modales
function showEditModal() {
    document.getElementById('editEtapeModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editEtapeModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function showProgressModal() {
    document.getElementById('progressModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeProgressModal() {
    document.getElementById('progressModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function updateProgressValue(value) {
    document.getElementById('progressValue').textContent = value + '%';
}

function updateQuickProgressValue(value) {
    document.getElementById('quickProgressValue').textContent = value + '%';
}

// Mode réorganisation (fonction future)
function toggleReorderMode() {
    showToast('Fonctionnalité de réorganisation en cours de développement', 'info');
}

// Fonction de toast pour les notifications
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 max-w-sm p-4 rounded-xl shadow-lg transform transition-all duration-300 translate-x-full`;
    
    const bgColors = {
        'info': 'bg-blue-500 text-white',
        'success': 'bg-green-500 text-white',
        'warning': 'bg-yellow-500 text-white',
        'error': 'bg-red-500 text-white'
    };
    
    toast.classList.add(...(bgColors[type] || bgColors.info).split(' '));
    toast.innerHTML = `
        <div class="flex items-center">
            <span class="flex-1">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animation d'entrée
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Auto-suppression
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 4000);
}

// Fermer les modals avec Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
        closeProgressModal();
    }
});

// Actualisation automatique de l'avancement global
function refreshGlobalProgress() {
    fetch(`/api/chantiers/{{ $chantier->id }}/avancement`)
        .then(response => response.json())
        .then(data => {
            // Mettre à jour l'affichage de l'avancement global
            const globalProgressCounters = document.querySelectorAll('[data-target]');
            globalProgressCounters.forEach(counter => {
                if (counter.dataset.target !== undefined) {
                    counter.textContent = Math.round(data.avancement);
                }
            });
            
            // Mettre à jour les barres de progression
            const progressBars = document.querySelectorAll('.progress-bar[data-width]');
            progressBars.forEach(bar => {
                bar.style.width = Math.round(data.avancement) + '%';
            });
        })
        .catch(error => console.error('Erreur lors de la mise à jour:', error));
}

// Actualisation périodique (optionnelle)
// setInterval(refreshGlobalProgress, 30000);

        counters.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-6">
                            <h1 class="text-3xl font-bold text-white sm:text-4xl">
                                Gestion des étapes
                            </h1>
                            <p class="mt-2 text-blue-100 text-lg">
                                {{ $chantier->titre }}
                            </p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 text-white">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                    </svg>
                                    {{ number_format($chantier->avancement_global, 0) }}% complété
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex space-x-3 md:mt-0 md:ml-4">
                    <a href="{{ route('chantiers.show', $chantier) }}" 
                       class="inline-flex items-center px-6 py-3 border border-white/20 rounded-full shadow-sm text-sm font-medium text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Retour au chantier
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistiques des étapes -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <!-- Total étapes -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium uppercase tracking-wide">Total Étapes</p>
                            <p class="text-4xl font-bold text-white mt-2 counter" data-target="{{ $chantier->etapes->count() }}">0</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terminées -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-emerald-100 text-sm font-medium uppercase tracking-wide">Terminées</p>
                            <p class="text-4xl font-bold text-white mt-2 counter" data-target="{{ $chantier->etapes->where('terminee', true)->count() }}">0</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- En cours -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-amber-100 text-sm font-medium uppercase tracking-wide">En Cours</p>
                            <p class="text-4xl font-bold text-white mt-2 counter" data-target="{{ $chantier->etapes->where('terminee', false)->count() }}">0</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-white animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avancement global -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium uppercase tracking-wide">Avancement</p>
                            <p class="text-4xl font-bold text-white mt-2 counter" data-target="{{ $chantier->avancement_global }}">0</p>
                            <div class="w-full bg-white/20 rounded-full h-2 mt-3">
                                <div class="bg-white h-2 rounded-full transition-all duration-1000 ease-out progress-bar" 
                                     style="width: 0%" data-width="{{ $chantier->avancement_global }}%"></div>
                            </div>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grille principale -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulaire d'ajout et liste des étapes -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Formulaire d'ajout -->
                @can('update', $chantier)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="h-6 w-6 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Ajouter une nouvelle étape
                        </h3>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('etapes.store', $chantier) }}" x-data="{ submitting: false }">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div class="md:col-span-2">
                                    <label class="form-label">Nom de l'étape <span class="text-red-500">*</span></label>
                                    <input type="text" 
                                           class="form-input @error('nom') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                           name="nom" 
                                           value="{{ old('nom') }}"
                                           placeholder="Ex: Installation électrique"
                                           required>
                                    @error('nom')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label class="form-label">Ordre <span class="text-red-500">*</span></label>
                                    <input type="number" 
                                           class="form-input @error('ordre') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                           name="ordre" 
                                           value="{{ old('ordre', $chantier->etapes->count() + 1) }}" 
                                           min="1" 
                                           required>
                                    @error('ordre')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="form-label">Pourcentage initial</label>
                                    <input type="number" 
                                           class="form-input @error('pourcentage') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                           name="pourcentage" 
                                           value="{{ old('pourcentage', 0) }}" 
                                           min="0" 
                                           max="100">
                                    @error('pourcentage')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label class="form-label">Date début prévue</label>
                                    <input type="date" 
                                           class="form-input @error('date_debut') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                           name="date_debut" 
                                           value="{{ old('date_debut') }}">
                                    @error('date_debut')
                                        <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <label class="form-label">Description</label>
                                <textarea class="form-input @error('description') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                          name="description" 
                                          rows="3"
                                          placeholder="Description détaillée de l'étape...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="btn-primary" 
                                        x-bind:disabled="submitting"
                                        @click="submitting = true">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    <span x-show="!submitting">Ajouter l'étape</span>
                                    <span x-show="submitting" style="display: none;">Ajout en cours...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endcan

                <!-- Liste des étapes -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                                <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                                Étapes ({{ $chantier->etapes->count() }})
                            </h3>
                            @if($chantier->etapes->count() > 1)
                                <button class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors" 
                                        onclick="toggleReorderMode()">
                                    Réorganiser
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="overflow-hidden">
                        @if($chantier->etapes->count() > 0)
                            <div class="divide-y divide-gray-200" id="etapesList">
                                @foreach($chantier->etapes->sortBy('ordre') as $etape)
                                    <div class="p-6 hover:bg-blue-50 transition-colors duration-200 etape-item" 
                                         data-etape-id="{{ $etape->id }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-4">
                                                    <!-- Badge ordre -->
                                                    <div class="flex-shrink-0">
                                                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full 
                                                                     {{ $etape->terminee ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                            @if($etape->terminee)
                                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            @else
                                                                {{ $etape->ordre }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="text-lg font-semibold text-gray-900 {{ $etape->terminee ? 'line-through' : '' }}">
                                                            {{ $etape->nom }}
                                                        </h4>
                                                        @if($etape->description)
                                                            <p class="text-gray-600 text-sm mt-1">{{ $etape->description }}</p>
                                                        @endif
                                                        
                                                        <!-- Dates -->
                                                        @if($etape->date_debut || $etape->date_fin_prevue)
                                                            <div class="flex items-center text-sm text-gray-500 mt-2 space-x-4">
                                                                @if($etape->date_debut)
                                                                    <span class="flex items-center">
                                                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                                                                        </svg>
                                                                        Début: {{ $etape->date_debut->format('d/m/Y') }}
                                                                    </span>
                                                                @endif
                                                                @if($etape->date_fin_prevue)
                                                                    <span class="flex items-center {{ $etape->isEnRetard() ? 'text-red-600 font-medium' : '' }}">
                                                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                        </svg>
                                                                        Fin prévue: {{ $etape->date_fin_prevue->format('d/m/Y') }}
                                                                        @if($etape->isEnRetard())
                                                                            <span class="ml-1 text-red-600">⚠️</span>
                                                                        @endif
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Barre de progression -->
                                                <div class="mt-4">
                                                    <div class="flex justify-between items-center mb-2">
                                                        <span class="text-sm font-medium text-gray-700">Progression</span>
                                                        <span class="text-sm font-medium text-gray-900">{{ number_format($etape->pourcentage, 0) }}%</span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                                        <div class="h-3 rounded-full transition-all duration-300 
                                                                    {{ $etape->terminee ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-blue-500 to-blue-600' }}" 
                                                             style="width: {{ $etape->pourcentage }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Actions -->
                                            @can('update', $chantier)
                                                <div class="flex-shrink-0 ml-6">
                                                    <div class="flex items-center space-x-2">
                                                        <!-- Modification rapide de progression -->
                                                        <button class="btn-outline btn-sm update-progress" 
                                                                data-etape-id="{{ $etape->id }}"
                                                                data-current="{{ $etape->pourcentage }}"
                                                                title="Modifier progression">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m0 0L9 8.25m0 0l1.5-2.25M9 8.25l1.5 2.25m0-3l-3-3m0 0l-3 3m3-3v12" />
                                                            </svg>
                                                        </button>
                                                        
                                                        <!-- Édition -->
                                                        <button class="btn-outline btn-sm edit-etape" 
                                                                data-etape="{{ json_encode($etape) }}"
                                                                title="Modifier">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                            </svg>
                                                        </button>
                                                        
                                                        <!-- Suppression -->
                                                        <form method="POST" 
                                                              action="{{ route('etapes.destroy', [$chantier, $etape]) }}" 
                                                              class="inline"
                                                              onsubmit="return confirm('Supprimer cette étape ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn-outline btn-sm text-red-600 border-red-200 hover:bg-red-50"
                                                                    title="Supprimer">
                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endcan
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v