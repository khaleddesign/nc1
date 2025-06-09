@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-briefcase mr-3 text-primary-600"></i>Dashboard Commercial
        </h1>
        <h2 class="text-xl text-gray-700 mt-2">Bonjour {{ Auth::user()->name }} !</h2>
        <p class="text-gray-500 mt-1">Gérez vos chantiers et suivez leur progression</p>
    </div>
    
    <!-- Statistiques dynamiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card bg-primary-500 text-white h-full">
            <div class="card-body">
                <div class="flex justify-between items-center">
                    <div>
                        <h6 class="text-primary-100 uppercase text-sm font-medium mb-1">Total Chantiers</h6>
                        <h2 class="text-2xl font-bold mb-0">{{ $stats['total_chantiers'] }}</h2>
                    </div>
                    <div class="opacity-75">
                        <i class="fas fa-building text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card bg-warning-500 text-white h-full">
            <div class="card-body">
                <div class="flex justify-between items-center">
                    <div>
                        <h6 class="text-warning-100 uppercase text-sm font-medium mb-1">En Cours</h6>
                        <h2 class="text-2xl font-bold mb-0">{{ $stats['en_cours'] }}</h2>
                    </div>
                    <div class="opacity-75">
                        <i class="fas fa-hard-hat text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card bg-success-500 text-white h-full">
            <div class="card-body">
                <div class="flex justify-between items-center">
                    <div>
                        <h6 class="text-success-100 uppercase text-sm font-medium mb-1">Terminés</h6>
                        <h2 class="text-2xl font-bold mb-0">{{ $stats['termines'] }}</h2>
                    </div>
                    <div class="opacity-75">
                        <i class="fas fa-check-circle text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card bg-blue-500 text-white h-full">
            <div class="card-body">
                <div class="flex justify-between items-center">
                    <div>
                        <h6 class="text-blue-100 uppercase text-sm font-medium mb-1">Avancement Moyen</h6>
                        <h2 class="text-2xl font-bold mb-0">{{ number_format($stats['avancement_moyen'], 1) }}%</h2>
                    </div>
                    <div class="opacity-75">
                        <i class="fas fa-chart-line text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Liste des chantiers -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-list mr-2 text-primary-500"></i>Mes Chantiers
                    </h5>
                    <a href="{{ route('chantiers.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i>Nouveau
                    </a>
                </div>
                <div class="card-body">
                    @if($mes_chantiers->count() > 0)
                        <div class="space-y-4">
                            @foreach($mes_chantiers as $chantier)
                                <div class="card {{ $chantier->isEnRetard() ? 'border-danger-400' : 'border-gray-200' }} hover:shadow-md transition-shadow">
                                    <div class="card-body">
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="flex-1">
                                                <h5 class="text-lg font-semibold mb-2">
                                                    <a href="{{ route('chantiers.show', $chantier) }}" 
                                                       class="text-gray-900 hover:text-primary-600 transition-colors">
                                                        {{ $chantier->titre }}
                                                    </a>
                                                </h5>
                                                <div class="text-gray-600 space-y-1">
                                                    <p class="flex items-center">
                                                        <i class="fas fa-user mr-2 text-gray-400"></i>{{ $chantier->client->name }}
                                                        @if($chantier->client->telephone)
                                                            <span class="ml-3 flex items-center">
                                                                <i class="fas fa-phone mr-1 text-gray-400"></i>{{ $chantier->client->telephone }}
                                                            </span>
                                                        @endif
                                                    </p>
                                                    @if($chantier->date_fin_prevue)
                                                        <p class="flex items-center">
                                                            <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                                            Fin prévue : {{ $chantier->date_fin_prevue->format('d/m/Y') }}
                                                            @if($chantier->isEnRetard())
                                                                <span class="text-danger-600 ml-2 font-medium">
                                                                    ({{ $chantier->date_fin_prevue->diffForHumans() }})
                                                                </span>
                                                            @endif
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="badge {{ $chantier->getStatutBadgeClass() }}">
                                                    {{ $chantier->getStatutTexte() }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Barre de progression -->
                                        <div class="mb-4">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-sm font-medium text-gray-700">Avancement</span>
                                                <span class="text-sm font-medium text-gray-900">{{ number_format($chantier->avancement_global, 0) }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-6">
                                                <div class="h-6 rounded-full flex items-center justify-center text-white text-sm font-medium transition-all duration-500 {{ $chantier->avancement_global == 100 ? 'bg-success-500' : 'bg-primary-500' }}" 
                                                     style="width: {{ $chantier->avancement_global }}%">
                                                    {{ number_format($chantier->avancement_global, 0) }}%
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Informations et actions -->
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                                            <div class="text-sm text-gray-500 space-x-4">
                                                @if($chantier->budget)
                                                    <span>Budget: {{ number_format($chantier->budget, 0, ',', ' ') }} €</span>
                                                @endif
                                                @if($chantier->etapes->count() > 0)
                                                    <span>{{ $chantier->etapes->count() }} étapes</span>
                                                @endif
                                                @if($chantier->documents->count() > 0)
                                                    <span>{{ $chantier->documents->count() }} documents</span>
                                                @endif
                                            </div>
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('chantiers.show', $chantier) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye mr-1"></i>Voir
                                                </a>
                                                <a href="{{ route('chantiers.edit', $chantier) }}" 
                                                   class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-edit mr-1"></i>Modifier
                                                </a>
                                                <a href="{{ route('chantiers.etapes', $chantier) }}" 
                                                   class="btn btn-outline-info btn-sm">
                                                    <i class="fas fa-tasks mr-1"></i>Étapes
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-folder-open text-6xl text-gray-400 mb-6"></i>
                            <h5 class="text-xl font-semibold text-gray-900 mb-2">Aucun chantier assigné</h5>
                            <p class="text-gray-500 mb-6">Créez votre premier chantier pour commencer</p>
                            <a href="{{ route('chantiers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>Créer un chantier
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Panneau latéral -->
        <div class="space-y-6">
            <!-- Notifications récentes -->
            <div class="card">
                <div class="card-header">
                    <h6 class="font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bell mr-2 text-primary-500"></i>Notifications Récentes
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($notifications) && $notifications->count() > 0)
                        <div class="space-y-3">
                            @foreach($notifications as $notification)
                                <div class="pb-3 border-b border-gray-200 last:border-b-0">
                                    <div class="flex justify-between items-start">
                                        <strong class="text-gray-900 text-sm">{{ $notification->titre }}</strong>
                                        <form method="POST" action="{{ route('notifications.read', $notification) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-gray-400 hover:text-gray-600 transition-colors">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="text-gray-600 text-sm mt-1">{{ $notification->message }}</p>
                                    <div class="text-xs text-gray-500 mt-2 flex items-center">
                                        <i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary btn-sm">
                                Voir toutes les notifications
                            </a>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Aucune nouvelle notification</p>
                    @endif
                </div>
            </div>
            
            <!-- Actions rapides -->
            <div class="card">
                <div class="card-header">
                    <h6 class="font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt mr-2 text-primary-500"></i>Actions Rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <a href="{{ route('chantiers.create') }}" class="btn btn-primary w-full">
                            <i class="fas fa-plus mr-2"></i>Nouveau Chantier
                        </a>
                        <a href="{{ route('chantiers.calendrier') }}" class="btn btn-outline-secondary w-full">
                            <i class="fas fa-calendar mr-2"></i>Voir le Calendrier
                        </a>
                        <button class="btn btn-outline-info w-full" onclick="ouvrirModalUpload()">
                            <i class="fas fa-upload mr-2"></i>Upload Rapide
                        </button>
                        <a href="#" class="btn btn-outline-success w-full">
                            <i class="fas fa-file-pdf mr-2"></i>Générer Rapport
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Chantiers en retard -->
            @php
                $chantiersEnRetard = $mes_chantiers->filter(function($c) {
                    return $c->isEnRetard();
                });
            @endphp
            @if($chantiersEnRetard->count() > 0)
                <div class="card border-danger-400">
                    <div class="card-header bg-danger-500 text-white">
                        <h6 class="font-semibold flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Chantiers en Retard
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3">
                            @foreach($chantiersEnRetard as $chantier)
                                <div>
                                    <a href="{{ route('chantiers.show', $chantier) }}" 
                                       class="text-gray-900 hover:text-primary-600 font-medium transition-colors">
                                        {{ $chantier->titre }}
                                    </a>
                                    <div class="text-danger-600 text-sm mt-1">
                                        {{ $chantier->date_fin_prevue->diffForHumans() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Upload Rapide -->
<div id="quickUploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto" x-data="{ open: false }" x-show="open" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false"></div>
        
        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6" x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="flex items-center justify-between mb-4">
                <h5 class="text-lg font-semibold text-gray-900">Upload Rapide de Documents</h5>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="quickUploadForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sélectionner le chantier</label>
                    <select class="form-select w-full" name="chantier_id" required>
                        <option value="">Choisir un chantier...</option>
                        @foreach($mes_chantiers as $chantier)
                            <option value="{{ $chantier->id }}">{{ $chantier->titre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fichiers</label>
                    <input type="file" class="form-input w-full" name="fichiers[]" multiple required>
                </div>
            </form>
            <div class="flex space-x-3 mt-6">
                <button @click="open = false" class="btn btn-secondary flex-1">Annuler</button>
                <button onclick="submitQuickUpload()" class="btn btn-primary flex-1">
                    <i class="fas fa-upload mr-1"></i>Uploader
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function ouvrirModalUpload() {
    document.querySelector('#quickUploadModal').__x.$data.open = true;
}

function submitQuickUpload() {
    const form = document.getElementById('quickUploadForm');
    const chantierId = form.querySelector('[name="chantier_id"]').value;
    
    if (chantierId) {
        form.action = `/chantiers/${chantierId}/documents`;
        form.method = 'POST';
        form.enctype = 'multipart/form-data';
        
        // Ajouter le token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        // Ajouter le type
        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type';
        typeInput.value = 'document';
        form.appendChild(typeInput);
        
        form.submit();
    }
}

console.log('Dashboard Commercial migré vers Tailwind CSS avec succès');
</script>
@endsection
