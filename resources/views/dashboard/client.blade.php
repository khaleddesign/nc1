
// resources/views/dashboard/client.blade.php
@extends('layouts.app')

@section('title', 'Mes Chantiers')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-home me-2"></i>Mes Chantiers</h1>
            <h2>Bonjour {{ Auth::user()->name }} !</h2>
            <p class="text-muted">Suivez l'avancement de vos projets en temps réel</p>
        </div>
    </div>
    
    <div class="row">
        <!-- Chantiers du client -->
        <div class="col-md-8">
            @forelse($mes_chantiers as $chantier)
                <div class="card mb-4 {{ $chantier->isEnRetard() ? 'border-danger' : ($chantier->statut == 'termine' ? 'border-success' : 'border-primary') }}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
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
                            {{ $chantier->titre }}
                        </h5>
                        <span class="badge {{ $chantier->getStatutBadgeClass() }}">
                            {{ $chantier->getStatutTexte() }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="card-text">{{ $chantier->description ?: 'Aucune description disponible.' }}</p>
                                
                                <div class="mb-3">
                                    <strong>Commercial :</strong> {{ $chantier->commercial->name }}
                                    @if($chantier->commercial->telephone)
                                        <br><i class="fas fa-phone me-1"></i>{{ $chantier->commercial->telephone }}
                                    @endif
                                    @if($chantier->commercial->email)
                                        <br><i class="fas fa-envelope me-1"></i>{{ $chantier->commercial->email }}
                                    @endif
                                </div>
                                
                                <div class="mb-3">
                                    @if($chantier->date_debut)
                                        <div><strong>Début :</strong> {{ $chantier->date_debut->format('d/m/Y') }}</div>
                                    @endif
                                    @if($chantier->date_fin_prevue)
                                        <div><strong>Fin prévue :</strong> {{ $chantier->date_fin_prevue->format('d/m/Y') }}</div>
                                    @endif
                                    @if($chantier->date_fin_effective)
                                        <div><strong>Terminé le :</strong> {{ $chantier->date_fin_effective->format('d/m/Y') }}</div>
                                    @endif
                                    @if($chantier->budget)
                                        <div><strong>Budget :</strong> {{ number_format($chantier->budget, 2, ',', ' ') }} €</div>
                                    @endif
                                </div>
                                
                                <!-- Avancement global -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong>Avancement global</strong>
                                        <span class="badge bg-{{ $chantier->avancement_global == 100 ? 'success' : 'info' }}">
                                            {{ number_format($chantier->avancement_global, 0) }}%
                                        </span>
                                    </div>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar {{ $chantier->avancement_global == 100 ? 'bg-success' : 'progress-bar-striped progress-bar-animated' }}" 
                                             style="width: {{ $chantier->avancement_global }}%"></div>
                                    </div>
                                </div>
                                
                                <!-- Étapes -->
                                @if($chantier->etapes->count() > 0)
                                    <div class="mb-3">
                                        <h6><i class="fas fa-tasks me-2"></i>Étapes du projet ({{ $chantier->etapes->count() }})</h6>
                                        @foreach($chantier->etapes as $etape)
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    @if($etape->terminee)
                                                        <span class="text-decoration-line-through text-muted">{{ $etape->nom }}</span>
                                                        <i class="fas fa-check-circle text-success ms-2"></i>
                                                    @else
                                                        <span>{{ $etape->nom }}</span>
                                                        @if($etape->isEnRetard())
                                                            <i class="fas fa-exclamation-triangle text-danger ms-2"></i>
                                                        @endif
                                                    @endif
                                                </div>
                                                <span class="badge bg-{{ $etape->terminee ? 'success' : ($etape->pourcentage > 0 ? 'primary' : 'secondary') }}">
                                                    {{ number_format($etape->pourcentage, 0) }}%
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            
                            <div class="col-md-4">
                                <!-- Documents -->
                                @if($chantier->documents->count() > 0)
                                    <h6><i class="fas fa-folder me-2"></i>Documents ({{ $chantier->documents->count() }})</h6>
                                    <div class="list-group list-group-flush mb-3">
                                        @foreach($chantier->documents->take(3) as $document)
                                            <a href="{{ route('documents.download', $document) }}" 
                                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="{{ $document->getIconeType() }} me-2"></i>
                                                    <span class="small">{{ Str::limit($document->nom_original, 20) }}</span>
                                                </div>
                                                <small class="text-muted">{{ $document->getTailleFormatee() }}</small>
                                            </a>
                                        @endforeach
                                        @if($chantier->documents->count() > 3)
                                            <button class="list-group-item list-group-item-action text-center" 
                                                    onclick="voirTousDocuments({{ $chantier->id }})">
                                                <small>+ {{ $chantier->documents->count() - 3 }} autres documents</small>
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Messages de statut spéciaux -->
                        @if($chantier->statut == 'termine')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Projet terminé avec succès !</strong>
                                <br>Nous espérons que vous êtes satisfait du résultat. N'hésitez pas à nous contacter pour vos futurs projets.
                            </div>
                        @elseif($chantier->isEnRetard())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Projet en retard</strong>
                                <br>Le chantier accuse un retard. Votre commercial vous contactera prochainement pour vous informer de la nouvelle planification.
                            </div>
                        @endif
                        
                        <!-- Actions -->
                        <div class="text-center mt-3">
                            <a href="{{ route('chantiers.show', $chantier) }}" class="btn btn-primary me-2">
                                <i class="fas fa-eye me-2"></i>Voir le détail
                            </a>
                            <button class="btn btn-outline-success me-2" onclick="contacterCommercial({{ $chantier->commercial->id }})">
                                <i class="fas fa-phone me-2"></i>Contacter {{ $chantier->commercial->name }}
                            </button>
                            @if($chantier->documents->count() > 0)
                                <button class="btn btn-outline-secondary" onclick="telechargerTousDocuments({{ $chantier->id }})">
                                    <i class="fas fa-download me-2"></i>Tous les documents
                                </button>
                            @endif
                            @if($chantier->statut == 'termine')
                                <button class="btn btn-outline-warning ms-2" onclick="noterProjet({{ $chantier->id }})">
                                    <i class="fas fa-star me-2"></i>Noter ce projet
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-hard-hat fa-4x text-muted mb-3"></i>
                        <h4>Aucun chantier en cours</h4>
                        <p class="text-muted">Vous n'avez pas encore de chantiers assignés. Contactez notre équipe commerciale pour démarrer votre projet.</p>
                        <button class="btn btn-primary" onclick="demanderDevis()">
                            <i class="fas fa-plus me-2"></i>Demander un devis
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Notifications -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bell me-2"></i>Dernières Nouvelles
                        @if($notifications->where('lu', false)->count() > 0)
                            <span class="badge bg-danger">{{ $notifications->where('lu', false)->count() }}</span>
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($notifications->take(3) as $notification)
                        <div class="border-bottom py-2 {{ !$notification->lu ? 'bg-light rounded p-2 mb-2' : '' }}">
                            <div class="fw-bold">{{ $notification->titre }}</div>
                            <small class="text-muted">{{ Str::limit($notification->message, 50) }}</small>
                            <div class="small text-muted">
                                <i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                @if(!$notification->lu)
                                    <span class="badge bg-primary ms-2">Nouveau</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Aucune notification récente</p>
                    @endforelse
                    @if($notifications->count() > 0)
                        <div class="text-center mt-3">
                            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary">
                                Voir toutes les notifications
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Contact rapide -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-phone me-2"></i>Contact Rapide
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $commercialPrincipal = $mes_chantiers->first()?->commercial;
                    @endphp
                    @if($commercialPrincipal)
                        <div class="text-center mb-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 60px; height: 60px;">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                            <h6>{{ $commercialPrincipal->name }}</h6>
                            <small class="text-muted">Votre commercial</small>
                        </div>
                    @endif
                    <div class="d-grid gap-2">
                        @if($commercialPrincipal && $commercialPrincipal->telephone)
                            <a href="tel:{{ $commercialPrincipal->telephone }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-phone me-2"></i>{{ $commercialPrincipal->telephone }}
                            </a>
                        @endif
                        @if($commercialPrincipal && $commercialPrincipal->email)
                            <a href="mailto:{{ $commercialPrincipal->email }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope me-2"></i>Envoyer un email
                            </a>
                        @endif
                        <button class="btn btn-outline-secondary btn-sm" onclick="ouvrirChat()">
                            <i class="fas fa-comments me-2"></i>Chat en direct
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="demanderDevis()">
                            <i class="fas fa-plus me-2"></i>Nouveau projet
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Prochains rendez-vous / Statistiques -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Mes Statistiques
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="mb-0 text-primary">{{ $mes_chantiers->count() }}</h4>
                            <small class="text-muted">Total chantiers</small>
                        </div>
                        <div class="col-6">
                            <h4 class="mb-0 text-success">{{ $mes_chantiers->where('statut', 'termine')->count() }}</h4>
                            <small class="text-muted">Terminés</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="mb-0 text-warning">{{ $mes_chantiers->where('statut', 'en_cours')->count() }}</h4>
                            <small class="text-muted">En cours</small>
                        </div>
                        <div class="col-6">
                            <h4 class="mb-0 text-info">{{ number_format($mes_chantiers->avg('avancement_global') ?? 0, 0) }}%</h4>
                            <small class="text-muted">Avancement moyen</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Modal Contact Commercial -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contacter votre commercial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" id="btnAppel">
                        <i class="fas fa-phone me-2"></i>Appeler maintenant
                    </button>
                    <button class="btn btn-outline-primary" id="btnEmail">
                        <i class="fas fa-envelope me-2"></i>Envoyer un email
                    </button>
                    <button class="btn btn-outline-success" onclick="demanderRappel()">
                        <i class="fas fa-calendar me-2"></i>Demander un rappel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Notation Projet -->
<div class="modal fade" id="notationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Noter ce projet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="notationForm">
                    <div class="mb-3">
                        <label class="form-label">Note globale</label>
                        <div class="text-center">
                            <div class="star-rating" data-rating="0">
                                <i class="fas fa-star" data-value="1"></i>
                                <i class="fas fa-star" data-value="2"></i>
                                <i class="fas fa-star" data-value="3"></i>
                                <i class="fas fa-star" data-value="4"></i>
                                <i class="fas fa-star" data-value="5"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Commentaire (optionnel)</label>
                        <textarea class="form-control" rows="3" placeholder="Partagez votre expérience..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="soumettreNotation()">Envoyer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Documents -->
<div class="modal fade" id="documentsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tous les documents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="documentsListe">
                    <!-- Contenu chargé dynamiquement -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
            new bootstrap.Modal(document.getElementById('contactModal')).show();
        })
        .catch(() => {
            alert('Erreur lors du chargement des informations');
        });
}

// Noter un projet
function noterProjet(chantierId) {
    currentChantierId = chantierId;
    new bootstrap.Modal(document.getElementById('notationModal')).show();
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
                star.classList.add('text-warning');
                star.classList.remove('text-muted');
            } else {
                star.classList.add('text-muted');
                star.classList.remove('text-warning');
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
            bootstrap.Modal.getInstance(document.getElementById('notationModal')).hide();
            // Recharger la page ou mettre à jour l'affichage
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
            let html = '<div class="list-group">';
            data.documents.forEach(doc => {
                html += `
                    <a href="/documents/${doc.id}/download" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="${doc.icone}" me-2"></i>
                            <span>${doc.nom_original}</span>
                            ${doc.description ? `<small class="text-muted d-block">${doc.description}</small>` : ''}
                        </div>
                        <div class="text-end">
                            <small class="text-muted">${doc.taille_formatee}</small>
                            <br><small class="text-muted">${doc.date_upload}</small>
                        </div>
                    </a>
                `;
            });
            html += '</div>';
            
            document.getElementById('documentsListe').innerHTML = html;
            new bootstrap.Modal(document.getElementById('documentsModal')).show();
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des documents');
        });
}

// Télécharger tous les documents
function telechargerTousDocuments(chantierId) {
    // Créer un lien temporaire pour télécharger l'archive
    const link = document.createElement('a');
    link.href = `/chantiers/${chantierId}/documents/download-all`;
    link.download = `documents_chantier_${chantierId}.zip`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Demander un devis
function demanderDevis() {
    // Rediriger vers un formulaire de demande
    window.location.href = '/devis/nouveau';
}

// Ouvrir le chat
function ouvrirChat() {
    // Intégration avec un système de chat (Intercom, Crisp, etc.)
    if (typeof Intercom !== 'undefined') {
        Intercom('show');
    } else if (typeof $crisp !== 'undefined') {
        $crisp.push(["do", "chat:open"]);
    } else {
        // Fallback
        alert('Le chat sera bientôt disponible. En attendant, vous pouvez nous contacter par email ou téléphone.');
    }
}

// Demander un rappel
function demanderRappel() {
    const commercial = currentCommercialId;
    
    // Simuler une demande de rappel
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
        bootstrap.Modal.getInstance(document.getElementById('contactModal')).hide();
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'envoi de la demande');
    });
}

// Auto-refresh de l'avancement (optionnel)
function rafraichirAvancement() {
    fetch('/api/dashboard/avancement')
        .then(response => response.json())
        .then(data => {
            // Mettre à jour les barres de progression
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

// Marquer les notifications comme lues
function marquerNotificationsLues() {
    fetch('/api/notifications/marquer-lues', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Supprimer les badges "Nouveau"
            document.querySelectorAll('.badge.bg-primary').forEach(badge => {
                if (badge.textContent === 'Nouveau') {
                    badge.remove();
                }
            });
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}

// Marquer les notifications comme lues au clic
document.addEventListener('click', function(e) {
    if (e.target.closest('.notification')) {
        marquerNotificationsLues();
    }
});

// Gestion des erreurs globales
window.addEventListener('error', function(e) {
    console.error('Erreur JavaScript:', e.error);
});

// Affichage des messages de succès/erreur
function afficherMessage(message, type = 'success') {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <i class="fas ${icon} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Supprimer automatiquement après 5 secondes
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Validation des formulaires
function validerFormulaire(formId) {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let valide = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            valide = false;
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
    });
    
    return valide;
}

// Formatage des dates
function formaterDate(dateString) {
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(dateString).toLocaleDateString('fr-FR', options);
}

// Formatage des montants
function formaterMontant(montant) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR'
    }).format(montant);
}

// Gestion du mode sombre (optionnel)
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDark);
}

// Charger le mode sombre depuis le localStorage
document.addEventListener('DOMContentLoaded', function() {
    const darkMode = localStorage.getItem('darkMode') === 'true';
    if (darkMode) {
        document.body.classList.add('dark-mode');
    }
    
    // Initialiser les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialiser les popovers Bootstrap
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

// Gestion de la déconnexion automatique
let timeoutWarning;
let timeoutLogout;

function resetTimeouts() {
    clearTimeout(timeoutWarning);
    clearTimeout(timeoutLogout);
    
    // Avertissement après 25 minutes d'inactivité
    timeoutWarning = setTimeout(() => {
        if (confirm('Votre session va expirer dans 5 minutes. Voulez-vous rester connecté ?')) {
            // Rafraîchir la session
            fetch('/api/refresh-session', { method: 'POST' })
                .then(() => resetTimeouts())
                .catch(() => window.location.href = '/login');
        }
    }, 25 * 60 * 1000);
    
    // Déconnexion automatique après 30 minutes
    timeoutLogout = setTimeout(() => {
        alert('Votre session a expiré. Vous allez être redirigé vers la page de connexion.');
        window.location.href = '/login';
    }, 30 * 60 * 1000);
}

// Réinitialiser les timeouts à chaque activité
document.addEventListener('mousemove', resetTimeouts);
document.addEventListener('keypress', resetTimeouts);
document.addEventListener('click', resetTimeouts);

// Initialiser les timeouts
resetTimeouts();

// Gestion des fichiers drag & drop (pour uploads futurs)
function initDragAndDrop() {
    const dropZones = document.querySelectorAll('.drop-zone');
    
    dropZones.forEach(zone => {
        zone.addEventListener('dragover', (e) => {
            e.preventDefault();
            zone.classList.add('dragover');
        });
        
        zone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            zone.classList.remove('dragover');
        });
        
        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            zone.classList.remove('dragover');
            
            const files = Array.from(e.dataTransfer.files);
            handleFileUpload(files, zone);
        });
    });
}

