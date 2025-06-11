@extends('layouts.app')

@section('title', 'Modifier un utilisateur')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="card">
        <div class="card-header flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
            <h4 class="text-xl font-semibold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                Modifier l'utilisateur : {{ $user->name }}
            </h4>
            <span class="badge {{ $user->active ? 'badge-success' : 'badge-secondary' }}">
                {{ $user->active ? 'Actif' : 'Inactif' }}
            </span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" id="editUserForm">
                @csrf
                @method('PUT')
                
                <!-- Informations sur l'utilisateur -->
                <div class="alert alert-info mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                                </svg>
                                <span class="font-medium">Inscrit le :</span>
                                <span class="ml-1">{{ $user->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                <span class="font-medium">Dernière modification :</span>
                                <span class="ml-1">{{ $user->updated_at->format('d/m/Y à H:i') }}</span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            @if($user->role == 'client')
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21l.75-5.25L9 8.25l3-3 8.25 8.25-3 3-7.75 7L9 21.75 2.25 21z" />
                                    </svg>
                                    <span class="font-medium">Nombre de chantiers :</span>
                                    <span class="ml-1">{{ $user->chantiersClient->count() }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                                    </svg>
                                    <span class="font-medium">Chantiers actifs :</span>
                                    <span class="ml-1">{{ $user->chantiersClient->where('statut', 'en_cours')->count() }}</span>
                                </div>
                            @elseif($user->role == 'commercial')
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3A2.25 2.25 0 008.25 5.25v3.093m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                    <span class="font-medium">Chantiers gérés :</span>
                                    <span class="ml-1">{{ $user->chantiersCommercial->count() }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                                    </svg>
                                    <span class="font-medium">Chantiers actifs :</span>
                                    <span class="ml-1">{{ $user->chantiersCommercial->where('statut', 'en_cours')->count() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
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
                                       value="{{ old('name', $user->name) }}" 
                                       required>
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
                                       value="{{ old('email', $user->email) }}" 
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
                                       value="{{ old('telephone', $user->telephone) }}" 
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
                                          rows="3">{{ old('adresse', $user->adresse) }}</textarea>
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
                                    <div class="flex items-center mt-2 p-2 bg-yellow-50 rounded-md">
                                        <svg class="w-4 h-4 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                        </svg>
                                        <small class="text-yellow-800">
                                            Le rôle ne peut pas être modifié car l'utilisateur a des chantiers associés.
                                        </small>
                                    </div>
                                @endif
                                @error('role')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="form-label">Nouveau mot de passe</label>
                                <div class="relative">
                                    <input type="password" 
                                           class="form-input pr-10 @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                                           id="password" 
                                           name="password">
                                    <button type="button" 
                                            class="absolute inset-y-0 right-0 flex items-center pr-3"
                                            onclick="togglePassword('password')">
                                        <svg class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                </div>
                                <small class="text-gray-500">Laissez vide pour conserver le mot de passe actuel</small>
                                @error('password')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="form-label">
                                    Confirmer le nouveau mot de passe
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           class="form-input pr-10" 
                                           id="password_confirmation" 
                                           name="password_confirmation">
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
                                           {{ old('active', $user->active) ? 'checked' : '' }}
                                           {{ $user->id == Auth::id() ? 'disabled' : '' }}>
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
                            @if($user->id == Auth::id())
                                <div class="flex items-center p-3 bg-yellow-50 rounded-md">
                                    <svg class="w-4 h-4 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                    <small class="text-yellow-800">
                                        Vous ne pouvez pas désactiver votre propre compte.
                                    </small>
                                </div>
                            @else
                                <small class="text-gray-500">Les comptes inactifs ne peuvent pas se connecter</small>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Historique des chantiers -->
                @if($user->chantiersClient->count() > 0 || $user->chantiersCommercial->count() > 0)
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h5 class="text-lg font-medium text-gray-900 mb-6 pb-2 border-b border-gray-200">
                            Chantiers associés
                        </h5>
                        <div class="overflow-x-auto">
                            <table class="table">
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
                                            <td class="font-medium">{{ $chantier->titre }}</td>
                                            <td>
                                                @switch($chantier->statut)
                                                    @case('planifie')
                                                        <span class="badge badge-secondary">Planifié</span>
                                                        @break
                                                    @case('en_cours')
                                                        <span class="badge badge-warning">En cours</span>
                                                        @break
                                                    @case('termine')
                                                        <span class="badge badge-success">Terminé</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-secondary">{{ ucfirst($chantier->statut) }}</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $chantier->date_debut ? $chantier->date_debut->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                <div class="flex items-center">
                                                    <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                                        <div class="bg-primary-600 h-2 rounded-full" 
                                                             style="width: {{ $chantier->avancement_global }}%"></div>
                                                    </div>
                                                    <span class="text-sm font-medium text-gray-700">
                                                        {{ number_format($chantier->avancement_global, 0) }}%
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('chantiers.show', $chantier) }}" 
                                                   class="text-primary-600 hover:text-primary-800"
                                                   title="Voir le chantier">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                
                <!-- Boutons -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-8 pt-6 border-t border-gray-200 space-y-3 sm:space-y-0">
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Retour
                    </a>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
                            </svg>
                            Enregistrer les modifications
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

// Validation du formulaire
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirmation').value;
    
    if (password && password !== passwordConfirm) {
        e.preventDefault();
        
        // Afficher l'erreur visuellement
        const confirmField = document.getElementById('password_confirmation');
        confirmField.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500');
        
        // Créer un message d'erreur s'il n'existe pas
        let errorDiv = confirmField.parentNode.parentNode.querySelector('.form-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'form-error';
            confirmField.parentNode.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = 'Les mots de passe ne correspondent pas !';
        
        // Scroll vers l'erreur
        confirmField.scrollIntoView({ behavior: 'smooth', block: 'center' });
</script>
@endsection
<script>
        confirmField.focus();
        
        return false;
    }
    
    if (password && password.length < 8) {
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
        Enregistrement en cours...
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
        const errorDiv = this.parentNode.parentNode.querySelector('.form-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
});

// Confirmation pour les changements sensibles
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const activeCheckbox = document.getElementById('active');
    
    // Avertir pour le changement de rôle
    if (roleSelect && !roleSelect.disabled) {
        const originalRole = roleSelect.value;
        roleSelect.addEventListener('change', function() {
            if (this.value !== originalRole) {
                if (!confirm('Attention : Changer le rôle peut affecter les permissions de l\'utilisateur. Continuer ?')) {
                    this.value = originalRole;
                }
            }
        });
    }
    
    // Avertir pour la désactivation
    if (activeCheckbox && !activeCheckbox.disabled) {
        activeCheckbox.addEventListener('change', function() {
            if (!this.checked) {
                if (!confirm('Attention : Désactiver ce compte empêchera l\'utilisateur de se connecter. Continuer ?')) {
                    this.checked = true;
                }
            }
        });
    }
});

// Animation pour les barres de progression dans le tableau
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.bg-primary-600');
    progressBars.forEach((bar, index) => {
        setTimeout(() => {
            bar.style.transition = 'width 1s ease-in-out';
            bar.style.width = bar.style.width; // Redéclencher l'animation
        }, index * 100);
    });
});
</script>