@extends('layouts.app')

@section('title', 'Mes Chantiers')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-home mr-3 text-primary-600"></i>Mes Chantiers
        </h1>
        <h2 class="text-xl text-gray-700 mt-2">Bonjour {{ Auth::user()->name }} !</h2>
        <p class="text-gray-500 mt-1">Suivez l'avancement de vos projets en temps réel</p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chantiers du client -->
        <div class="lg:col-span-2 space-y-6">
            @forelse($mes_chantiers as $chantier)
                <div class="card {{ $chantier->isEnRetard() ? 'border-danger-400' : ($chantier->statut == 'termine' ? 'border-success-400' : 'border-primary-400') }} hover:shadow-lg transition-all duration-300">
                    <div class="card-header flex justify-between items-center">
                        <h5 class="text-lg font-semibold text-gray-900 flex items-center">
                            <span class="mr-2 text-xl">
                                @switch($chantier->statut)
                                    @case('planifie')
                                        📋
                                        @break
                                    @case('en_cours')
                                        🏗️
                                        @break
                                    @case('termine')
                                        ✅
                                        @break
                                    @default
                                        🏠
                                @endswitch
                            </span>
                            {{ $chantier->titre }}
                        </h5>
                        <span class="badge {{ $chantier->getStatutBadgeClass() }}">
                            {{ $chantier->getStatutTexte() }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="lg:col-span-2 space-y-4">
                                <p class="text-gray-700">{{ $chantier->description ?: 'Aucune description disponible.' }}</p>
                                
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h6 class="font-semibold text-gray-900 mb-2">Commercial :</h6>
                                    <p class="text-gray-700 font-medium">{{ $chantier->commercial->name }}</p>
                                    @if($chantier->commercial->telephone)
                                        <p class="text-gray-600 flex items-center mt-1">
                                            <i class="fas fa-phone mr-2 text-primary-500"></i>{{ $chantier->commercial->telephone }}
                                        </p>
                                    @endif
                                    @if($chantier->commercial->email)
                                        <p class="text-gray-600 flex items-center mt-1">
                                            <i class="fas fa-envelope mr-2 text-primary-500"></i>{{ $chantier->commercial->email }}
                                        </p>
                                    @endif
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                    @if($chantier->date_debut)
                                        <div class="flex justify-between">
                                            <span class="font-medium text-gray-700">Début :</span>
                                            <span class="text-gray-600">{{ $chantier->date_debut->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                    @if($chantier->date_fin_prevue)
                                        <div class="flex justify-between">
                                            <span class="font-medium text-gray-700">Fin prévue :</span>
                                            <span class="text-gray-600">{{ $chantier->date_fin_prevue->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                    @if($chantier->date_fin_effective)
                                        <div class="flex justify-between">
                                            <span class="font-medium text-gray-700">Terminé le :</span>
                                            <span class="text-success-600 font-medium">{{ $chantier->date_fin_effective->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                    @if($chantier->budget)
                                        <div class="flex justify-between">
                                            <span class="font-medium text-gray-700">Budget :</span>
                                            <span class="text-gray-900 font-semibold">{{ number_format($chantier->budget, 2, ',', ' ') }} €</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Avancement global -->
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="font-semibold text-gray-900">Avancement global</span>
                                        <span class="badge {{ $chantier->avancement_global == 100 ? 'badge-success' : 'badge-primary' }}">
                                            {{ number_format($chantier->avancement_global, 0) }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-6">
                                        <div class="h-6 rounded-full flex items-center justify-center text-white text-sm font-medium transition-all duration-500 {{ $chantier->avancement_global == 100 ? 'bg-success-500' : 'bg-primary-500 animate-pulse' }}" 
                                             style="width: {{ $chantier->avancement_global }}%">
                                            {{ number_format($chantier->avancement_global, 0) }}%
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Étapes -->
                                @if($chantier->etapes->count() > 0)
                                    <div class="space-y-3">
                                        <h6 class="font-semibold text-gray-900 flex items-center">
                                            <i class="fas fa-tasks mr-2 text-primary-500"></i>
                                            Étapes du projet ({{ $chantier->etapes->count() }})
                                        </h6>
                                        <div class="space-y-2">
                                            @foreach($chantier->etapes as $etape)
                                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                                    <div class="flex items-center space-x-3">
                                                        @if($etape->terminee)
                                                            <i class="fas fa-check-circle text-success-500"></i>
                                                            <span class="line-through text-gray-500">{{ $etape->nom }}</span>
                                                        @else
                                                            <i class="fas fa-circle text-gray-300"></i>
                                                            <span class="text-gray-700">{{ $etape->nom }}</span>
                                                            @if($etape->isEnRetard())
                                                                <i class="fas fa-exclamation-triangle text-danger-500"></i>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <span class="badge {{ $etape->terminee ? 'badge-success' : ($etape->pourcentage > 0 ? 'badge-primary' : 'badge-secondary') }}">
                                                        {{ number_format($etape->pourcentage, 0) }}%
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="space-y-4">
                                <!-- Documents -->
                                @if($chantier->documents->count() > 0)
                                    <div>
                                        <h6 class="font-semibold text-gray-900 flex items-center mb-3">
                                            <i class="fas fa-folder mr-2 text-primary-500"></i>
                                            Documents ({{ $chantier->documents->count() }})
                                        </h6>
                                        <div class="space-y-2">
                                            @foreach($chantier->documents->take(3) as $document)
                                                <a href="{{ route('documents.download', $document) }}" 
                                                   class="flex justify-between items-center p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                                    <div class="flex items-center space-x-2">
                                                        <i class="{{ $document->getIconeType() }} text-primary-500"></i>
                                                        <span class="text-sm text-gray-700 truncate">{{ Str::limit($document->nom_original, 20) }}</span>
                                                    </div>
                                                    <span class="text-xs text-gray-500">{{ $document->getTailleFormatee() }}</span>
                                                </a>
                                            @endforeach
                                            @if($chantier->documents->count() > 3)
                                                <button class="w-full p-3 text-center text-sm text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors" 
                                                        onclick="voirTousDocuments({{ $chantier->id }})">
                                                    + {{ $chantier->documents->count() - 3 }} autres documents
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Messages de statut spéciaux -->
                        @if($chantier->statut == 'termine')
                            <div class="mt-6 p-4 bg-success-50 border border-success-200 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-check-circle text-success-500 mt-1"></i>
                                    <div>
                                        <h6 class="font-semibold text-success-800">Projet terminé avec succès !</h6>
                                        <p class="text-success-700 text-sm mt-1">Nous espérons que vous êtes satisfait du résultat. N'hésitez pas à nous contacter pour vos futurs projets.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($chantier->isEnRetard())
                            <div class="mt-6 p-4 bg-warning-50 border border-warning-200 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-exclamation-triangle text-warning-500 mt-1"></i>
                                    <div>
                                        <h6 class="font-semibold text-warning-800">Projet en retard</h6>
                                        <p class="text-warning-700 text-sm mt-1">Le chantier accuse un retard. Votre commercial vous contactera prochainement pour vous informer de la nouvelle planification.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Actions -->
                        <div class="flex flex-wrap gap-3 justify-center mt-6 pt-4 border-t border-gray-200">
                            <a href="{{ route('chantiers.show', $chantier) }}" class="btn btn-primary">
                                <i class="fas fa-eye mr-2"></i>Voir le détail
                            </a>
                            <button class="btn btn-outline-success" onclick="contacterCommercial({{ $chantier->commercial->id }})">
                                <i class="fas fa-phone mr-2"></i>Contacter {{ $chantier->commercial->name }}
                            </button>
                            @if($chantier->documents->count() > 0)
                                <button class="btn btn-outline-secondary" onclick="telechargerTousDocuments({{ $chantier->id }})">
                                    <i class="fas fa-download mr-2"></i>Tous les documents
                                </button>
                            @endif
                            @if($chantier->statut == 'termine')
                                <button class="btn btn-outline-warning" onclick="noterProjet({{ $chantier->id }})">
                                    <i class="fas fa-star mr-2"></i>Noter ce projet
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="card text-center py-12">
                    <div class="card-body">
                        <i class="fas fa-hard-hat text-6xl text-gray-400 mb-6"></i>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">Aucun chantier en cours</h4>
                        <p class="text-gray-500 mb-6">Vous n'avez pas encore de chantiers assignés. Contactez notre équipe commerciale pour démarrer votre projet.</p>
                        <button class="btn btn-primary" onclick="demanderDevis()">
                            <i class="fas fa-plus mr-2"></i>Demander un devis
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Notifications -->
            <div class="card">
                <div class="card-header">
                    <h6 class="font-semibold text-gray-900 flex items-center justify-between">
                        <span class="flex items-center">
                            <i class="fas fa-bell mr-2 text-primary-500"></i>Dernières Nouvelles
                        </span>
                        @if($notifications->where('lu', false)->count() > 0)
                            <span class="badge badge-danger">{{ $notifications->where('lu', false)->count() }}</span>
                        @endif
                    </h6>
                </div>
                <div class="card-body space-y-3">
                    @forelse($notifications->take(3) as $notification)
                        <div class="pb-3 border-b border-gray-200 last:border-b-0 {{ !$notification->lu ? 'bg-primary-50 rounded-lg p-3' : '' }}">
                            <h6 class="font-medium text-gray-900 text-sm">{{ $notification->titre }}</h6>
                            <p class="text-gray-600 text-sm mt-1">{{ Str::limit($notification->message, 50) }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </span>
                                @if(!$notification->lu)
                                    <span class="badge badge-primary text-xs">Nouveau</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucune notification récente</p>
                    @endforelse
                    @if($notifications->count() > 0)
                        <div class="text-center pt-3">
                            <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary btn-sm">
                                Voir toutes les notifications
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Contact rapide -->
            <div class="card">
                <div class="card-header">
                    <h6 class="font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-phone mr-2 text-primary-500"></i>Contact Rapide
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $commercialPrincipal = $mes_chantiers->first()?->commercial;
                    @endphp
                    @if($commercialPrincipal)
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-primary-500 text-white rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-user text-2xl"></i>
                            </div>
                            <h6 class="font-semibold text-gray-900">{{ $commercialPrincipal->name }}</h6>
                            <p class="text-gray-500 text-sm">Votre commercial</p>
                        </div>
                    @endif
                    <div class="space-y-3">
                        @if($commercialPrincipal && $commercialPrincipal->telephone)
                            <a href="tel:{{ $commercialPrincipal->telephone }}" class="btn btn-primary w-full">
                                <i class="fas fa-phone mr-2"></i>{{ $commercialPrincipal->telephone }}
                            </a>
                        @endif
                        @if($commercialPrincipal && $commercialPrincipal->email)
                            <a href="mailto:{{ $commercialPrincipal->email }}" class="btn btn-outline-primary w-full">
                                <i class="fas fa-envelope mr-2"></i>Envoyer un email
                            </a>
                        @endif
                        <button class="btn btn-outline-secondary w-full" onclick="ouvrirChat()">
                            <i class="fas fa-comments mr-2"></i>Chat en direct
                        </button>
                        <button class="btn btn-outline-success w-full" onclick="demanderDevis()">
                            <i class="fas fa-plus mr-2"></i>Nouveau projet
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Statistiques -->
            <div class="card">
                <div class="card-header">
                    <h6 class="font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-chart-pie mr-2 text-primary-500"></i>Mes Statistiques
                    </h6>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-primary-600">{{ $mes_chantiers->count() }}</div>
                            <div class="text-sm text-gray-500">Total chantiers</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-success-600">{{ $mes_chantiers->where('statut', 'termine')->count() }}</div>
                            <div class="text-sm text-gray-500">Terminés</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-warning-600">{{ $mes_chantiers->where('statut', 'en_cours')->count() }}</div>
                            <div class="text-sm text-gray-500">En cours</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-primary-600">{{ number_format($mes_chantiers->avg('avancement_global') ?? 0, 0) }}%</div>
                            <div class="text-sm text-gray-500">Avancement moyen</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Modal Contact Commercial -->
<div id="contactModal" class="fixed inset-0 z-50 hidden overflow-y-auto" x-data="{ open: false }" x-show="open" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false"></div>
        
        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6" x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Contacter votre commercial</h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-3">
                <button class="btn btn-primary w-full" id="btnAppel">
                    <i class="fas fa-phone mr-2"></i>Appeler maintenant
                </button>
                <button class="btn btn-outline-primary w-full" id="btnEmail">
                    <i class="fas fa-envelope mr-2"></i>Envoyer un email
                </button>
                <button class="btn btn-outline-success w-full" onclick="demanderRappel()">
                    <i class="fas fa-calendar mr-2"></i>Demander un rappel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Notation Projet -->
<div id="notationModal" class="fixed inset-0 z-50 hidden overflow-y-auto" x-data="{ open: false }" x-show="open" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false"></div>
        
        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6" x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Noter ce projet</h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="notationForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Note globale</label>
                    <div class="flex justify-center">
                        <div class="star-rating flex space-x-1" data-rating="0">
                            <i class="fas fa-star text-2xl text-gray-300 cursor-pointer hover:text-warning-400 transition-colors" data-value="1"></i>
                            <i class="fas fa-star text-2xl text-gray-300 cursor-pointer hover:text-warning-400 transition-colors" data-value="2"></i>
                            <i class="fas fa-star text-2xl text-gray-300 cursor-pointer hover:text-warning-400 transition-colors" data-value="3"></i>
                            <i class="fas fa-star text-2xl text-gray-300 cursor-pointer hover:text-warning-400 transition-colors" data-value="4"></i>
                            <i class="fas fa-star text-2xl text-gray-300 cursor-pointer hover:text-warning-400 transition-colors" data-value="5"></i>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire (optionnel)</label>
                    <textarea class="form-input w-full" rows="3" placeholder="Partagez votre expérience..."></textarea>
                </div>
            </form>
            <div class="flex space-x-3 mt-6">
                <button @click="open = false" class="btn btn-secondary flex-1">Annuler</button>
                <button onclick="soumettreNotation()" class="btn btn-primary flex-1">Envoyer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Documents -->
<div id="documentsModal" class="fixed inset-0 z-50 hidden overflow-y-auto" x-data="{ open: false }" x-show="open" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false"></div>
        
        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6" x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Tous les documents</h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="documentsListe">
                <!-- Contenu chargé dynamiquement -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Le JavaScript reste identique - il fonctionne parfaitement avec Tailwind
// Variables globales
let currentCommercialId = null;
let currentChantierId = null;

// Contacter le commercial
function contacterCommercial(commercialId) {
    currentCommercialId = commercialId;
    // Récupérer les infos du commercial via AJAX
    fetch(`/api/commercial/${commercialId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('btnAppel').onclick = () => {
                if (data.telephone) {
                    window.open(`tel:${data.telephone}`);
                } else {
                    alert('Numéro de téléphone non disponible');
                }
            };
            document.getElementById('btnEmail').onclick = () => {
                if (data.email) {
                    window.open(`mailto:${data.email}`);
                } else {
                    alert('Email non disponible');
                }
            };
            // Ouvrir le modal avec Alpine.js
            document.querySelector('#contactModal').__x.$data.open = true;
        })
        .catch(() => {
            alert('Erreur lors du chargement des informations');
        });
}

// Noter un projet
function noterProjet(chantierId) {
    currentChantierId = chantierId;
    document.querySelector('#notationModal').__x.$data.open = true;
}

// Système de notation par étoiles
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating i');
    let currentRating = 0;
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            currentRating = parseInt(this.dataset.value);
            updateStars(currentRating);
        });
        
        star.addEventListener('mouseover', function() {
            updateStars(parseInt(this.dataset.value));
        });
    });
    
    document.querySelector('.star-rating').addEventListener('mouseleave', function() {
        updateStars(currentRating);
    });
    
    function updateStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-warning-400');
            } else {
                star.classList.remove('text-warning-400');
                star.classList.add('text-gray-300');
            }
        });
        document.querySelector('.star-rating').dataset.rating = rating;
    }
});

// Soumettre la notation
function soumettreNotation() {
    const rating = document.querySelector('.star-rating').dataset.rating;
    const commentaire = document.querySelector('#notationForm textarea').value;
    
    if (rating == 0) {
        alert('Veuillez donner une note');
        return;
    }
    
    // Envoyer la notation via AJAX
    fetch(`/api/chantiers/${currentChantierId}/notation`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ rating, commentaire })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Merci pour votre évaluation !');
            document.querySelector('#notationModal').__x.$data.open = false;
            location.reload();
        } else {
            alert('Erreur lors de l\'envoi : ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'envoi de la notation');
    });
}

// Voir tous les documents
function voirTousDocuments(chantierId) {
    fetch(`/api/chantiers/${chantierId}/documents`)
        .then(response => response.json())
        .then(data => {
            let html = '<div class="space-y-2">';
            data.documents.forEach(doc => {
                html += `
                    <a href="/documents/${doc.id}/download" class="flex justify-between items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="${doc.icone} text-primary-500"></i>
                            <div>
                                <div class="font-medium text-gray-900">${doc.nom_original}</div>
                                ${doc.description ? `<div class="text-sm text-gray-500">${doc.description}</div>` : ''}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">${doc.taille_formatee}</div>
                            <div class="text-xs text-gray-500">${doc.date_upload}</div>
                        </div>
                    </a>
                `;
            });
            html += '</div>';
            
            document.getElementById('documentsListe').innerHTML = html;
            document.querySelector('#documentsModal').__x.$data.open = true;
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des documents');
        });
}

// Télécharger tous les documents
function telechargerTousDocuments(chantierId) {
    const link = document.createElement('a');
    link.href = `/chantiers/${chantierId}/documents/download-all`;
    link.download = `documents_chantier_${chantierId}.zip`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Demander un devis
function demanderDevis() {
    window.location.href = '/devis/nouveau';
}

// Ouvrir le chat
function ouvrirChat() {
    if (typeof Intercom !== 'undefined') {
        Intercom('show');
    } else if (typeof $crisp !== 'undefined') {
        $crisp.push(["do", "chat:open"]);
    } else {
        alert('Le chat sera bientôt disponible. En attendant, vous pouvez nous contacter par email ou téléphone.');
    }
}

// Demander un rappel
function demanderRappel() {
    const commercial = currentCommercialId;
    
    fetch('/api/rappel/demander', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
            commercial_id: commercial,
            message: 'Demande de rappel depuis le dashboard client'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Demande de rappel enregistrée. Nous vous contacterons dans les 24h.');
        } else {
            alert('Erreur lors de l\'envoi de la demande.');
        }
        document.querySelector('#contactModal').__x.$data.open = false;
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'envoi de la demande');
    });
}

// Auto-refresh de l'avancement
function rafraichirAvancement() {
    fetch('/api/dashboard/avancement')
        .then(response => response.json())
        .then(data => {
            data.chantiers.forEach(chantier => {
                const progressBar = document.querySelector(`[data-chantier="${chantier.id}"] .progress-bar`);
                if (progressBar) {
                    progressBar.style.width = `${chantier.avancement_global}%`;
                    progressBar.textContent = `${chantier.avancement_global}%`;
                }
            });
        })
        .catch(error => {
            console.error('Erreur lors du rafraîchissement:', error);
        });
}

// Rafraîchir toutes les 5 minutes
setInterval(rafraichirAvancement, 5 * 60 * 1000);

console.log('Dashboard Client migré vers Tailwind CSS avec succès');
</script>
@endsection
