@extends('layouts.app')

@section('title', 'Créer un utilisateur')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>Créer un nouvel utilisateur
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}" id="createUserForm">
                        @csrf
                        
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
                                           value="{{ old('name') }}" 
                                           required
                                           autofocus>
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
                                           value="{{ old('email') }}" 
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
                                           value="{{ old('telephone') }}" 
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
                                              rows="3">{{ old('adresse') }}</textarea>
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
                                            required>
                                        <option value="">Sélectionner un rôle</option>
                                        <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>
                                            Client - Peut voir ses chantiers
                                        </option>
                                        <option value="commercial" {{ old('role') == 'commercial' ? 'selected' : '' }}>
                                            Commercial - Peut gérer les chantiers
                                        </option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                            Administrateur - Accès complet
                                        </option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        Mot de passe <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Minimum 8 caractères</small>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        Confirmer le mot de passe <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               required>
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
                                               {{ old('active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="active">
                                            Compte actif
                                        </label>
                                    </div>
                                    <small class="text-muted">Les comptes inactifs ne peuvent pas se connecter</small>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="send_welcome_email" 
                                               name="send_welcome_email" 
                                               value="1" 
                                               {{ old('send_welcome_email', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="send_welcome_email">
                                            Envoyer un email de bienvenue
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Options supplémentaires pour les clients -->
                        <div id="clientOptions" style="display: none;">
                            <hr>
                            <h5 class="mb-3">Options client</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="commercial_id" class="form-label">Commercial assigné</label>
                                        <select class="form-select" id="commercial_id" name="commercial_id">
                                            <option value="">Aucun (à assigner plus tard)</option>
                                            @foreach(App\Models\User::where('role', 'commercial')->orderBy('name')->get() as $commercial)
                                                <option value="{{ $commercial->id }}">{{ $commercial->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="notes_client" class="form-label">Notes internes</label>
                                        <textarea class="form-control" 
                                                  id="notes_client" 
                                                  name="notes_client" 
                                                  rows="2" 
                                                  placeholder="Notes visibles uniquement par l'équipe"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Boutons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-undo me-2"></i>Réinitialiser
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Créer l'utilisateur
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

// Afficher les options client si le rôle client est sélectionné
document.getElementById('role').addEventListener('change', function() {
    const clientOptions = document.getElementById('clientOptions');
    if (this.value === 'client') {
        clientOptions.style.display = 'block';
    } else {
        clientOptions.style.display = 'none';
    }
});

// Générer un mot de passe aléatoire
function generatePassword() {
    const length = 12;
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
    let password = "";
    for (let i = 0; i < length; i++) {
        password += charset.charAt(Math.floor(Math.random() * charset.length));
    }
    
    document.getElementById('password').value = password;
    document.getElementById('password_confirmation').value = password;
    
    // Afficher les mots de passe
    document.getElementById('password').type = 'text';
    document.getElementById('password_confirmation').type = 'text';
}

// Validation du formulaire côté client
document.getElementById('createUserForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirmation').value;
    
    if (password !== passwordConfirm) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas !');
        return false;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        alert('Le mot de passe doit contenir au moins 8 caractères !');
        return false;
    }
});
</script>
@endsection