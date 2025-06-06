<?php
// app/Http/Controllers/EtapeController.php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Etape;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EtapeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Créer une nouvelle étape pour un chantier
     */
    public function store(Request $request, Chantier $chantier)
    {
        // Vérifier que l'utilisateur peut modifier ce chantier
        $this->authorize('update', $chantier);
        
        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ordre' => 'required|integer|min:0',
            'pourcentage' => 'required|numeric|min:0|max:100',
            'date_debut' => 'nullable|date',
            'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut',
            'notes' => 'nullable|string',
        ]);

        // Ajouter l'ID du chantier
        $validated['chantier_id'] = $chantier->id;
        
        // Créer l'étape
        $etape = Etape::create($validated);
        
        // Recalculer l'avancement global du chantier
        $chantier->calculerAvancement();
        
        // Créer une notification pour le client
        Notification::creerNotification(
            $chantier->client_id,
            $chantier->id,
            'nouvelle_etape',
            'Nouvelle étape ajoutée',
            "Une nouvelle étape '{$etape->nom}' a été ajoutée au chantier '{$chantier->titre}'"
        );

        return redirect()->route('chantiers.show', $chantier)
                        ->with('success', 'Étape ajoutée avec succès.');
    }

    /**
     * Mettre à jour une étape existante
     */
    public function update(Request $request, Chantier $chantier, Etape $etape)
    {
        // Vérifier que l'utilisateur peut modifier ce chantier
        $this->authorize('update', $chantier);
        
        // Vérifier que l'étape appartient bien à ce chantier
        if ($etape->chantier_id !== $chantier->id) {
            abort(404);
        }
        
        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ordre' => 'required|integer|min:0',
            'pourcentage' => 'required|numeric|min:0|max:100',
            'date_debut' => 'nullable|date',
            'date_fin_prevue' => 'nullable|date',
            'date_fin_effective' => 'nullable|date',
            'notes' => 'nullable|string',
            'terminee' => 'boolean',
        ]);

        // Si l'étape est marquée comme terminée, ajuster les valeurs
        if ($request->boolean('terminee')) {
            $validated['pourcentage'] = 100;
            $validated['date_fin_effective'] = $validated['date_fin_effective'] ?? now();
        }

        // Mettre à jour l'étape
        $etape->update($validated);
        
        // Recalculer l'avancement global du chantier
        $chantier->calculerAvancement();
        
        // Notification au client si étape terminée
        if ($request->boolean('terminee') && $etape->wasChanged('terminee')) {
            Notification::creerNotification(
                $chantier->client_id,
                $chantier->id,
                'etape_terminee',
                'Étape terminée',
                "L'étape '{$etape->nom}' du chantier '{$chantier->titre}' a été terminée."
            );
        }

        return redirect()->route('chantiers.show', $chantier)
                        ->with('success', 'Étape modifiée avec succès.');
    }

    /**
     * Supprimer une étape
     */
    public function destroy(Chantier $chantier, Etape $etape)
    {
        // Vérifier que l'utilisateur peut modifier ce chantier
        $this->authorize('update', $chantier);
        
        // Vérifier que l'étape appartient bien à ce chantier
        if ($etape->chantier_id !== $chantier->id) {
            abort(404);
        }
        
        // Supprimer l'étape
        $etape->delete();
        
        // Recalculer l'avancement global du chantier
        $chantier->calculerAvancement();
        
        return redirect()->route('chantiers.show', $chantier)
                        ->with('success', 'Étape supprimée avec succès.');
    }

    /**
     * Marquer une étape comme terminée (AJAX)
     */
    public function toggleComplete(Request $request, Chantier $chantier, Etape $etape)
    {
        // Vérifier que l'utilisateur peut modifier ce chantier
        $this->authorize('update', $chantier);
        
        // Vérifier que l'étape appartient bien à ce chantier
        if ($etape->chantier_id !== $chantier->id) {
            abort(404);
        }
        
        // Basculer le statut de l'étape
        $etape->update([
            'terminee' => !$etape->terminee,
            'pourcentage' => $etape->terminee ? 0 : 100,
            'date_fin_effective' => $etape->terminee ? null : now()
        ]);
        
        // Recalculer l'avancement global
        $nouvelAvancement = $chantier->calculerAvancement();
        
        // Notification si l'étape vient d'être terminée
        if (!$etape->terminee) { // Vient d'être marquée comme terminée
            Notification::creerNotification(
                $chantier->client_id,
                $chantier->id,
                'etape_terminee',
                'Étape terminée',
                "L'étape '{$etape->nom}' a été terminée."
            );
        }
        
        // Réponse JSON pour AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'terminee' => !$etape->terminee,
                'pourcentage' => $etape->pourcentage,
                'avancement_global' => $nouvelAvancement,
                'message' => $etape->terminee ? 'Étape marquée comme non terminée' : 'Étape terminée'
            ]);
        }
        
        return redirect()->route('chantiers.show', $chantier)
                        ->with('success', 'Statut de l\'étape modifié avec succès.');
    }

    /**
     * Mettre à jour rapidement le pourcentage d'une étape (AJAX)
     */
    public function updateProgress(Request $request, Chantier $chantier, Etape $etape)
    {
        // Vérifier que l'utilisateur peut modifier ce chantier
        $this->authorize('update', $chantier);
        
        // Vérifier que l'étape appartient bien à ce chantier
        if ($etape->chantier_id !== $chantier->id) {
            abort(404);
        }
        
        // Validation
        $validated = $request->validate([
            'pourcentage' => 'required|numeric|min:0|max:100'
        ]);
        
        // Mettre à jour le pourcentage
        $etape->update([
            'pourcentage' => $validated['pourcentage'],
            'terminee' => $validated['pourcentage'] >= 100,
            'date_fin_effective' => $validated['pourcentage'] >= 100 ? now() : null
        ]);
        
        // Recalculer l'avancement global
        $nouvelAvancement = $chantier->calculerAvancement();
        
        // Notification si l'étape vient d'être terminée
        if ($validated['pourcentage'] >= 100 && $etape->wasChanged('terminee')) {
            Notification::creerNotification(
                $chantier->client_id,
                $chantier->id,
                'etape_terminee',
                'Étape terminée',
                "L'étape '{$etape->nom}' a été terminée."
            );
        }
        
        // Réponse JSON pour AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'pourcentage' => $etape->pourcentage,
                'terminee' => $etape->terminee,
                'avancement_global' => $nouvelAvancement,
                'message' => 'Progression mise à jour'
            ]);
        }
        
        return redirect()->route('chantiers.show', $chantier)
                        ->with('success', 'Progression de l\'étape mise à jour.');
    }

    /**
     * Réorganiser l'ordre des étapes (AJAX)
     */
    public function reorder(Request $request, Chantier $chantier)
    {
        // Vérifier que l'utilisateur peut modifier ce chantier
        $this->authorize('update', $chantier);
        
        // Validation
        $validated = $request->validate([
            'etapes' => 'required|array',
            'etapes.*' => 'integer|exists:etapes,id'
        ]);
        
        // Mettre à jour l'ordre des étapes
        foreach ($validated['etapes'] as $ordre => $etapeId) {
            Etape::where('id', $etapeId)
                 ->where('chantier_id', $chantier->id)
                 ->update(['ordre' => $ordre + 1]);
        }
        
        // Réponse JSON pour AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ordre des étapes mis à jour'
            ]);
        }
        
        return redirect()->route('chantiers.show', $chantier)
                        ->with('success', 'Ordre des étapes mis à jour.');
    }

    /**
     * Obtenir les étapes d'un chantier en JSON (pour API)
     */
    public function getEtapes(Chantier $chantier)
    {
        // Vérifier que l'utilisateur peut voir ce chantier
        $this->authorize('view', $chantier);
        
        $etapes = $chantier->etapes->map(function ($etape) {
            return [
                'id' => $etape->id,
                'nom' => $etape->nom,
                'description' => $etape->description,
                'ordre' => $etape->ordre,
                'pourcentage' => $etape->pourcentage,
                'terminee' => $etape->terminee,
                'date_debut' => $etape->date_debut?->format('Y-m-d'),
                'date_fin_prevue' => $etape->date_fin_prevue?->format('Y-m-d'),
                'date_fin_effective' => $etape->date_fin_effective?->format('Y-m-d'),
                'en_retard' => $etape->isEnRetard(),
                'notes' => $etape->notes,
            ];
        });
        
        return response()->json([
            'success' => true,
            'etapes' => $etapes,
            'avancement_global' => $chantier->avancement_global
        ]);
    }
}