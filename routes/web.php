<?php
// routes/web.php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChantierController;
use App\Http\Controllers\EtapeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil - Redirect vers dashboard
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Routes d'authentification Laravel UI
Auth::routes();

// Routes protégées par l'authentification
Route::middleware(['auth'])->group(function () {
    
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    
    // Gestion des chantiers
    Route::resource('chantiers', ChantierController::class);
    Route::get('chantiers/{chantier}/etapes', [ChantierController::class, 'etapes'])->name('chantiers.etapes');
    Route::get('chantiers/{chantier}/export', [ChantierController::class, 'export'])->name('chantiers.export');
    Route::get('chantiers/calendrier/view', [ChantierController::class, 'calendrier'])->name('chantiers.calendrier');
    Route::get('chantiers/search', [ChantierController::class, 'search'])->name('chantiers.search');
    
    // Gestion des étapes (nested routes)
    Route::prefix('chantiers/{chantier}')->group(function () {
        Route::post('etapes', [EtapeController::class, 'store'])->name('etapes.store');
        Route::put('etapes/{etape}', [EtapeController::class, 'update'])->name('etapes.update');
        Route::delete('etapes/{etape}', [EtapeController::class, 'destroy'])->name('etapes.destroy');
        Route::post('etapes/{etape}/toggle', [EtapeController::class, 'toggleComplete'])->name('etapes.toggle');
        Route::put('etapes/{etape}/progress', [EtapeController::class, 'updateProgress'])->name('etapes.progress');
        Route::post('etapes/reorder', [EtapeController::class, 'reorder'])->name('etapes.reorder');
        Route::get('etapes/json', [EtapeController::class, 'getEtapes'])->name('etapes.json');
    });
    
    // Gestion des documents
    Route::prefix('chantiers/{chantier}')->group(function () {
        Route::post('documents', [DocumentController::class, 'store'])->name('documents.store');
    });
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    
    // Gestion des commentaires
    Route::prefix('chantiers/{chantier}')->group(function () {
        Route::post('commentaires', [CommentaireController::class, 'store'])->name('commentaires.store');
    });
    Route::delete('commentaires/{commentaire}', [CommentaireController::class, 'destroy'])->name('commentaires.destroy');
    
    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    });
    
    // Routes admin uniquement
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::get('users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::post('users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('admin.users.toggle');
        
        Route::get('statistics', [AdminController::class, 'statistics'])->name('admin.statistics');
        
        // Nettoyage des fichiers orphelins (admin seulement)
        Route::post('cleanup/files', [DocumentController::class, 'cleanupOrphanedFiles'])->name('admin.cleanup.files');
    });
});

// Routes API pour les appels AJAX (sécurisées)
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('chantiers/{chantier}/avancement', function (App\Models\Chantier $chantier) {
        // Vérification des autorisations
        if (!auth()->user()->can('view', $chantier)) {
            abort(403, 'Accès non autorisé');
        }
        
        return response()->json([
            'avancement' => $chantier->avancement_global,
            'etapes' => $chantier->etapes->map(function ($etape) {
                return [
                    'id' => $etape->id,
                    'nom' => $etape->nom,
                    'pourcentage' => $etape->pourcentage,
                    'terminee' => $etape->terminee,
                ];
            }),
        ]);
    })->name('api.chantiers.avancement');
    
    Route::get('notifications/count', function () {
        $count = auth()->user()->getNotificationsNonLues();
        return response()->json(['count' => $count]);
    })->name('api.notifications.count');
    
    // API pour les statistiques (admin seulement)
    Route::middleware(['role:admin'])->get('admin/stats', function () {
        return response()->json([
            'total_users' => \App\Models\User::count(),
            'total_chantiers' => \App\Models\Chantier::count(),
            'chantiers_actifs' => \App\Models\Chantier::where('statut', 'en_cours')->count(),
            'chantiers_termines' => \App\Models\Chantier::where('statut', 'termine')->count(),
            'chantiers_en_retard' => \App\Models\Chantier::whereDate('date_fin_prevue', '<', now())
                ->where('statut', '!=', 'termine')
                ->count(),
        ]);
    })->name('api.admin.stats');
    
    // API pour la recherche de chantiers
    Route::get('chantiers/search', function (Illuminate\Http\Request $request) {
        $query = $request->get('q', '');
        $user = auth()->user();
        
        $chantiersQuery = \App\Models\Chantier::query();
        
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
    })->name('api.chantiers.search');
});

// Routes d'erreur personnalisées
Route::fallback(function () {
    if (request()->expectsJson()) {
        return response()->json(['error' => 'Route non trouvée'], 404);
    }
    return response()->view('errors.404', [], 404);
});

// Routes de test (à supprimer en production)
if (app()->environment('local')) {
    Route::get('/test-email', function () {
        $notification = \App\Models\Notification::first();
        $user = \App\Models\User::first();
        $chantier = \App\Models\Chantier::first();
        
        return view('emails.notification', compact('notification', 'user', 'chantier'));
    });
}