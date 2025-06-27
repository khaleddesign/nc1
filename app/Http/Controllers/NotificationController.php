<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Auth::user()->notifications()
                    ->with(['chantier', 'devis', 'facture'])
                    ->orderBy('created_at', 'desc');

        // Appliquer les filtres
        if ($request->get('filter') === 'unread') {
            $query->where('lu', false);
        } elseif ($request->get('filter') === 'read') {
            $query->where('lu', true);
        }

        $notifications = $query->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette notification.');
        }
        
        $notification->marquerLue();
        
        return redirect()->back()->with('success', 'Notification marquée comme lue.');
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()
            ->where('lu', false)
            ->update(['lu' => true, 'lu_at' => now()]);
        
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Voir une notification et la marquer comme lue avec redirection dynamique
     */
    public function viewAndMarkAsRead(Notification $notification)
    {
        // Vérifier les autorisations
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette notification.');
        }
        
        // Marquer comme lue si elle ne l'est pas déjà
        if (!$notification->lu) {
            $notification->marquerLue();
        }
        
        // Obtenir la route de redirection dynamique
        $redirectInfo = $notification->getRedirectRoute();
        
        try {
            // Vérifier que la route existe et que les paramètres sont valides
            if (empty($redirectInfo['params'])) {
                return redirect()->route($redirectInfo['name'])
                                ->with('success', 'Notification marquée comme lue.');
            } else {
                // Valider que les IDs existent encore (éviter les 404)
                $this->validateRouteParams($redirectInfo);
                
                return redirect()->route($redirectInfo['name'], $redirectInfo['params'])
                                ->with('success', 'Notification marquée comme lue.');
            }
        } catch (\Exception $e) {
            // En cas d'erreur (route inexistante, paramètres invalides), fallback
            Log::warning('Erreur redirection notification: ' . $e->getMessage(), [
                'notification_id' => $notification->id,
                'route_info' => $redirectInfo,
                'user_id' => Auth::id()
            ]);
            
            // Fallback intelligent
            return $this->getFallbackRedirection($notification);
        }
    }

    /**
     * Valider que les paramètres de route correspondent à des entités existantes
     */
    private function validateRouteParams(array $redirectInfo): void
    {
        if (count($redirectInfo['params']) >= 2) {
            $chantierId = $redirectInfo['params'][0];
            $entityId = $redirectInfo['params'][1];
            
            // Vérifier que le chantier existe
            $chantier = \App\Models\Chantier::find($chantierId);
            if (!$chantier) {
                throw new \Exception("Chantier {$chantierId} introuvable");
            }
            
            // Vérifier selon le type de route
            if (str_contains($redirectInfo['name'], 'devis')) {
                $devis = \App\Models\Devis::find($entityId);
                if (!$devis || $devis->chantier_id != $chantierId) {
                    throw new \Exception("Devis {$entityId} introuvable ou non lié au chantier");
                }
            } elseif (str_contains($redirectInfo['name'], 'factures')) {
                $facture = \App\Models\Facture::find($entityId);
                if (!$facture || $facture->chantier_id != $chantierId) {
                    throw new \Exception("Facture {$entityId} introuvable ou non liée au chantier");
                }
            }
        } elseif (count($redirectInfo['params']) === 1) {
            // Vérification pour les routes avec seulement chantier_id
            $chantierId = $redirectInfo['params'][0];
            $chantier = \App\Models\Chantier::find($chantierId);
            if (!$chantier) {
                throw new \Exception("Chantier {$chantierId} introuvable");
            }
        }
    }

    /**
     * Redirection de fallback en cas d'erreur
     */
    private function getFallbackRedirection(Notification $notification)
    {
        // Essayer de rediriger vers le chantier si possible
        if ($notification->chantier_id) {
            try {
                $chantier = \App\Models\Chantier::find($notification->chantier_id);
                if ($chantier && Auth::user()->can('view', $chantier)) {
                    return redirect()->route('chantiers.show', $notification->chantier_id)
                                    ->with('warning', 'Notification marquée comme lue. L\'élément spécifique n\'est plus disponible.');
                }
            } catch (\Exception $e) {
                Log::info('Fallback vers chantier échoué: ' . $e->getMessage());
            }
        }
        
        // Dernier recours : dashboard
        return redirect()->route('dashboard')
                        ->with('warning', 'Notification marquée comme lue. Redirection vers le tableau de bord.');
    }
}