{{-- 
    components/header.blade.php
    Header moderne N-C BTP avec recherche et user menu - VERSION SÉCURISÉE
    Style : HubSpot/Leanpay Premium
--}}

@auth
<div class="px-6 py-4" x-data="headerData()">
    <div class="flex items-center justify-between">
        
        {{-- Left Section : Menu Mobile + Page Title --}}
        <div class="flex items-center">
            {{-- Mobile Menu Button --}}
            <button @click="$dispatch('toggle-sidebar')" 
                    class="lg:hidden p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-all duration-200 mr-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            {{-- Page Title & Subtitle --}}
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                    @yield('page-title', 'Tableau de bord')
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    @yield('page-subtitle', 'Vue d\'ensemble de votre activité BTP')
                </p>
            </div>
        </div>

        {{-- Center Section : Search Bar --}}
        <div class="hidden md:flex flex-1 max-w-lg mx-8">
            <div class="relative w-full">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" 
                           x-model="searchQuery"
                           @focus="searchOpen = true"
                           @keydown.escape="searchOpen = false; searchQuery = ''"
                           @keydown.enter="performSearch()"
                           data-search-input
                           class="block w-full pl-10 pr-4 py-3 border border-slate-300 rounded-xl bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm hover:shadow-md"
                           placeholder="Rechercher un chantier, client, devis... (Ctrl+K)">
                </div>

                {{-- Search Dropdown Results --}}
                <div x-show="searchOpen && searchQuery.length > 0"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.away="searchOpen = false"
                     class="absolute z-50 mt-2 w-full bg-white rounded-xl shadow-strong border border-slate-200 max-h-96 overflow-y-auto">
                    
                    {{-- Search Results Header --}}
                    <div class="px-4 py-3 border-b border-slate-100">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-700">Résultats de recherche</span>
                            <span class="text-xs text-slate-500" x-text="`${searchResults.length} résultat(s)`"></span>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="px-4 py-3 border-b border-slate-100">
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Actions rapides</div>
                        <div class="space-y-1">
                            <a href="{{ route('chantiers.create') }}" class="search-result-item group">
                                <div class="search-result-icon bg-indigo-100 text-indigo-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="search-result-title">Nouveau Chantier</div>
                                    <div class="search-result-subtitle">Créer un nouveau chantier</div>
                                </div>
                            </a>
                            
                            @php
                                $user = auth()->user();
                                $canCreateDevis = false;
                                
                                if ($user) {
                                    if (method_exists($user, 'isAdmin') && method_exists($user, 'isCommercial')) {
                                        $canCreateDevis = $user->isAdmin() || $user->isCommercial();
                                    } else {
                                        // Fallback basé sur le rôle
                                        $canCreateDevis = in_array($user->role ?? '', ['admin', 'commercial']);
                                    }
                                }
                            @endphp
                            
                            @if($canCreateDevis)
                            <a href="{{ route('devis.create') }}" class="search-result-item group">
                                <div class="search-result-icon bg-emerald-100 text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="search-result-title">Nouveau Devis</div>
                                    <div class="search-result-subtitle">Créer un nouveau devis</div>
                                </div>
                            </a>
                            @endif
                        </div>
                    </div>

                    {{-- Dynamic Search Results --}}
                    <div class="px-4 py-3" x-show="searchResults.length > 0">
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Résultats</div>
                        <template x-for="result in searchResults" :key="result.id">
                            <a :href="result.url" class="search-result-item group">
                                <div class="search-result-icon"
                                     :class="{
                                         'bg-blue-100 text-blue-600': result.type === 'chantier',
                                         'bg-yellow-100 text-yellow-600': result.type === 'devis',
                                         'bg-green-100 text-green-600': result.type === 'facture',
                                         'bg-purple-100 text-purple-600': result.type === 'client'
                                     }">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path x-show="result.type === 'chantier'" stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                        <path x-show="result.type === 'devis'" stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        <path x-show="result.type === 'facture'" stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        <path x-show="result.type === 'client'" stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="search-result-title" x-text="result.title"></div>
                                    <div class="search-result-subtitle" x-text="result.subtitle"></div>
                                </div>
                                <div class="search-result-badge" 
                                     :class="{
                                         'badge-primary': result.type === 'chantier',
                                         'badge-warning': result.type === 'devis',
                                         'badge-success': result.type === 'facture',
                                         'badge-info': result.type === 'client'
                                     }"
                                     x-text="result.type">
                                </div>
                            </a>
                        </template>
                    </div>

                    {{-- No Results --}}
                    <div class="px-4 py-8 text-center" x-show="searchQuery.length > 2 && searchResults.length === 0">
                        <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">Aucun résultat</h3>
                        <p class="mt-1 text-sm text-slate-500">Essayez avec d'autres mots-clés</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Section : Actions & User Menu --}}
        <div class="flex items-center space-x-4">
            
            {{-- Mobile Search Button --}}
            <button @click="mobileSearchOpen = !mobileSearchOpen"
                    class="md:hidden p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-all duration-200">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </button>

            {{-- Quick Actions Dropdown --}}
            <div class="relative" x-data="{ quickActionsOpen: false }">
                <button @click="quickActionsOpen = !quickActionsOpen"
                        class="p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-all duration-200 relative">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </button>

                <div x-show="quickActionsOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.away="quickActionsOpen = false"
                     class="dropdown-menu w-64 right-0">
                    
                    <div class="px-4 py-3 border-b border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-900">Actions rapides</h3>
                    </div>
                    
                    <div class="py-2">
                        <a href="{{ route('chantiers.create') }}" class="dropdown-item">
                            <svg class="w-5 h-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                            <div>
                                <div class="font-medium text-slate-900">Nouveau Chantier</div>
                                <div class="text-xs text-slate-500">Créer un chantier</div>
                            </div>
                        </a>

                        @if($canCreateDevis)
                        <a href="{{ route('devis.create') }}" class="dropdown-item">
                            <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <div>
                                <div class="font-medium text-slate-900">Nouveau Devis</div>
                                <div class="text-xs text-slate-500">Créer un devis</div>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Notifications --}}
            <div class="relative" x-data="{ notificationsOpen: false }">
                <button @click="notificationsOpen = !notificationsOpen"
                        class="p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-all duration-200 relative">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    @php
                        $notificationsCount = 0;
                        if ($user && method_exists($user, 'getNotificationsNonLues')) {
                            try {
                                $notificationsCount = $user->getNotificationsNonLues();
                            } catch (\Exception $e) {
                                $notificationsCount = 0;
                            }
                        }
                    @endphp
                    @if($notificationsCount > 0)
                        <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse font-bold">
                            {{ $notificationsCount }}
                        </span>
                    @endif
                </button>

                <div x-show="notificationsOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.away="notificationsOpen = false"
                     class="dropdown-menu w-80 right-0">
                    
                    <div class="px-4 py-3 border-b border-slate-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-slate-900">Notifications</h3>
                            @if($notificationsCount > 0)
                                <a href="{{ route('notifications.mark-all-read') }}" 
                                   class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                    Tout marquer lu
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="max-h-96 overflow-y-auto">
                        {{-- Récentes notifications --}}
                        @if($user)
                            @forelse($user->notifications()->limit(5)->get() as $notification)
                                <div class="px-4 py-3 hover:bg-slate-50 border-b border-slate-100 last:border-b-0 transition-colors duration-200 {{ $notification->read_at ? '' : 'bg-indigo-50' }}">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-slate-900 truncate">
                                                {{ $notification->titre ?? 'Notification' }}
                                            </p>
                                            <p class="text-sm text-slate-500 truncate">
                                                {{ $notification->message ?? 'Nouvelle notification' }}
                                            </p>
                                            <p class="text-xs text-slate-400 mt-1">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        @if(!$notification->lu)
                                            <div class="w-2 h-2 bg-indigo-500 rounded-full flex-shrink-0"></div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-slate-900">Aucune notification</h3>
                                    <p class="mt-1 text-sm text-slate-500">Vous êtes à jour !</p>
                                </div>
                            @endforelse
                        @endif
                    </div>
                    
                    @if($user && $user->notifications()->count() > 5)
                        <div class="px-4 py-3 border-t border-slate-100">
                            <a href="{{ route('notifications.index') }}" 
                               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                Voir toutes les notifications
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Messages --}}
            <div class="relative">
                <a href="{{ route('messages.index') }}"
                   class="p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-all duration-200 relative">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    @php
                        $messagesCount = 0;
                        if ($user && method_exists($user, 'getUnreadMessagesCount')) {
                            try {
                                $messagesCount = $user->getUnreadMessagesCount();
                            } catch (\Exception $e) {
                                $messagesCount = 0;
                            }
                        }
                    @endphp
                    @if($messagesCount > 0)
                        <span class="absolute -top-1 -right-1 h-5 w-5 bg-cyan-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse font-bold">
                            {{ $messagesCount }}
                        </span>
                    @endif
                </a>
            </div>

            {{-- User Menu --}}
            <div class="relative" x-data="{ userMenuOpen: false }">
                <button @click="userMenuOpen = !userMenuOpen"
                        class="flex items-center space-x-3 p-2 rounded-lg hover:bg-slate-100 transition-all duration-200 group">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-md group-hover:shadow-lg transition-all duration-200">
                        <span class="text-white font-semibold text-sm">
                            {{ $user ? substr($user->name, 0, 1) : 'U' }}
                        </span>
                    </div>
                    <div class="hidden sm:block text-left">
                        <div class="text-sm font-semibold text-slate-900 group-hover:text-indigo-600 transition-colors duration-200">
                            {{ $user ? $user->name : 'Utilisateur' }}
                        </div>
                        <div class="text-xs text-slate-500 capitalize">
                            {{ $user ? ($user->role ?? 'user') : 'Invité' }}
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-all duration-200" 
                         :class="{ 'rotate-180': userMenuOpen }"
                         fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="userMenuOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.away="userMenuOpen = false"
                     class="dropdown-menu w-64 right-0">
                    
                    {{-- User Info --}}
                    <div class="px-4 py-4 border-b border-slate-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-lg">
                                    {{ $user ? substr($user->name, 0, 1) : 'U' }}
                                </span>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-900">{{ $user ? $user->name : 'Utilisateur' }}</div>
                                <div class="text-sm text-slate-500">{{ $user ? $user->email : '' }}</div>
                                @if($user)
                                    @php
                                        $roleClass = 'bg-blue-100 text-blue-700';
                                        $roleText = ucfirst($user->role ?? 'user');
                                        
                                        switch($user->role ?? '') {
                                            case 'admin':
                                                $roleClass = 'bg-red-100 text-red-700';
                                                break;
                                            case 'commercial':
                                                $roleClass = 'bg-yellow-100 text-yellow-700';
                                                break;
                                            case 'client':
                                                $roleClass = 'bg-blue-100 text-blue-700';
                                                break;
                                        }
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mt-1 {{ $roleClass }}">
                                        {{ $roleText }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- Menu Items --}}
                    <div class="py-2">
                        <a href="{{ route('notifications.index') }}" class="dropdown-item">
                            <svg class="w-5 h-5 mr-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <div class="font-medium text-slate-900">Notifications</div>
                                <div class="text-xs text-slate-500">Centre de notifications</div>
                            </div>
                            @if($notificationsCount > 0)
                                <span class="ml-auto bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                                    {{ $notificationsCount }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('messages.index') }}" class="dropdown-item">
                            <svg class="w-5 h-5 mr-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            <div>
                                <div class="font-medium text-slate-900">Messages</div>
                                <div class="text-xs text-slate-500">Messagerie interne</div>
                            </div>
                            @if($messagesCount > 0)
                                <span class="ml-auto bg-cyan-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                                    {{ $messagesCount }}
                                </span>
                            @endif
                        </a>

                        @if($canCreateDevis)
                        <div class="px-4 py-2">
                            <div class="border-t border-slate-100"></div>
                        </div>

                        <a href="{{ route('reports.dashboard') }}" class="dropdown-item">
                            <svg class="w-5 h-5 mr-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            <div>
                                <div class="font-medium text-slate-900">Reporting</div>
                                <div class="text-xs text-slate-500">Analyses et rapports</div>
                            </div>
                        </a>
                        @endif

                        @php
                            $isAdmin = false;
                            if ($user) {
                                if (method_exists($user, 'isAdmin')) {
                                    $isAdmin = $user->isAdmin();
                                } else {
                                    $isAdmin = ($user->role ?? '') === 'admin';
                                }
                            }
                        @endphp

                        @if($isAdmin)
                        <a href="{{ route('admin.users') }}" class="dropdown-item">
                            <svg class="w-5 h-5 mr-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <div>
                                <div class="font-medium text-slate-900">Administration</div>
                                <div class="text-xs text-slate-500">Gestion des utilisateurs</div>
                            </div>
                        </a>
                        @endif
                    </div>

                    {{-- Logout Section --}}
                    <div class="px-4 py-2">
                        <div class="border-t border-slate-100"></div>
                    </div>
                    
                    <div class="py-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item w-full text-left group hover:bg-red-50">
                                <svg class="w-5 h-5 mr-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                </svg>
                                <div>
                                    <div class="font-medium text-red-600 group-hover:text-red-700">Déconnexion</div>
                                    <div class="text-xs text-red-500">Fermer la session</div>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Search Bar --}}
    <div x-show="mobileSearchOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden mt-4 px-6 pb-4">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input type="text" 
                   x-model="mobileSearchQuery"
                   @keydown.enter="performSearch()"
                   class="block w-full pl-10 pr-4 py-3 border border-slate-300 rounded-xl bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                   placeholder="Rechercher...">
        </div>
    </div>
