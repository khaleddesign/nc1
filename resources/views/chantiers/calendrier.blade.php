@extends('layouts.app')

@section('title', 'Nouveau chantier')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="card">
        <div class="card-header">
            <h4 class="text-xl font-semibold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Créer un nouveau chantier
            </h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('chantiers.store') }}" id="createChantierForm">
                @csrf
                
                <!-- Titre -->
                <div class="mb-6">
                    <label for="titre" class="form-label">
                        Titre du chantier <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           class="form-input @error('titre') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                           id="titre" 
                           name="titre" 
                           value="{{ old('titre') }}" 
                           required
                           autofocus>
                    @error('titre')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-input @error('description') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                              id="description" 
                              name="description" 
                              rows="3"
                              placeholder="Décrivez brièvement le chantier...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Client -->
                    <div>
                        <label for="client_id" class="form-label">
                            Client <span class="text-red-500">*</span>
                        </label>
                        <select class="form-select @error('client_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
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
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Commercial -->
                    <div>
                        <label for="commercial_id" class="form-label">
                            Commercial responsable <span class="text-red-500">*</span>
                        </label>
                        <select class="form-select @error('commercial_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
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
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Statut -->
                    <div>
                        <label for="statut" class="form-label">
                            Statut initial <span class="text-red-500">*</span>
                        </label>
                        <select class="form-select @error('statut') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
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
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Budget -->
                    <div>
                        <label for="budget" class="form-label">Budget (€)</label>
                        <div class="relative">
                            <input type="number" 
                                   class="form-input pl-8 @error('budget') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                   id="budget" 
                                   name="budget" 
                                   value="{{ old('budget') }}" 
                                   step="0.01" 
                                   min="0"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">€</span>
                            </div>
                        </div>
                        @error('budget')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Date début -->
                    <div>
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="date" 
                               class="form-input @error('date_debut') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                               id="date_debut" 
                               name="date_debut" 
                               value="{{ old('date_debut') }}">
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
                               value="{{ old('date_fin_prevue') }}">
                        @error('date_fin_prevue')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="form-label">Notes internes</label>
                    <textarea class="form-input @error('notes') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                              id="notes" 
                              name="notes" 
                              rows="3" 
                              placeholder="Notes visibles uniquement par l'équipe">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    <small class="text-gray-500 mt-1 block">
                        <svg class="inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        Ces notes ne seront visibles que par l'équipe interne
                    </small>
                </div>

                <!-- Boutons -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center pt-6 border-t border-gray-200 space-y-3 sm:space-y-0">
                    <a href="{{ route('chantiers.index') }}" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Retour à la liste
                    </a>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <button type="button" class="btn btn-outline" onclick="previewForm()">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Aperçu
                        </button>
                        <button type="submit" class="btn btn-primary">
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

<!-- Modal d'aperçu -->
<div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Aperçu du chantier</h3>
                <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="previewContent" class="p-6">
                <!-- Contenu dynamique -->
            </div>
            <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50">
                <button onclick="closePreviewModal()" class="btn btn-secondary">
                    Fermer
                </button>
                <button onclick="submitFormFromPreview()" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Confirmer et créer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation des dates
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin_prevue');
    
    // Définir la date minimum à aujourd'hui
    const today = new Date().toISOString().split('T')[0];
    dateDebut.min = today;
    dateFin.min = today;
    
    dateDebut?.addEventListener('change', function() {
        if (dateFin) {
            dateFin.min = this.value;
            if (dateFin.value && dateFin.value < this.value) {
                dateFin.value = this.value;
                showToast('Date de fin ajustée automatiquement', 'info');
            }
        }
    });
    
    // Auto-sélection du commercial actuel si c'est un commercial
    @if(Auth::user()->isCommercial())
        document.getElementById('commercial_id').value = '{{ Auth::id() }}';
    @endif
    
    // Validation en temps réel
    const form = document.getElementById('createChantierForm');
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        field.addEventListener('blur', validateField);
        field.addEventListener('input', clearFieldError);
    });
    
    // Soumission du formulaire
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            // Animation de soumission
            const submitBtn = document.querySelector('button[type="submit"]');
            const originalContent = submitBtn.innerHTML;
            
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = `
                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Création en cours...
            `;
            
            // Simuler un délai puis soumettre
            setTimeout(() => {
                this.submit();
            }, 500);
        }
    });
});

