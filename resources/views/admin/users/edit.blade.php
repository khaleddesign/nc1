@extends('layouts.app')

@section('title', 'Modifier un utilisateur')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>Modifier l'utilisateur : {{ $user->name }}
                    </h4>
                    <span class="badge {{ $user->active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $user->active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" id="editUserForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Informations sur l'utilisateur -->
                        <div class="alert alert-info mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Inscrit le :</strong> {{ $user->created_at->format('d/m/Y à H:i') }}<br>
                                    <strong>Dernière modification :</strong> {{ $user->updated_at->format('d/m/Y à H:i') }}
                                </div>
                                <div class="col-md-6">
                                    @if($user->role == 'client')
                                        <strong>Nombre de chantiers :</strong> {{ $user->chantiersClient->count() }}<br>
                                        <strong>Chantiers actifs :</strong> {{ $user->chantiersClient->where('statut', 'en_cours')->count() }}
                                    @elseif($user->role == 'commercial')
                                        <strong>Chantiers gérés :</strong> {{ $user->chantiersCommercial->count() }}<br>
                                        <strong>Chantiers actifs :</strong> {{ $user->chantiersCommercial->where('statut', 'en_cours')->count() }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Informations personnelles -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Informations personnelles</h5>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        Nom complet <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="tel" 
                                           class="form-control @error('telephone') is-invalid @enderror" 
                                           id="telephone" 
                                           name="telephone" 
                                           value="{{ old('telephone', $user->telephone) }}" 
                                           placeholder="06 12 34 56 78">
                                    @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                              id="adresse" 
                                              name="adresse" 
                                              rows="3">{{ old('adresse', $user->adresse) }}</textarea>
                                    @error('adresse')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Paramètres du compte -->
                            <div class="col-md-6">
                                <h5 class="mb-3">Paramètres du compte</h5>
                                
                                <div class="mb-3">
                                    <label for="role" class="form-label">
                                        Rôle <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                            id="role" 
                                            name="role" 
                                            required
                                            {{ $user->chantiersClient->count() > 0 || $user->chantiersCommercial->count() > 0 ? 'disabled' : '' }}>
                                        <option value="client" {{ old('role', $user->role) == 'client' ? 'selected' : '' }}>
                                            Client
                                        </option>
                                        <option value="commercial" {{ old('role', $user->role) == 'commercial' ? 'selected' : '' }}>
                                            Commercial
                                        </option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                            Administrateur
                                        </option>
                                    </select>
                                    @if($user->chantiersClient->count() > 0 || $user->chantiersCommercial->count() > 0)
                                        <small class="text-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Le rôle ne peut pas être modifié car l'utilisateur a des chantiers associés.
                                        </small>
                                    @endif
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Nouveau mot de passe</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Laissez vide pour conserver le mot de passe actuel</small>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        Confirmer le nouveau mot de passe
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="active" 
                                               name="active" 
                                               value="1" 
                                               {{ old('active', $user->active) ? 'checked' : '' }}
                                               {{ $user->id == Auth::id() ? 'disabled' : '' }}>
                                        <label class="form-check-label" for="active">
                                            Compte actif
                                        </label>
                                    </div>
                                    @if($user->id == Auth::id())
                                        <small class="text-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Vous ne pouvez pas désactiver votre propre compte.
                                        </small>
                                    @else
                                        <small class="text-muted">Les comptes inactifs ne peuvent pas se connecter</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Historique des chantiers -->
                        @if($user->chantiersClient->count() > 0 || $user->chantiersCommercial->count() > 0)
                            <hr>
                            <h5 class="mb-3">Chantiers associés</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Titre</th>
                                            <th>Statut</th>
                                            <th>Date début</th>
                                            <th>Avancement</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->role == 'client' ? $user->chantiersClient : $user->chantiersCommercial as $chantier)
                                            <tr>
                                                <td>{{ $chantier->titre }}</td>
                                                <td>
                                                    <span class="badge {{ $chantier->getStatutBadgeClass() }}">
                                                        {{ $chantier->getStatutTexte() }}
                                                    </span>
                                                </td>
                                                <td>{{ $chantier->date_debut ? $chantier->date_debut->format('d/m/Y') : '-' }}</td>
                                                <td>{{ number_format($chantier->avancement_global, 0) }}%</td>
                                                <td>
                                                    <a href="{{ route('chantiers.show', $chantier) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        
                        <!-- Boutons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                            </div>
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
// Afficher/masquer le mot de passe
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Validation du formulaire
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirmation').value;
    
    if (password && password !== passwordConfirm) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas !');
        return false;
    }
    
    if (password && password.length < 8) {
        e.preventDefault();
        alert('Le mot de passe doit contenir au moins 8 caractères !');
        return false;
    }
});
</script>
@endsection