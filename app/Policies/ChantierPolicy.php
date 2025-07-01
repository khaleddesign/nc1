<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Chantier;

class ChantierPolicy
{
    /**
     * Peut voir la liste de tous les chantiers.
     */
    public function viewAny(User $user): bool
    {
        // Les trois rôles peuvent voir la liste (avec filtrage dans le contrôleur)
        return in_array($user->role, ['admin', 'commercial', 'client']);
    }

    /**
     * Peut voir un chantier précis.
     */
    public function view(User $user, Chantier $chantier): bool
    {
        // Admin : toujours OK
        if ($user->isAdmin()) return true;
        // Commercial lié au chantier
        if ($user->isCommercial() && $chantier->commercial_id === $user->id) return true;
        // Client concerné par ce chantier
        if ($user->isClient() && $chantier->client_id === $user->id) return true;
        // Sinon, interdit
        return false;
    }

    /**
     * Peut créer un chantier.
     */
    public function create(User $user): bool
    {
        // Uniquement admin ou commercial
        return $user->isAdmin() || $user->isCommercial();
    }
// À ajouter APRÈS la méthode delete() existante (vers la ligne 45) :

    /**
     * Peut masquer un chantier (suppression douce pour commercial).
     */
    public function softDelete(User $user, Chantier $chantier): bool
    {
        // Commercial rattaché : peut masquer SON chantier
        if ($user->isCommercial() && $chantier->commercial_id === $user->id) {
            return true;
        }
        
        // Admin : peut aussi masquer (en plus de supprimer)
        if ($user->isAdmin()) {
            return true;
        }
        
        return false;
    }

    /**
     * Peut restaurer la visibilité d'un chantier masqué.
     */
    public function restore(User $user, Chantier $chantier): bool
    {
        // Seul l'admin peut restaurer un chantier masqué
        return $user->isAdmin();
    }
    
    /**
     * Peut éditer un chantier.
     */
    public function update(User $user, Chantier $chantier): bool
    {
        // Admin : OK
        if ($user->isAdmin()) return true;
        // Commercial rattaché : OK
        return $user->isCommercial() && $chantier->commercial_id === $user->id;
    }

    /**
     * Peut supprimer un chantier.
     */
    public function delete(User $user, Chantier $chantier): bool
    {
        // Seul l’admin (option : commercial rattaché aussi)
        return $user->isAdmin();
    }

    /**
     * Peut gérer les étapes d’un chantier.
     */
    public function manageEtapes(User $user, Chantier $chantier): bool
    {
        // Admin ou commercial rattaché
        return $user->isAdmin() || ($user->isCommercial() && $chantier->commercial_id === $user->id);
    }
}
