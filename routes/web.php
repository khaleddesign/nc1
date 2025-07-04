<?php
// routes/web.php - VERSION CORRIGÉE PROGRESSIVE

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

/*
|--------------------------------------------------------------------------
| ÉTAPE 1 : CORRECTION DES ROUTES DUPLIQUÉES
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    Route::get('/password/reset', function () {
        return view('auth.passwords.email');
    })->name('password.request');
    
    Route::post('/password/email', function (Illuminate\Http\Request $request) {
        $request->validate(['email' => 'required|email']);
        return back()->with('status', 'Si cette adresse email existe, vous recevrez un lien de réinitialisation.');
    })->name('password.email');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ROUTES PROTÉGÉES - ORDRE CRITIQUE RESPECTÉ
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', function() { return redirect()->route('dashboard'); });
    
    /*
    |--------------------------------------------------------------------------
    | CHANTIERS - Routes spécifiques AVANT resource
    |--------------------------------------------------------------------------
    */
    Route::get('chantiers/export', [ChantierController::class, 'export'])->name('chantiers.export');
    Route::get('chantiers/calendrier', [ChantierController::class, 'calendrier'])->name('chantiers.calendrier');
    Route::get('chantiers/search', [ChantierController::class, 'search'])->name('chantiers.search');
    
    // Resource chantiers
    Route::resource('chantiers', ChantierController::class);
    
    // Actions spéciales chantiers
    Route::post('chantiers/{chantier}/soft-delete', [ChantierController::class, 'softDelete'])->name('chantiers.soft-delete');
    Route::post('chantiers/{chantier}/restore', [ChantierController::class, 'restore'])->name('chantiers.restore');
    
    /*
    |--------------------------------------------------------------------------
    | DEVIS - STRUCTURE CLAIRE PROSPECT vs CHANTIER
    |--------------------------------------------------------------------------
    */
    
    // A. DEVIS GLOBAUX (prospects + vue d'ensemble)
    Route::prefix('devis')->name('devis.global.')->group(function () {
        Route::get('/', [DevisController::class, 'globalIndex'])->name('index');
        Route::get('prospects', [DevisController::class, 'prospects'])->name('prospects'); // 🆕 NOUVEAU
        Route::get('create', [DevisController::class, 'globalCreate'])->name('create');
        Route::post('/', [DevisController::class, 'globalStore'])->name('store');
        Route::get('{devis}', [DevisController::class, 'globalShow'])->name('show');
        
        // 🆕 Actions spécifiques aux prospects
        Route::post('{devis}/convert-to-chantier', [DevisController::class, 'convertToChantier'])->name('convert-to-chantier');
    });
    
    // B. DEVIS LIÉS AUX CHANTIERS (flux B)
    Route::prefix('chantiers/{chantier}')->name('chantiers.devis.')->group(function () {
        Route::get('devis', [DevisController::class, 'index'])->name('index');
        Route::get('devis/create', [DevisController::class, 'create'])->name('create');
        Route::post('devis', [DevisController::class, 'store'])->name('store');
        Route::get('devis/{devis}', [DevisController::class, 'show'])->name('show');
        Route::get('devis/{devis}/edit', [DevisController::class, 'edit'])->name('edit');
        Route::put('devis/{devis}', [DevisController::class, 'update'])->name('update');
        Route::delete('devis/{devis}', [DevisController::class, 'destroy'])->name('destroy');
        
        // Actions
        Route::post('devis/{devis}/envoyer', [DevisController::class, 'envoyer'])->name('envoyer');
        Route::post('devis/{devis}/accepter', [DevisController::class, 'accepter'])->name('accepter');
        Route::post('devis/{devis}/refuser', [DevisController::class, 'refuser'])->name('refuser');
        Route::post('devis/{devis}/convertir-facture', [DevisController::class, 'convertirEnFacture'])->name('convertir-facture');
        Route::post('devis/{devis}/dupliquer', [DevisController::class, 'dupliquer'])->name('dupliquer');
        
        // PDF et exports
        Route::get('devis/{devis}/pdf', [DevisController::class, 'downloadPdf'])->name('pdf');
        Route::get('devis/{devis}/preview', [DevisController::class, 'previewPdf'])->name('preview');
        
        // Conformité électronique
        Route::post('devis/{devis}/generer-conformite', [DevisController::class, 'genererConformite'])->name('generer-conformite');
        Route::get('devis/{devis}/export-electronique/{format}', [DevisController::class, 'exportElectronique'])->name('export-electronique');
        Route::get('devis/{devis}/verifier-integrite', [DevisController::class, 'verifierIntegrite'])->name('verifier-integrite');
    });
    
    /*
    |--------------------------------------------------------------------------
    | FACTURES - Structure similaire
    |--------------------------------------------------------------------------
    */
    Route::prefix('factures')->name('factures.global.')->group(function () {
        Route::get('/', [FactureController::class, 'globalIndex'])->name('index');
        Route::get('{facture}', [FactureController::class, 'globalShow'])->name('show');
    });
    
    Route::prefix('chantiers/{chantier}')->name('chantiers.factures.')->group(function () {
        Route::get('factures', [FactureController::class, 'index'])->name('index');
        Route::get('factures/create', [FactureController::class, 'create'])->name('create');
        Route::post('factures', [FactureController::class, 'store'])->name('store');
        Route::get('factures/{facture}', [FactureController::class, 'show'])->name('show');
        Route::get('factures/{facture}/edit', [FactureController::class, 'edit'])->name('edit');
        Route::put('factures/{facture}', [FactureController::class, 'update'])->name('update');
        Route::delete('factures/{facture}', [FactureController::class, 'destroy'])->name('destroy');
        
        // Actions factures
        Route::post('factures/{facture}/envoyer', [FactureController::class, 'envoyer'])->name('envoyer');
        Route::post('factures/{facture}/annuler', [FactureController::class, 'annuler'])->name('annuler');
        Route::post('factures/{facture}/paiement', [FactureController::class, 'ajouterPaiement'])->name('paiement');
        Route::post('factures/{facture}/relance', [FactureController::class, 'envoyerRelance'])->name('relance');
        Route::post('factures/{facture}/dupliquer', [FactureController::class, 'dupliquer'])->name('dupliquer');
        
        // PDF et exports
        Route::get('factures/{facture}/pdf', [FactureController::class, 'downloadPdf'])->name('pdf');
        Route::get('factures/{facture}/preview', [FactureController::class, 'previewPdf'])->name('preview');
        Route::get('factures/{facture}/paiements', [FactureController::class, 'recapitulatifPaiements'])->name('paiements');
        
        // Conformité électronique
        Route::post('factures/{facture}/generer-conformite', [FactureController::class, 'genererConformite'])->name('generer-conformite');
        Route::get('factures/{facture}/export-electronique/{format}', [FactureController::class, 'exportElectronique'])->name('export-electronique');
        Route::get('factures/{facture}/verifier-integrite', [FactureController::class, 'verifierIntegrite'])->name('verifier-integrite');
    });
    
    /*
    |--------------------------------------------------------------------------
    | ÉTAPES, DOCUMENTS, COMMENTAIRES
    |--------------------------------------------------------------------------
    */
    Route::prefix('chantiers/{chantier}')->group(function () {
        Route::get('etapes', [ChantierController::class, 'etapes'])->name('chantiers.etapes');
        Route::post('etapes', [EtapeController::class, 'store'])->name('etapes.store');
        Route::put('etapes/{etape}', [EtapeController::class, 'update'])->name('etapes.update');
        Route::delete('etapes/{etape}', [EtapeController::class, 'destroy'])->name('etapes.destroy');
        Route::post('etapes/{etape}/toggle', [EtapeController::class, 'toggleComplete'])->name('etapes.toggle');
        Route::put('etapes/{etape}/progress', [EtapeController::class, 'updateProgress'])->name('etapes.progress');
        Route::post('etapes/reorder', [EtapeController::class, 'reorder'])->name('etapes.reorder');
        Route::get('etapes/json', [EtapeController::class, 'getEtapes'])->name('etapes.json');
        
        Route::post('documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::post('commentaires', [CommentaireController::class, 'store'])->name('commentaires.store');
    });
    
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::delete('commentaires/{commentaire}', [CommentaireController::class, 'destroy'])->name('commentaires.destroy');
    
    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS ET MESSAGES
    |--------------------------------------------------------------------------
    */
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('{notification}/view', [NotificationController::class, 'viewAndMarkAsRead'])->name('view');
    });
    
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::get('sent', [MessageController::class, 'sent'])->name('sent');
        Route::get('create', [MessageController::class, 'create'])->name('create');
        Route::post('/', [MessageController::class, 'store'])->name('store');
        Route::get('{message}', [MessageController::class, 'show'])->name('show');
        Route::get('{message}/reply', [MessageController::class, 'reply'])->name('reply');
        Route::post('{message}/mark-read', [MessageController::class, 'markAsRead'])->name('mark-read');
        Route::delete('{message}', [MessageController::class, 'destroy'])->name('destroy');
        Route::get('modal', [MessageController::class, 'modal'])->name('modal');
    });
    
    /*
    |--------------------------------------------------------------------------
    | ADMINISTRATION
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
        
        Route::get('users', [AdminController::class, 'users'])->name('users');
        Route::get('users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('users/{user}', [AdminController::class, 'showUser'])->name('users.show');
        Route::get('users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::patch('users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
        Route::post('users/bulk-action', [AdminController::class, 'bulkAction'])->name('users.bulk-action');
        Route::get('users/export', [AdminController::class, 'exportUsers'])->name('users.export');
        Route::get('statistics', [AdminController::class, 'statistics'])->name('statistics');
        Route::post('cleanup/files', [DocumentController::class, 'cleanupOrphanedFiles'])->name('cleanup.files');
    });
    
    /*
    |--------------------------------------------------------------------------
    | RAPPORTS
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:commercial,admin'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\ReportController::class, 'dashboard'])->name('dashboard');
        Route::get('dashboard', [App\Http\Controllers\ReportController::class, 'dashboard'])->name('dashboard.main');
        Route::get('chiffre-affaires', [App\Http\Controllers\ReportController::class, 'chiffreAffaires'])->name('chiffre-affaires');
        Route::get('performance-commerciale', [App\Http\Controllers\ReportController::class, 'performanceCommerciale'])->name('performance-commerciale');
        Route::get('sante-financiere', [App\Http\Controllers\ReportController::class, 'santeFinanciere'])->name('sante-financiere');
        Route::get('export-pdf', [App\Http\Controllers\ReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('api-data', [App\Http\Controllers\ReportController::class, 'apiData'])->name('api-data');
        Route::post('bookmark', [App\Http\Controllers\ReportController::class, 'bookmarkReport'])->name('bookmark');
        Route::get('bookmarks', [App\Http\Controllers\ReportController::class, 'getBookmarks'])->name('bookmarks');
        Route::delete('bookmarks/{bookmark}', [App\Http\Controllers\ReportController::class, 'deleteBookmark'])->name('bookmarks.delete');
    });
});

/*
|--------------------------------------------------------------------------
| API ROUTES (JSON)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::get('chantiers/{chantier}/avancement', function (App\Models\Chantier $chantier) {
        if (!auth()->user()->can('view', $chantier)) {
            abort(403);
        }
        return response()->json([
            'avancement' => $chantier->avancement_global,
            'etapes' => $chantier->etapes->map(fn($etape) => [
                'id' => $etape->id,
                'nom' => $etape->nom,
                'pourcentage' => $etape->pourcentage,
                'terminee' => $etape->terminee,
            ]),
        ]);
    })->name('chantiers.avancement');
    
    Route::get('notifications/count', function () {
        return response()->json(['count' => auth()->user()->getNotificationsNonLues()]);
    })->name('notifications.count');
    
    Route::get('messages/unread-count', function () {
        return response()->json(['count' => Auth::user()->getUnreadMessagesCount()]);
    })->name('messages.unread-count');
    
    Route::get('dashboard/progress', function () {
        $user = auth()->user();
        $nouvelles_notifications = $user->notifications()
            ->where('created_at', '>', now()->subMinutes(5))
            ->where('lu', false)
            ->get();

        $updates = $nouvelles_notifications->map(fn($notification) => [
            'type' => 'success',
            'message' => $notification->titre . ': ' . $notification->message
        ]);

        return response()->json(['updates' => $updates]);
    })->name('dashboard.progress');
    
    Route::get('admin/stats', function () {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        return response()->json([
            'total_users' => \App\Models\User::count(),
            'total_chantiers' => \App\Models\Chantier::count(),
            'chantiers_actifs' => \App\Models\Chantier::where('statut', 'en_cours')->count(),
            'chantiers_termines' => \App\Models\Chantier::where('statut', 'termine')->count(),
            'chantiers_en_retard' => \App\Models\Chantier::whereDate('date_fin_prevue', '<', now())
                ->where('statut', '!=', 'termine')
                ->count(),
        ]);
    })->name('admin.stats');
});

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES (devis clients)
|--------------------------------------------------------------------------
*/
Route::get('/devis-public/{devis}/{token}', [DevisController::class, 'showPublic'])
    ->name('devis.public.show')
    ->middleware(['signed', 'throttle:10,1']);

Route::post('/devis-public/{devis}/{token}/reponse', [DevisController::class, 'storePublicResponse'])
    ->name('devis.public.reponse')
    ->middleware(['signed', 'throttle:5,1']);

/*
|--------------------------------------------------------------------------
| FALLBACK
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    if (request()->expectsJson()) {
        return response()->json(['error' => 'Route not found'], 404);
    }
    
    if (Auth::check()) {
        return redirect()->route('dashboard')->with('error', 'Page non trouvée');
    }
    
    return redirect()->route('login')->with('error', 'Page non trouvée');
});