</div>
@endauth

{{-- Header pour utilisateurs non connectés --}}
@guest
<div class="px-6 py-4 border-b border-slate-200">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                <span class="text-white font-bold text-lg">🏗️</span>
            </div>
            <div class="ml-3">
                <h1 class="text-xl font-bold text-slate-900">N-C Gestion BTP</h1>
                <p class="text-xs text-slate-500">Plateforme de gestion</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-4">
            <a href="{{ route('login') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-all duration-200">
                Connexion
            </a>
        </div>
    </div>
</div>
@endguest

{{-- Alpine.js Data --}}
<script>
function headerData() {
    return {
        // États
        mobileSearchOpen: false,
        searchOpen: false,
        searchQuery: '',
        searchResults: [],
        mobileSearchQuery: '',
        
        // Méthodes de recherche
        async performSearch() {
            const query = this.searchQuery || this.mobileSearchQuery;
            if (query.length < 2) return;
            
            try {
                // Loading state
                if (typeof NC_BTP !== 'undefined' && NC_BTP.showLoading) {
                    NC_BTP.showLoading();
                }
                
                // Appel API de recherche (seulement si la route existe)
                const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.searchResults = data.results || [];
                } else {
                    // Pas d'erreur si API pas disponible
                    this.searchResults = [];
                }
            } catch (error) {
                console.log('Recherche non disponible:', error);
                this.searchResults = [];
            } finally {
                if (typeof NC_BTP !== 'undefined' && NC_BTP.hideLoading) {
                    NC_BTP.hideLoading();
                }
            }
        },
        
        // Recherche en temps réel (debounced)
        searchTimeout: null,
        watchSearch() {
            this.$watch('searchQuery', (value) => {
                clearTimeout(this.searchTimeout);
                if (value && value.length >= 2) {
                    this.searchTimeout = setTimeout(() => {
                        this.performSearch();
                    }, 300);
                } else {
                    this.searchResults = [];
                }
            });
        },
        
        // Gestion des raccourcis clavier
        handleKeyboardShortcuts() {
            document.addEventListener('keydown', (e) => {
                // Ctrl/Cmd + K pour focus search
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const searchInput = document.querySelector('[data-search-input]');
                    if (searchInput) {
                        searchInput.focus();
                    } else {
                        this.mobileSearchOpen = true;
                        this.$nextTick(() => {
                            const mobileInput = this.$el.querySelector('input[x-model="mobileSearchQuery"]');
                            if (mobileInput) mobileInput.focus();
                        });
                    }
                }
            });
        },
        
        // Initialisation
        init() {
            this.watchSearch();
            this.handleKeyboardShortcuts();
        }
    }
}
</script>

