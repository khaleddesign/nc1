@extends('layouts.app')

@section('title', 'Nouveau chantier')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Créer un nouveau chantier
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('chantiers.store') }}">
                        @csrf
                        
                        <!-- Titre -->
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre du chantier <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('titre') is-invalid @enderror" 
                                   id="titre" 
                                   name="titre" 
                                   value="{{ old('titre') }}" 
                                   required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Client -->
                            <div class="col-md-6 mb-3">
                                <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                                <select class="form-select @error('client_id') is-invalid @enderror" 
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
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Commercial -->
                            <div class="col-md-6 mb-3">
                                <label for="commercial_id" class="form-label">Commercial responsable <span class="text-danger">*</span></label>
                                <select class="form-select @error('commercial_id') is-invalid @enderror" 
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
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Statut -->
                            <div class="col-md-6 mb-3">
                                <label for="statut" class="form-label">Statut initial <span class="text-danger">*</span></label>
                                <select class="form-select @error('statut') is-invalid @enderror" 
                                        id="statut" 
                                        name="statut" 
                                        required>
                                    <option value="planifie" {{ old('statut', 'planifie') == 'planifie' ? 'selected' : '' }}>
                                        Planifié
                                    </option>
                                    <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>
                                        En cours
                                    </option>
                                    <option value="termine" {{ old('statut') == 'termine' ? 'selected' : '' }}>
                                        Terminé
                                    </option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Budget -->
                            <div class="col-md-6 mb-3">
                                <label for="budget" class="form-label">Budget (€)</label>
                                <input type="number" 
                                       class="form-control @error('budget') is-invalid @enderror" 
                                       id="budget" 
                                       name="budget" 
                                       value="{{ old('budget') }}" 
                                       step="0.01" 
                                       min="0">
                                @error('budget')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Date début -->
                            <div class="col-md-6 mb-3">
                                <label for="date_debut" class="form-label">Date de début</label>
                                <input type="date" 
                                       class="form-control @error('date_debut') is-invalid @enderror" 
                                       id="date_debut" 
                                       name="date_debut" 
                                       value="{{ old('date_debut') }}">
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date fin prévue -->
                            <div class="col-md-6 mb-3">
                                <label for="date_fin_prevue" class="form-label">Date de fin prévue</label>
                                <input type="date" 
                                       class="form-control @error('date_fin_prevue') is-invalid @enderror" 
                                       id="date_fin_prevue" 
                                       name="date_fin_prevue" 
                                       value="{{ old('date_fin_prevue') }}">
                                @error('date_fin_prevue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes internes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Notes visibles uniquement par l'équipe">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('chantiers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Créer le chantier
                            </button>
                        </div>
                    </form>
                </div>
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
    
    dateDebut?.addEventListener('change', function() {
        if (dateFin) {
            dateFin.min = this.value;
            if (dateFin.value && dateFin.value < this.value) {
                dateFin.value = this.value;
            }
        }
    });
    
    // Auto-sélection du commercial actuel si c'est un commercial
    @if(Auth::user()->isCommercial())
        document.getElementById('commercial_id').value = '{{ Auth::id() }}';
    @endif
});
</script>
@endsection