<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChantierController;
use App\Http\Controllers\EtapeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\PaiementController;

// ✅ IMPORTS API avec ALIAS pour éviter les conflits
use App\Http\Controllers\Api\PhotoController as ApiPhotoController;
use App\Http\Controllers\Api\DashboardController as ApiDashboardController;
use App\Http\Controllers\Api\DevisController as ApiDevisController;
use App\Http\Controllers\Api\NotificationController as ApiNotificationController;
use App\Http\Controllers\Api\EvaluationController as ApiEvaluationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil - Redirect vers dashboard ou login
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Routes d'authentification manuelles (Laravel UI style)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes d'inscription (optionnelles)
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');

// Routes de réinitialisation de mot de passe (optionnelles)
Route::get('/password/reset', function () {
    return view('auth.passwords.email');
})->name('password.request')->middleware('guest');

Route::post('/password/email', function (Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);
    
    return back()->with('status', 'Si cette adresse email existe, vous recevrez un lien de réinitialisation.');
})->name('password.email')->middleware('guest');

// Routes protégées par l'authentification
Route::middleware(['auth'])->group(function () {
    
    // Dashboard principal (route vers le bon dashboard selon le rôle)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home'); // Fallback pour Laravel UI
    
    // ✅ ROUTES SPÉCIFIQUES AVANT LE RESOURCE (ordre CRITIQUE !)
    Route::get('chantiers/export', [ChantierController::class, 'export'])->name('chantiers.export');
    Route::get('chantiers/calendrier', [ChantierController::class, 'calendrier'])->name('chantiers.calendrier');
    Route::get('chantiers/search', [ChantierController::class, 'search'])->name('chantiers.search');
    
    // ✅ RESOURCE ROUTE APRÈS (pour éviter les conflits)
    Route::resource('chantiers', ChantierController::class);
    
    // Routes spécifiques avec paramètres (après le resource)
    Route::get('chantiers/{chantier}/etapes', [ChantierController::class, 'etapes'])->name('chantiers.etapes');
    
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

    // ================================
    // 🚀 ROUTES DEVIS ET FACTURES
    // ================================
    
    // Routes pour les devis (liées aux chantiers)
    Route::prefix('chantiers/{chantier}')->group(function () {
        Route::get('devis', [DevisController::class, 'index'])->name('chantiers.devis.index');
        Route::get('devis/create', [DevisController::class, 'create'])->name('chantiers.devis.create');
        Route::post('devis', [DevisController::class, 'store'])->name('chantiers.devis.store');
        Route::get('devis/{devis}', [DevisController::class, 'show'])->name('chantiers.devis.show');
        Route::get('devis/{devis}/edit', [DevisController::class, 'edit'])->name('chantiers.devis.edit');
        Route::put('devis/{devis}', [DevisController::class, 'update'])->name('chantiers.devis.update');
        Route::delete('devis/{devis}', [DevisController::class, 'destroy'])->name('chantiers.devis.destroy');
        
        // Actions spéciales pour les devis
        Route::post('devis/{devis}/envoyer', [DevisController::class, 'envoyer'])->name('chantiers.devis.envoyer');
        Route::post('devis/{devis}/accepter', [DevisController::class, 'accepter'])->name('chantiers.devis.accepter');
        Route::post('devis/{devis}/refuser', [DevisController::class, 'refuser'])->name('chantiers.devis.refuser');
        Route::post('devis/{devis}/convertir-facture', [DevisController::class, 'convertirEnFacture'])->name('chantiers.devis.convertir-facture');
        Route::post('devis/{devis}/dupliquer', [DevisController::class, 'dupliquer'])->name('chantiers.devis.dupliquer');
        
        // PDF
        Route::get('devis/{devis}/pdf', [DevisController::class, 'downloadPdf'])->name('chantiers.devis.pdf');
        Route::get('devis/{devis}/preview', [DevisController::class, 'previewPdf'])->name('chantiers.devis.preview');
    });

    // Routes pour les factures (liées aux chantiers)
    Route::prefix('chantiers/{chantier}')->group(function () {
        Route::get('factures', [FactureController::class, 'index'])->name('chantiers.factures.index');
        Route::get('factures/create', [FactureController::class, 'create'])->name('chantiers.factures.create');
        Route::post('factures', [FactureController::class, 'store'])->name('chantiers.factures.store');
        Route::get('factures/{facture}', [FactureController::class, 'show'])->name('chantiers.factures.show');
        Route::get('factures/{facture}/edit', [FactureController::class, 'edit'])->name('chantiers.factures.edit');
        Route::put('factures/{facture}', [FactureController::class, 'update'])->name('chantiers.factures.update');
        Route::delete('factures/{facture}', [FactureController::class, 'destroy'])->name('chantiers.factures.destroy');
        
        // Actions spéciales pour les factures
        Route::post('factures/{facture}/envoyer', [FactureController::class, 'envoyer'])->name('chantiers.factures.envoyer');
        Route::post('factures/{facture}/annuler', [FactureController::class, 'annuler'])->name('chantiers.factures.annuler');
        Route::post('factures/{facture}/paiement', [FactureController::class, 'ajouterPaiement'])->name('chantiers.factures.paiement');
        Route::post('factures/{facture}/relance', [FactureController::class, 'envoyerRelance'])->name('chantiers.factures.relance');
        Route::post('factures/{facture}/dupliquer', [FactureController::class, 'dupliquer'])->name('chantiers.factures.dupliquer');
        
        // PDF
        Route::get('factures/{facture}/pdf', [FactureController::class, 'downloadPdf'])->name('chantiers.factures.pdf');
        Route::get('factures/{facture}/preview', [FactureController::class, 'previewPdf'])->name('chantiers.factures.preview');
        Route::get('factures/{facture}/paiements', [FactureController::class, 'recapitulatifPaiements'])->name('chantiers.factures.paiements');
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

    // Routes pour les messages
    Route::prefix('messages')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('messages.index');
        Route::get('/sent', [MessageController::class, 'sent'])->name('messages.sent');
        Route::get('/create', [MessageController::class, 'create'])->name('messages.create');
        Route::post('/', [MessageController::class, 'store'])->name('messages.store');
        Route::get('/{message}', [MessageController::class, 'show'])->name('messages.show');
        Route::get('/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');
        Route::post('/{message}/mark-read', [MessageController::class, 'markAsRead'])->name('messages.mark-read');
        Route::delete('/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
        Route::get('/modal', [MessageController::class, 'modal'])->name('messages.modal');
    });
});

// Routes admin uniquement
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard'); // Alias
    
    // Gestion des utilisateurs
    Route::get('users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::get('users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::patch('users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('admin.users.toggle');
    
    // Actions en lot et export
    Route::post('users/bulk-action', [AdminController::class, 'bulkAction'])->name('admin.users.bulk-action');
    Route::get('users/export', [AdminController::class, 'exportUsers'])->name('admin.users.export');
    
    // Statistiques
    Route::get('statistics', [AdminController::class, 'statistics'])->name('admin.statistics');
    
    // Nettoyage des fichiers orphelins (admin seulement)
    Route::post('cleanup/files', [DocumentController::class, 'cleanupOrphanedFiles'])->name('admin.cleanup.files');
});

// ================================
// ROUTES API EXISTANTES (AJAX)
// ================================
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('chantiers/{chantier}/avancement', function (App\Models\Chantier $chantier) {
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

    // API pour les messages non lus
    Route::get('messages/unread-count', function () {
        return response()->json([
            'count' => Auth::user()->getUnreadMessagesCount()
        ]);
    })->name('api.messages.unread-count');

    // API pour les types de projets (devis)
    Route::get('devis/project-types', [ApiDevisController::class, 'getProjectTypes'])->name('api.devis.project-types');
    Route::resource('devis', ApiDevisController::class);
    
    Route::get('dashboard/progress', function () {
        $user = auth()->user();
        $updates = [];

        $nouvelles_notifications = $user->notifications()
                                       ->where('created_at', '>', now()->subMinutes(5))
                                       ->where('lu', false)
                                       ->get();

        foreach ($nouvelles_notifications as $notification) {
            $updates[] = [
                'type' => 'success',
                'message' => $notification->titre . ': ' . $notification->message
            ];
        }

        return response()->json(['updates' => $updates]);
    })->name('api.dashboard.progress');
    
    Route::get('admin/stats', function () {
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
});

// Routes d'erreur personnalisées
Route::fallback(function () {
    if (request()->expectsJson()) {
        return response()->json(['error' => 'Route non trouvée'], 404);
    }
    return response()->view('errors.404', [], 404);
});