// Validation des champs
function validateField(e) {
    const field = e.target;
    const value = field.value.trim();
    
    // Supprimer les erreurs existantes
    clearFieldError({ target: field });
    
    if (field.hasAttribute('required') && !value) {
        showFieldError(field, 'Ce champ est obligatoire');
        return false;
    }
    
    // Validations spécifiques
    switch (field.name) {
        case 'titre':
            if (value.length < 3) {
                showFieldError(field, 'Le titre doit contenir au moins 3 caractères');
                return false;
            }
            break;
        case 'budget':
            if (value && (isNaN(value) || parseFloat(value) < 0)) {
                showFieldError(field, 'Le budget doit être un nombre positif');
                return false;
            }
            break;
        case 'date_fin_prevue':
            const dateDebut = document.getElementById('date_debut').value;
            if (value && dateDebut && value < dateDebut) {
                showFieldError(field, 'La date de fin ne peut pas être antérieure à la date de début');
                return false;
            }
            break;
    }
    
    return true;
}

function clearFieldError(e) {
    const field = e.target;
    field.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
    
    const errorDiv = field.parentNode.querySelector('.form-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

function showFieldError(field, message) {
    field.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
    
    let errorDiv = field.parentNode.querySelector('.form-error');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'form-error';
        field.parentNode.appendChild(errorDiv);
    }
    errorDiv.textContent = message;
}

function validateForm() {
    const form = document.getElementById('createChantierForm');
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!validateField({ target: field })) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        showToast('Veuillez corriger les erreurs dans le formulaire', 'error');
        // Scroll vers la première erreur
        const firstError = document.querySelector('.border-red-300');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
    }
    
    return isValid;
}

// Aperçu du formulaire
function previewForm() {
    const formData = new FormData(document.getElementById('createChantierForm'));
    
    // Obtenir les textes des sélects
    const clientSelect = document.getElementById('client_id');
    const commercialSelect = document.getElementById('commercial_id');
    const statutSelect = document.getElementById('statut');
    
    const clientText = clientSelect.options[clientSelect.selectedIndex]?.text || 'Non sélectionné';
    const commercialText = commercialSelect.options[commercialSelect.selectedIndex]?.text || 'Non sélectionné';
    const statutText = statutSelect.options[statutSelect.selectedIndex]?.text || 'Non sélectionné';
    
    const previewHTML = `
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-900">Titre</h4>
                    <p class="text-gray-600">${formData.get('titre') || 'Non défini'}</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Statut</h4>
                    <p class="text-gray-600">${statutText}</p>
                </div>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900">Description</h4>
                <p class="text-gray-600">${formData.get('description') || 'Aucune description'}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-900">Client</h4>
                    <p class="text-gray-600">${clientText}</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Commercial</h4>
                    <p class="text-gray-600">${commercialText}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-900">Budget</h4>
                    <p class="text-gray-600">${formData.get('budget') ? Number(formData.get('budget')).toLocaleString('fr-FR') + ' €' : 'Non défini'}</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Dates</h4>
                    <p class="text-gray-600">
                        Du ${formData.get('date_debut') ? new Date(formData.get('date_debut')).toLocaleDateString('fr-FR') : '?'} 
                        au ${formData.get('date_fin_prevue') ? new Date(formData.get('date_fin_prevue')).toLocaleDateString('fr-FR') : '?'}
                    </p>
                </div>
            </div>
            
            ${formData.get('notes') ? `
                <div>
                    <h4 class="font-medium text-gray-900">Notes internes</h4>
                    <p class="text-gray-600">${formData.get('notes')}</p>
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
    document.getElementById('createChantierForm').dispatchEvent(new Event('submit'));
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
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Fermer les modals avec Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePreviewModal();
    }
});

// Fermer en cliquant en dehors
document.getElementById('previewModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closePreviewModal();
    }
});
</script>
@endsection