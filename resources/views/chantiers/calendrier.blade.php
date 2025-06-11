@extends('layouts.app')

@section('title', 'Calendrier des Chantiers')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- Header bleu homogène -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-xl">
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
                            <p class="mt-2 text-blue-100 text-lg">
                                {{ count($events) }} projet(s) planifiés • Vue d'ensemble temporelle 📅
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex space-x-3 md:mt-0 md:ml-4">
                    @can('create', App\Models\Chantier::class)
                        <a href="{{ route('chantiers.create') }}" 
                           class="inline-flex items-center px-6 py-3 border border-transparent rounded-full shadow-sm text-sm font-medium text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Nouveau chantier
                        </a>
                    @endcan
                    <a href="{{ route('chantiers.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-white/20 rounded-full shadow-sm text-sm font-medium text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-all duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        Liste des chantiers
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filtres rapides -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-8">
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    Affichage & Filtres
                </h3>
            </div>
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <!-- Navigation mois -->
                    <div class="flex items-center space-x-4">
                        <button id="prevMonth" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <h2 id="currentMonth" class="text-xl font-bold text-gray-900 min-w-48 text-center">
                            {{ now()->format('F Y') }}
                        </h2>
                        <button id="nextMonth" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>

                    <!-- Filtres de statut -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-700">Afficher :</span>
                        <div class="flex space-x-2">
                            <button class="filter-btn active" data-status="all">
                                <span class="w-3 h-3 bg-gray-400 rounded-full"></span>
                                Tous
                            </button>
                            <button class="filter-btn" data-status="planifie">
                                <span class="w-3 h-3 bg-gray-500 rounded-full"></span>
                                Planifiés
                            </button>
                            <button class="filter-btn" data-status="en_cours">
                                <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                                En cours
                            </button>
                            <button class="filter-btn" data-status="termine">
                                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                                Terminés
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Layout double : Calendrier + Liste -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Calendrier visuel -->
            <div class="xl:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <!-- Calendrier simplifié -->
                        <div class="grid grid-cols-7 gap-1 mb-4">
                            <!-- En-têtes des jours -->
                            <div class="p-3 text-center text-sm font-medium text-gray-500">Lun</div>
                            <div class="p-3 text-center text-sm font-medium text-gray-500">Mar</div>
                            <div class="p-3 text-center text-sm font-medium text-gray-500">Mer</div>
                            <div class="p-3 text-center text-sm font-medium text-gray-500">Jeu</div>
                            <div class="p-3 text-center text-sm font-medium text-gray-500">Ven</div>
                            <div class="p-3 text-center text-sm font-medium text-gray-500">Sam</div>
                            <div class="p-3 text-center text-sm font-medium text-gray-500">Dim</div>
                        </div>

                        <!-- Grille calendrier -->
                        <div id="calendar-grid" class="grid grid-cols-7 gap-1">
                            <!-- Généré dynamiquement par JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste chronologique -->
            <div class="xl:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            Prochains événements
                        </h3>
                    </div>
                    <div class="p-6">
                        <div id="events-list" class="space-y-4">
                            <!-- Généré dynamiquement -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Aperçu de la charge</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-xl">
                    <div class="text-2xl font-bold text-gray-600" id="stat-total">{{ $stats['total_chantiers'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Total projets</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-xl">
                    <div class="text-2xl font-bold text-blue-600" id="stat-cours">{{ $stats['en_cours'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">En cours</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-xl">
                    <div class="text-2xl font-bold text-yellow-600" id="stat-planifies">{{ $stats['planifies'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Planifiés</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-xl">
                    <div class="text-2xl font-bold text-green-600" id="stat-termines">{{ $stats['termines'] ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Terminés</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données des événements depuis Laravel
    const events = @json($events);
    let currentDate = new Date();
    let activeFilter = 'all';

    // Configuration mois en français
    const monthNames = [
        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    ];

    // Initialisation
    updateCalendar();
    updateEventsList();
    attachEventListeners();

    function updateCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        // Mettre à jour le titre
        document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
        
        // Calculer les jours
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - (firstDay.getDay() || 7) + 1);
        
        const grid = document.getElementById('calendar-grid');
        grid.innerHTML = '';
        
        // Générer 42 cases (6 semaines)
        for (let i = 0; i < 42; i++) {
            const date = new Date(startDate);
            date.setDate(startDate.getDate() + i);
            
            const dayEvents = getEventsForDate(date);
            const isCurrentMonth = date.getMonth() === month;
            const isToday = isDateToday(date);
            
            const dayElement = createDayElement(date, dayEvents, isCurrentMonth, isToday);
            grid.appendChild(dayElement);
        }
    }

    function createDayElement(date, dayEvents, isCurrentMonth, isToday) {
        const day = document.createElement('div');
        day.className = `relative p-2 h-24 border border-gray-100 ${
            isCurrentMonth ? 'bg-white' : 'bg-gray-50'
        } ${isToday ? 'ring-2 ring-blue-500' : ''} hover:bg-blue-50 transition-colors cursor-pointer`;
        
        // Numéro du jour
        const dayNumber = document.createElement('div');
        dayNumber.className = `text-sm font-medium ${
            isCurrentMonth ? 'text-gray-900' : 'text-gray-400'
        } ${isToday ? 'text-blue-600 font-bold' : ''}`;
        dayNumber.textContent = date.getDate();
        day.appendChild(dayNumber);
        
        // Événements
        const visibleEvents = dayEvents.slice(0, 2); // Max 2 événements visibles
        visibleEvents.forEach(event => {
            const eventElement = document.createElement('div');
            eventElement.className = `mt-1 px-1 py-0.5 text-xs rounded truncate ${getEventClass(event.statut)}`;
            eventElement.textContent = event.title;
            eventElement.title = event.title;
            day.appendChild(eventElement);
        });
        
        // Indicateur "+X autres" si plus d'événements
        if (dayEvents.length > 2) {
            const moreElement = document.createElement('div');
            moreElement.className = 'mt-1 text-xs text-gray-500';
            moreElement.textContent = `+${dayEvents.length - 2} autres`;
            day.appendChild(moreElement);
        }
        
        return day;
    }

    function getEventsForDate(date) {
        const dateStr = date.toISOString().split('T')[0];
        return events.filter(event => {
            const startDate = event.start;
            const endDate = event.end || event.start;
            return dateStr >= startDate && dateStr <= endDate;
        }).filter(event => {
            if (activeFilter === 'all') return true;
            return event.statut === activeFilter;
        });
    }

    function getEventClass(statut) {
        const classes = {
            'planifie': 'bg-gray-100 text-gray-800',
            'en_cours': 'bg-blue-100 text-blue-800',
            'termine': 'bg-green-100 text-green-800'
        };
        return classes[statut] || 'bg-gray-100 text-gray-800';
    }

    function updateEventsList() {
        const now = new Date();
        const futureEvents = events
            .filter(event => new Date(event.start) >= now)
            .filter(event => activeFilter === 'all' || event.statut === activeFilter)
            .sort((a, b) => new Date(a.start) - new Date(b.start))
            .slice(0, 10);
        
        const list = document.getElementById('events-list');
        
        if (futureEvents.length === 0) {
            list.innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                    </svg>
                    Aucun événement à venir
                </div>
            `;
            return;
        }
        
        list.innerHTML = futureEvents.map(event => {
            const startDate = new Date(event.start);
            const endDate = event.end ? new Date(event.end) : null;
            
            return `
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-3 h-3 rounded-full ${getStatusColor(event.statut)} mt-2"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            <a href="${event.url}" class="hover:text-blue-600 transition-colors">
                                ${event.title}
                            </a>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            ${formatEventDate(startDate, endDate)}
                        </p>
                    </div>
                </div>
            `;
        }).join('');
    }

    function getStatusColor(statut) {
        const colors = {
            'planifie': 'bg-gray-400',
            'en_cours': 'bg-blue-500',
            'termine': 'bg-green-500'
        };
        return colors[statut] || 'bg-gray-400';
    }

    function formatEventDate(start, end) {
        const options = { day: 'numeric', month: 'short' };
        if (end && start.toDateString() !== end.toDateString()) {
            return `${start.toLocaleDateString('fr-FR', options)} → ${end.toLocaleDateString('fr-FR', options)}`;
        }
        return start.toLocaleDateString('fr-FR', options);
    }

    function isDateToday(date) {
        const today = new Date();
        return date.toDateString() === today.toDateString();
    }

    function attachEventListeners() {
        // Navigation mois
        document.getElementById('prevMonth').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            updateCalendar();
        });
        
        document.getElementById('nextMonth').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            updateCalendar();
        });
        
        // Filtres de statut
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                activeFilter = btn.dataset.status;
                updateCalendar();
                updateEventsList();
            });
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.filter-btn {
    @apply inline-flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors;
}

.filter-btn.active {
    @apply bg-blue-50 border-blue-200 text-blue-700;
}

/* Animation pour les événements */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.calendar-event {
    animation: slideIn 0.3s ease-out;
}

/* Responsive design */
@media (max-width: 768px) {
    #calendar-grid {
        font-size: 0.875rem;
    }
    
    #calendar-grid > div {
        height: 60px;
    }
}
</style>
@endpush