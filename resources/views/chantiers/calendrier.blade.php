@extends('layouts.app')

@section('title', 'Calendrier des chantiers')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- Header avec dégradé -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-6">
                            <h1 class="text-3xl font-bold text-white sm:text-4xl">
                                Calendrier des Chantiers
                            </h1>
                            <p class="mt-2 text-emerald-100 text-lg">
                                Vue d'ensemble de la planification des projets 📅
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex space-x-3 md:mt-0 md:ml-4">
                    @can('create', App\Models\Chantier::class)
                        <a href="{{ route('chantiers.create') }}" 
                           class="inline-flex items-center px-6 py-3 border border-transparent rounded-full shadow-sm text-sm font-medium text-emerald-700 bg-white hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 transform hover:scale-105">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Nouveau Chantier
                        </a>
                    @endcan
                    <a href="{{ route('chantiers.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-white/20 rounded-full shadow-sm text-sm font-medium text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        Vue Liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <!-- Total Chantiers -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium uppercase tracking-wide">Total</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $stats['total_chantiers'] ?? 0 }}</p>
                            <p class="text-blue-200 text-xs mt-1">Chantiers planifiés</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18M6.75 9h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-3.75-16.5h3.75A2.25 2.25 0 0121 6.75v12a2.25 2.25 0 01-2.25 2.25h-3.75M16.5 7.5V21a.75.75 0 01-.75.75M6 7.5V21a.75.75 0 01-.75.75" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- En Cours -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-amber-100 text-sm font-medium uppercase tracking-wide">En Cours</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $stats['en_cours'] ?? 0 }}</p>
                            <p class="text-amber-200 text-xs mt-1">Projets actifs</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center">
                            <div class="h-2 w-2 bg-white rounded-full animate-pulse"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Planifiés -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium uppercase tracking-wide">Planifiés</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $stats['planifies'] ?? 0 }}</p>
                            <p class="text-purple-200 text-xs mt-1">À venir</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- En Retard -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-red-500 to-red-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm font-medium uppercase tracking-wide">En Retard</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $stats['en_retard'] ?? 0 }}</p>
                            <p class="text-red-200 text-xs mt-1">À traiter</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Légende et contrôles -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>
                        Légende des couleurs
                    </h3>
                    <div class="mt-3 sm:mt-0 flex items-center space-x-2">
                        <button onclick="toggleView('month')" 
                                class="calendar-view-btn active px-3 py-1 text-sm rounded-lg bg-blue-100 text-blue-700 font-medium transition-colors"
                                data-view="month">
                            Mois
                        </button>
                        <button onclick="toggleView('week')" 
                                class="calendar-view-btn px-3 py-1 text-sm rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-colors"
                                data-view="week">
                            Semaine
                        </button>
                        <button onclick="toggleView('list')" 
                                class="calendar-view-btn px-3 py-1 text-sm rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition-colors"
                                data-view="list">
                            Liste
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-gray-400 rounded mr-3"></div>
                        <span class="text-sm text-gray-700 font-medium">Planifié</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-700 font-medium">En cours</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-700 font-medium">Terminé</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-500 rounded mr-3"></div>
                        <span class="text-sm text-gray-700 font-medium">En retard</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendrier principal -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6">
                <div id="calendar-container" class="min-h-96">
                    <!-- Le calendrier sera injecté ici par JavaScript -->
                    <div class="text-center py-12">
                        <div class="animate-spin inline-block w-8 h-8 border-4 border-current border-t-transparent text-blue-600 rounded-full"></div>
                        <p class="mt-4 text-gray-500">Chargement du calendrier...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vue liste (masquée par défaut) -->
        <div id="list-view" class="hidden mt-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        Chantiers par ordre chronologique
                    </h3>
                </div>
                <div class="overflow-hidden">
                    <div id="list-content" class="p-6">
                        <!-- Contenu généré par JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de détails de chantier -->
<div id="chantierModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-96 overflow-y-auto">
            <div class="flex justify-between items-center p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-xl font-semibold text-gray-900" id="modal-title">Détails du chantier</h3>
                <button onclick="closeChantierModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="modal-content" class="p-6">
                <!-- Contenu dynamique -->
            </div>
            <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50">
                <button onclick="closeChantierModal()" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Fermer
                </button>
                <div id="modal-actions" class="flex space-x-3">
                    <!-- Actions dynamiques -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données des chantiers depuis le serveur
    const chantiers = @json($events ?? []);
    
    let currentView = 'month';
    let currentDate = new Date();
    
    // Initialiser le calendrier
    initializeCalendar();
    
    function initializeCalendar() {
        if (currentView === 'list') {
            showListView();
        } else {
            showCalendarView();
        }
    }
    
    function showCalendarView() {
        document.getElementById('calendar-container').classList.remove('hidden');
        document.getElementById('list-view').classList.add('hidden');
        
        if (currentView === 'month') {
            renderMonthView();
        } else if (currentView === 'week') {
            renderWeekView();
        }
    }
    
    function showListView() {
        document.getElementById('calendar-container').classList.add('hidden');
        document.getElementById('list-view').classList.remove('hidden');
        renderListView();
    }
    
    function renderMonthView() {
        const container = document.getElementById('calendar-container');
        const month = currentDate.getMonth();
        const year = currentDate.getFullYear();
        
        // En-tête du calendrier
        const monthNames = [
            'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
        ];
        
        let html = `
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-900">${monthNames[month]} ${year}</h2>
                    <div class="flex space-x-2">
                        <button onclick="changeMonth(-1)" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button onclick="goToToday()" class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            Aujourd'hui
                        </button>
                        <button onclick="changeMonth(1)" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Grille du calendrier -->
                <div class="grid grid-cols-7 gap-1 mb-2">
                    ${['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'].map(day => 
                        `<div class="p-2 text-center text-sm font-medium text-gray-500">${day}</div>`
                    ).join('')}
                </div>
                
                <div class="grid grid-cols-7 gap-1" id="calendar-grid">
                    ${generateMonthGrid(year, month)}
                </div>
            </div>
        `;
        
        container.innerHTML = html;
    }
    
    function generateMonthGrid(year, month) {
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - (firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1));
        
        let html = '';
        let currentGridDate = new Date(startDate);
        
        for (let week = 0; week < 6; week++) {
            for (let day = 0; day < 7; day++) {
                const dayChantiers = getChantiersByDate(currentGridDate);
                const isCurrentMonth = currentGridDate.getMonth() === month;
                const isToday = isDateToday(currentGridDate);
                
                let dayClass = 'min-h-24 p-2 border border-gray-100 ';
                if (!isCurrentMonth) dayClass += 'bg-gray-50 text-gray-400 ';
                if (isToday) dayClass += 'bg-blue-50 border-blue-200 ';
                
                html += `
                    <div class="${dayClass}">
                        <div class="text-sm font-medium mb-1 ${isToday ? 'text-blue-600' : ''}">${currentGridDate.getDate()}</div>
                        <div class="space-y-1">
                            ${dayChantiers.slice(0, 3).map(chantier => `
                                <div class="text-xs px-2 py-1 rounded cursor-pointer hover:opacity-80 transition-opacity"
                                     style="background-color: ${chantier.color}; color: white;"
                                     onclick="showChantierModal(${chantier.id})"
                                     title="${chantier.title}">
                                    ${chantier.title.length > 15 ? chantier.title.substring(0, 15) + '...' : chantier.title}
                                </div>
                            `).join('')}
                            ${dayChantiers.length > 3 ? `<div class="text-xs text-gray-500">+${dayChantiers.length - 3} autres</div>` : ''}
                        </div>
                    </div>
                `;
                
                currentGridDate.setDate(currentGridDate.getDate() + 1);
            }
            
            // Arrêter si on a dépassé le mois suivant
            if (currentGridDate.getMonth() > month + 1 || (currentGridDate.getMonth() === 0 && month === 11)) {
                break;
            }
        }
        
        return html;
    }
    
    function renderWeekView() {
        const container = document.getElementById('calendar-container');
        const startOfWeek = getStartOfWeek(currentDate);
        const endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(endOfWeek.getDate() + 6);
        
        let html = `
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-900">
                        ${formatDate(startOfWeek)} - ${formatDate(endOfWeek)}
                    </h2>
                    <div class="flex space-x-2">
                        <button onclick="changeWeek(-1)" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button onclick="goToToday()" class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            Cette semaine
                        </button>
                        <button onclick="changeWeek(1)" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-7 gap-1">
                    ${generateWeekGrid(startOfWeek)}
                </div>
            </div>
        `;
        
        container.innerHTML = html;
    }
    
    function generateWeekGrid(startOfWeek) {
        const dayNames = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        let html = '';
        
        for (let i = 0; i < 7; i++) {
            const currentDay = new Date(startOfWeek);
            currentDay.setDate(currentDay.getDate() + i);
            
            const dayChantiers = getChantiersByDate(currentDay);
            const isToday = isDateToday(currentDay);
            
            html += `
                <div class="border border-gray-200 rounded-lg p-4 min-h-48 ${isToday ? 'bg-blue-50 border-blue-200' : 'bg-white'}">
                    <div class="text-center mb-3">
                        <div class="text-sm font-medium text-gray-500">${dayNames[i]}</div>
                        <div class="text-lg font-bold ${isToday ? 'text-blue-600' : 'text-gray-900'}">${currentDay.getDate()}</div>
                    </div>
                    <div class="space-y-2">
                        ${dayChantiers.map(chantier => `
                            <div class="text-xs px-3 py-2 rounded-lg cursor-pointer hover:opacity-80 transition-opacity"
                                 style="background-color: ${chantier.color}; color: white;"
                                 onclick="showChantierModal(${chantier.id})"
                                 title="${chantier.title}">
                                <div class="font-medium">${chantier.title}</div>
                                ${chantier.time ? `<div class="opacity-75">${chantier.time}</div>` : ''}
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }
        
        return html;
    }
    
    function renderListView() {
        const container = document.getElementById('list-content');
        
        // Trier les chantiers par date
        const sortedChantiers = [...chantiers].sort((a, b) => new Date(a.start) - new Date(b.start));
        
        // Grouper par mois
        const groupedByMonth = {};
        sortedChantiers.forEach(chantier => {
            const date = new Date(chantier.start);
            const monthKey = `${date.getFullYear()}-${date.getMonth()}`;
            if (!groupedByMonth[monthKey]) {
                groupedByMonth[monthKey] = [];
            }
            groupedByMonth[monthKey].push(chantier);
        });
        
        let html = '';
        Object.keys(groupedByMonth).forEach(monthKey => {
            const [year, month] = monthKey.split('-');
            const monthNames = [
                'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
            ];
            
            html += `
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                        </svg>
                        ${monthNames[parseInt(month)]} ${year}
                    </h3>
                    <div class="space-y-3">
                        ${groupedByMonth[monthKey].map(chantier => `
                            <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow cursor-pointer"
                                 onclick="showChantierModal(${chantier.id})">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-4 h-4 rounded" style="background-color: ${chantier.color}"></div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">${chantier.title}</h4>
                                            <p class="text-sm text-gray-500">
                                                Du ${formatDate(new Date(chantier.start))} 
                                                ${chantier.end ? `au ${formatDate(new Date(chantier.end))}` : ''}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="${chantier.url}" class="text-blue-600 hover:text-blue-800 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        });
        
        if (html === '') {
            html = `
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Aucun chantier planifié</h3>
                    <p class="mt-2 text-gray-500">Commencez par créer un nouveau chantier avec des dates.</p>
                    @can('create', App\Models\Chantier::class)
                        <div class="mt-6">
                            <a href="{{ route('chantiers.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Créer un chantier
                            </a>
                        </div>
                    @endcan
                </div>
            `;
        }
        
        container.innerHTML = html;
    }
    
    // Fonctions utilitaires
    function getChantiersByDate(date) {
        return chantiers.filter(chantier => {
            const start = new Date(chantier.start);
            const end = chantier.end ? new Date(chantier.end) : start;
            return date >= start && date <= end;
        });
    }
    
    function isDateToday(date) {
        const today = new Date();
        return date.toDateString() === today.toDateString();
    }
    
    function getStartOfWeek(date) {
        const d = new Date(date);
        const day = d.getDay();
        const diff = d.getDate() - day + (day === 0 ? -6 : 1);
        return new Date(d.setDate(diff));
    }
    
    function formatDate(date) {
        return date.toLocaleDateString('fr-FR', {
            day: 'numeric',
            month: 'short'
        });
    }
    
    // Fonctions de navigation
    window.changeMonth = function(direction) {
        currentDate.setMonth(currentDate.getMonth() + direction);
        renderMonthView();
    };
    
    window.changeWeek = function(direction) {
        currentDate.setDate(currentDate.getDate() + (direction * 7));
        renderWeekView();
    };
    
    window.goToToday = function() {
        currentDate = new Date();
        initializeCalendar();
    };
    
    // Gestion des vues
    window.toggleView = function(view) {
        currentView = view;
        
        // Mettre à jour les boutons
        document.querySelectorAll('.calendar-view-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-blue-100', 'text-blue-700');
            btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
        });
        
        const activeBtn = document.querySelector(`[data-view="${view}"]`);
        activeBtn.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
        activeBtn.classList.add('active', 'bg-blue-100', 'text-blue-700');
        
        initializeCalendar();
    };
    
    // Modal de chantier
    window.showChantierModal = function(chantierId) {
        const chantier = chantiers.find(c => c.id === chantierId);
        if (!chantier) return;
        
        document.getElementById('modal-title').textContent = chantier.title;
        
        const modalContent = document.getElementById('modal-content');
        modalContent.innerHTML = `
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Dates</h4>
                        <p class="text-gray-600">
                            Du ${formatDate(new Date(chantier.start))} 
                            ${chantier.end ? `au ${formatDate(new Date(chantier.end))}` : ''}
                        </p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Statut</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                              style="background-color: ${chantier.color}20; color: ${chantier.color};">
                            ${getStatutText(chantier.color)}
                        </span>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Description</h4>
                    <p class="text-gray-600">Informations détaillées du chantier disponibles dans la vue complète.</p>
                </div>
            </div>
        `;
        
        const modalActions = document.getElementById('modal-actions');
        modalActions.innerHTML = `
            <a href="${chantier.url}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Voir détails
            </a>
        `;
        
        document.getElementById('chantierModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };
    
    window.closeChantierModal = function() {
        document.getElementById('chantierModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    };
    
    function getStatutText(color) {
        const statusMap = {
            '#6c757d': 'Planifié',
            '#007bff': 'En cours',
            '#28a745': 'Terminé'
        };
        return statusMap[color] || 'Inconnu';
    }
    
    // Fermer modal avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeChantierModal();
        }
    });
    
    // Fermer modal en cliquant dehors
    document.getElementById('chantierModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeChantierModal();
        }
    });
});
</script>
@endpush
