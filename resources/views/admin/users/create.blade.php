@extends('layouts.app')

@section('title', 'Créer un utilisateur')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="card">
        <div class="card-header">
            <h4 class="text-xl font-semibold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg>
                Créer un nouvel utilisateur
            </h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}" id="createUserForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Informations personnelles -->
                    <div>
                        <h5 class="text-lg font-medium text-gray-900 mb-6 pb-2 border-b border-gray-200">
                            Informations personnelles
                        </h5>
                        
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="form-label">
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="form-input @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required
                                       autofocus>
                                @error('name')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="form-label">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       class="form-input @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="tel" 
                                       class="form-input @error('telephone') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                       id="telephone" 
                                       name="telephone" 
                                       value="{{ old('telephone') }}" 
                                       placeholder="06 12 34 56 78">
                                @error('telephone')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="adresse" class="form-label">Adresse</label>
                                <textarea class="form-input @error('adresse') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                          id="adresse" 
                                          name="adresse" 
                                          rows="3">{{ old('adresse') }}</textarea>
                                @error('adresse')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paramètres du compte -->
                    <div>
                        <h5 class="text-lg font-medium text-gray-900 mb-6 pb-2 border-b border-gray-200">
                            Paramètres du compte
                        </h5>
                        
                        <div class="space-y-6">
                            <div>
                                <label for="role" class="form-label">
                                    Rôle <span class="text-red-500">*</span>
                                </label>
                                <select class="form-select @error('role') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
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
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password" class="form-label">
                                    Mot de passe <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           class="form-input pr-10 @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                           id="password" 
                                           name="password" 
                                           required>
                                    <button type="button" 
                                            class="absolute inset-y-0 right-0 flex items-center pr-3"
                                            onclick="togglePassword('password')">
                                        <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                </div>
                                <small class="text-gray-500">Minimum 8 caractères</small>
                                @error('password')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="form-label">
                                    Confirmer le mot de passe <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           class="form-input pr-10" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required>
                                    <button type="button" 
                                            class="absolute inset-y-0 right-0 flex items-center pr-3"
                                            onclick="togglePassword('password_confirmation')">
                                        <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           class="form-checkbox" 
                                           id="active" 
                                           name="active" 
                                           value="1" 
                                           {{ old('active', true) ? 'checked' : '' }}>
                                    <label for="active" class="ml-2 text-sm font-medium text-gray-700">
                                        Compte actif
                                    </label>
                                </div>
                                <button type="button" 
                                        class="text-sm text-primary-600 hover:text-primary-800"
                                        onclick="generatePassword()">
                                    Générer un mot de passe
                                </button>
                            </div>
                            <small class="text-gray-500 -mt-2 block">Les comptes inactifs ne peuvent pas se connecter</small>
                            
                            <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                                <input type="checkbox" 
                                       class="form-checkbox" 
                                       id="send_welcome_email" 
                                       name="send_welcome_email" 
                                       value="1" 
                                       {{ old('send_welcome_email', true) ? 'checked' : '' }}>
                                <label for="send_welcome_email" class="ml-2 text-sm font-medium text-gray-700">
                                    Envoyer un email de bienvenue
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Options supplémentaires pour les clients -->
                <div id="clientOptions" class="hidden mt-8">
                    <div class="border-t border-gray-200 pt-8">
                        <h5 class="text-lg font-medium text-gray-900 mb-6 pb-2 border-b border-gray-200">
                            Options client
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="commercial_id" class="form-label">Commercial assigné</label>
                                <select class="form-select" id="commercial_id" name="commercial_id">
                                    <option value="">Aucun (à assigner plus tard)</option>
                                    @foreach(App\Models\User::where('role', 'commercial')->orderBy('name')->get() as $commercial)
                                        <option value="{{ $commercial->id }}">{{ $commercial->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="notes_client" class="form-label">Notes internes</label>
                                <textarea class="form-input" 
                                          id="notes_client" 
                                          name="notes_client" 
                                          rows="2" 
                                          placeholder="Notes visibles uniquement par l'équipe"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Boutons -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-8 pt-6 border-t border-gray-200 space-y-3 sm:space-y-0">
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Retour
                    </a>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <button type="reset" class="btn btn-outline">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Réinitialiser
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
                            </svg>
                            Créer l'utilisateur
                        </button>
                    </div>
                </div>
            </form>
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
    const icon = button.querySelector('svg');
    
    if (field.type === 'password') {
        field.type = 'text';
        // Icône eye-slash
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 11-4.243-4.243m4.242 4.242L9.88 9.88" />
        `;
    } else {
        field.type = 'password';
        // Icône eye
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        `;
    }
}

// Afficher les options client si le rôle client est sélectionné
document.getElementById('role').addEventListener('change', function() {
    const clientOptions = document.getElementById('clientOptions');
    if (this.value === 'client') {
        clientOptions.classList.remove('hidden');
        clientOptions.classList.add('animate-fade-in');
    } else {
        clientOptions.classList.add('hidden');
        clientOptions.classList.remove('animate-fade-in');
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
    
    const passwordField = document.getElementById('password');
    const confirmField = document.getElementById('password_confirmation');
    
    passwordField.value = password;
    confirmField.value = password;
    
    // Afficher les mots de passe temporairement
    passwordField.type = 'text';
    confirmField.type = 'text';
    
    // Mettre à jour les icônes
    togglePasswordIcon('password', true);
    togglePasswordIcon('password_confirmation', true);
    
    // Notification visuelle
    const button = document.querySelector('[onclick="generatePassword()"]');
    const originalText = button.textContent;
    button.textContent = '✓ Mot de passe généré';
    button.classList.add('text-success-600');
    
    setTimeout(() => {
        button.textContent = originalText;
        button.classList.remove('text-success-600');
    }, 2000);
}

// Helper pour mettre à jour l'icône
function togglePasswordIcon(fieldId, isVisible) {
    const field = document.getElementById(fieldId);
    const icon = field.nextElementSibling.querySelector('svg');
    
    if (isVisible) {
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 11-4.243-4.243m4.242 4.242L9.88 9.88" />
        `;
    } else {
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        `;
    }
}

// Validation du formulaire côté client
document.getElementById('createUserForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirmation').value;
    
    if (password !== passwordConfirm) {
        e.preventDefault();
        
        // Afficher l'erreur visuellement
        const confirmField = document.getElementById('password_confirmation');
        confirmField.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
        
        // Créer un message d'erreur s'il n'existe pas
        let errorDiv = confirmField.parentNode.querySelector('.form-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'form-error';
            confirmField.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = 'Les mots de passe ne correspondent pas !';
        
        // Scroll vers l'erreur
        confirmField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        confirmField.focus();
        
        return false;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        
        const passwordField = document.getElementById('password');
        passwordField.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
        
        let errorDiv = passwordField.parentNode.parentNode.querySelector('.form-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'form-error';
            passwordField.parentNode.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = 'Le mot de passe doit contenir au moins 8 caractères !';
        
        passwordField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        passwordField.focus();
        
        return false;
    }
    
    // Animation de soumission
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.classList.add('loading');
    submitBtn.innerHTML = `
        <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Création en cours...
    `;
});

// Validation en temps réel
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && password !== confirmPassword) {
        this.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
    } else {
        this.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
        const errorDiv = this.parentNode.querySelector('.form-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
});
</script>
@endsection