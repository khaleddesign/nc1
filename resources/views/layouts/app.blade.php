<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'N-C BTP - Gestion Moderne')</title>
    <meta name="description" content="@yield('description', 'Plateforme moderne de gestion BTP - Chantiers, Devis, Factures')">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    
    <!-- Fonts - Inter optimisé -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS + Custom CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('Service Worker enregistré avec succès:', registration.scope);
                        
                        // Vérifier les mises à jour
                        registration.addEventListener('updatefound', function() {
                            const newWorker = registration.installing;
                            newWorker.addEventListener('statechange', function() {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    // Nouvelle version disponible
                                    if (confirm('Une nouvelle version est disponible. Voulez-vous recharger la page ?')) {
                                        window.location.reload();
                                    }
                                }
                            });
                        });
                    })
                    .catch(function(error) {
                        console.error('Erreur lors de l\'enregistrement du Service Worker:', error);
                    });
            });
        }
    </script>
    
    <!-- Additional Styles -->
    @stack('styles')

    
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900 overflow-x-hidden" 
      x-data="{ 
          sidebarOpen: false,
          isMobile: window.innerWidth < 768,
          notifications: []
      }"
      x-init="
          // Responsive handler
          window.addEventListener('resize', () => {
              isMobile = window.innerWidth < 768;
              if (!isMobile) sidebarOpen = false;
          });
          
          // Auto-hide notifications
          $watch('notifications', (value) => {
              value.forEach((notification, index) => {
                  if (notification.autoHide !== false) {
                      setTimeout(() => {
                          notifications.splice(index, 1);
                      }, notification.duration || 5000);
                  }
              });
          });
      ">
    
    <!-- Flash Messages / Notifications -->
    @if(session('success') || session('error') || session('warning') || session('info'))
        <div class="fixed top-4 right-4 z-100 space-y-3 pointer-events-none"
             x-data="{ 
                 messages: [
                     @if(session('success'))
                         { type: 'success', text: '{{ session('success') }}', id: Date.now() + Math.random() },
                     @endif
                     @if(session('error'))
                         { type: 'error', text: '{{ session('error') }}', id: Date.now() + Math.random() + 1 },
                     @endif
                     @if(session('warning'))
                         { type: 'warning', text: '{{ session('warning') }}', id: Date.now() + Math.random() + 2 },
                     @endif
                     @if(session('info'))
                         { type: 'info', text: '{{ session('info') }}', id: Date.now() + Math.random() + 3 },
                     @endif
                 ].filter(Boolean)
             }"
             x-init="
                 messages.forEach((message, index) => {
                     setTimeout(() => {
                         messages = messages.filter(m => m.id !== message.id);
                     }, 5000 + (index * 500));
                 });
             ">
            
            <template x-for="message in messages" :key="message.id">
                <div class="animate-fade-in-up max-w-sm pointer-events-auto"
                     x-show="messages.find(m => m.id === message.id)"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-full scale-95"
                     x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-x-0 scale-100"
                     x-transition:leave-end="opacity-0 transform translate-x-full scale-95">
                    
                    <div class="card p-4 shadow-strong border-l-4"
                         :class="{
                             'border-emerald-400 bg-emerald-50': message.type === 'success',
                             'border-red-400 bg-red-50': message.type === 'error',
                             'border-amber-400 bg-amber-50': message.type === 'warning',
                             'border-cyan-400 bg-cyan-50': message.type === 'info'
                         }">
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-3">
                                <!-- Success Icon -->
                                <svg x-show="message.type === 'success'" 
                                     class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                
                                <!-- Error Icon -->
                                <svg x-show="message.type === 'error'" 
                                     class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                
                                <!-- Warning Icon -->
                                <svg x-show="message.type === 'warning'" 
                                     class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                
                                <!-- Info Icon -->
                                <svg x-show="message.type === 'info'" 
                                     class="w-5 h-5 text-cyan-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                                </svg>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium"
                                   :class="{
                                       'text-emerald-800': message.type === 'success',
                                       'text-red-800': message.type === 'error',
                                       'text-amber-800': message.type === 'warning',
                                       'text-cyan-800': message.type === 'info'
                                   }"
                                   x-text="message.text">
                                </p>
                            </div>
                            
                            <button @click="messages = messages.filter(m => m.id !== message.id)"
                                    class="ml-3 flex-shrink-0 text-slate-400 hover:text-slate-600 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    @endif
    
    <!-- Loading Overlay -->
    <div x-show="false" 
         x-data="{ loading: false }"
         x-on:start-loading.window="loading = true"
         x-on:stop-loading.window="loading = false"
         x-show="loading"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-100 bg-slate-900 bg-opacity-50 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen">
            <div class="card p-8 max-w-sm mx-4">
                <div class="flex items-center space-x-4">
                    <div class="spinner"></div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Chargement...</h3>
                        <p class="text-sm text-slate-600">Veuillez patienter</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Overlay -->
    <div x-show="isMobile && sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-80 bg-slate-900 bg-opacity-50 backdrop-blur-sm lg:hidden">
    </div>
    
    <!-- Application Layout -->
    <div class="min-h-screen flex">
        
        {{-- Sidebar - Composant à créer --}}
        <div class="sidebar-transition"
             :class="{
                 'translate-x-0': !isMobile || sidebarOpen,
                 '-translate-x-full': isMobile && !sidebarOpen
             }"
             class="fixed inset-y-0 left-0 z-90 w-72 bg-white border-r border-slate-200 shadow-soft lg:translate-x-0">
            @include('components.sidebar')
        </div>
        
        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col"
             :class="{ 'lg:ml-72': true }">
            
            {{-- Header - Composant à créer --}}
            <header class="sticky top-0 z-70 bg-white border-b border-slate-200 shadow-soft">
                @include('components.header')
            </header>
            
            {{-- Breadcrumb (optionnel) --}}
            @hasSection('breadcrumb')
                <nav class="bg-slate-50 border-b border-slate-200 px-6 py-3">
                    <div class="flex items-center space-x-2 text-sm">
                        <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-slate-700 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-9 9a1 1 0 001.414 1.414L2 12.414V15a1 1 0 001 1h3a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h3a1 1 0 001-1v-2.586l.293.293a1 1 0 001.414-1.414l-9-9z"/>
                            </svg>
                        </a>
                        <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                        </svg>
                        @yield('breadcrumb')
                    </div>
                </nav>
            @endif
            
            {{-- Page Content --}}
            <main class="flex-1 bg-slate-50">
                <div class="p-6 lg:p-8">
                    {{-- Page Header (optionnel) --}}
                    @hasSection('page-header')
                        <div class="mb-8">
                            @yield('page-header')
                        </div>
                    @endif
                    
                    {{-- Main Content --}}
                    <div class="animate-fade-in-up">
                        @yield('content')
                    </div>
                </div>
            </main>
            
            {{-- Footer (optionnel) --}}
            @hasSection('footer')
                <footer class="bg-white border-t border-slate-200 px-6 py-4">
                    @yield('footer')
                </footer>
            @endif
        </div>
    </div>
    
    {{-- Quick Actions FAB (optionnel) --}}
    @hasSection('fab')
        <div class="fixed bottom-6 right-6 z-60">
            @yield('fab')
        </div>
    @endif
    
    {{-- Global Scripts --}}
    <script>
        // Global helper functions
        window.NC_BTP = {
            // Show notification
            notify: function(message, type = 'info', duration = 5000) {
                window.dispatchEvent(new CustomEvent('show-notification', {
                    detail: { message, type, duration }
                }));
            },
            
            // Show loading
            showLoading: function() {
                window.dispatchEvent(new CustomEvent('start-loading'));
            },
            
            // Hide loading
            hideLoading: function() {
                window.dispatchEvent(new CustomEvent('stop-loading'));
            },
            
            // Confirm dialog
            confirm: function(message, callback) {
                if (window.confirm(message)) {
                    callback();
                }
            },
            
            // Format currency
            formatCurrency: function(amount) {
                return new Intl.NumberFormat('fr-FR', {
                    style: 'currency',
                    currency: 'EUR'
                }).format(amount);
            },
            
            // Format date
            formatDate: function(date) {
                return new Date(date).toLocaleDateString('fr-FR');
            }
        };
        
        // Global event listeners
        document.addEventListener('alpine:init', () => {
            // Auto-save forms (optionnel)
            Alpine.data('autoSave', () => ({
                saving: false,
                saved: false,
                
                async save(formData) {
                    this.saving = true;
                    try {
                        // Implémentation selon vos besoins
                        this.saved = true;
                        setTimeout(() => this.saved = false, 2000);
                    } catch (error) {
                        NC_BTP.notify('Erreur lors de la sauvegarde', 'error');
                    } finally {
                        this.saving = false;
                    }
                }
            }));
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K pour la recherche
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.querySelector('[data-search-input]')?.focus();
            }
            
            // Escape pour fermer les modales
            if (e.key === 'Escape') {
                window.dispatchEvent(new CustomEvent('close-modal'));
            }
        });
        
        // Service Worker pour PWA (optionnel)
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    console.log('SW registered: ', registration);
                }, function(registrationError) {
                    console.log('SW registration failed: ', registrationError);
                });
            });
        }
    </script>
    
    {{-- Additional Scripts --}}
    @stack('scripts')
    @yield('scripts')
    
    {{-- Development helpers --}}
    @if(config('app.debug'))
        <script>
            // Development mode indicators
            console.log('🏗️ N-C BTP Application - Mode Développement');
            console.log('📱 Device:', window.innerWidth < 768 ? 'Mobile' : 'Desktop');
            console.log('🎨 Tailwind CSS & Alpine.js chargés');
        </script>
    @endif
</body>
</html>