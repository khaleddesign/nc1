@extends('layouts.app')

@section('title', $chantier->titre)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="breadcrumb mb-6">
        <a href="{{ route('chantiers.index') }}" class="breadcrumb-item">Chantiers</a>
        <span class="breadcrumb-separator">/</span>
        <span class="text-gray-900">{{ $chantier->titre }}</span>
    </nav>

    <!-- En-tête -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $chantier->titre }}</h1>
                    <div class="mt-2 flex items-center space-x-4">
                        <span class="{{ $chantier->getStatutBadgeClass() }}">
                            {{ $chantier->getStatutTexte() }}
                        </span>
                        @if($chantier->isEnRetard())
                            <span class="badge badge-danger">En retard</span>
                        @endif
                        <span class="text-sm text-gray-500">
                            Créé le {{ $chantier->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
                
                @can('update', $chantier)
                    <div class="flex space-x-2">
                        <a href="{{ route('chantiers.edit', $chantier) }}" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Modifier
                        </a>
                    </div>
                @endcan
            </div>
        </div>

        <!-- Informations principales -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Client</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $chantier->client->name }}</p>
                    @if($chantier->client->telephone)
                        <p class="text-xs text-gray-500">{{ $chantier->client->telephone }}</p>
                    @endif
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Commercial</h3>
                    <p class="mt-1 text-sm text-gray-900">{{ $chantier->commercial->name }}</p>
                    @if($chantier->commercial->telephone)
                        <p class="text-xs text-gray-500">{{ $chantier->commercial->telephone }}</p>
                    @endif
                </div>
                
                @if($chantier->date_debut)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Dates</h3>
                        <p class="mt-1 text-sm text-gray-900">
                            Du {{ $chantier->date_debut->format('d/m/Y') }}
                            @if($chantier->date_fin_prevue)
                                <br>au <span class="{{ $chantier->getRetardClass() }}">{{ $chantier->date_fin_prevue->format('d/m/Y') }}</span>
                            @endif
                        </p>
                    </div>
                @endif
                
                @if($chantier->budget)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Budget</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ number_format($chantier->budget, 0, ',', ' ') }} €</p>
                    </div>
                @endif
            </div>

            @if($chantier->description)
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Description</h3>
                    <p class="text-sm text-gray-900">{{ $chantier->description }}</p>
                </div>
            @endif

            <!-- Barre de progression -->
            <div class="mt-6">
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span class="font-medium">Avancement global</span>
                    <span class="font-semibold">{{ number_format($chantier->avancement_global, 1) }}%</span>
                </div>
                <div class="progress">
                    <div class="progress-bar {{ $chantier->getProgressBarColor() }}" 
                         style="width: {{ $chantier->avancement_global }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Étapes -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900">Étapes du projet</h2>
                        @can('update', $chantier)
                            <button onclick="openModal('modal-nouvelle-etape')" class="btn-sm btn-primary">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Ajouter
                            </button>
                        @endcan
                    </div>
                </div>
                
                <div class="px-6 py-4">
                    @forelse($chantier->etapes as $etape)
                        <div class="etape-item-{{ $etape->terminee ? 'completed' : ($etape->pourcentage > 0 ? 'in-progress' : 'pending') }} mb-4 last:mb-0">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $etape->nom }}</h4>
                                    @if($etape->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $etape->description }}</p>
                                    @endif
                                    <div class="flex items-center mt-2 space-x-4 text-xs text-gray-500">
                                        @if($etape->date_debut)
                                            <span>Début: {{ $etape->date_debut->format('d/m/Y') }}</span>
                                        @endif
                                        @if($etape->date_fin_prevue)
                                            <span class="{{ $etape->isEnRetard() ? 'text-red-600 font-semibold' : '' }}">
                                                Fin prévue: {{ $etape->date_fin_prevue->format('d/m/Y') }}
                                            </span>
                                        @endif
                                        @if($etape->date_fin_effective)
                                            <span class="text-green-600">Terminé le {{ $etape->date_fin_effective->format('d/m/Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <div class="text-sm font-medium">{{ $etape->pourcentage }}%</div>
                                        <div class="w-20 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" 
                                                 style="width: {{ $etape->pourcentage }}%"></div>
                                        </div>
                                    </div>
                                    
                                    @can('update', $chantier)
                                        <div class="flex space-x-1">
                                            <button class="text-gray-400 hover:text-gray-600" title="Modifier">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <form method="POST" action="{{ route('etapes.destroy', [$chantier, $etape]) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600" title="Supprimer"
                                                        onclick="return confirm('Supprimer cette étape ?')">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune étape</h3>
                            <p class="mt-1 text-sm text-gray-500">Commencez par ajouter une première étape à ce chantier.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Commentaires -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Commentaires</h2>
                </div>
                
                <div class="px-6 py-4">
                    <!-- Formulaire nouveau commentaire -->
                    <form method="POST" action="{{ route('commentaires.store', $chantier) }}" class="mb-6">
                        @csrf
                        <div>
                            <label for="contenu" class="sr-only">Votre commentaire</label>
                            <textarea name="contenu" id="contenu" rows="3" 
                                      class="form-textarea" 
                                      placeholder="Ajouter un commentaire..."
                                      required></textarea>
                        </div>
                        <div class="mt-3 flex justify-end">
                            <button type="submit" class="btn-primary">
                                Publier
                            </button>
                        </div>
                    </form>

                    <!-- Liste des commentaires -->
                    <div class="space-y-4">
                        @forelse($chantier->commentaires as $commentaire)
                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ substr($commentaire->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $commentaire->user->name }}</h4>
                                        <span class="badge badge-{{ $commentaire->user->role === 'client' ? 'primary' : 'warning' }}">
                                            {{ ucfirst($commentaire->user->role) }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $commentaire->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-700">{{ $commentaire->contenu }}</p>
                                    
                                    @if(Auth::id() === $commentaire->user_id || Auth::user()->isAdmin())
                                        <div class="mt-2">
                                            <form method="POST" action="{{ route('commentaires.destroy', $commentaire) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-red-600 hover:text-red-800"
                                                        onclick="return confirm('Supprimer ce commentaire ?')">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 italic">Aucun commentaire pour le moment.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Documents -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900">Documents</h2>
                        @can('update', $chantier)
                            <button onclick="openModal('modal-upload-document')" class="btn-sm btn-primary">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Upload
                            </button>
                        @endcan
                    </div>
                </div>
                
                <div class="px-6 py-4">
                    @forelse($chantier->documents as $document)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($document->isImage())
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        @endif
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $document->nom_original }}</p>
                                    <p class="text-xs text-gray-500">{{ $document->getTailleFormatee() }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('documents.download', $document) }}" 
                                   class="text-blue-600 hover:text-blue-800" 
                                   title="Télécharger">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </a>
                                @can('update', $chantier)
                                    <form method="POST" action="{{ route('documents.destroy', $document) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-400 hover:text-red-600" 
                                                title="Supprimer"
                                                onclick="return confirm('Supprimer ce document ?')">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Aucun document</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Informations complémentaires -->
            @if($chantier->notes)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Notes</h2>
                    </div>
                    <div class="px-6 py-4">
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $chantier->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal - Nouvelle étape -->
@can('update', $chantier)
<div id="modal-nouvelle-etape" class="modal-overlay hidden" onclick="if(event.target === this) closeModal('modal-nouvelle-etape')">
    <div class="modal-content max-w-md">
        <div class="modal-header">
            <h3 class="text-lg font-medium text-gray-900">Nouvelle étape</h3>
            <button onclick="closeModal('modal-nouvelle-etape')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form method="POST" action="{{ route('etapes.store', $chantier) }}">
            @csrf
            <div class="modal-body space-y-4">
                <div>
                    <label for="nom" class="form-label">Nom de l'étape *</label>
                    <input type="text" name="nom" id="nom" class="form-input" required>
                </div>
                
                <div>
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="3" class="form-textarea"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="ordre" class="form-label">Ordre *</label>
                        <input type="number" name="ordre" id="ordre" value="{{ $chantier->etapes->count() + 1 }}" class="form-input" required min="1">
                    </div>
                    
                    <div>
                        <label for="pourcentage" class="form-label">Avancement (%)</label>
                        <input type="number" name="pourcentage" id="pourcentage" value="0" class="form-input" min="0" max="100">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="date_debut" class="form-label">Date début</label>
                        <input type="date" name="date_debut" id="date_debut" class="form-input">
                    </div>
                    
                    <div>
                        <label for="date_fin_prevue" class="form-label">Date fin prévue</label>
                        <input type="date" name="date_fin_prevue" id="date_fin_prevue" class="form-input">
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-nouvelle-etape')" class="btn-outline">Annuler</button>
                <button type="submit" class="btn-primary">Créer l'étape</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal - Upload document -->
