@extends('layouts.app')

@section('title', 'Modifier le chantier')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="breadcrumb mb-6">
        <a href="{{ route('chantiers.index') }}" class="breadcrumb-item">Chantiers</a>
        <span class="breadcrumb-separator">/</span>
        <a href="{{ route('chantiers.show', $chantier) }}" class="breadcrumb-item">{{ Str::limit($chantier->titre, 30) }}</a>
        <span class="breadcrumb-separator">/</span>
        <span class="text-gray-900">Modifier</span>
    </nav>

    <!-- En-tête -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl mb-8">
        <div class="px-6 py-8 sm:px-8">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </div>
                </div>
                <div class="ml-6">
                    <h1 class="text-3xl font-bold text-white">
                        Modifier le chantier
                    </h1>
                    <p class="mt-2 text-blue-100">
                        {{ $chantier->titre }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire principal -->
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <form method="POST" action="{{ route('chantiers.update', $chantier) }}" id="editChantierForm">
            @csrf
            @method('PUT')

            <!-- Contenu du formulaire -->
            <div class="px-6 py-8 sm:px-8">
                <!-- Section 1: Informations générales -->
                <div class="mb-10">
                    <div class="border-l-4 border-blue-500 pl-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Informations générales</h2>
                        <p class="text-gray-600 text-sm mt-1">Titre, description et détails du chantier</p>
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
                                   autofocus>
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
                                      placeholder="Décrivez le projet en détail...">{{ old('description', $chantier->description) }}</textarea>
                            @error('description')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Intervenants -->
                <div class="mb-10">
                    <div class="border-l-4 border-green-500 pl-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Intervenants</h2>
                        <p class="text-gray-600 text-sm mt-1">Client et commercial responsable</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Client -->
                        <div>
                            <label for="client_id" class="form-label required">Client</label>
                            <select class="form-select @error('client_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                    id="client_id" 
                                    name="client_id" 
                                    required>
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
                            <div id="client-info" class="mt-2 text-sm text-gray-600 hidden">
                                <div class="bg-blue-50 rounded-lg p-3">
                                    <div id="client-details"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Commercial -->
                        <div>
                            <label for="commercial_id" class="form-label required">Commercial responsable</label>
                            <select class="form-select @error('commercial_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                    id="commercial_id" 
                                    name="commercial_id" 
                                    required>
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
                <div class="mb-10">
                    <div class="border-l-4 border-amber-500 pl-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Planning et Budget</h2>
                        <p class="text-gray-600 text-sm mt-1">Dates de réalisation et informations financières</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Statut -->
                        <div>
                            <label for="statut" class="form-label required">Statut du chantier</label>
                            <select class="form-select @error('statut') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                    id="statut" 
                                    name="statut" 
                                    required>
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
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">€</span>
                                </div>
                                <input type="number" 
                                       class="form-input pl-8 @error('budget') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                       id="budget" 
                                       name="budget" 
                                       value="{{ old('budget', $chantier->budget) }}" 
                                       step="0.01" 
                                       min="0"
                                       placeholder="0.00">
                            </div>
                            @error('budget')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Avancement actuel (lecture seule) -->
                        <div>
                            <label class="form-label">Avancement actuel</label>
                            <div class="mt-2">
                                <div class="flex items-center justify-between text-sm mb-2">
                                    <span class="text-gray-600">Progression</span>
                                    <span class="font-semibold text-gray-900">{{ number_format($chantier->avancement_global, 1) }}%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar {{ $chantier->getProgressBarColor() }}" 
                                         style="width: {{ $chantier->avancement_global }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Calculé automatiquement selon les étapes
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Dates -->
                <div class="mb-10">
                    <div class="border-l-4 border-purple-500 pl-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Dates importantes</h2>
                        <p class="text-gray-600 text-sm mt-1">Planning prévisionnel et réel</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Date début -->
                        <div>
                            <label for="date_debut" class="form-label">Date de début</label>
                            <input type="date" 
                                   class="form-input @error('date_debut') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   id="date_debut" 
                                   name="date_debut" 
                                   value="{{ old('date_debut', optional($chantier->date_debut)->format('Y-m-d')) }}">
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
                                   value="{{ old('date_fin_prevue', optional($chantier->date_fin_prevue)->format('Y-m-d')) }}">
                            @error('date_fin_prevue')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                            @if($chantier->isEnRetard())
                                <div class="mt-1 text-sm text-red-600 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    Ce chantier est en retard
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
                                   value="{{ old('date_fin_effective', optional($chantier->date_fin_effective)->format('Y-m-d')) }}">
                            @error('date_fin_effective')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">
                                À renseigner une fois le chantier terminé
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Notes -->
                <div class="mb-8">
                    <div class="border-l-4 border-gray-500 pl-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Notes internes</h2>
                        <p class="text-gray-600 text-sm mt-1">Informations complémentaires visibles par l'équipe</p>
                    </div>

                    <div>
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-textarea @error('notes') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="4" 
                                  placeholder="Notes techniques, contraintes, points d'attention...">{{ old('notes', $chantier->notes) }}</textarea>
                        @error('notes')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                        <p class="text-xs text-gray-500 mt-2 flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Ces notes sont visibles par l'équipe interne uniquement
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer avec boutons -->
            <div class="bg-gray-50 px-6 py-6 sm:px-8 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
                    <!-- Boutons de navigation -->
                    <div class="flex space-x-3">
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
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <button type="button" onclick="previewChanges()" class="btn-outline">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Aperçu des modifications
                        </button>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
                            </svg>
                            Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Informations complémentaires -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Historique des modifications -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="h-5 w-5 text-gray-600 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Informations du chantier
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Créé le :</span>
                    <span class="font-medium">{{ $chantier->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Dernière modification :</span>
                    <span class="font-medium">{{ $chantier->updated_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Étapes définies :</span>
                    <span class="font-medium">{{ $chantier->etapes->count() }} étape(s)</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Documents :</span>
                    <span class="font-medium">{{ $chantier->documents->count() }} fichier(s)</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Commentaires :</span>
                    <span class="font-medium">{{ $chantier->commentaires->count() }} message(s)</span>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="h-5 w-5 text-gray-600 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
                Actions rapides
            </h3>
            <div class="space-y-3">
                <a href="{{ route('chantiers.show', $chantier) }}" 
                   class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Voir le chantier
                </a>
                @can('update', $chantier)
                    @if($chantier->etapes->count() === 0)
                        <button onclick="suggestSteps()" 
                                class="w-full flex items-center justify-center px-4 py-2 border border-blue-300 rounded-lg text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Ajouter des étapes
                        </button>
                    @endif
                @endcan
                <button onclick="window.print()" 
                        class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231a1.125 1.125 0 01-1.12-1.227L6.34 18m11.32 0H6.34m11.32 0H17.66M6.34 18H6.14" />
                    </svg>
                    Imprimer la fiche
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'aperçu des modifications -->
<div id="previewModal" class="modal-overlay hidden" onclick="if(event.target === this) closePreviewModal()">
    <div class="modal-content max-w-2xl">
        <div class="modal-header">
            <h3 class="text-lg font-medium text-gray-900">Aperçu des modifications</h3>
            <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div id="previewContent" class="modal-body">
            <!-- Contenu dynamique -->
        </div>
        
        <div class="modal-footer">
            <button onclick="closePreviewModal()" class="btn-outline">Fermer</button>
            <button onclick="submitFormFromPreview()" class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Confirmer les modifications
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation des dates
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin_prevue');
    const dateFinEffective = document.getElementById('date_fin_effective');
    
    function validateDates() {
        if (dateDebut.value && dateFin.value) {
            if (dateFin.value < dateDebut.value) {
                dateFin.value = dateDebut.value;
                showToast('Date de fin ajustée automatiquement', 'info');
            }
        }
        
        if (dateFinEffective.value && dateDebut.value) {
            if (dateFinEffective.value < dateDebut.value) {
                showToast('La date de fin effective ne peut pas être antérieure à la date de début', 'warning');
            }
        }
    }
    
    dateDebut?.addEventListener('change', validateDates);
    dateFin?.addEventListener('change', validateDates);
    dateFinEffective?.addEventListener('change', validateDates);
    
    // Auto-completion du statut selon les dates
    const statutSelect = document.getElementById('statut');
    function suggestStatus() {
        if (dateFinEffective.value && statutSelect.value !== 'termine') {
            if (confirm('Une date de fin effective est renseignée. Voulez-vous marquer le chantier comme terminé ?')) {
                statutSelect.value = 'termine';
            }
        }
    }
    
    dateFinEffective?.addEventListener('change', suggestStatus);
    
    // Affichage des informations client
    const clientSelect = document.getElementById('client_id');
    const clientInfo = document.getElementById('client-info');
    const clientDetails = document.getElementById('client-details');
    
    clientSelect?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const phone = selectedOption.dataset.phone;
            const address = selectedOption.dataset.address;
            
            let details = '<div class="space-y-1">';
            if (phone) {
                details += `<div class="flex items-center text-sm"><svg class="h-4 w-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>${phone}</div>`;
            }
            if (address) {
                details += `<div class="flex items-start text-sm"><svg class="h-4 w-4 mr-2 mt-0.5 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg><span>${address}</span></div>`;
            }
            details += '</div>';
            
            clientDetails.innerHTML = details;
            clientInfo.classList.remove('hidden');
        } else {
            clientInfo.classList.add('hidden');
        }
    });
    
    // Déclencher l'affichage initial si un client est sélectionné
    if (clientSelect?.value) {
        clientSelect.dispatchEvent(new Event('change'));
    }
    
    // Validation du formulaire
    const form = document.getElementById('editChantierForm');
    form?.addEventListener('submit', function(e) {
        const titre = document.getElementById('titre').value.trim();
        const client = document.getElementById('client_id').value;
        const commercial = document.getElementById('commercial_id').value;
        
        if (!titre || titre.length < 3) {
            e.preventDefault();
            showToast('Le titre doit contenir au moins 3 caractères', 'error');
            document.getElementById('titre').focus();
            return;
        }
        
        if (!client) {
            e.preventDefault();
            showToast('Veuillez sélectionner un client', 'error');
            document.getElementById('client_id').focus();
            return;
        }
        
        if (!commercial) {
            e.preventDefault();
            showToast('Veuillez sélectionner un commercial', 'error');
            document.getElementById('commercial_id').focus();
            return;
        }
        
        // Animation de soumission
        const submitBtn = document.querySelector('button[type="submit"]');
        const originalContent = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Enregistrement en cours...
        `;
    });
});

// Fonctions pour l'aperçu
function previewChanges() {
    const formData = new FormData(document.getElementById('editChantierForm'));
    
    // Obtenir les textes des sélects
    const clientSelect = document.getElementById('client_id');
    const commercialSelect = document.getElementById('commercial_id');
    const statutSelect = document.getElementById('statut');
    
    const clientText = clientSelect.options[clientSelect.selectedIndex]?.text.split(' - ')[0] || 'Non sélectionné';
    const commercialText = commercialSelect.options[commercialSelect.selectedIndex]?.text || 'Non sélectionné';
    const statutText = statutSelect.options[statutSelect.selectedIndex]?.text || 'Non sélectionné';
    
    const previewHTML = `
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-900 mb-1">Titre du chantier</h4>
                    <p class="text-gray-600">${formData.get('titre') || 'Non défini'}</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-1">Statut</h4>
                    <p class="text-gray-600">${statutText}</p>
                </div>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900 mb-1">Description</h4>
                <p class="text-gray-600">${formData.get('description') || 'Aucune description'}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-900 mb-1">Client</h4>
                    <p class="text-gray-600">${clientText}</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-1">Commercial responsable</h4>
                    <p class="text-gray-600">${commercialText}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-900 mb-1">Budget</h4>
                    <p class="text-gray-600">${formData.get('budget') ? Number(formData.get('budget')).toLocaleString('fr-FR') + ' €' : 'Non défini'}</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-1">Planning</h4>
                    <p class="text-gray-600">
                        ${formData.get('date_debut') ? 'Du ' + new Date(formData.get('date_debut')).toLocaleDateString('fr-FR') : 'Début non défini'}
                        ${formData.get('date_fin_prevue') ? ' au ' + new Date(formData.get('date_fin_prevue')).toLocaleDateString('fr-FR') : ''}
                        ${formData.get('date_fin_effective') ? '<br><strong>Terminé le ' + new Date(formData.get('date_fin_effective')).toLocaleDateString('fr-FR') + '</strong>' : ''}
                    </p>
                </div>
            </div>
            
            ${formData.get('notes') ? `
                <div>
                    <h4 class="font-medium text-gray-900 mb-1">Notes internes</h4>
                    <p class="text-gray-600 whitespace-pre-wrap">${formData.get('notes')}</p>
                </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('previewContent').innerHTML = previewHTML;
    showPreviewModal();
}

function showPreviewModal() {
    document.getElementById('previewModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePreviewModal() {
    document.getElementById('previewModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function submitFormFromPreview() {
    closePreviewModal();
    document.getElementById('editChantierForm').submit();
}

// Suggestions d'étapes
function suggestSteps() {
    const titre = document.getElementById('titre').value.toLowerCase();
    const description = document.getElementById('description').value.toLowerCase();
    
    let suggestions = [];
    
    // Suggestions basées sur des mots-clés
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
    
    if (confirm('Voulez-vous ajouter des étapes types pour ce chantier ?\\n\\nÉtapes suggérées :\\n' + suggestions.join('\\n'))) {
        window.location.href = `{{ route('chantiers.show', $chantier) }}#etapes`;
    }
}

// Fonction de toast pour les notifications
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 max-w-sm p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
    
    const bgColors = {
        'info': 'bg-blue-500',
        'success': 'bg-green-500',
        'warning': 'bg-yellow-500',
        'error': 'bg-red-500'
    };
    
    toast.classList.add(bgColors[type] || bgColors.info, 'text-white');
    toast.textContent = message;
    
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
    }, 3000);
}

// Fermer les modals avec Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePreviewModal();
    }
});
</script>
@endpush

@push('styles')
<style>
.required::after {
    content: ' *';
    color: #ef4444;
}

@media print {
    .no-print {
        display: none !important;
    }
    
    .bg-gradient-to-r {
        background: #1e40af !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
    }
    
    .shadow-xl {
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out;
}

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
</style>
@endpush