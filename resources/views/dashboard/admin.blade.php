@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-tachometer-alt me-2"></i>Dashboard Administrateur</h1>
            <h2>Bonjour {{ Auth::user()->name }} !</h2>
            <p class="text-muted">Vue d'ensemble de tous les chantiers</p>
        </div>
    </div>
    
    <!-- Statistiques globales -->
    <div class="row mt-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4 mb-0">8</div>
                            <div class="small">Total Chantiers</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hammer fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4 mb-0">3</div>
                            <div class="small">En Cours</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-play-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4 mb-0">4</div>
                            <div class="small">Terminés</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="h4 mb-0">72%</div>
                            <div class="small">Avancement Moyen</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chantiers récents -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Chantiers Récents
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Chantier</th>
                                    <th>Client</th>
                                    <th>Commercial</th>
                                    <th>Statut</th>
                                    <th>Avancement</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>🏠 Rénovation Cuisine</strong>
                                        <br><small class="text-muted">Rénovation complète avec nouveaux équipements</small>
                                    </td>
                                    <td>Marie Martin</td>
                                    <td>Jean Dupont</td>
                                    <td>
                                        <span class="badge bg-primary">En cours</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" style="width: 65%">
                                                65%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        <strong>🏗️ Extension Garage</strong>
                                        <br><small class="text-muted">Construction d'une extension</small>
                                    </td>
                                    <td>Pierre Durand</td>
                                    <td>Jean Dupont</td>
                                    <td>
                                        <span class="badge bg-secondary">Planifié</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%">
                                                0%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        <strong>🛁 Rénovation Salle de Bain</strong>
                                        <br><small class="text-muted">Modernisation complète</small>
                                    </td>
                                    <td>Marie Martin</td>
                                    <td>Jean Dupont</td>
                                    <td>
                                        <span class="badge bg-success">Terminé</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%">
                                                100%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        <strong>🏡 Isolation Combles</strong>
                                        <br><small class="text-muted">Isolation thermique</small>
                                    </td>
                                    <td>Paul Martin</td>
                                    <td>Sophie Leroy</td>
                                    <td>
                                        <span class="badge bg-primary">En cours</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" style="width: 30%">
                                                30%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        <strong>🚪 Changement Fenêtres</strong>
                                        <br><small class="text-muted">Remplacement 8 fenêtres</small>
                                    </td>
                                    <td>Anne Dubois</td>
                                    <td>Marc Petit</td>
                                    <td>
                                        <span class="badge bg-success">Terminé</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%">
                                                100%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Alertes et actions rapides -->
        <div class="col-md-4">
            <!-- Chantiers en retard -->
            <div class="card mb-3">
                <div class="card-header bg-danger text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Chantiers en Retard
                    </h6>
                </div>
                <div class="card-body">
                    <div class="border-bottom py-2">
                        <div class="fw-bold">Rénovation Terrasse</div>
                        <small class="text-muted">
                            Client: Julie Bernard<br>
                            Prévu: 10/01/2025 <span class="text-danger">(5 jours de retard)</span>
                        </small>
                    </div>
                    <div class="py-2">
                        <div class="fw-bold">Peinture Façade</div>
                        <small class="text-muted">
                            Client: Robert Moreau<br>
                            Prévu: 08/01/2025 <span class="text-danger">(7 jours de retard)</span>
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Actions rapides admin -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>Actions Rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Nouveau Chantier
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-users me-2"></i>Gérer Utilisateurs
                        </button>
                        <button class="btn btn-outline-info btn-sm">
                            <i class="fas fa-chart-bar me-2"></i>Statistiques Détaillées
                        </button>
                        <button class="btn btn-outline-success btn-sm">
                            <i class="fas fa-file-export me-2"></i>Export Données
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Statistiques équipe -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Équipe
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="h5 text-primary">1</div>
                            <small class="text-muted">Admin</small>
                        </div>
                        <div class="col-4">
                            <div class="h5 text-success">3</div>
                            <small class="text-muted">Commerciaux</small>
                        </div>
                        <div class="col-4">
                            <div class="h5 text-info">12</div>
                            <small class="text-muted">Clients</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Graphiques et métriques -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Répartition par Statut</h6>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-4">
                            <div class="text-secondary display-6">12%</div>
                            <small>Planifiés</small>
                        </div>
                        <div class="col-4">
                            <div class="text-primary display-6">38%</div>
                            <small>En cours</small>
                        </div>
                        <div class="col-4">
                            <div class="text-success display-6">50%</div>
                            <small>Terminés</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-euro-sign me-2"></i>Chiffre d'Affaires</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-success h4">284,500 €</div>
                            <small class="text-muted">CA Réalisé</small>
                        </div>
                        <div class="col-6">
                            <div class="text-primary h4">156,000 €</div>
                            <small class="text-muted">CA Prévisionnel</small>
                        </div>
                    </div>
                    <div class="progress mt-3">
                        <div class="progress-bar bg-success" style="width: 65%"></div>
                        <div class="progress-bar bg-primary" style="width: 35%"></div>
                    </div>
                    <small class="text-muted">Objectif annuel: 440,000 €</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection