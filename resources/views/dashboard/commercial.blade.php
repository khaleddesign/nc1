@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-briefcase me-2"></i>Dashboard Commercial</h1>
            <h2>Bonjour {{ Auth::user()->name }} !</h2>
            <p class="text-muted">Rôle : {{ Auth::user()->role }}</p>
        </div>
    </div>
    
    <!-- Statistiques factices pour le test -->
    <div class="row mt-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3>3</h3>
                    <p>Total Chantiers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3>2</h3>
                    <p>En Cours</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>1</h3>
                    <p>Terminés</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3>65%</h3>
                    <p>Avancement Moyen</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Chantiers factices -->
    <div class="row mt-4">
        <div class="col-md-8">
            <h3>Mes Chantiers</h3>
            
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">🏠 Rénovation Cuisine</h5>
                    <p class="text-muted">
                        <i class="fas fa-user me-1"></i>Marie Martin
                        <br><i class="fas fa-calendar me-1"></i>Fin prévue : 15/02/2025
                    </p>
                    <span class="badge bg-primary mb-2">En cours</span>
                    <div class="progress mb-2" style="height: 25px;">
                        <div class="progress-bar" style="width: 65%">65%</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Budget: 15,000 €</small>
                        <button class="btn btn-sm btn-outline-primary">Voir détails</button>
                    </div>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">🏗️ Extension Garage</h5>
                    <p class="text-muted">
                        <i class="fas fa-user me-1"></i>Pierre Durand
                        <br><i class="fas fa-calendar me-1"></i>Fin prévue : 30/03/2025
                    </p>
                    <span class="badge bg-secondary mb-2">Planifié</span>
                    <div class="progress mb-2" style="height: 25px;">
                        <div class="progress-bar bg-secondary" style="width: 0%">0%</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Budget: 25,000 €</small>
                        <button class="btn btn-sm btn-outline-primary">Voir détails</button>
                    </div>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">🛁 Rénovation Salle de Bain</h5>
                    <p class="text-muted">
                        <i class="fas fa-user me-1"></i>Marie Martin
                        <br><i class="fas fa-calendar me-1"></i>Terminé le : 10/01/2025
                    </p>
                    <span class="badge bg-success mb-2">Terminé</span>
                    <div class="progress mb-2" style="height: 25px;">
                        <div class="progress-bar bg-success" style="width: 100%">100%</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Budget: 8,000 €</small>
                        <button class="btn btn-sm btn-outline-success">Voir détails</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-bell me-2"></i>Notifications</h6>
                </div>
                <div class="card-body">
                    <div class="border-bottom py-2">
                        <div class="fw-bold">Nouveau commentaire</div>
                        <small class="text-muted">Marie Martin a posté un commentaire</small>
                        <div class="small text-muted">Il y a 2 heures</div>
                    </div>
                    <div class="py-2">
                        <div class="fw-bold">Étape terminée</div>
                        <small class="text-muted">Plomberie terminée sur le projet cuisine</small>
                        <div class="small text-muted">Hier</div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Actions rapides</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Nouveau Chantier
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-upload me-2"></i>Ajouter des Photos
                        </button>
                        <button class="btn btn-outline-info btn-sm">
                            <i class="fas fa-file-pdf me-2"></i>Générer Rapport
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection