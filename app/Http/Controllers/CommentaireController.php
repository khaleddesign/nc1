<?php

// app/Http/Controllers/CommentaireController.php
namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Commentaire;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentaireController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Chantier $chantier)
    {
        $this->authorize('view', $chantier);
        
        $validated = $request->validate([
            'contenu' => 'required|string|max:1000',
        ]);

        $commentaire = Commentaire::create([
            'chantier_id' => $chantier->id,
            'user_id' => Auth::id(),
            'contenu' => $validated['contenu'],
        ]);

        // Notification selon le rôle
        if (Auth::user()->isClient()) {
            // Notifier le commercial
            Notification::creerNotification(
                $chantier->commercial_id,
                $chantier->id,
                'nouveau_commentaire_client',
                'Nouveau commentaire client',
                "Le client {$chantier->client->name} a posté un commentaire sur le chantier '{$chantier->titre}'"
            );
        } elseif (Auth::user()->isCommercial()) {
            // Notifier le client
            Notification::creerNotification(
                $chantier->client_id,
                $chantier->id,
                'nouveau_commentaire_commercial',
                'Réponse du commercial',
                "Le commercial {$chantier->commercial->name} a répondu sur le chantier '{$chantier->titre}'"
            );
        }

        return redirect()->route('chantiers.show', $chantier)
                        ->with('success', 'Commentaire ajouté avec succès.');
    }

    public function destroy(Commentaire $commentaire)
    {
        // Seul l'auteur ou un admin peut supprimer
        if (Auth::id() !== $commentaire->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $chantier = $commentaire->chantier;
        $commentaire->delete();
        
        return redirect()->route('chantiers.show', $chantier)
                        ->with('success', 'Commentaire supprimé avec succès.');
    }
}