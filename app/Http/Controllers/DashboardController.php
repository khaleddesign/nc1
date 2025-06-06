<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return $this->dashboardAdmin();
            case 'commercial':
                return $this->dashboardCommercial();
            case 'client':
                return $this->dashboardClient();
            default:
                abort(403);
        }
    }

    // Tableau de bord ADMIN
    private function dashboardAdmin()
    {
        $chantiers = Chantier::with(['client', 'commercial'])->orderBy('created_at', 'desc')->get();

        $stats = [
            'total_chantiers' => $chantiers->count(),
            'chantiers_en_cours' => $chantiers->where('statut', 'en_cours')->count(),
            'chantiers_termines' => $chantiers->where('statut', 'termine')->count(),
            'chantiers_planifies' => $chantiers->where('statut', 'planifie')->count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_commerciaux' => User::where('role', 'commercial')->count(),
            'avancement_moyen' => $chantiers->avg('avancement_global') ?? 0,
        ];

        $chantiers_recents = $chantiers->take(10);

        $chantiers_retard = Chantier::whereDate('date_fin_prevue', '<', now())
                                  ->where('statut', '!=', 'termine')
                                  ->with(['client', 'commercial'])
                                  ->get();

        return view('dashboard.admin', compact('stats', 'chantiers_recents', 'chantiers_retard', 'chantiers'));
    }

    // Tableau de bord COMMERCIAL
    private function dashboardCommercial()
    {
        $user = Auth::user();

        $mes_chantiers = Chantier::where('commercial_id', $user->id)
                                 ->with(['client'])
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        $stats = [
            'total_chantiers' => $mes_chantiers->count(),
            'en_cours' => $mes_chantiers->where('statut', 'en_cours')->count(),
            'termines' => $mes_chantiers->where('statut', 'termine')->count(),
            'avancement_moyen' => $mes_chantiers->avg('avancement_global') ?? 0,
        ];

        $notifications = $user->notifications()
                             ->where('lu', false)
                             ->orderBy('created_at', 'desc')
                             ->limit(5)
                             ->get();

        return view('dashboard.commercial', compact('mes_chantiers', 'stats', 'notifications'));
    }

    // Tableau de bord CLIENT
    private function dashboardClient()
    {
        $user = Auth::user();

        $mes_chantiers = $user->chantiersClient()
                             ->with(['commercial', 'etapes', 'documents'])
                             ->orderBy('created_at', 'desc')
                             ->get();

        $notifications = $user->notifications()
                             ->where('lu', false)
                             ->orderBy('created_at', 'desc')
                             ->limit(5)
                             ->get();

        return view('dashboard.client', compact('mes_chantiers', 'notifications'));
    }
}
