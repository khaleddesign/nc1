@extends('layouts.app')

@section('title', 'Nouveau Devis')
@section('page-title', 'Nouveau Devis')
@section('page-subtitle', 'Création de devis prospect ou chantier')

@section('content')
<div class="min-h-screen bg-slate-50" x-data="devisCreator()" x-init="init()">
    
    {{-- 📝 HEADER AVEC CONTEXTE --}}
    <div class="bg-white border-b border-slate-200 shadow-sm mb-8">
        <div class="px-6 py-6">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('devis.index') }}" 
                           class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-all duration-200"
                           title="Retour à la liste">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900 gradient-text">
                                <svg class="w-7 h-7 inline mr-3 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Nouveau Devis
                            </h1>
                            <p class="text-slate-600 mt-1" x-text="getSubtitle()"></p>
                        </div>
                    </div>
                    
                    {{-- Indicateur de type --}}
                    <div class="flex items-center space-x-3">
                        <div class="px-3 py-1 rounded-full text-sm font-medium" 
                             :class="formData.type === 'prospect' ? 'bg-orange-100 text-orange-800' : 'bg-emerald-100 text-emerald-800'">
                            <span x-text="formData.type === 'prospect' ? '🎯 Prospect' : '🏗️ Chantier'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 📋 FORMULAIRE PRINCIPAL --}}
    <form method="POST" action="{{ route('devis.store') }}" class="max-w-4xl mx-auto px-6 pb-8" @submit="loading = true">
        @csrf
        
        {{-- Type de devis (hidden si paramètre URL) --}}
        <input type="hidden" name="type" x-model="formData.type">
        @if(request('chantier_id'))
            <input type="hidden" name="chantier_id" value="{{ request('chantier_id') }}">
        @endif

        {{-- 🎛️ SÉLECTEUR DE TYPE (si pas de paramètre URL spécifique) --}}
        @if(!request('chantier_id'))
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Type de devis</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Option Prospect --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="type_selector" value="prospect" 
                           x-model="formData.type" 
                           class="sr-only">
                    <div class="p-6 border-2 rounded-lg transition-all duration-200"
                         :class="formData.type === 'prospect' ? 'border-orange-500 bg-orange-50' : 'border-slate-200 hover:border-slate-300'">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center">
                                <span class="text-white text-2xl">🎯</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-900">Nouveau Prospect</h3>
                                <p class="text-sm text-slate-600 mt-1">Créer un devis pour un prospect sans chantier associé</p>
                                <div class="text-xs text-slate-500 mt-2">
                                    • Client libre • Statuts : Brouillon → Envoyé → Négocié → Accepté
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 rounded-full border-2 transition-all duration-200"
                                     :class="formData.type === 'prospect' ? 'border-orange-500 bg-orange-500' : 'border-slate-300'">
                                    <div class="w-2 h-2 bg-white rounded-full mx-auto mt-1"
                                         x-show="formData.type === 'prospect'"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>

                {{-- Option Chantier --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="type_selector" value="chantier" 
                           x-model="formData.type" 
                           class="sr-only">
                    <div class="p-6 border-2 rounded-lg transition-all duration-200"
                         :class="formData.type === 'chantier' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 hover:border-slate-300'">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center">
                                <span class="text-white text-2xl">🏗️</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-900">Devis Chantier</h3>
                                <p class="text-sm text-slate-600 mt-1">Créer un devis pour un chantier existant</p>
                                <div class="text-xs text-slate-500 mt-2">
                                    • Client du chantier • Statuts : Validé → Facturable → Facturé
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 rounded-full border-2 transition-all duration-200"
                                     :class="formData.type === 'chantier' ? 'border-emerald-500 bg-emerald-500' : 'border-slate-300'">
                                    <div class="w-2 h-2 bg-white rounded-full mx-auto mt-1"
                                         x-show="formData.type === 'chantier'"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>
        @endif

        {{-- 📋 INFORMATIONS GÉNÉRALES --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Informations générales</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Titre --}}
                <div class="md:col-span-2">
                    <label for="titre" class="form-label">
                        Titre du devis <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="titre" id="titre" 
                           x-model="formData.titre"
                           placeholder="Ex: Rénovation salle de bain"
                           class="form-input @error('titre') border-red-500 @enderror" required>
                    @error('titre')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Numéro (auto-généré) --}}
                <div>
                    <label for="numero" class="form-label">Numéro de devis</label>
                    <input type="text" name="numero" id="numero" 
                           value="{{ old('numero', $numeroSuggestion ?? '') }}"
                           placeholder="Auto-généré si vide"
                           class="form-input @error('numero') border-red-500 @enderror">
                    <p class="text-xs text-slate-500 mt-1">Laissez vide pour génération automatique</p>
                    @error('numero')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date de validité --}}
                <div>
                    <label for="date_validite" class="form-label">
                        Date de validité <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date_validite" id="date_validite" 
                           value="{{ old('date_validite', date('Y-m-d', strtotime('+30 days'))) }}"
                           class="form-input @error('date_validite') border-red-500 @enderror" required>
                    @error('date_validite')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- 👤 SECTION CLIENT (conditionelle) --}}
        <div x-show="formData.type === 'prospect'" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">
                <svg class="w-5 h-5 inline mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Informations client prospect
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nom du client --}}
                <div>
                    <label for="client_nom" class="form-label">
                        Nom du client <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="client_nom" id="client_nom" 
                           value="{{ old('client_nom') }}"
                           placeholder="Nom complet du client"
                           class="form-input @error('client_nom') border-red-500 @enderror"
                           :required="formData.type === 'prospect'">
                    @error('client_nom')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email du client --}}
                <div>
                    <label for="client_email" class="form-label">
                        Email du client <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="client_email" id="client_email" 
                           value="{{ old('client_email') }}"
                           placeholder="email@exemple.com"
                           class="form-input @error('client_email') border-red-500 @enderror"
                           :required="formData.type === 'prospect'">
                    @error('client_email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Téléphone --}}
                <div>
                    <label for="client_telephone" class="form-label">Téléphone</label>
                    <input type="tel" name="client_telephone" id="client_telephone" 
                           value="{{ old('client_telephone') }}"
                           placeholder="06 12 34 56 78"
                           class="form-input @error('client_telephone') border-red-500 @enderror">
                    @error('client_telephone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Entreprise --}}
                <div>
                    <label for="client_entreprise" class="form-label">Entreprise</label>
                    <input type="text" name="client_entreprise" id="client_entreprise" 
                           value="{{ old('client_entreprise') }}"
                           placeholder="Nom de l'entreprise (optionnel)"
                           class="form-input @error('client_entreprise') border-red-500 @enderror">
                    @error('client_entreprise')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Adresse --}}
                <div class="md:col-span-2">
                    <label for="client_adresse" class="form-label">Adresse complète</label>
                    <textarea name="client_adresse" id="client_adresse" rows="3"
                              placeholder="Adresse complète du client"
                              class="form-input @error('client_adresse') border-red-500 @enderror">{{ old('client_adresse') }}</textarea>
                    @error('client_adresse')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- 🏗️ SÉLECTION CHANTIER (conditionelle) --}}
        <div x-show="formData.type === 'chantier'" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">
                <svg class="w-5 h-5 inline mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Chantier associé
            </h2>
            
            @if(request('chantier_id') && isset($chantier))
                {{-- Chantier pré-sélectionné --}}
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center">
                            <span class="text-white text-xl">🏗️</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-emerald-900">{{ $chantier->titre }}</h3>
                            <p class="text-sm text-emerald-700">{{ $chantier->client->name ?? 'Client non défini' }}</p>
                            <p class="text-xs text-emerald-600">{{ $chantier->description ?? 'Aucune description' }}</p>
                        </div>
                        <div class="text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            @else
                {{-- Sélection de chantier --}}
                <div>
                    <label for="chantier_id" class="form-label">
                        Choisir un chantier <span class="text-red-500">*</span>
                    </label>
                    <select name="chantier_id" id="chantier_id" 
                            class="form-select @error('chantier_id') border-red-500 @enderror"
                            :required="formData.type === 'chantier'"
                            x-model="formData.chantier_id"
                            @change="loadChantierInfo()">
                        <option value="">Sélectionner un chantier...</option>
                        @if(isset($chantiers))
                            @foreach($chantiers as $chantierOption)
                                <option value="{{ $chantierOption->id }}" {{ old('chantier_id') == $chantierOption->id ? 'selected' : '' }}>
                                    {{ $chantierOption->titre }} - {{ $chantierOption->client->name ?? 'Client non défini' }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('chantier_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    {{-- Infos chantier sélectionné --}}
                    <div x-show="selectedChantier" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="mt-4 p-4 bg-slate-50 rounded-lg">
                        <div class="text-sm text-slate-600">
                            <strong>Client :</strong> <span x-text="selectedChantier?.client_name"></span><br>
                            <strong>Statut :</strong> <span x-text="selectedChantier?.statut"></span><br>
                            <strong>Budget :</strong> <span x-text="selectedChantier?.budget ? selectedChantier.budget + ' €' : 'Non défini'"></span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- 💰 LIGNES DE DEVIS --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-slate-900">
                    <svg class="w-5 h-5 inline mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Lignes du devis
                </h2>
                
                <button type="button" @click="addLigne()" 
                        class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Ajouter une ligne
                </button>
            </div>

            {{-- Table des lignes --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Désignation</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Unité</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Qté</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Prix Unit. HT</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">TVA</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Total HT</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <template x-for="(ligne, index) in lignes" :key="index">
                            <tr class="hover:bg-slate-50">
                                {{-- Désignation --}}
                                <td class="px-4 py-3">
                                    <input type="text" 
                                           :name="`lignes[${index}][designation]`"
                                           x-model="ligne.designation"
                                           placeholder="Description de la prestation"
                                           class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           required>
                                </td>
                                
                                {{-- Unité --}}
                                <td class="px-4 py-3">
                                    <select :name="`lignes[${index}][unite]`"
                                            x-model="ligne.unite"
                                            class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="u">u (unité)</option>
                                        <option value="m²">m² (mètre carré)</option>
                                        <option value="ml">ml (mètre linéaire)</option>
                                        <option value="h">h (heure)</option>
                                        <option value="j">j (jour)</option>
                                        <option value="forfait">Forfait</option>
                                        <option value="lot">Lot</option>
                                    </select>
                                </td>
                                
                                {{-- Quantité --}}
                                <td class="px-4 py-3">
                                    <input type="number" 
                                           :name="`lignes[${index}][quantite]`"
                                           x-model="ligne.quantite"
                                           @input="calculateLigneTotal(index)"
                                           placeholder="1"
                                           step="0.01"
                                           min="0"
                                           class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           required>
                                </td>
                                
                                {{-- Prix unitaire HT --}}
                                <td class="px-4 py-3">
                                    <input type="number" 
                                           :name="`lignes[${index}][prix_unitaire_ht]`"
                                           x-model="ligne.prix_unitaire_ht"
                                           @input="calculateLigneTotal(index)"
                                           placeholder="0.00"
                                           step="0.01"
                                           min="0"
                                           class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           required>
                                </td>
                                
                                {{-- TVA --}}
                                <td class="px-4 py-3">
                                    <select :name="`lignes[${index}][taux_tva]`"
                                            x-model="ligne.taux_tva"
                                            @change="calculateLigneTotal(index)"
                                            class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="20">20%</option>
                                        <option value="10">10%</option>
                                        <option value="5.5">5.5%</option>
                                        <option value="2.1">2.1%</option>
                                        <option value="0">0%</option>
                                    </select>
                                </td>
                                
                                {{-- Total HT --}}
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-slate-900" 
                                         x-text="formatMoney(ligne.total_ht)">
                                    </div>
                                    <input type="hidden" 
                                           :name="`lignes[${index}][montant_ht]`"
                                           :value="ligne.total_ht">
                                </td>
                                
                                {{-- Actions --}}
                                <td class="px-4 py-3 text-center">
                                    <button type="button" 
                                            @click="removeLigne(index)"
                                            class="p-1 text-red-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors duration-200"
                                            title="Supprimer cette ligne">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        
                        {{-- Ligne vide si aucune ligne --}}
                        <tr x-show="lignes.length === 0">
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-sm">Aucune ligne pour le moment</p>
                                    <p class="text-xs text-slate-400 mt-1">Cliquez sur "Ajouter une ligne" pour commencer</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Récapitulatif des totaux --}}
            <div class="mt-6 border-t border-slate-200 pt-6">
                <div class="flex justify-end">
                    <div class="w-full max-w-sm space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Sous-total HT :</span>
                            <span class="text-sm font-medium" x-text="formatMoney(totaux.sousTotal)"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Total TVA :</span>
                            <span class="text-sm font-medium" x-text="formatMoney(totaux.totalTva)"></span>
                        </div>
                        <div class="flex justify-between items-center border-t border-slate-200 pt-3">
                            <span class="text-base font-semibold text-slate-900">Total TTC :</span>
                            <span class="text-lg font-bold text-indigo-600" x-text="formatMoney(totaux.totalTtc)"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Champs cachés pour les totaux --}}
            <input type="hidden" name="montant_ht" :value="totaux.sousTotal">
            <input type="hidden" name="montant_tva" :value="totaux.totalTva">
            <input type="hidden" name="montant_ttc" :value="totaux.totalTtc">
        </div>

        {{-- 📝 INFORMATIONS COMPLÉMENTAIRES --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Informations complémentaires</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Notes internes --}}
                <div>
                    <label for="notes_internes" class="form-label">Notes internes</label>
                    <textarea name="notes_internes" id="notes_internes" rows="4"
                              placeholder="Notes visibles uniquement par l'équipe interne"
                              class="form-input @error('notes_internes') border-red-500 @enderror">{{ old('notes_internes') }}</textarea>
                    @error('notes_internes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Conditions particulières --}}
                <div>
                    <label for="conditions_particulieres" class="form-label">Conditions particulières</label>
                    <textarea name="conditions_particulieres" id="conditions_particulieres" rows="4"
                              placeholder="Conditions affichées sur le devis client"
                              class="form-input @error('conditions_particulieres') border-red-500 @enderror">{{ old('conditions_particulieres') }}</textarea>
                    @error('conditions_particulieres')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- ⚙️ ACTIONS DE SAUVEGARDE --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-slate-600">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Statut initial : <strong x-text="getInitialStatus()"></strong></span>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    {{-- Sauvegarder brouillon --}}
                    <button type="submit" name="action" value="brouillon"
                            class="btn btn-outline" 
                            :class="{ 'opacity-50 cursor-not-allowed': loading }"
                            :disabled="loading">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <span x-show="!loading">Sauvegarder brouillon</span>
                        <span x-show="loading">Sauvegarde...</span>
                    </button>

                    {{-- Sauvegarder et envoyer/valider --}}
                    <button type="submit" name="action" value="envoyer"
                            class="btn btn-primary" 
                            :class="{ 'opacity-50 cursor-not-allowed': loading }"
                            :disabled="loading">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <span x-show="!loading" x-text="getActionButtonText()"></span>
                        <span x-show="loading">Traitement...</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Scripts Alpine.js --}}
