@extends('layouts.app')

@section('title', 'Calendrier des chantiers')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-calendar-alt me-2"></i>Calendrier des Chantiers</h1>
        
        <div>
            <button type="button" class="btn btn-outline-secondary me-2" onclick="changeView('month')">
                <i class="fas fa-calendar me-1"></i>Mois
            </button>
            <button type="button" class="btn btn-outline-secondary me-2" onclick="changeView('week')">
                <i class="fas fa-calendar-week me-1"></i>Semaine
            </button>
            <button type="button" class="btn btn-outline-secondary" onclick="changeView('list')">
                <i class="fas fa-list me-1"></i>Liste
            </button>
        </div>
    </div>

    <!-- Légende -->
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="card-title mb-3">Légende</h6>
            <div class="d-flex flex-wrap gap-3">
                <div class="d-flex align-items-center">
                    <div style="width: 20px; height: 20px; background-color: #6c757d;" class="rounded me-2"></div>
                    <span>Planifié</span>
                </div>
                <div class="d-flex align-items-center">
                    <div style="width: 20px; height: 20px; background-color: #007bff;" class="rounded me-2"></div>
                    <span>En cours</span>
                </div>
                <div class="d-flex align-items-center">
                    <div style="width: 20px; height: 20px; background-color: #28a745;" class="rounded me-2"></div>
                    <span>Terminé</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendrier -->
    <div class="card">
        <div class="card-body">
            <div id="calendar" style="min-height: 600px;"></div>
        </div>
    </div>
</div>

<!-- Modal détails événement -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="eventDetails">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="#" id="eventLink" class="btn btn-primary">Voir le chantier</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            list: 'Liste'
        },
        events: @json($events),
        height: 'auto',
        eventClick: function(info) {
            // Afficher les détails dans la modal
            document.getElementById('eventTitle').textContent = info.event.title;
            
            let details = `
                <p><strong>Statut :</strong> ${getStatutLabel(info.event.backgroundColor)}</p>
                <p><strong>Date début :</strong> ${formatDate(info.event.start)}</p>
            `;
            
            if (info.event.end) {
                details += `<p><strong>Date fin prévue :</strong> ${formatDate(info.event.end)}</p>`;
            }
            
            document.getElementById('eventDetails').innerHTML = details;
            document.getElementById('eventLink').href = info.event.url;
            
            info.jsEvent.preventDefault(); // Empêcher la navigation directe
            eventModal.show();
        },
        eventDidMount: function(info) {
            // Ajouter un tooltip
            info.el.setAttribute('title', info.event.title);
            
            // Style personnalisé pour les chantiers en retard
            const today = new Date();
            if (info.event.end && info.event.end < today && info.event.backgroundColor !== '#28a745') {
                info.el.style.border = '2px solid #dc3545';
                info.el.style.opacity = '0.8';
            }
        },
        dayMaxEvents: 3,
        moreLinkText: function(num) {
            return '+' + num + ' chantiers';
        }
    });
    
    calendar.render();
    
    // Fonction pour changer la vue
    window.changeView = function(viewName) {
        const viewMap = {
            'month': 'dayGridMonth',
            'week': 'timeGridWeek',
            'list': 'listMonth'
        };
        calendar.changeView(viewMap[viewName]);
    };
    
    // Fonction pour formater les dates
    function formatDate(date) {
        if (!date) return 'Non définie';
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(date).toLocaleDateString('fr-FR', options);
    }
    
    // Fonction pour obtenir le label du statut
    function getStatutLabel(color) {
        const statuts = {
            '#6c757d': 'Planifié',
            '#007bff': 'En cours',
            '#28a745': 'Terminé'
        };
        return statuts[color] || 'Inconnu';
    }
});
</script>

<style>
/* Styles personnalisés pour le calendrier */
.fc-event {
    cursor: pointer;
    padding: 2px 5px;
    border-radius: 3px;
}

.fc-event:hover {
    opacity: 0.8;
}

.fc-daygrid-event {
    white-space: normal;
}

.fc-more-link {
    color: #007bff;
    font-weight: bold;
}

/* Style pour les événements en retard */
.fc-event-overdue {
    border: 2px solid #dc3545 !important;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
    100% {
        opacity: 1;
    }
}
</style>
@endsection