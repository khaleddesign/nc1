<?php
// app/Http/Controllers/ChantierController.php
namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ChantierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la liste des chantiers
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            // L'admin voit tous les chantiers
            $chantiers = Chantier::with(['client', 'commercial'])
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
        } elseif ($user->isCommercial()) {
            // Le commercial voit ses chantiers
            $chantiers = Chantier::where('commercial_id', $user->id)
                                ->with(['client'])
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
        } else {
            // Le client voit ses chantiers
            $chantiers = Chantier::where('client_id', $user->id)
                                ->with(['commercial'])
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
        }
        
        return view('chantiers.index', compact('chantiers'));
    }

    /**
     * Afficher le formulaire de création d'un chantier
     */
    public function create()
    {
        Gate::authorize('commercial-or-admin');
        
        $clients = User::where('role', 'client')->orderBy('name')->get();
        $commerciaux = User::where('role', 'commercial')->orderBy('name')->get();
        
        return view('chantiers.create', compact('clients', 'commerciaux'));
    }

    /**
     * Enregistrer un nouveau chantier
     */
    public function store(Request $request)
    {
        Gate::authorize('commercial-or-admin');
        
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:users,id',
            'commercial_id' => 'required|exists:users,id',
            'statut' => 'required|in:planifie,en_cours,termine',
            'date_debut' => 'nullable|date',
            'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut',
            'budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        
        // Vérifier que le client et le commercial ont les bons rôles
        $client = User::findOrFail($validated['client_id']);
        $commercial = User::findOrFail($validated['commercial_id']);
        
        if (!$client->isClient()) {
            return back()->withErrors(['client_id' => 'L\'utilisateur sélectionné n\'est pas un client.']);
        }
        
        if (!$commercial->isCommercial() && !$commercial->isAdmin()) {
            return back()->withErrors(['commercial_id' => 'L\'utilisateur sélectionné n\'est pas un commercial.']);
        }
        
        // Créer le chantier
        $chantier = Chantier::create($validated);
        
        // Notifier le client
        Notification::creerNotification(
            $chantier->client_id,
            $chantier->id,
            'nouveau_chantier',
            'Nouveau chantier créé',
            "Un nouveau chantier '{$chantier->titre}' a été créé pour vous."
        );
        
        return redirect()->route('chantiers.show', $chantier)
                        ->with('success', 'Chantier créé avec succès.');
    }

    /**
     * Afficher un chantier spécifique
     */
    public function show(Chantier $chantier)
    {
        // Vérifier que l'utilisateur peut voir ce chantier
        $user = Auth::user();
        
        if (!$user->isAdmin() && 
            $chantier->client_id !== $user->id && 
            $chantier->commercial_id !== $user->id) {
            abort(403, 'Accès non autorisé à ce chantier.');
        }
        
        // Charger les relations nécessaires
        $chantier->load([
            'client',
            'commercial',
            'etapes' => function ($query) {
                $query->orderBy('ordre');
            },
            'documents' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'commentaires' => function ($query) {
                $query->with('user')->orderBy('created_at', 'desc');
            }
        ]);
        
        return view('chantiers.show', compact('chantier'));
    }

    /**
     * Afficher le formulaire d'édition d'un chantier
     */
    public function edit(Chantier $chantier)
    {
        Gate::authorize('commercial-or-admin');
        
        // Vérifier que l'utilisateur peut éditer ce chantier
        $user = Auth::user();
        if (!$user->isAdmin() && $chantier->commercial_id !== $user->id) {
            abort(403, 'Vous ne pouvez pas éditer ce chantier.');
        }
        
        $clients = User::where('role', 'client')->orderBy('name')->get();
        $commerciaux = User::where('role', 'commercial')->orderBy('name')->get();
        
        return view('chantiers.edit', compact('chantier', 'clients', 'commerciaux'));
    }

    /**
     * Mettre à jour un chantier
     */
    public function update(Request $request, Chantier $chantier)
    {
        Gate::authorize('commercial-or-admin');
        
        // Vérifier que l'utilisateur peut éditer ce chantier
        $user = Auth::user();
        if (!$user->isAdmin() && $chantier->commercial_id !== $user->id) {
            abort(403, 'Vous ne pouvez pas éditer ce chantier.');
        }
        
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:users,id',
            'commercial_id' => 'required|exists:users,id',
            'statut' => 'required|in:planifie,en_cours,termine',
            'date_debut' => 'nullable|date',
            'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut',
            'date_fin_effective' => 'nullable|date',
            'budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        
        // Vérifier que le client et le commercial ont les bons rôles
        $client = User::findOrFail($validated['client_id']);
        $commercial = User::findOrFail($validated['commercial_id']);
        
        if (!$client->isClient()) {
            return back()->withErrors(['client_id' => 'L\'utilisateur sélectionné n\'est pas un client.']);
        }
        
        if (!$commercial->isCommercial() && !$commercial->isAdmin()) {
            return back()->withErrors(['commercial_id' => 'L\'utilisateur sélectionné n\'est pas un commercial.']);
        }
        
        // Si le statut passe à "terminé", définir la date de fin effective
        if ($validated['statut'] === 'termine' && !$chantier->date_fin_effective) {
            $validated['date_fin_effective'] = now();
        }
        
        // Garder l'ancien statut pour comparaison
        $ancienStatut = $chantier->statut;
        
        // Mettre à jour le chantier
        $chantier->update($validated);
        
        // Notifications selon les changements
        if ($ancienStatut !== $validated['statut']) {
            $message = match($validated['statut']) {
                'en_cours' => "Le chantier '{$chantier->titre}' est maintenant en cours.",
                'termine' => "Le chantier '{$chantier->titre}' est terminé.",
                default => "Le statut du chantier '{$chantier->titre}' a été modifié."
            };
            
            Notification::creerNotification(
                $chantier->client_id,
                $chantier->id,
                'changement_statut',
                'Changement de statut',
                $message
            );
        }
        
        return redirect()->route('chantiers.show', $chantier)
                        ->with('success', 'Chantier mis à jour avec succès.');
    }

    /**
     * Supprimer un chantier
     */
    public function destroy(Chantier $chantier)
    {
        Gate::authorize('admin-only');
        
        // Supprimer le chantier (les relations seront supprimées en cascade)
        $chantier->delete();
        
        return redirect()->route('chantiers.index')
                        ->with('success', 'Chantier supprimé avec succès.');
    }

    /**
     * Afficher le formulaire d'ajout rapide d'étapes
     */
    public function etapes(Chantier $chantier)
    {
        Gate::authorize('commercial-or-admin');
        
        // Vérifier que l'utilisateur peut éditer ce chantier
        $user = Auth::user();
        if (!$user->isAdmin() && $chantier->commercial_id !== $user->id) {
            abort(403, 'Vous ne pouvez pas gérer les étapes de ce chantier.');
        }
        
        $chantier->load('etapes');
        
        return view('chantiers.etapes', compact('chantier'));
    }

    /**
     * Exporter les données d'un chantier en PDF (optionnel)
     */
    public function export(Chantier $chantier)
    {
        // Vérifier que l'utilisateur peut voir ce chantier
        $user = Auth::user();
        
        if (!$user->isAdmin() && 
            $chantier->client_id !== $user->id && 
            $chantier->commercial_id !== $user->id) {
            abort(403, 'Accès non autorisé à ce chantier.');
        }
        
        // TODO: Implémenter l'export PDF avec une librairie comme DomPDF ou TCPDF
        // Pour l'instant, on redirige avec un message
        return redirect()->route('chantiers.show', $chantier)
                        ->with('info', 'La fonction d\'export PDF sera bientôt disponible.');
    }

    /**
     * Afficher le calendrier des chantiers (optionnel)
     */
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
        
        // Formatter les données pour le calendrier
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
        
        return view('chantiers.calendrier', compact('events'));
    }

    /**
     * Rechercher des chantiers (AJAX)
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $user = Auth::user();
        
        $chantiersQuery = Chantier::query();
        
        // Filtrer selon le rôle
        if ($user->isCommercial()) {
            $chantiersQuery->where('commercial_id', $user->id);
        } elseif ($user->isClient()) {
            $chantiersQuery->where('client_id', $user->id);
        }
        
        // Recherche
        $chantiers = $chantiersQuery->where(function ($q) use ($query) {
                                        $q->where('titre', 'like', "%{$query}%")
                                          ->orWhere('description', 'like', "%{$query}%");
                                    })
                                    ->with(['client', 'commercial'])
                                    ->limit(10)
                                    ->get();
        
        return response()->json($chantiers);
    }
}