<script>
function devisCreator() {
    return {
        // États
        loading: false,
        formData: {
            type: '{{ request("type", request("chantier_id") ? "chantier" : "prospect") }}',
            chantier_id: '{{ request("chantier_id", "") }}',
            titre: ''
        },
        lignes: [],
        selectedChantier: null,
        totaux: {
            sousTotal: 0,
            totalTva: 0,
            totalTtc: 0
        },
        
        // Initialisation
        init() {
            // Ajouter une ligne par défaut
            this.addLigne();
            
            // Charger les infos du chantier si pré-sélectionné
            if (this.formData.chantier_id) {
                this.loadChantierInfo();
            }
            
            // Écouter les changements de type
            this.$watch('formData.type', (newType) => {
                // Réinitialiser les champs selon le type
                if (newType === 'prospect') {
                    this.formData.chantier_id = '';
                    this.selectedChantier = null;
                }
            });
        },
        
        // Gestion des lignes
        addLigne() {
            this.lignes.push({
                designation: '',
                unite: 'u',
                quantite: 1,
                prix_unitaire_ht: 0,
                taux_tva: 20,
                total_ht: 0
            });
            this.calculateTotaux();
        },
        
        removeLigne(index) {
            if (this.lignes.length > 1) {
                this.lignes.splice(index, 1);
                this.calculateTotaux();
            } else {
                alert('Un devis doit contenir au moins une ligne');
            }
        },
        
        calculateLigneTotal(index) {
            const ligne = this.lignes[index];
            if (ligne) {
                const quantite = parseFloat(ligne.quantite) || 0;
                const prixUnitaire = parseFloat(ligne.prix_unitaire_ht) || 0;
                ligne.total_ht = quantite * prixUnitaire;
                this.calculateTotaux();
            }
        },
        
        calculateTotaux() {
            let sousTotal = 0;
            let totalTva = 0;
            
            this.lignes.forEach(ligne => {
                const montantHt = parseFloat(ligne.total_ht) || 0;
                const tauxTva = parseFloat(ligne.taux_tva) || 0;
                
                sousTotal += montantHt;
                totalTva += montantHt * (tauxTva / 100);
            });
            
            this.totaux.sousTotal = sousTotal;
            this.totaux.totalTva = totalTva;
            this.totaux.totalTtc = sousTotal + totalTva;
        },
        
        // Chargement des infos chantier
        async loadChantierInfo() {
            if (!this.formData.chantier_id) {
                this.selectedChantier = null;
                return;
            }
            
            try {
                // Simulation - à remplacer par un appel AJAX réel
                const response = await fetch(`/api/chantiers/${this.formData.chantier_id}`);
                if (response.ok) {
                    this.selectedChantier = await response.json();
                }
            } catch (error) {
                console.error('Erreur lors du chargement des infos chantier:', error);
                // Fallback - récupérer depuis le DOM si disponible
                const select = document.getElementById('chantier_id');
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    this.selectedChantier = {
                        client_name: selectedOption.text.split(' - ')[1] || 'Client non défini',
                        statut: 'En cours',
                        budget: null
                    };
                }
            }
        },
        
        // Utilitaires d'affichage
        getSubtitle() {
            if (this.formData.type === 'prospect') {
                return 'Créer un devis pour un nouveau prospect';
            } else if (this.formData.chantier_id) {
                return 'Créer un devis pour le chantier sélectionné';
            } else {
                return 'Créer un devis pour un chantier existant';
            }
        },
        
        getInitialStatus() {
            return this.formData.type === 'prospect' ? 'Prospect Brouillon' : 'Chantier Validé';
        },
        
        getActionButtonText() {
            return this.formData.type === 'prospect' ? 'Sauvegarder et envoyer' : 'Sauvegarder et valider';
        },
        
        formatMoney(amount) {
            return new Intl.NumberFormat('fr-FR', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 2
            }).format(amount || 0);
        }
    }
}

