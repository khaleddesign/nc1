<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name', 'Gestion Chantiers'))</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts et Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 antialiased" x-data="{ mobileMenuOpen: false }">
    <div id="app">
        <!-- Navigation -->
        @auth
            <nav class="bg-white shadow-lg border-b border-gray-200 sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        {{-- Logo et navigation principale --}}
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center">
                                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-900 hover:text-blue-600 transition-colors duration-200">
                                    🏗️ {{ config('app.name', 'Gestion Chantiers') }}
                                </a>
                            </div>
                            
                            {{-- Menu principal desktop --}}
                            <div class="hidden space-x-1 sm:-my-px sm:ml-10 sm:flex">
                                {{-- Dashboard --}}
                                <a href="{{ route('dashboard') }}" 
                                   class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                    <svg class="w-4 h-4 mr-2 inline-flex items-center" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                    </svg>
                                    <span class="inline-flex items-center">Dashboard</span>
                                </a>
                                
                                {{-- Chantiers --}}
                                <a href="{{ route('chantiers.index') }}" 
                                   class="nav-link {{ request()->routeIs('chantiers.*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                    <svg class="w-4 h-4 mr-2 inline-flex items-center" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18M6.75 9h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-3.75-16.5h3.75A2.25 2.25 0 0121 6.75v12a2.25 2.25 0 01-2.25 2.25h-3.75M16.5 7.5V21a.75.75 0 01-.75.75M6 7.5V21a.75.75 0 01-.75.75" />
                                    </svg>
                                    <span class="inline-flex items-center">Chantiers</span>
                                </a>
                                
                                {{-- NOUVEAU : Dropdown Gestion --}}
                                @can('commercial-or-admin')
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="nav-link {{ request()->routeIs(['devis.*', 'factures.*']) ? 'nav-link-active' : 'nav-link-inactive' }} flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        Gestion
                                        <svg class="ml-1 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    
                                    {{-- Dropdown menu --}}
                                    <div x-show="open" @click.away="open = false" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <div class="py-1">
                                            <a href="{{ route('devis.global.index') }}" class="dropdown-item flex items-center group">
                                                <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621 0 1.125-.504 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                                <div>
                                                    <div class="font-medium text-gray-900">Devis</div>
                                                    <div class="text-xs text-gray-500">Gérer tous les devis</div>
                                                </div>
                                            </a>
                                            <a href="{{ route('factures.global.index') }}" class="dropdown-item flex items-center group">
                                                <svg class="w-4 h-4 mr-3 text-gray-400 group-hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M16.5 18.75h-9A2.25 2.25 0 015.25 16.5v-10.5A2.25 2.25 0 017.5 3.75h1.5m0 0h6m-6 0v1.5m6-1.5v1.5m6 1.5v10.5a2.25 2.25 0 01-2.25 2.25H13.5m-6-0h6m-6 3h6" />
                                                </svg>
                                                <div>
                                                    <div class="font-medium text-gray-900">Factures</div>
                                                    <div class="text-xs text-gray-500">Gérer toutes les factures</div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endcan
                                
                                {{-- Reporting --}}
                                @can('commercial-or-admin')
                                <a href="{{ route('reports.dashboard') }}" 
                                   class="nav-link {{ request()->routeIs('reports.*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                    <svg class="w-4 h-4 mr-2 inline-flex items-center" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                    </svg>
                                    <span class="inline-flex items-center">Reporting</span>
                                </a>
                                @endcan
                                
                                {{-- Admin --}}
                                @can('admin-only')
                                    <a href="{{ route('admin.users') }}" 
                                       class="nav-link {{ request()->routeIs('admin.*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                        <svg class="w-4 h-4 mr-2 inline-flex items-center" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                        </svg>
                                        <span class="inline-flex items-center">Admin</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                        
                        {{-- Icônes et Menu utilisateur --}}
                        <div class="hidden sm:flex sm:items-center space-x-4">
                            {{-- Notifications --}}
                            <div class="relative">
                                <a href="{{ route('notifications.index') }}" 
                                   class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-all duration-200 relative flex items-center">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                    </svg>
                                    @if(Auth::user()->getNotificationsNonLues() > 0)
                                        <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse">
                                            {{ Auth::user()->getNotificationsNonLues() }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                            
                            {{-- NOUVEAU : Icône Messages --}}
                            <div class="relative">
                                <a href="{{ route('messages.index') }}" 
                                   class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-all duration-200 relative flex items-center">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07 1.916V6.75" />
                                    </svg>
                                    @if(Auth::user()->getUnreadMessagesCount() > 0)
                                        <span class="absolute -top-1 -right-1 h-5 w-5 bg-blue-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse">
                                            {{ Auth::user()->getUnreadMessagesCount() }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                            
                            {{-- Menu utilisateur --}}
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 hover:bg-gray-50 p-2 transition-all duration-200">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center shadow-md">
                                        <span class="text-sm font-medium text-white">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <span class="ml-2 text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                                    <svg class="ml-1 h-4 w-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                
                                {{-- Menu déroulant --}}
                                <div x-show="open" @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="origin-top-right absolute right-0 mt-2 w-64 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <div class="py-1">
                                        <div class="px-4 py-3 border-b border-gray-100">
                                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                                            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ Auth::user()->role === 'admin' ? 'red' : (Auth::user()->role === 'commercial' ? 'yellow' : 'blue') }}-100 text-{{ Auth::user()->role === 'admin' ? 'red' : (Auth::user()->role === 'commercial' ? 'yellow' : 'blue') }}-800">
                                                {{ ucfirst(Auth::user()->role) }}
                                            </span>
                                        </div>
                                        <a href="{{ route('notifications.index') }}" class="dropdown-item flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                            </svg>
                                            Notifications
                                            @if(Auth::user()->getNotificationsNonLues() > 0)
                                                <span class="ml-auto bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                                    {{ Auth::user()->getNotificationsNonLues() }}
                                                </span>
                                            @endif
                                        </a>
                                        <a href="{{ route('messages.index') }}" class="dropdown-item flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07 1.916V6.75" />
                                            </svg>
                                            Messages
                                            @if(Auth::user()->getUnreadMessagesCount() > 0)
                                                <span class="ml-auto bg-blue-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                                    {{ Auth::user()->getUnreadMessagesCount() }}
                                                </span>
                                            @endif
                                        </a>
                                        <div class="border-t border-gray-100"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item w-full text-left flex items-center text-red-600 hover:bg-red-50">
                                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                                </svg>
                                                Déconnexion
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Menu mobile --}}
                        <div class="sm:hidden flex items-center">
                            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none p-2 rounded-md hover:bg-gray-100 transition-colors duration-200">
                                <svg class="h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <svg class="h-6 w-6" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                {{-- Menu mobile --}}
                <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="sm:hidden border-t border-gray-200 bg-white">
                    <div class="pt-2 pb-3 space-y-1">
                        <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 flex items-center transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('chantiers.index') }}" class="block pl-3 pr-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 flex items-center transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18M6.75 9h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-3.75-16.5h3.75A2.25 2.25 0 0121 6.75v12a2.25 2.25 0 01-2.25 2.25h-3.75M16.5 7.5V21a.75.75 0 01-.75.75M6 7.5V21a.75.75 0 01-.75.75" />
                            </svg>
                            Chantiers
                        </a>
                        
                        {{-- Menu Gestion mobile --}}
                        @can('commercial-or-admin')
                        <div class="pl-3 pr-4 py-2">
                            <div class="text-sm font-medium text-gray-500 mb-2">Gestion</div>
                            <a href="{{ route('devis.global.index') }}" class="block pl-4 pr-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 flex items-center transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M16.5 18.75h-9A2.25 2.25 0 015.25 16.5v-10.5A2.25 2.25 0 017.5 3.75h1.5m0 0h6m-6 0v1.5m6-1.5v1.5m6 1.5v10.5a2.25 2.25 0 01-2.25 2.25H13.5m-6-0h6m-6 3h6" />
                                </svg>
                                Devis
                            </a>
                            <a href="{{ route('factures.global.index') }}" class="block pl-4 pr-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 flex items-center transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M16.5 18.75h-9A2.25 2.25 0 015.25 16.5v-10.5A2.25 2.25 0 017.5 3.75h1.5m0 0h6m-6 0v1.5m6-1.5v1.5m6 1.5v10.5a2.25 2.25 0 01-2.25 2.25H13.5m-6-0h6m-6 3h6" />
                                </svg>
                                Factures
                            </a>
                        </div>
                        @endcan
                        
                        {{-- Reporting mobile --}}
                        @can('commercial-or-admin')
                        <a href="{{ route('reports.dashboard') }}" class="block pl-3 pr-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 flex items-center transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            Reporting
                        </a>
                        @endcan
                        
                        {{-- Messages mobile --}}
                        <a href="{{ route('messages.index') }}" class="block pl-3 pr-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 flex items-center transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07 1.916V6.75" />
                            </svg>
                            Messages
                            @if(Auth::user()->getUnreadMessagesCount() > 0)
                                <span class="ml-auto bg-blue-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ Auth::user()->getUnreadMessagesCount() }}
                                </span>
                            @endif
                        </a>
                        
                        {{-- Admin mobile --}}
                        @can('admin-only')
                            <a href="{{ route('admin.users') }}" class="block pl-3 pr-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 flex items-center transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                                Admin
                            </a>
                        @endcan
                    </div>
                    
                    {{-- User section mobile --}}
                    <div class="pt-4 pb-3 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <span class="text-sm font-medium text-white">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <a href="{{ route('notifications.index') }}" class="block pl-3 pr-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-50 flex items-center transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                Notifications
                                @if(Auth::user()->getNotificationsNonLues() > 0)
                                    <span class="ml-auto bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ Auth::user()->getNotificationsNonLues() }}
                                    </span>
                                @endif
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left pl-3 pr-4 py-2 text-red-600 hover:text-red-900 hover:bg-red-50 flex items-center transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                    </svg>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        @endauth
        
        {{-- Messages Flash --}}
        @if(session('success'))
            <div class="alert alert-success animate-fade-in-up mx-4 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <svg class="w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
                <button @click="show = false" class="ml-auto text-green-600 hover:text-green-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error animate-fade-in-up mx-4 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <svg class="w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                {{ session('error') }}
                <button @click="show = false" class="ml-auto text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
        
        @if(session('warning'))
            <div class="alert alert-warning animate-fade-in-up mx-4 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <svg class="w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                {{ session('warning') }}
                <button @click="show = false" class="ml-auto text-yellow-600 hover:text-yellow-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info animate-fade-in-up mx-4 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <svg class="w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                {{ session('info') }}
                <button @click="show = false" class="ml-auto text-blue-600 hover:text-blue-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
        
        {{-- Contenu principal --}}
        <main class="min-h-screen">
            @yield('content')
        </main>
    </div>
    
    {{-- Scripts --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
    @yield('scripts')
</body>
</html>