{{-- Styles spécifiques au header --}}
<style>
/* Search Results */
.search-result-item {
    @apply flex items-center p-3 rounded-lg hover:bg-slate-50 transition-colors duration-200 cursor-pointer;
}

.search-result-icon {
    @apply w-8 h-8 rounded-lg flex items-center justify-center mr-3 flex-shrink-0;
}

.search-result-title {
    @apply font-medium text-slate-900 text-sm;
}

.search-result-subtitle {
    @apply text-xs text-slate-500 mt-1;
}

.search-result-badge {
    @apply ml-auto;
}

/* Dropdown amélioré */
.dropdown-menu {
    @apply absolute mt-2 bg-white rounded-2xl shadow-strong border border-slate-200 focus:outline-none z-50 overflow-hidden;
    min-width: 200px;
}

.dropdown-item {
    @apply flex items-center px-4 py-3 text-sm transition-colors duration-200 hover:bg-slate-50;
}

.dropdown-item:first-child {
    @apply rounded-t-2xl;
}

.dropdown-item:last-child {
    @apply rounded-b-2xl;
}

/* Badges pour les notifications */
.badge-primary {
    @apply bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full font-medium;
}

.badge-warning {
    @apply bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded-full font-medium;
}

.badge-success {
    @apply bg-emerald-100 text-emerald-700 text-xs px-2 py-1 rounded-full font-medium;
}

.badge-info {
    @apply bg-cyan-100 text-cyan-700 text-xs px-2 py-1 rounded-full font-medium;
}

/* Animation pour les badges de notification */
@keyframes notification-pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.8;
    }
}

.animate-notification-pulse {
    animation: notification-pulse 2s infinite;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .dropdown-menu {
        @apply left-0 right-0 mx-4;
        width: auto;
        min-width: auto;
    }
}

.shadow-strong {
    box-shadow: 0 16px 48px rgba(15, 23, 42, 0.16);
}
</style>