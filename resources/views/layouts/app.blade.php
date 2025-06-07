<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Gestion Chantiers') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .navbar-nav .nav-link {
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.25rem;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: bold;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
            padding: 1rem;
        }
        .sidebar .nav-link {
            color: #333;
            padding: 0.5rem 1rem;
            margin-bottom: 0.25rem;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
            color: #0d6efd;
        }
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-hard-hat me-2"></i>{{ config('app.name', 'Gestion Chantiers') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    <ul class="navbar-nav me-auto">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                               href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                        </li>
                        
                        <!-- Chantiers -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('chantiers.*') ? 'active' : '' }}" 
                               href="{{ route('chantiers.index') }}">
                                <i class="fas fa-building me-1"></i>Chantiers
                            </a>
                        </li>
                        
                        @if(Auth::user()->isAdmin() || Auth::user()->isCommercial())
                            <!-- Calendrier -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('chantiers.calendrier') ? 'active' : '' }}" 
                                   href="{{ route('chantiers.calendrier') }}">
                                    <i class="fas fa-calendar me-1"></i>Calendrier
                                </a>
                            </li>
                        @endif
                        
                        @if(Auth::user()->isAdmin())
                            <!-- Administration -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.*') ? 'active' : '' }}" 
                                   href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-cog me-1"></i>Administration
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.users') }}">
                                            <i class="fas fa-users me-2"></i>Utilisateurs
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.statistics') }}">
                                            <i class="fas fa-chart-bar me-2"></i>Statistiques
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                    
                    <ul class="navbar-nav">
                        <!-- Notifications -->
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('notifications.index') }}">
                                <i class="fas fa-bell"></i>
                                @php
                                    $unreadCount = Auth::user()->getNotificationsNonLues();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="notification-badge">{{ $unreadCount }}</span>
                                @endif
                            </a>
                        </li>
                        
                        <!-- Profil utilisateur -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ Auth::user()->name }}
                                <span class="badge bg-secondary ms-1">{{ ucfirst(Auth::user()->role) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user me-2"></i>Mon Profil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cog me-2"></i>Paramètres
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @else
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Inscription</a>
                            </li>
                        @endif
                    </ul>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main content avec sidebar optionnelle -->
    <div class="container-fluid">
        <div class="row">
            @auth
                @if(View::hasSection('sidebar'))
                    <!-- Sidebar -->
                    <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                        @yield('sidebar')
                    </nav>
                    
                    <!-- Content avec sidebar -->
                    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                        @include('partials.alerts')
                        @yield('content')
                    </main>
                @else
                    <!-- Content pleine largeur -->
                    <main class="col-12">
                        @include('partials.alerts')
                        @yield('content')
                    </main>
                @endif
            @else
                <main class="col-12">
                    @yield('content')
                </main>
            @endauth
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center text-lg-start mt-5">
        <div class="text-center p-3 bg-dark text-white">
            © {{ date('Y') }} {{ config('app.name', 'Gestion Chantiers') }} - Tous droits réservés
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (optionnel) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Auto-refresh des notifications -->
    @auth
    <script>
        // Actualisation du badge de notifications toutes les 30 secondes
        setInterval(function() {
            fetch('/api/notifications/count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.notification-badge');
                    if (data.count > 0) {
                        if (badge) {
                            badge.textContent = data.count;
                        } else {
                            const bellIcon = document.querySelector('.fa-bell').parentElement;
                            const newBadge = document.createElement('span');
                            newBadge.className = 'notification-badge';
                            newBadge.textContent = data.count;
                            bellIcon.appendChild(newBadge);
                        }
                    } else if (badge) {
                        badge.remove();
                    }
                });
        }, 30000);
    </script>
    @endauth
    
    @yield('scripts')
</body>
</html>