// Gestion de l'upload de fichiers
function handleFileUpload(files, zone) {
    const maxSize = 10 * 1024 * 1024; // 10MB
    const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    
    files.forEach(file => {
        if (file.size > maxSize) {
            afficherMessage(`Le fichier ${file.name} est trop volumineux (max 10MB)`, 'error');
            return;
        }
        
        if (!allowedTypes.includes(file.type)) {
            afficherMessage(`Le type de fichier ${file.name} n'est pas autorisé`, 'error');
            return;
        }
        
        // Ici, vous pouvez implémenter l'upload via AJAX
        uploadFile(file, zone);
    });
}

// Upload d'un fichier
function uploadFile(file, zone) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('chantier_id', zone.dataset.chantierId);
    
    // Créer une barre de progression
    const progressDiv = document.createElement('div');
    progressDiv.className = 'upload-progress mb-2';
    progressDiv.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-1">
            <small>${file.name}</small>
            <small>0%</small>
        </div>
        <div class="progress" style="height: 5px;">
            <div class="progress-bar" style="width: 0%"></div>
        </div>
    `;
    zone.appendChild(progressDiv);
    
    fetch('/api/documents/upload', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            afficherMessage(`Fichier ${file.name} uploadé avec succès`, 'success');
            // Rafraîchir la liste des documents
            setTimeout(() => location.reload(), 1000);
        } else {
            afficherMessage(`Erreur lors de l'upload de ${file.name}: ${data.message}`, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur upload:', error);
        afficherMessage(`Erreur lors de l'upload de ${file.name}`, 'error');
    })
    .finally(() => {
        progressDiv.remove();
    });
}

// Recherche en temps réel
function initSearchFunction() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;
    
    let searchTimeout;
    
    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();
        
        if (query.length < 2) {
            // Réafficher tous les éléments
            document.querySelectorAll('.chantier-card').forEach(card => {
                card.style.display = 'block';
            });
            return;
        }
        
        searchTimeout = setTimeout(() => {
            rechercher(query);
        }, 300);
    });
}

// Fonction de recherche
function rechercher(query) {
    const cards = document.querySelectorAll('.chantier-card');
    let resultats = 0;
    
    cards.forEach(card => {
        const titre = card.querySelector('.card-title').textContent.toLowerCase();
        const description = card.querySelector('.card-text')?.textContent.toLowerCase() || '';
        const commercial = card.querySelector('.commercial-name')?.textContent.toLowerCase() || '';
        
        if (titre.includes(query.toLowerCase()) || 
            description.includes(query.toLowerCase()) || 
            commercial.includes(query.toLowerCase())) {
            card.style.display = 'block';
            resultats++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Afficher un message si aucun résultat
    let noResultDiv = document.getElementById('noResults');
    if (resultats === 0) {
        if (!noResultDiv) {
            noResultDiv = document.createElement('div');
            noResultDiv.id = 'noResults';
            noResultDiv.className = 'alert alert-info text-center';
            noResultDiv.innerHTML = `
                <i class="fas fa-search me-2"></i>
                Aucun chantier trouvé pour "${query}"
            `;
            document.querySelector('.col-md-8').appendChild(noResultDiv);
        }
    } else if (noResultDiv) {
        noResultDiv.remove();
    }
}

// Impression
function imprimerDashboard() {
    // Masquer les éléments non nécessaires à l'impression
    const elementsToHide = document.querySelectorAll('.btn, .modal, .sidebar');
    elementsToHide.forEach(el => el.style.display = 'none');
    
    window.print();
    
    // Réafficher les éléments
    elementsToHide.forEach(el => el.style.display = '');
}

// Export PDF (nécessite jsPDF)
function exporterPDF() {
    if (typeof jsPDF === 'undefined') {
        afficherMessage('Fonctionnalité d\'export PDF non disponible', 'error');
        return;
    }
    
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Titre
    doc.setFontSize(20);
    doc.text('Mes Chantiers', 20, 30);
    
    // Date
    doc.setFontSize(12);
    doc.text(`Généré le ${new Date().toLocaleDateString('fr-FR')}`, 20, 40);
    
    // Contenu (simplifié)
    let y = 60;
    const chantiers = document.querySelectorAll('.chantier-card:not([style*="display: none"])');
    
    chantiers.forEach((card, index) => {
        if (y > 250) {
            doc.addPage();
            y = 30;
        }
        
        const titre = card.querySelector('.card-title').textContent;
        const statut = card.querySelector('.badge').textContent;
        const avancement = card.querySelector('.progress-bar').style.width;
        
        doc.setFontSize(14);
        doc.text(`${index + 1}. ${titre}`, 20, y);
        doc.setFontSize(10);
        doc.text(`Statut: ${statut} | Avancement: ${avancement}`, 20, y + 8);
        
        y += 20;
    });
    
    doc.save('mes-chantiers.pdf');
}

// Raccourcis clavier
document.addEventListener('keydown', function(e) {
    // Ctrl + F pour rechercher
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        document.getElementById('searchInput')?.focus();
    }
    
    // Echap pour fermer les modals
    if (e.key === 'Escape') {
        const openModal = document.querySelector('.modal.show');
        if (openModal) {
            bootstrap.Modal.getInstance(openModal).hide();
        }
    }
    
    // Ctrl + P pour imprimer
    if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        imprimerDashboard();
    }
});

// Service Worker pour le cache (optionnel)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('SW registered: ', registration);
            })
            .catch(function(registrationError) {
                console.log('SW registration failed: ', registrationError);
            });
    });
}

