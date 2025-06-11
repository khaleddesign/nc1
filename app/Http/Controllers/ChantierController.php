<?php
namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChantierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la liste des chantiers
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Chantier::with(['client', 'commercial']);

        // Filtrage selon le rôle
        if ($user->isCommercial()) {
            $query->where('commercial_id', $user->id);
        } elseif ($user->isClient()) {
            $query->where('client_id', $user->id);
        }

        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('commercial_id') && $user->isAdmin()) {
            $query->where('commercial_id', $request->commercial_id);
        }

        if ($request->filled('client_id') && ($user->isAdmin() || $user->isCommercial())) {
            $query->where('client_id', $request->client_id);
        }

        // Tri
        $orderBy = $request->get('order_by', 'created_at');
        $orderDirection = $request->get('order_direction', 'desc');
        
        if (in_array($orderBy, ['created_at', 'titre', 'statut', 'date_debut', 'date_fin_prevue'])) {
            $query->orderBy($orderBy, $orderDirection);
        }

        $chantiers = $query->paginate(12)->withQueryString();

        // Statistiques pour la vue
        $stats = [
            'total' => $chantiers->total(),
            'planifies' => Chantier::where('statut', 'planifie')
                ->when($user->isCommercial(), fn($q) => $q->where('commercial_id', $user->id))
                ->when($user->isClient(), fn($q) => $q->where('client_id', $user->id))
                ->count(),
            'en_cours' => Chantier::where('statut', 'en_cours')
                ->when($user->isCommercial(), fn($q) => $q->where('commercial_id', $user->id))
                ->when($user->isClient(), fn($q) => $q->where('client_id', $user->id))
                ->count(),
            'termines' => Chantier::where('statut', 'termine')
                ->when($user->isCommercial(), fn($q) => $q->where('commercial_id', $user->id))
                ->when($user->isClient(), fn($q) => $q->where('client_id', $user->id))
                ->count(),
        ];

        // Listes pour les filtres
        $commerciaux = $user->isAdmin() ? User::where('role', 'commercial')->where('active', true)->get() : collect();
        $clients = ($user->isAdmin() || $user->isCommercial()) ? User::where('role', 'client')->where('active', true)->get() : collect();

        return view('chantiers.index', compact('chantiers', 'stats', 'commerciaux', 'clients'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $this->authorize('create', Chantier::class);

        $clients = User::where('role', 'client')->where('active', true)->orderBy('name')->get();
        $commerciaux = User::where('role', 'commercial')->where('active', true)->orderBy('name')->get();

        return view('chantiers.create', compact('clients', 'commerciaux'));
    }

    /**
     * Sauvegarder un nouveau chantier
     */
    public function store(Request $request)
    {
        $this->authorize('create', Chantier::class);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:users,id',
            'commercial_id' => 'required|exists:users,id',
            'date_debut' => 'nullable|date',
            'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut',
            'budget' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Vérifier que les utilisateurs ont les bons rôles
        $client = User::where('id', $validated['client_id'])->where('role', 'client')->first();
        $commercial = User::where('id', $validated['commercial_id'])->where('role', 'commercial')->first();

        if (!$client || !$commercial) {
            return back()->withErrors(['error' => 'Client ou commercial invalide.'])->withInput();
        }

        $chantier = Chantier::create($validated);

        // Notification au client
        Notification::creerNotification(
            $chantier->client_id,
            $chantier->id,
            'nouveau_chantier',
            'Nouveau chantier créé',
            "Un nouveau chantier '{$chantier->titre}' vous a été assigné."
        );

        return redirect()->route('chantiers.index')
                        ->with('success', 'Chantier créé avec succès.');
    }

    /**
     * Afficher un chantier
     */
    public function show(Chantier $chantier)
    {
        $this->authorize('view', $chantier);

        $chantier->load([
            'client',
            'commercial',
            'etapes' => function($query) {
                $query->orderBy('ordre');
            },
            'documents' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'commentaires' => function($query) {
                $query->with('user')->orderBy('created_at', 'desc');
            }
        ]);

        return view('chantiers.show', compact('chantier'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Chantier $chantier)
    {
        $this->authorize('update', $chantier);

        $clients = User::where('role', 'client')->where('active', true)->orderBy('name')->get();
        $commerciaux = User::where('role', 'commercial')->where('active', true)->orderBy('name')->get();

        return view('chantiers.edit', compact('chantier', 'clients', 'commerciaux'));
    }

    /**
     * Mettre à jour un chantier
     */
    public function update(Request $request, Chantier $chantier)
    {
        $this->authorize('update', $chantier);

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

        // Vérifier les rôles
        $client = User::where('id', $validated['client_id'])->where('role', 'client')->first();
        $commercial = User::where('id', $validated['commercial_id'])->where('role', 'commercial')->first();

        if (!$client || !$commercial) {
            return back()->withErrors(['error' => 'Client ou commercial invalide.'])->withInput();
        }

        // Si le statut change
        $ancienStatut = $chantier->statut;
        $chantier->update($validated);

        if ($ancienStatut !== $validated['statut']) {
            // Notification de changement de statut
            Notification::creerNotification(
                $chantier->client_id,
                $chantier->id,
                'changement_statut',
                'Statut du chantier modifié',
                "Le statut du chantier '{$chantier->titre}' est passé de " . ucfirst($ancienStatut) . " à " . ucfirst($validated['statut'])
            );
        }

        return redirect()->route('chantiers.show', $chantier)
                        ->with('success', 'Chantier modifié avec succès.');
    }

    /**
     * Supprimer un chantier
     */
    public function destroy(Chantier $chantier)
    {
        $this->authorize('delete', $chantier);

        $titre = $chantier->titre;
        $chantier->delete();

        return redirect()->route('chantiers.index')
                        ->with('success', "Chantier '{$titre}' supprimé avec succès.");
    }

    /**
     * Vue calendrier des chantiers
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
        $clients = collect();
        $commerciaux = collect();
        
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

    /**
     * Recherche AJAX
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $user = Auth::user();
        
        $chantiersQuery = Chantier::query();
        
        // Filtrage selon le rôle
        if ($user->isCommercial()) {
            $chantiersQuery->where('commercial_id', $user->id);
        } elseif ($user->isClient()) {
            $chantiersQuery->where('client_id', $user->id);
        }
        
        $chantiers = $chantiersQuery->where(function ($q) use ($query) {
            $q->where('titre', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
        })
            ->with(['client', 'commercial'])
            ->limit(10)
            ->get()
            ->map(function ($chantier) {
                return [
                    'id' => $chantier->id,
                    'titre' => $chantier->titre,
                    'description' => $chantier->description,
                    'client' => $chantier->client->name,
                    'commercial' => $chantier->commercial->name,
                    'statut' => $chantier->statut,
                    'url' => route('chantiers.show', $chantier),
                ];
            });
        
        return response()->json($chantiers);
    }

    /**
     * Export des chantiers avec filtres
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $query = Chantier::with(['client', 'commercial']);

        // 🎯 Filtrage selon le rôle (identique à la méthode index)
        if ($user->isCommercial()) {
            $query->where('commercial_id', $user->id);
        } elseif ($user->isClient()) {
            $query->where('client_id', $user->id);
        }

        // 🔍 Application des mêmes filtres que dans la méthode index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('commercial_id') && $user->isAdmin()) {
            $query->where('commercial_id', $request->commercial_id);
        }

        if ($request->filled('client_id') && ($user->isAdmin() || $user->isCommercial())) {
            $query->where('client_id', $request->client_id);
        }

        // 📊 Appliquer le même tri que dans l'index
        $orderBy = $request->get('order_by', 'created_at');
        $orderDirection = $request->get('order_direction', 'desc');
        
        if (in_array($orderBy, ['created_at', 'titre', 'statut', 'date_debut', 'date_fin_prevue'])) {
            $query->orderBy($orderBy, $orderDirection);
        }

        // 📁 Récupérer les chantiers (sans pagination pour l'export)
        $chantiers = $query->get();

        // 📝 Générer un nom de fichier informatif
        $filename = 'chantiers_export_' . date('Y-m-d_H-i-s');
        
        // Ajouter des informations sur les filtres dans le nom du fichier
        if ($request->filled('statut')) {
            $filename .= '_' . $request->statut;
        }
        if ($request->filled('search')) {
            $filename .= '_' . \Str::slug($request->search);
        }
        
        $filename .= '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, must-revalidate',
        ];

        $callback = function() use ($chantiers, $request) {
            // 🔧 Ajout du BOM UTF-8 pour Excel
            echo "\xEF\xBB\xBF";
            
            $file = fopen('php://output', 'w');
            
            // 📋 En-têtes du CSV
            $headers = [
                'Titre', 'Description', 'Client', 'Commercial', 'Statut', 
                'Date début', 'Date fin prévue', 'Budget (€)', 'Avancement (%)', 
                'Créé le', 'Modifié le'
            ];
            
            fputcsv($file, $headers, ';'); // Utilisation du point-virgule pour Excel français
            
            // 📊 Ligne de résumé des filtres (optionnelle)
            if ($request->hasAny(['search', 'statut', 'commercial_id', 'client_id'])) {
                $filtresAppliques = [];
                if ($request->filled('search')) {
                    $filtresAppliques[] = "Recherche: {$request->search}";
                }
                if ($request->filled('statut')) {
                    $filtresAppliques[] = "Statut: " . ucfirst($request->statut);
                }
                if ($request->filled('commercial_id')) {
                    $commercial = \App\Models\User::find($request->commercial_id);
                    $filtresAppliques[] = "Commercial: " . ($commercial ? $commercial->name : 'Inconnu');
                }
                if ($request->filled('client_id')) {
                    $client = \App\Models\User::find($request->client_id);
                    $filtresAppliques[] = "Client: " . ($client ? $client->name : 'Inconnu');
                }
                
                // Ligne de commentaire avec les filtres
                fputcsv($file, ["# Filtres appliqués: " . implode(', ', $filtresAppliques)], ';');
                fputcsv($file, ["# Nombre de résultats: " . $chantiers->count()], ';');
                fputcsv($file, ["# Exporté le: " . now()->format('d/m/Y à H:i:s')], ';');
                fputcsv($file, [], ';'); // Ligne vide
            }
            
            // 📋 Données des chantiers
            foreach ($chantiers as $chantier) {
                fputcsv($file, [
                    $chantier->titre,
                    $chantier->description ?: '',
                    $chantier->client->name,
                    $chantier->commercial->name,
                    ucfirst(str_replace('_', ' ', $chantier->statut)),
                    $chantier->date_debut?->format('d/m/Y') ?: '',
                    $chantier->date_fin_prevue?->format('d/m/Y') ?: '',
                    $chantier->budget ? number_format($chantier->budget, 2, ',', ' ') : '',
                    number_format($chantier->avancement_global, 1, ',', ''),
                    $chantier->created_at->format('d/m/Y H:i'),
                    $chantier->updated_at->format('d/m/Y H:i'),
                ], ';');
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}