<div id="modal-upload-document" class="modal-overlay hidden" onclick="if(event.target === this) closeModal('modal-upload-document')">
    <div class="modal-content max-w-md">
        <div class="modal-header">
            <h3 class="text-lg font-medium text-gray-900">Ajouter des documents</h3>
            <button onclick="closeModal('modal-upload-document')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form method="POST" action="{{ route('documents.store', $chantier) }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body space-y-4">
                <div>
                    <label for="fichiers" class="form-label">Fichiers *</label>
                    <input type="file" name="fichiers[]" id="fichiers" class="form-input" multiple required
                           accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx">
                    <p class="text-xs text-gray-500 mt-1">
                        Formats acceptés : JPG, PNG, PDF, DOC, XLS (max 10MB par fichier)
                    </p>
                </div>
                
                <div>
                    <label for="type" class="form-label">Type de document</label>
                    <select name="type" id="type" class="form-select">
                        <option value="document">Document général</option>
                        <option value="image">Image/Photo</option>
                        <option value="plan">Plan</option>
                        <option value="facture">Facture</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                
                <div>
                    <label for="description_doc" class="form-label">Description</label>
                    <textarea name="description" id="description_doc" rows="3" class="form-textarea" 
                              placeholder="Description optionnelle des documents..."></textarea>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-upload-document')" class="btn-outline">Annuler</button>
                <button type="submit" class="btn-primary">Uploader</button>
            </div>
        </form>
    </div>
</div>
@endcan

@push('scripts')
<script>
// Gestion des modales
window.openModal = function(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
};

window.closeModal = function(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
};

// Fermer modal avec Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.modal-overlay:not(.hidden)');
        modals.forEach(modal => {
            modal.classList.add('hidden');
        });
        document.body.classList.remove('overflow-hidden');
    }
});

// Auto-focus sur le premier champ des modales
document.addEventListener('DOMContentLoaded', function() {
    // Observer pour détecter l'ouverture des modales
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                const modal = mutation.target;
                if (!modal.classList.contains('hidden')) {
                    const firstInput = modal.querySelector('input:not([type="hidden"]), textarea, select');
                    if (firstInput) {
                        setTimeout(() => firstInput.focus(), 100);
                    }
                }
            }
        });
    });
    
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        observer.observe(modal, { attributes: true });
    });
});

// Validation côté client pour les fichiers
document.getElementById('fichiers')?.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const maxSize = 10 * 1024 * 1024; // 10MB
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 
                         'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                         'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    
    let hasError = false;
    const errors = [];
    
    files.forEach(file => {
        if (file.size > maxSize) {
            errors.push(`${file.name} dépasse la taille maximale de 10MB`);
            hasError = true;
        }
        
        if (!allowedTypes.includes(file.type)) {
            errors.push(`${file.name} n'est pas un format accepté`);
            hasError = true;
        }
    });
    
    if (hasError) {
        alert('Erreurs détectées:\n' + errors.join('\n'));
        e.target.value = '';
    }
});
</script>
@endpush
@endsection