@extends('layouts.app')

@section('title', 'Mes Notifications')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-bell me-2"></i>Mes Notifications</h1>
                @if($notifications->where('lu', false)->count() > 0)
                    <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-check-double me-2"></i>Tout marquer comme lu
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="btn-group" role="group">
                <a href="{{ route('notifications.index') }}" 
                   class="btn {{ !request('filter') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Toutes
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
                   class="btn {{ request('filter') == 'unread' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Non lues ({{ Auth::user()->notifications()->where('lu', false)->count() }})
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
                   class="btn {{ request('filter') == 'read' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Lues
                </a>
            </div>
        </div>
    </div>
    
    <!-- Liste des notifications -->
    @if($notifications->count() > 0)
        <div class="row">
            @foreach($notifications as $notification)
                <div class="col-12 mb-3">
                    <div class="card {{ !$notification->lu ? 'border-primary' : '' }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="rounded-circle bg-{{ $notification->lu ? 'secondary' : 'primary' }} text-white d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="{{ getNotificationIcon($notification->type) }} fa-lg"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <h5 class="mb-1">
                                        {{ $notification->titre }}
                                        @if(!$notification->lu)
                                            <span class="badge bg-primary ms-2">Nouveau</span>
                                        @endif
                                    </h5>
                                    <p class="mb-1">{{ $notification->message }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                        @if($notification->chantier)
                                            | <i class="fas fa-building me-1"></i>
                                            <a href="{{ route('chantiers.show', $notification->chantier) }}">
                                                {{ $notification->chantier->titre }}
                                            </a>
                                        @endif
                                    </small>
                                </div>
                                <div class="col-auto">
                                    @if(!$notification->lu)
                                        <form method="POST" action="{{ route('notifications.read', $notification) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Marquer comme lu">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($notification->chantier)
                                        <a href="{{ route('chantiers.show', $notification->chantier) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye me-1"></i>Voir
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($notification->lu && $notification->lu_at)
                            <div class="card-footer bg-light">
                                <small class="text-muted">
                                    Lu le {{ $notification->lu_at->format('d/m/Y à H:i') }}
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                <h4>Aucune notification</h4>
                <p class="text-muted">
                    @if(request('filter') == 'unread')
                        Vous avez lu toutes vos notifications !
                    @else
                        Vous n'avez pas encore reçu de notifications.
                    @endif
                </p>
            </div>
        </div>
    @endif
</div>

@php
function getNotificationIcon($type) {
    return match($type) {
        'nouveau_chantier' => 'fas fa-plus-circle',
        'changement_statut' => 'fas fa-sync',
        'nouvelle_etape' => 'fas fa-tasks',
        'etape_terminee' => 'fas fa-check-circle',
        'nouveau_document' => 'fas fa-file',
        'nouveau_commentaire_client' => 'fas fa-comment',
        'nouveau_commentaire_commercial' => 'fas fa-reply',
        'chantier_retard' => 'fas fa-exclamation-triangle',
        default => 'fas fa-bell'
    };
}
@endphp
@endsection