// Validation côté client
document.addEventListener('DOMContentLoaded', function() {
    // Validation en temps réel
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const lignesContainer = document.querySelector('tbody');
            const lignes = lignesContainer.querySelectorAll('tr:not([x-show])');
            
            if (lignes.length === 0) {
                e.preventDefault();
                alert('Veuillez ajouter au moins une ligne au devis');
                return false;
            }
            
            // Vérifier que tous les champs requis des lignes sont remplis
            let hasError = false;
            lignes.forEach((ligne, index) => {
                const designation = ligne.querySelector('input[name*="designation"]');
                const quantite = ligne.querySelector('input[name*="quantite"]');
                const prix = ligne.querySelector('input[name*="prix_unitaire_ht"]');
                
                if (!designation.value || !quantite.value || !prix.value) {
                    hasError = true;
                }
            });
            
            if (hasError) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires des lignes de devis');
                return false;
            }
        });
    }
});
</script>

{{-- Styles additionnels --}}
<style>
/* Gradient text */
.gradient-text {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Animation de focus sur les radios */
input[type="radio"]:checked + div {
    animation: pulse 0.3s ease-in-out;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

/* Amélioration des tables sur mobile */
@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .table-responsive table {
        min-width: 800px;
    }
}

/* Loading states */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Form improvements */
.form-label {
    @apply block text-sm font-medium text-slate-700 mb-1;
}

.form-input, .form-select {
    @apply w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200;
}

.form-input:focus, .form-select:focus {
    @apply ring-2 ring-indigo-500 border-indigo-500;
}

/* Button styles */
.btn {
    @apply inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200;
}

.btn-primary {
    @apply border-transparent text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500;
}

.btn-outline {
    @apply border-slate-300 text-slate-700 bg-white hover:bg-slate-50 focus:ring-indigo-500;
}

.btn-sm {
    @apply px-2.5 py-1.5 text-xs;
}

/* Card styles */
.card {
    @apply bg-white rounded-lg shadow-sm;
}

/* Transitions améliorées */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Hover effects */
.hover\:shadow-lg:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Focus improvements pour l'accessibilité */
.focus\:ring-2:focus {
    ring-width: 2px;
}

.focus\:ring-indigo-500:focus {
    --tw-ring-color: rgb(99 102 241 / 0.5);
}

/* Responsive text */
@media (max-width: 640px) {
    .text-2xl {
        font-size: 1.5rem;
        line-height: 2rem;
    }
    
    .text-lg {
        font-size: 1rem;
        line-height: 1.5rem;
    }
}
</style>
@endsection