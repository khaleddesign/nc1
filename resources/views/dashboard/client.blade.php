@extends('layouts.app')

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
            <!-- Chantier 1 - En cours -->
            <div class="card mb-4 border-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">🏠 Rénovation Cuisine</h5>
                    <span class="badge bg-primary">En cours</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="card-text">Rénovation complète de la cuisine avec nouveaux équipements modernes et électroménager haut de gamme.</p>
                            
                            <div class="mb-3">
                                <strong>Commercial :</strong> Jean Dupont
                                <br><i class="fas fa-phone me-1"></i>06 12 34 56 78
                                <br><i class="fas fa-envelope me-1"></i>jean.dupont@chantiers.com
                            </div>
                            
                            <div class="mb-3">
                                <div><strong>Début :</strong> 15 décembre 2024</div>
                                <div><strong>Fin prévue :</strong> 15 février 2025</div>
                            </div>
                            
                            <!-- Avancement global -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong>Avancement global</strong>
                                    <span>65%</span>
                                </div>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar progress-bar-striped" style="width: 65%"></div>
                                </div>
                            </div>
                            
                            <!-- Étapes -->
                            <div class="mb-3">
                                <h6><i class="fas fa-tasks me-2"></i>Étapes du projet</h6>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="text-decoration-line-through text-muted">Démolition</span>
                                        <i class="fas fa-check-circle text-success ms-2"></i>
                                    </div>
                                    <span class="badge bg-success">100%</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="text-decoration-line-through text-muted">Électricité</span>
                                        <i class="fas fa-check-circle text-success ms-2"></i>
                                    </div>
                                    <span class="badge bg-success">100%</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>Plomberie</div>
                                    <span class="badge bg-primary">80%</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>Carrelage</div>
                                    <span class="badge bg-secondary">0%</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>Peinture</div>
                                    <span class="badge bg-secondary">0%</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>Installation cuisine</div>
                                    <span class="badge bg-secondary">0%</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Photos récentes -->
                            <h6><i class="fas fa-camera me-2"></i>Photos récentes</h6>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 80px; border-radius: 5px;">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                    </div>
                                    <small class="text-muted">Plomberie en cours</small>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 80px; border-radius: 5px;">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                    </div>
                                    <small class="text-muted">Électricité terminée</small>
                                </div>
                            </div>
                            
                            <!-- Documents -->
                            <h6><i class="fas fa-folder me-2"></i>Documents</h6>
                            <div class="list-group list-group-flush mb-3">
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-pdf me-2 text-danger"></i>
                                        <span class="small">Devis_cuisine.pdf</span>
                                    </div>
                                    <small class="text-muted">2.1 MB</small>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-image me-2 text-primary"></i>
                                        <span class="small">Plan_3D.jpg</span>
                                    </div>
                                    <small class="text-muted">1.8 MB</small>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-word me-2 text-info"></i>
                                        <span class="small">Garantie.docx</span>
                                    </div>
                                    <small class="text-muted">128 KB</small>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <button class="btn btn-primary me-2">
                            <i class="fas fa-comments me-2"></i>Contacter le commercial
                        </button>
                        <button class="btn btn-outline-secondary">
                            <i class="fas fa-download me-2"></i>Télécharger tous les documents
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Chantier 2 - Terminé -->
            <div class="card mb-4 border-success">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">🛁 Rénovation Salle de Bain</h5>
                    <span class="badge bg-success">Terminé</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="card-text">Modernisation complète de la salle de bain avec douche italienne et nouveaux équipements.</p>
                            
                            <div class="mb-3">
                                <strong>Commercial :</strong> Jean Dupont
                                <br><strong>Terminé le :</strong> 10 janvier 2025
                            </div>
                            
                            <!-- Avancement global -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong>Projet terminé</strong>
                                    <span class="text-success"><i class="fas fa-check-circle me-1"></i>100%</span>
                                </div>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-success" style="width: 100%"></div>
                                </div>
                            </div>
                            
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Projet terminé avec succès !</strong>
                                <br>Nous espérons que vous êtes satisfait du résultat. N'hésitez pas à nous contacter pour vos futurs projets.
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Photos finales -->
                            <h6><i class="fas fa-camera me-2"></i>Photos finales</h6>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 80px; border-radius: 5px;">
                                        <i class="fas fa-image fa-2x text-success"></i>
                                    </div>
                                    <small class="text-muted">Résultat final</small>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 80px; border-radius: 5px;">
                                        <i class="fas fa-image fa-2x text-success"></i>
                                    </div>
                                    <small class="text-muted">Douche italienne</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <button class="btn btn-success me-2">
                            <i class="fas fa-star me-2"></i>Noter ce projet
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>Certificat de garantie
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Notifications -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bell me-2"></i>Dernières Nouvelles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="border-bottom py-2">
                        <div class="fw-bold">Étape terminée</div>
                        <small class="text-muted">L'électricité de votre cuisine a été terminée</small>
                        <div class="small text-muted">Il y a 2 jours</div>
                    </div>
                    <div class="border-bottom py-2">
                        <div class="fw-bold">Nouvelles photos</div>
                        <small class="text-muted">Jean Dupont a ajouté des photos du chantier</small>
                        <div class="small text-muted">Il y a 3 jours</div>
                    </div>
                    <div class="py-2">
                        <div class="fw-bold">Livraison prévue</div>
                        <small class="text-muted">Les nouveaux équipements arriveront demain</small>
                        <div class="small text-muted">Il y a 1 semaine</div>
                    </div>
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
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-phone me-2"></i>Appeler Jean Dupont
                        </button>
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope me-2"></i>Envoyer un email
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-comments me-2"></i>Chat en direct
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Prochains rendez-vous -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-calendar me-2"></i>Prochains RDV
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <span class="small">18</span>
                        </div>
                        <div>
                            <div class="fw-bold">Point d'avancement</div>
                            <small class="text-muted">18 janvier à 14h00</small>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <span class="small">25</span>
                        </div>
                        <div>
                            <div class="fw-bold">Réception finale</div>
                            <small class="text-muted">25 janvier à 10h00</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection