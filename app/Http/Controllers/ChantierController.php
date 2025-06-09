<?php
namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChantierController extends Controller
{
    public function calendrier()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $chantiers = Chantier::with(['client', 'commercial'])
                ->whereNotNull('date_debut')
                ->get();
        } elseif ($user->isCommercial()) {
            $chantiers = Chantier::where('commercial_id', $user->id)
                ->with(['client'])
                ->whereNotNull('date_debut')
                ->get();
        } else {
            $chantiers = Chantier::where('client_id', $user->id)
                ->with(['commercial'])
                ->whereNotNull('date_debut')
                ->get();
        }

        $events = $chantiers->map(function ($chantier) {
            return [
                'id' => $chantier->id,
                'title' => $chantier->titre,
                'start' => $chantier->date_debut->format('Y-m-d'),
                'end' => $chantier->date_fin_prevue ? $chantier->date_fin_prevue->format('Y-m-d') : null,
                'color' => match($chantier->statut) {
                    'planifie' => '#6c757d',
                    'en_cours' => '#007bff',
                    'termine' => '#28a745',
                    default => '#6c757d'
                },
                'url' => route('chantiers.show', $chantier)
            ];
        });

        // Ajouter les données nécessaires pour les formulaires de la vue
        $clients = collect(); // Collection vide par défaut
        $commerciaux = collect(); // Collection vide par défaut
        
        // Si l'utilisateur peut créer des chantiers, charger les listes
        if ($user->isAdmin() || $user->isCommercial()) {
            $clients = User::where('role', 'client')
                ->where('active', true)
                ->orderBy('name')
                ->get();
            
            $commerciaux = User::where('role', 'commercial')
                ->where('active', true)
                ->orderBy('name')
                ->get();
        }

        // Statistiques pour le calendrier
        $stats = [
            'total_chantiers' => $chantiers->count(),
            'en_cours' => $chantiers->where('statut', 'en_cours')->count(),
            'planifies' => $chantiers->where('statut', 'planifie')->count(),
            'termines' => $chantiers->where('statut', 'termine')->count(),
            'en_retard' => $chantiers->filter(function($chantier) {
                return $chantier->isEnRetard();
            })->count()
        ];

        return view('chantiers.calendrier', compact('events', 'clients', 'commerciaux', 'stats'));
    }
}
