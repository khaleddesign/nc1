@extends('layouts.app')

@section('title', 'Nouveau chantier')

@section('content')
<!-- Version simplifiée pour debug responsive -->
<div class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Header fixe sans responsive complexe -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-xl">
        <div class="mx-auto px-4 py-6 max-w-7xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-2xl font-bold text-white">Créer un nouveau chantier</h1>
                        <p class="text-blue-100">Ajoutez un nouveau projet</p>
                    </div>
                </div>
                <a href="{{ route('chantiers.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-white border-opacity-20 rounded-lg text-sm font-medium text-white hover:bg-white hover:bg-opacity-10 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Contenu principal simplifié -->
    <div class="mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <form method="POST" action="{{ route('chantiers.store') }}" class="space-y-0">
                @csrf
                
                <!-- Section 1: Informations de base -->
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        Informations générales
                    </h3>
                </div>

                <div class="px-6 py-6 space-y-6">
                    <!-- Titre -->
                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                            Titre du chantier <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('titre') border-red-300 @enderror" 
                               id="titre" 
                               name="titre" 
                               value="{{ old('titre') }}" 
                               required
                               placeholder="Ex: Rénovation cuisine moderne">
                        @error('titre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('description') border-red-300 @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3"
                                  placeholder="Décrivez le chantier...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Section 2: Intervenants -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        Intervenants
                    </h3>
                </div>

                <div class="px-6 py-6">
                    <!-- Grid responsive fixée -->
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Client -->
                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Client <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('client_id') border-red-300 @enderror" 
                                    id="client_id" 
                                    name="client_id" 
                                    required>
                                <option value="">Sélectionner un client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                        @if($client->telephone)
                                            ({{ $client->telephone }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Commercial -->
                        <div>
                            <label for="commercial_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Commercial <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('commercial_id') border-red-300 @enderror" 
                                    id="commercial_id" 
                                    name="commercial_id" 
                                    required>
                                <option value="">Sélectionner un commercial</option>
                                @if(Auth::user()->isCommercial())
                                    <option value="{{ Auth::id() }}" selected>{{ Auth::user()->name }} (Moi)</option>
                                @endif
                                @foreach($commerciaux as $commercial)
                                    @if($commercial->id != Auth::id())
                                        <option value="{{ $commercial->id }}" {{ old('commercial_id') == $commercial->id ? 'selected' : '' }}>
                                            {{ $commercial->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('commercial_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Planning & Budget -->
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                        </svg>
                        Planning & Budget
                    </h3>
                </div>

                <div class="px-6 py-6">
                    <!-- Grid responsive simplifiée -->
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Statut -->
                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-700 mb-2">
                                Statut <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('statut') border-red-300 @enderror" 
                                    id="statut" 
                                    name="statut" 
                                    required>
                                <option value="planifie" {{ old('statut', 'planifie') == 'planifie' ? 'selected' : '' }}>
                                    📋 Planifié
                                </option>
                                <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>
                                    🚧 En cours
                                </option>
                                <option value="termine" {{ old('statut') == 'termine' ? 'selected' : '' }}>
                                    ✅ Terminé
                                </option>
                            </select>
                            @error('statut')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Budget -->
                        <div>
                            <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">Budget (€)</label>
                            <div class="relative">
                                <input type="number" 
                                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm pl-8 @error('budget') border-red-300 @enderror" 
                                       id="budget" 
                                       name="budget" 
                                       value="{{ old('budget') }}" 
                                       step="100" 
                                       min="0"
                                       placeholder="25000">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">€</span>
                                </div>
                            </div>
                            @error('budget')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dates en grid séparée -->
                    <div class="grid grid-cols-1 gap-6 mt-6 lg:grid-cols-2">
                        <!-- Date début -->
                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                            <input type="date" 
                                   class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('date_debut') border-red-300 @enderror" 
                                   id="date_debut" 
                                   name="date_debut" 
                                   value="{{ old('date_debut') }}">
                            @error('date_debut')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date fin -->
                        <div>
                            <label for="date_fin_prevue" class="block text-sm font-medium text-gray-700 mb-2">Date de fin prévue</label>
                            <input type="date" 
                                   class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('date_fin_prevue') border-red-300 @enderror" 
                                   id="date_fin_prevue" 
                                   name="date_fin_prevue" 
                                   value="{{ old('date_fin_prevue') }}">
                            @error('date_fin_prevue')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 4: Notes -->
                <div class="bg-gradient-to-r from-amber-50 to-yellow-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-amber-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                        </svg>
                        Notes internes
                    </h3>
                </div>

                <div class="px-6 py-6">
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes internes</label>
                        <textarea class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm @error('notes') border-red-300 @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="4" 
                                  placeholder="Notes visibles uniquement par l'équipe">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Footer avec boutons -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col space-y-3 sm:flex-row sm:justify-between sm:space-y-0">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        Les champs * sont obligatoires
                    </div>
                    <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-3">
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
                            </svg>
                            Créer le chantier
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-sélection du commercial
    @if(Auth::user()->isCommercial())
        document.getElementById('commercial_id').value = '{{ Auth::id() }}';
    @endif
    
    // Validation des dates
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin_prevue');
    
    const today = new Date().toISOString().split('T')[0];
    dateDebut.min = today;
    dateFin.min = today;
    
    dateDebut?.addEventListener('change', function() {
        if (dateFin) {
            dateFin.min = this.value;
            if (dateFin.value && dateFin.value < this.value) {
                dateFin.value = this.value;
            }
        }
    });
});
</script>
@endsection