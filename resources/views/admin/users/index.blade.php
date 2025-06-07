@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-users me-2"></i>Gestion des Utilisateurs</h1>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Nouvel Utilisateur
                </a>
            </div>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Rechercher par nom ou email..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">Tous les rôles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="commercial" {{ request('role') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                        <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Client</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="active" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Actifs</option>
                        <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactifs</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3>{{ $users->total() }}</h3>
                    <p class="mb-0">Total Utilisateurs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">{{ $users->where('role', 'client')->count() }}</h3>
                    <p class="mb-0">Clients</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning">{{ $users->where('role', 'commercial')->count() }}</h3>
                    <p class="mb-0">Commerciaux</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-danger">{{ $users->where('role', 'admin')->count() }}</h3>
                    <p class="mb-0">Admins</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Liste des utilisateurs -->
    <div class="card">
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Téléphone</th>
                                <th>Statut</th>
                                <th>Inscrit le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->chantiersClient->count() > 0)
                                            <br><small class="text-muted">
                                                {{ $user->chantiersClient->count() }} chantier(s)
                                            </small>
                                        @elseif($user->chantiersCommercial->count() > 0)
                                            <br><small class="text-muted">
                                                {{ $user->chantiersCommercial->count() }} chantier(s)
                                            </small>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @switch($user->role)
                                            @case('admin')
                                                <span class="badge bg-danger">Admin</span>
                                                @break
                                            @case('commercial')
                                                <span class="badge bg-warning">Commercial</span>
                                                @break
                                            @case('client')
                                                <span class="badge bg-primary">Client</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $user->telephone ?? '-' }}</td>
                                    <td>
                                        @if($user->active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="btn btn-outline-primary" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-outline-{{ $user->active ? 'warning' : 'success' }}"
                                                        title="{{ $user->active ? 'Désactiver' : 'Activer' }}">
                                                    <i class="fas fa-{{ $user->active ? 'ban' : 'check' }}"></i>
                                                </button>
                                            </form>
                                            
                                            @if($user->id !== Auth::id() && !($user->isAdmin() && App\Models\User::where('role', 'admin')->count() <= 1))
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5>Aucun utilisateur trouvé</h5>
                    <p class="text-muted">Modifiez vos critères de recherche ou créez un nouvel utilisateur.</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Actions en masse (optionnel) -->
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="mb-0">Actions rapides</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-outline-success w-100" onclick="exportUsers()">
                        <i class="fas fa-file-excel me-2"></i>Exporter en Excel
                    </button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-outline-danger w-100" onclick="cleanupInactiveUsers()">
                        <i class="fas fa-broom me-2"></i>Nettoyer les comptes inactifs
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function exportUsers() {
    // Ajouter les paramètres de recherche actuels
    const params = new URLSearchParams(window.location.search);
    params.append('export', 'excel');
    window.location.href = '{{ route("admin.users") }}?' + params.toString();
}

function cleanupInactiveUsers() {
    if (confirm('Voulez-vous supprimer tous les comptes inactifs depuis plus de 6 mois ?')) {
        // Implémenter l'action de nettoyage
        alert('Fonctionnalité à implémenter');
    }
}
</script>
@endsection