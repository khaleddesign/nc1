@extends('layouts.app')

@section('title', $chantier->titre)

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="mb-2">
                        <i class="fas fa-building me-2"></i>{{ $chantier->titre }}
                    </h1>
                    <p class="text-muted mb-0">{{ $chantier->description }}</p>
                </div>
                <div>
                    <span class="badge {{ $chantier->getStatutBadgeClass() }} fs-6">
                        {{ $chantier->getStatutTexte() }}
                    </span>
                    @if($chantier->isEnRetard())
                        <span class="badge bg-danger fs-6 ms-2">
                            <i class="fas fa-exclamation-triangle me-1"></i>En retard
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Informations générales -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations générales</h5>
                    @if(Auth::user()->isAdmin() || (Auth::user()->isCommercial() && $chantier->commercial_id == Auth::id()))
                        <a href="{{ route('chantiers.edit', $chantier) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Client :</strong> {{ $chantier->client->name }}</p>
                            <p><strong>Commercial :</strong> {{ $chantier->commercial->name }}</p>
                            @if($chantier->budget)
                                <p><strong>Budget :</strong> {{ number_format($chantier->budget, 2, ',', ' ') }} €</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date de début :</strong> 
                                {{ $chantier->date_debut ? $chantier->date_debut->format('d/m/Y') : 'Non définie' }}
                            </p>
                            <p><strong>Date de fin prévue :</strong> 
                                {{ $chantier->date_fin_prevue ? $chantier->date_fin_prevue->format('d/m/Y') : 'Non définie' }}
                            </p>
                            @if($chantier->date_fin_effective)
                                <p><strong>Date de fin réelle :</strong> 
                                    {{ $chantier->date_fin_effective->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Barre de progression globale -->
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Avancement global</h6>
                            <span class="badge bg-info">{{ number_format($chantier->avancement_global, 0) }}%</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar {{ $chantier->avancement_global == 100 ? 'bg-success' : '' }}" 
                                 role="progressbar" 
                                 style="width: {{ $chantier->avancement_global }}%">
                                {{ number_format($chantier->avancement_global, 0) }}%
                            </div>
                        </div>
                    </div>
                    
                    @if($chantier->notes && (Auth::user()->isAdmin() || Auth::user()->isCommercial()))
                        <div class="mt-3">
                            <h6><i class="fas fa-sticky-note me-2"></i>Notes internes</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $chantier->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Étapes -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-tasks me-2"></i>Étapes du projet ({{ $chantier->etapes->count() }})
                    </h5>
                    @if(Auth::user()->isAdmin() || (Auth::user()->isCommercial() && $chantier->commercial_id == Auth::id()))
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addEtapeModal">
                            <i class="fas fa-plus me-1"></i>Ajouter
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if($chantier->etapes->count() > 0)
                        <div class="accordion" id="etapesAccordion">
                            @foreach($chantier->etapes as $etape)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $etape->id }}">
                                        <button class="accordion-button collapsed d-flex justify-content-between align-items-center" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapse{{ $etape->id }}">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <span class="badge bg-secondary me-2">{{ $etape->ordre }}</span>
                                                <span class="me-auto">{{ $etape->nom }}</span>
                                                @if($etape->terminee)
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                @endif
                                                @if($etape->isEnRetard())
                                                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                                @endif
                                                <span class="badge bg-{{ $etape->pourcentage == 100 ? 'success' : 'info' }} me-2">
                                                    {{ $etape->pourcentage }}%
                                                </span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $etape->id }}" 
                                         class="accordion-collapse collapse" 
                                         data-bs-parent="#etapesAccordion">
                                        <div class="accordion-body">
                                            @if($etape->description)
                                                <p>{{ $etape->description }}</p>
                                            @endif
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <small class="text-muted">Date début</small><br>
                                                    {{ $etape->date_debut ? $etape->date_debut->format('d/m/Y') : 'Non définie' }}
                                                </div>
                                                <div class="col-md-4">
                                                    <small class="text-muted">Date fin prévue</small><br>
                                                    {{ $etape->date_fin_prevue ? $etape->date_fin_prevue->format('d/m/Y') : 'Non définie' }}
                                                </div>
                                                <div class="col-md-4">
                                                    <small class="text-muted">Date fin effective</small><br>
                                                    {{ $etape->date_fin_effective ? $etape->date_fin_effective->format('d/m/Y') : '-' }}
                                                </div>
                                            </div>
                                            
                                            <div class="progress mb-3" style="height: 20px;">
                                                <div class="progress-bar {{ $etape->pourcentage == 100 ? 'bg-success' : '' }}" 
                                                     style="width: {{ $etape->pourcentage }}%">
                                                    {{ $etape->pourcentage }}%
                                                </div>
                                            </div>
                                            
                                            @if($etape->notes)
                                                <div class="bg-light p-2 rounded mb-3">
                                                    <small class="text-muted">Notes :</small><br>
                                                    {{ $etape->notes }}
                                                </div>
                                            @endif
                                            
                                            @if(Auth::user()->isAdmin() || (Auth::user()->isCommercial() && $chantier->commercial_id == Auth::id()))
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary edit-etape" 
                                                            data-etape-id="{{ $etape->id }}"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editEtapeModal">
                                                        <i class="fas fa-edit"></i> Modifier
                                                    </button>
                                                    <form method="POST" 
                                                          action="{{ route('etapes.destroy', [$chantier, $etape]) }}" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Confirmer la suppression ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger">
                                                            <i class="fas fa-trash"></i> Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">Aucune étape définie pour ce chantier.</p>
                    @endif
                </div>
            </div>

            <!-- Commentaires -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Commentaires</h5>
                </div>
                <div class="card-body">
                    <!-- Formulaire d'ajout de commentaire -->
                    <form method="POST" action="{{ route('commentaires.store', $chantier) }}" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control" 
                                      name="contenu" 
                                      rows="3" 
                                      placeholder="Ajouter un commentaire..." 
                                      required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-paper-plane me-1"></i>Envoyer
                        </button>
                    </form>
                    
                    <!-- Liste des commentaires -->
                    @if($chantier->commentaires->count() > 0)
                        @foreach($chantier->commentaires as $commentaire)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $commentaire->user->name }}</strong>
                                    <small class="text-muted">{{ $commentaire->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">{{ $commentaire->contenu }}</p>
                                @if(Auth::id() == $commentaire->user_id || Auth::user()->isAdmin())
                                    <form method="POST" action="{{ route('commentaires.destroy', $commentaire) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link btn-sm text-danger p-0" onclick="return confirm('Supprimer ce commentaire ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">Aucun commentaire pour le moment.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="col-lg-4">
            <!-- Actions rapides -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(Auth::user()->isAdmin() || (Auth::user()->isCommercial() && $chantier->commercial_id == Auth::id()))
                            <a href="{{ route('chantiers.edit', $chantier) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i>Modifier le chantier
                            </a>
                            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                                <i class="fas fa-upload me-2"></i>Ajouter des documents
                            </button>
                        @endif
                        
                        <a href="{{ route('chantiers.export', $chantier) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-file-pdf me-2"></i>Exporter en PDF
                        </a>
                        
                        @if(Auth::user()->isAdmin())
                            <form method="POST" action="{{ route('chantiers.destroy', $chantier) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce chantier ? Cette action est irréversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-trash me-2"></i>Supprimer le chantier
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-folder me-2"></i>Documents ({{ $chantier->documents->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($chantier->documents->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($chantier->documents->take(5) as $document)
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div class="flex-grow-1">
                                        <i class="{{ $document->getIconeType() }} me-2"></i>
                                        <a href="{{ route('documents.download', $document) }}" class="text-decoration-none">
                                            {{ Str::limit($document->nom_original, 25) }}
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            {{ $document->getTailleFormatee() }} - {{ $document->created_at->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    @if(Auth::user()->isAdmin() || (Auth::user()->isCommercial() && $chantier->commercial_id == Auth::id()))
                                        <form method="POST" action="{{ route('documents.destroy', $document) }}" class="ms-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link btn-sm text-danger p-0" onclick="return confirm('Supprimer ce document ?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if($chantier->documents->count() > 5)
                            <div class="text-center mt-3">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#allDocumentsModal">
                                    Voir tous les documents
                                </button>
                            </div>
                        @endif
                    @else
                        <p class="text-muted text-center mb-0">Aucun document</p>
                    @endif
                </div>
            </div>

            <!-- Contacts -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-address-book me-2"></i>Contacts</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Client</h6>
                        <p class="mb-0">{{ $chantier->client->name }}</p>
                        @if($chantier->client->email)
                            <small><i class="fas fa-envelope me-1"></i>{{ $chantier->client->email }}</small><br>
                        @endif
                        @if($chantier->client->telephone)
                            <small><i class="fas fa-phone me-1"></i>{{ $chantier->client->telephone }}</small>
                        @endif
                    </div>
                    
                    <div>
                        <h6 class="text-muted mb-1">Commercial</h6>
                        <p class="mb-0">{{ $chantier->commercial->name }}</p>
                        @if($chantier->commercial->email)
                            <small><i class="fas fa-envelope me-1"></i>{{ $chantier->commercial->email }}</small><br>
                        @endif
                        @if($chantier->commercial->telephone)
                            <small><i class="fas fa-phone me-1"></i>{{ $chantier->commercial->telephone }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajout Étape -->
<div class="modal fade" id="addEtapeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('etapes.store', $chantier) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une étape</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom de l'étape <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ordre <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="ordre" value="{{ $chantier->etapes->count() + 1 }}" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Pourcentage initial</label>
                                <input type="number" class="form-control" name="pourcentage" value="0" min="0" max="100">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date début</label>
                                <input type="date" class="form-control" name="date_debut">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date fin prévue</label>
                                <input type="date" class="form-control" name="date_fin_prevue">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ajout Document -->
<div class="modal fade" id="addDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('documents.store', $chantier) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter des documents</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Fichiers <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="fichiers[]" multiple required>
                        <small class="text-muted">Max 10 fichiers, 10 MB par fichier. Formats: jpg, png, pdf, doc, xls</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type de document <span class="text-danger">*</span></label>
                        <select class="form-select" name="type" required>
                            <option value="image">Image/Photo</option>
                            <option value="document">Document</option>
                            <option value="plan">Plan</option>
                            <option value="facture">Facture</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Uploader</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Gestion de l'édition des étapes (à implémenter)
document.addEventListener('DOMContentLoaded', function() {
    // Actualisation automatique de l'avancement
    setInterval(function() {
        // Implémenter un appel AJAX pour rafraîchir l'avancement
    }, 30000); // Toutes les 30 secondes
});
</script>
@endsection