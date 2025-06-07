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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4F46E5;
            --secondary-color: #7C3AED;
            --success-color: #10B981;
            --danger-color: #EF4444;
            --warning-color: #F59E0B;
            --info-color: #3B82F6;
            --dark-color: #111827;
            --light-bg: #F9FAFB;
            --card-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --hover-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--light-bg);
            color: #374151;
        }

        /* Navbar moderne */
        .navbar-modern {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-modern .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .navbar-modern .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-modern .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white !important;
            transform: translateY(-1px);
        }

        .navbar-modern .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white !important;
        }

        /* Cards modernes */
        .card-modern {
            background: white;
            border-radius: 1rem;
            border: none;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card-modern:hover {
            box-shadow: var(--hover-shadow);
            transform: translateY(-2px);
        }

        .card-modern .card-header {
            background: transparent;
            border-bottom: 1px solid #E5E7EB;
            padding: 1.5rem;
        }

        /* Statistiques cards */
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            border: none;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, transparent 0%, rgba(79, 70, 229, 0.1) 100%);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        /* Boutons modernes */
        .btn-modern {
            padding: 0.625rem 1.25rem;
            font-weight: 500;
            border-radius: 0.5rem;
            border: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.35);
            color: white;
        }

        /* Progress bars */
        .progress-modern {
            height: 8px;
            background-color: #E5E7EB;
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-modern .progress-bar {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 999px;
            transition: width 0.6s ease;
        }

        /* Badges */
        .badge-modern {
            padding: 0.375rem 0.75rem;
            font-weight: 500;
            font-size: 0.75rem;
            border-radius: 0.375rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Sidebar moderne */
        .sidebar-modern {
            background: white;
            min-height: calc(100vh - 80px);
            box-shadow: 2px 0 6px -1px rgba(0, 0, 0, 0.1);
            padding: 2rem 1rem;
        }

        .sidebar-modern .nav-link {
            color: #6B7280;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.25rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar-modern .nav-link:hover {
            background-color: #F3F4F6;
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .sidebar-modern .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        /* Notifications */
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger-color);
            color: white;
            border-radius: 999px;
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }

        /* Tables modernes */
        .table-modern {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .table-modern thead {
            background-color: #F9FAFB;
        }

        .table-modern th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #6B7280;
            padding: 1rem;
            border: none;
        }

        .table-modern td {
            padding: 1rem;
            border-top: 1px solid #E5E7EB;
            vertical-align: middle;
        }

        .table-modern tbody tr {
            transition: all 0.3s ease;
        }

        .table-modern tbody tr:hover {
            background-color: #F9FAFB;
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Dropdown moderne */
        .dropdown-menu {
            border: none;
            box-shadow: var(--hover-shadow);
            border-radius: 0.75rem;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
        }

        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar-modern {
                display: none;
            }
            
            .stat-card {
                margin-bottom: 1rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-modern">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-hard-hat"></i>
                {{ config('app.name', 'Gestion Chantiers') }}
            </a>
            
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                               href="{{ route('dashboard') }}" data-aos="fade-down" data-aos-delay="100">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('chantiers.*') ? 'active' : '' }}" 
                               href="{{ route('chantiers.index') }}" data-aos="fade-down" data-aos-delay="200">
                                <i class="fas fa-building me-1"></i>Chantiers
                            </a>
                        </li>
                        
                        @if(Auth::user()->isAdmin() || Auth::user()->isCommercial())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('chantiers.calendrier') ? 'active' : '' }}" 
                                   href="{{ route('chantiers.calendrier') }}" data-aos="fade-down" data-aos-delay="300">
                                    <i class="fas fa-calendar me-1"></i>Calendrier
                                </a>
                            </li>
                        @endif
                        
                        @if(Auth::user()->isAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.*') ? 'active' : '' }}" 
                                   href="#" role="button" data-bs-toggle="dropdown" data-aos="fade-down" data-aos-delay="400">
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
                        <li class="nav-item me-3">
                            <a class="nav-link position-relative" href="{{ route('notifications.index') }}">
                                <i class="fas fa-bell fa-lg"></i>
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
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="text-start">
                                    <div class="fw-semibold">{{ Auth::user()->name }}</div>
                                    <div class="small opacity-75">{{ ucfirst(Auth::user()->role) }}</div>
                                </div>
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
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Connexion
                            </a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i>Inscription
                                </a>
                            </li>
                        @endif
                    </ul>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <div class="container-fluid px-4 py-4">
        @auth
            @if(View::hasSection('sidebar'))
                <div class="row">
                    <!-- Sidebar -->
                    <nav class="col-md-3 col-lg-2 sidebar-modern">
                        @yield('sidebar')
                    </nav>
                    
                    <!-- Content avec sidebar -->
                    <main class="col-md-9 col-lg-10">
                        @include('partials.alerts')
                        @yield('content')
                    </main>
                </div>
            @else
                <!-- Content pleine largeur -->
                <main>
                    @include('partials.alerts')
                    @yield('content')
                </main>
            @endif
        @else
            <main>
                @yield('content')
            </main>
        @endauth
    </div>

    <!-- Footer moderne -->
    <footer class="mt-5 py-4 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-hard-hat me-2"></i>{{ config('app.name') }}</h5>
                    <p class="mb-0 opacity-75">Gestion professionnelle de vos chantiers</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 opacity-75">
                        © {{ date('Y') }} Tous droits réservés | 
                        <a href="#" class="text-white">Mentions légales</a> | 
                        <a href="#" class="text-white">Support</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialisation AOS
        AOS.init({
            duration: 800,
            once: true
        });
        
        // Auto-refresh des notifications
        @auth
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
        @endauth
    </script>
    
    @yield('scripts')
</body>
</html>