<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Photo;

class PhotoPolicy
{
    /**
     * Peut voir la liste des photos
     */
    public function viewAny(User $user): bool
    {
        return true; // Tous les utilisateurs peuvent voir les photos (avec filtrage)
    }

    /**
     * Peut voir une photo spécifique
     */
    public function view(User $user, Photo $photo): bool
    {
        $chantier = $photo->chantier;

        // Admin : toujours autorisé
        if ($user->isAdmin()) {
            return true;
        }

        // Commercial du chantier
        if ($user->isCommercial() && $chantier->commercial_id === $user->id) {
            return true;
        }

        // Client du chantier (seulement si la photo est visible pour les clients)
        if ($user->isClient() && $chantier->client_id === $user->id) {
            return $photo->visible_client ?? true; // Par défaut visible
        }

        return false;
    }

    /**
     * Peut uploader des photos
     */
    public function create(User $user, $chantier = null): bool
    {
        // Admin : toujours autorisé
        if ($user->isAdmin()) {
            return true;
        }

        // Commercial : seulement sur ses chantiers
        if ($user->isCommercial() && $chantier && $chantier->commercial_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Peut modifier les paramètres d'une photo
     */
    public function update(User $user, Photo $photo): bool
    {
        $chantier = $photo->chantier;

        // Admin : autorisé
        if ($user->isAdmin()) {
            return true;
        }

        // Commercial du chantier ou propriétaire de la photo
        return $user->isCommercial() && 
               ($chantier->commercial_id === $user->id || $photo->user_id === $user->id);
    }

    /**
     * Peut supprimer une photo
     */
    public function delete(User $user, Photo $photo): bool
    {
        $chantier = $photo->chantier;

        // Admin : autorisé
        if ($user->isAdmin()) {
            return true;
        }

        // Commercial du chantier ou propriétaire de la photo
        return $user->isCommercial() && 
               ($chantier->commercial_id === $user->id || $photo->user_id === $user->id);
    }

    /**
     * Peut gérer la visibilité client
     */
    public function toggleVisibility(User $user, Photo $photo): bool
    {
        $chantier = $photo->chantier;

        // Admin : autorisé
        if ($user->isAdmin()) {
            return true;
        }

        // Commercial du chantier
        return $user->isCommercial() && $chantier->commercial_id === $user->id;
    }
}
