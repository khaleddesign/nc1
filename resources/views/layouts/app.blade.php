<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Gestion Chantiers') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- AlpineJS pour l'interactivité -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @yield('styles')
</head>
<body class="h-full bg-gray-50" x-data="{ sidebarOpen: false }">
    <!-- Navigation mobile -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 lg:hidden">
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-600 bg-opacity-75"
             @click="sidebarOpen = false"></div>
        
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative flex flex-col flex-1 w-full max-w-xs bg-white">
            <div class="absolute top-0 right-0 pt-2 -mr-12">
                <button @click="sidebarOpen = false" 
                        class="flex items-center justify-center w-10 h-10 ml-1 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Fermer la sidebar</span>
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Sidebar mobile content -->
            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <div class="flex items-center flex-shrink-0 px-4">
                    <h1 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-hard-hat mr-2 text-primary-600"></i>
                        {{ config('app.name') }}
                    </h1>
                </div>
                <nav class="mt-5 px-2 space-y-1">
                    @auth
                        @include('partials.navigation-items')
                    @endauth
                </nav>
            </div>
        </div>
    </div>

    <!-- Sidebar desktop -->
    <div class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0">
        <div class="flex flex-col flex-grow pt-5 bg-white border-r border-gray-200 overflow-y-auto">
            <div class="flex items-center flex-shrink-0 px-4">
                <h1 class="text-xl font-bold text-gray-900">
                    <i class="fas fa-hard-hat mr-2 text-primary-600"></i>
                    {{ config('app.name') }}
                </h1>
            </div>
            <div class="mt-5 flex-grow flex flex-col">
                <nav class="flex-1 px-2 pb-4 space-y-1">
                    @auth
                        @include('partials.navigation-items')
                    @endauth
                </nav>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="lg:pl-64 flex flex-col flex-1">
        <!-- Header -->
        <div class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white shadow">
            <button @click="sidebarOpen = true" 
                    class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 lg:hidden">
                <span class="sr-only">Ouvrir la sidebar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                </svg>
            </button>
            
            <div class="flex-1 px-4 flex justify-between">
                <div class="flex-1 flex">
                    <!-- Barre de recherche -->
                    <div class="w-full flex md:ml-0" x-data="{ searchOpen: false }">
                        <label for="search-field" class="sr-only">Rechercher</label>
                        <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                            <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input id="search-field" 
                                   class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent" 
                                   placeholder="Rechercher des chantiers..." 
                                   type="search" 
                                   name="search">
                        </div>
                    </div>
                </div>
                
                <div class="ml-4 flex items-center md:ml-6">
                    @auth
                        <!-- Notifications -->
                        <div class="relative" x-data="{ notificationOpen: false }">
                            <button @click="notificationOpen = !notificationOpen" 
                                    class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <span class="sr-only">Voir les notifications</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                @php
                                    $unreadCount = Auth::user()->getNotificationsNonLues();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-danger-400 ring-2 ring-white"></span>
                                @endif
                            </button>
                            
                            <!-- Dropdown notifications -->
                            <div x-show="notificationOpen" 
                                 @click.away="notificationOpen = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                <div class="py-1">
                                    <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-200">
                                        <div class="font-medium">Notifications</div>
                                    </div>
                                    @if($unreadCount > 0)
                                        <div class="max-h-96 overflow-y-auto">
                                            @foreach(Auth::user()->notifications()->where('lu', false)->latest()->take(5)->get() as $notification)
                                                <a href="{{ route('chantiers.show', $notification->chantier) }}" 
                                                   class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 border-b border-gray-100">
                                                    <div class="font-medium text-gray-900">{{ $notification->titre }}</div>
                                                    <div class="text-gray-600 mt-1">{{ Str::limit($notification->message, 80) }}</div>
                                                    <div class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                                                </a>
                                            @endforeach
                                        </div>
                                        <div class="px-4 py-2 border-t border-gray-200">
                                            <a href="{{ route('notifications.index') }}" 
                                               class="text-sm text-primary-600 hover:text-primary-500">
                                                Voir toutes les notifications
                                            </a>
                                        </div>
                                    @else
                                        <div class="px-4 py-3 text-sm text-gray-500">
                                            Aucune nouvelle notification
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Menu utilisateur -->
                        <div class="ml-3 relative" x-data="{ profileOpen: false }">
                            <div>
                                <button @click="profileOpen = !profileOpen" 
                                        class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <span class="sr-only">Ouvrir le menu utilisateur</span>
                                    <div class="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <span class="ml-3 text-gray-700 text-sm font-medium lg:block">
                                        <span class="sr-only">Ouvrir le menu utilisateur pour </span>
                                        {{ Auth::user()->name }}
                                    </span>
                                    <svg class="ml-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            
                            <div x-show="profileOpen" 
                                 @click.away="profileOpen = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                <div class="py-1">
                                    <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-200">
                                        <div class="font-medium">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</div>
                                    </div>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i>Mon Profil
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-2"></i>Paramètres
                                    </a>
                                    <div class="border-t border-gray-200"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex space-x-4">
                            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700">
                                Connexion
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-gray-500 hover:text-gray-700">
                                    Inscription
                                </a>
                            @endif
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Contenu de la page -->
        <main class="flex-1">
            @include('partials.alerts-tailwind')
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas fa-hard-hat mr-2 text-primary-600"></i>
                        <span class="text-gray-900 font-medium">{{ config('app.name') }}</span>
                        <span class="ml-2 text-gray-500">© {{ date('Y') }}</span>
                    </div>
                    <div class="flex space-x-6 text-sm text-gray-500">
                        <a href="#" class="hover:text-gray-700">Support</a>
                        <a href="#" class="hover:text-gray-700">Mentions légales</a>
                        <a href="#" class="hover:text-gray-700">Contact</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    <!-- Scripts -->
    <script>
        // Auto-refresh des notifications
        @auth
        setInterval(function() {
            fetch('/api/notifications/count')
                .then(response => response.json())
                .then(data => {
                    // Mise à jour du badge de notification
                    // Cette logique peut être étendue selon vos besoins
                });
        }, 30000);
        @endauth
    </script>
    
    @yield('scripts')
</body>
</html>