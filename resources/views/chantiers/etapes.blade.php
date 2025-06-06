@extends('layouts.app')

@section('title', 'Gestion des étapes - ' . $chantier->titre)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1>Gestion des étapes</h1>
                    <p class="text-muted mb-0">{{ $chantier->titre }}</p>
                </div>
                <a href="{{ route('chantiers.show', $chantier) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour au chantier
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire d'ajout rapide -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Ajouter une nouvelle étape</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('etapes.store', $chantier) }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nom de l'étape <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Ordre <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('ordre') is-invalid @enderror" 
                               name="ordre" value="{{ $chantier->etapes->count() + 1 }}" min="1" required>
                        @error('ordre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Pourcentage</label>
                        <input type="number" class="form-control @error('pourcentage') is-invalid @enderror" 
                               name="pourcentage" value="0" min="0" max="100">
                        @error('pourcentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" rows="2"></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Ajouter l'étape
                </button>
            </form>
        </div>
    </div>

    <!-- Liste des étapes -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Étapes existantes ({{ $chantier->etapes->count() }})
            </h5>
        </div>
        <div class="card-body">
            @if($chantier->etapes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id="etapesTable">
                        <thead>
                            <tr>
                                <th width="60">Ordre</th>
                                <th>Nom</th>
                                <th width="150">Avancement</th>
                                <th width="120">Statut</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chantier->etapes as $etape)
                                <tr data-etape-id="{{ $etape->id }}">
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $etape->ordre }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $etape->nom }}</strong>
                                        @if($etape->description)
                                            <br><small class="text-muted">{{ Str::limit($etape->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                                <div class="progress-bar {{ $etape->pourcentage == 100 ? 'bg-success' : '' }}" 
                                                     style="width: {{ $etape->pourcentage }}%">
                                                    {{ $etape->pourcentage }}%
                                                </div>
                                            </div>
                                            <button class="btn btn-sm btn-outline-secondary update-progress" 
                                                    data-etape-id="{{ $etape->id }}"
                                                    data-current="{{ $etape->pourcentage }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($etape->terminee)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Terminée
                                            </span>
                                        @elseif($etape->isEnRetard())
                                            <span class="badge bg-danger">
                                                <i class="fas fa-exclamation-triangle"></i> En retard
                                            </span>
                                        @else
                                            <span class="badge bg-primary">En cours</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary edit-etape" 
                                                    data-etape="{{ json_encode($etape) }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-info move-up" 
                                                    data-etape-id="{{ $etape->id }}"
                                                    {{ $etape->ordre == 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-arrow-up"></i>
                                            </button>
                                            <button class="btn btn-outline-info move-down" 
                                                    data-etape-id="{{ $etape->id }}"
                                                    {{ $etape->ordre == $chantier->etapes->count() ? 'disabled' : '' }}>
                                                <i class="fas fa-arrow-down"></i>
                                            </button>
                                            <form method="POST" 
                                                  action="{{ route('etapes.destroy', [$chantier, $etape]) }}" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Supprimer cette étape ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Résumé -->
                <div class="card bg-light mt-3">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h5 class="mb-0">{{ $chantier->etapes->count() }}</h5>
                                <small class="text-muted">Étapes totales</small>
                            </div>
                            <div class="col-md-3">
                                <h5 class="mb-0 text-success">{{ $chantier->etapes->where('terminee', true)->count() }}</h5>
                                <small class="text-muted">Terminées</small>
                            </div>
                            <div class="col-md-3">
                                <h5 class="mb-0 text-primary">{{ $chantier->etapes->where('terminee', false)->count() }}</h5>
                                <small class="text-muted">En cours</small>
                            </div>
                            <div class="col-md-3">
                                <h5 class="mb-0">{{ number_format($chantier->avancement_global, 0) }}%</h5>
                                <small class="text-muted">Avancement global</small>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucune étape définie pour ce chantier. Utilisez le formulaire ci-dessus pour en ajouter.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Edition Étape -->
<div class="modal fade" id="editEtapeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="editEtapeForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Modifier l'étape</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Nom de l'étape <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nom" id="edit_nom" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Ordre <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="ordre" id="edit_ordre" min="1" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="2"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Date début</label>
                                <input type="date" class="form-control" name="date_debut" id="edit_date_debut">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Date fin prévue</label>
                                <input type="date" class="form-control" name="date_fin_prevue" id="edit_date_fin_prevue">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Date fin effective</label>
                                <input type="date" class="form-control" name="date_fin_effective" id="edit_date_fin_effective">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Pourcentage d'avancement</label>
                                <input type="range" class="form-range" name="pourcentage" id="edit_pourcentage" 
                                       min="0" max="100" step="5" oninput="updateProgressValue(this.value)">
                                <div class="text-center">
                                    <span id="progressValue" class="badge bg-info">0%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="terminee" id="edit_terminee" value="1">
                                    <label class="form-check-label" for="edit_terminee">
                                        Marquer comme terminée
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" id="edit_notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Progression Rapide -->
<div class="modal fade" id="progressModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST" id="progressForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Modifier l'avancement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pourcentage d'avancement</label>
                        <input type="range" class="form-range" name="pourcentage" id="quick_pourcentage" 
                               min="0" max="100" step="5">
                        <div class="text-center mt-2">
                            <span id="quickProgressValue" class="badge bg-info fs-5">0%</span>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-secondary set-progress" data-value="0">0%</button>
                        <button type="button" class="btn btn-outline-secondary set-progress" data-value="25">25%</button>
                        <button type="button" class="btn btn-outline-secondary set-progress" data-value="50">50%</button>
                        <button type="button" class="btn btn-outline-secondary set-progress" data-value="75">75%</button>
                        <button type="button" class="btn btn-outline-success set-progress" data-value="100">100%</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editModal = new bootstrap.Modal(document.getElementById('editEtapeModal'));
    const progressModal = new bootstrap.Modal(document.getElementById('progressModal'));
    
    // Edition d'une étape
    document.querySelectorAll('.edit-etape').forEach(btn => {
        btn.addEventListener('click', function() {
            const etape = JSON.parse(this.dataset.etape);
            const form = document.getElementById('editEtapeForm');
            form.action = `/chantiers/{{ $chantier->id }}/etapes/${etape.id}`;
            
            document.getElementById('edit_nom').value = etape.nom;
            document.getElementById('edit_ordre').value = etape.ordre;
            document.getElementById('edit_description').value = etape.description || '';
            document.getElementById('edit_date_debut').value = etape.date_debut ? etape.date_debut.split(' ')[0] : '';
            document.getElementById('edit_date_fin_prevue').value = etape.date_fin_prevue ? etape.date_fin_prevue.split(' ')[0] : '';
            document.getElementById('edit_date_fin_effective').value = etape.date_fin_effective ? etape.date_fin_effective.split(' ')[0] : '';
            document.getElementById('edit_pourcentage').value = etape.pourcentage;
            document.getElementById('edit_terminee').checked = etape.terminee;
            document.getElementById('edit_notes').value = etape.notes || '';
            
            updateProgressValue(etape.pourcentage);
            editModal.show();
        });
    });
    
    // Modification rapide de la progression
    document.querySelectorAll('.update-progress').forEach(btn => {
        btn.addEventListener('click', function() {
            const etapeId = this.dataset.etapeId;
            const current = this.dataset.current;
            const form = document.getElementById('progressForm');
            form.action = `/chantiers/{{ $chantier->id }}/etapes/${etapeId}`;
            
            document.getElementById('quick_pourcentage').value = current;
            document.getElementById('quickProgressValue').textContent = current + '%';
            
            progressModal.show();
        });
    });
    
    // Boutons de progression rapide
    document.querySelectorAll('.set-progress').forEach(btn => {
        btn.addEventListener('click', function() {
            const value = this.dataset.value;
            document.getElementById('quick_pourcentage').value = value;
            document.getElementById('quickProgressValue').textContent = value + '%';
        });
    });
    
    // Mise à jour de l'affichage de la progression
    document.getElementById('quick_pourcentage')?.addEventListener('input', function() {
        document.getElementById('quickProgressValue').textContent = this.value + '%';
    });
    
    // Si terminée est cochée, mettre la progression à 100%
    document.getElementById('edit_terminee')?.addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('edit_pourcentage').value = 100;
            updateProgressValue(100);
        }
    });
});

function updateProgressValue(value) {
    document.getElementById('progressValue').textContent = value + '%';
}

// Réorganisation des étapes (à implémenter avec AJAX)
// TODO: Implémenter le drag & drop ou les boutons up/down
</script>
@endsection