// Détection de la connectivité
window.addEventListener('online', function() {
    afficherMessage('Connexion rétablie', 'success');
    // Resynchroniser les données
    rafraichirAvancement();
});

window.addEventListener('offline', function() {
    afficherMessage('Connexion perdue. Certaines fonctionnalités peuvent être limitées.', 'error');
});

// Analytics (si Google Analytics est configuré)
function trackEvent(action, category = 'Dashboard', label = '') {
    if (typeof gtag !== 'undefined') {
        gtag('event', action, {
            event_category: category,
            event_label: label
        });
    }
}

// Tracking des actions importantes
document.addEventListener('click', function(e) {
    if (e.target.matches('[onclick*="contacterCommercial"]')) {
        trackEvent('contact_commercial', 'Communication');
    }
    if (e.target.matches('[onclick*="noterProjet"]')) {
        trackEvent('noter_projet', 'Evaluation');
    }
    if (e.target.matches('[onclick*="telechargerTousDocuments"]')) {
        trackEvent('telecharger_documents', 'Documents');
    }
});

// Fonction de debug (développement uniquement)
function debugInfo() {
    console.group('Dashboard Debug Info');
    console.log('Chantiers visibles:', document.querySelectorAll('.chantier-card:not([style*="display: none"])').length);
    console.log('Notifications non lues:', document.querySelectorAll('.badge.bg-primary').length);
    console.log('User Agent:', navigator.userAgent);
    console.log('Écran:', `${screen.width}x${screen.height}`);
    console.log('Viewport:', `${window.innerWidth}x${window.innerHeight}`);
    console.groupEnd();
}

// Activer le debug en mode développement
if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    window.debugInfo = debugInfo;
    console.log('Mode développement détecté. Utilisez debugInfo() pour les informations de debug.');
}

// Initialisation finale
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard Client chargé avec succès');
    
    // Initialiser toutes les fonctionnalités
    initSearchFunction();
    initDragAndDrop();
    
    // Marquer la page comme chargée pour les analytics
    trackEvent('page_load', 'Navigation');
});

</script>

<!-- Styles CSS additionnels -->
<style>
.dark-mode {
    background-color: #1a1a1a;
    color: #ffffff;
}

.dark-mode .card {
    background-color: #2d2d2d;
    border-color: #404040;
}

.dark-mode .card-header {
    background-color: #3d3d3d;
    border-color: #404040;
}

.star-rating i {
    font-size: 1.5rem;
    margin: 0 2px;
    cursor: pointer;
    transition: color 0.2s ease;
}

.star-rating i:hover {
    color: #ffc107 !important;
}

.upload-progress {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #dee2e6;
}

.drop-zone {
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
}

.drop-zone.dragover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.chantier-card {
    transition: all 0.3s ease;
}

.chantier-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .col-md-8, .col-md-4 {
        margin-bottom: 1rem;
    }
    
    .btn-group-sm .btn {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
}

@media print {
    .btn, .modal, .sidebar, .no-print {
        display: none !important;
    }
    
    .card {
        break-inside: avoid;
        margin-bottom: 1rem;
    }
}

.notification-badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}

@keyframes progress-bar-stripes {
    0% { background-position: 1rem 0; }
    100% { background-position: 0 0; }
}
</style>
@endsection