<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Devis;
use App\Models\Chantier;
use Illuminate\Auth\Access\Response;

class DevisPolicy
{
    /**
     * Peut voir la liste des devis d'un chantier
     */
    public function viewAny(User $user, Chantier $chantier): bool
    {
        // Admin : toujours autorisé
        if ($user->isAdmin()) {
            return true;
        }

        // Commercial rattaché au chantier
        if ($user->isCommercial() && $chantier->commercial_id === $user->id) {
            return true;
        }

        // Client concerné par le chantier
        if ($user->isClient() && $chantier->client_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Peut voir un devis spécifique
     */
    public function view(User $user, Devis $devis): bool
    {
        $chantier = $devis->chantier;

        // Admin : toujours autorisé
        if ($user->isAdmin()) {
            return true;
        }

        // Commercial assigné au chantier
        if ($user->isCommercial() && $chantier->commercial_id === $user->id) {
            return true;
        }

        // Client propriétaire du chantier
        if ($user->isClient() && $chantier->client_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Peut créer un devis
     */
    public function create(User $user, Chantier $chantier): Response
    {
        // Seuls admin et commercial peuvent créer des devis
        if (!($user->isAdmin() || $user->isCommercial())) {
            return Response::deny('Seuls les administrateurs et commerciaux peuvent créer des devis.');
        }

        // Admin : toujours autorisé
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Commercial : seulement sur ses chantiers
        if ($user->isCommercial() && $chantier->commercial_id !== $user->id) {
            return Response::deny('Vous ne pouvez créer des devis que sur vos chantiers assignés.');
        }

        return Response::allow();
    }

    /**
     * Peut modifier un devis
     */
    public function update(User $user, Devis $devis): Response
    {
        $chantier = $devis->chantier;

        // Vérifier si le devis est modifiable (statut)
        if (in_array($devis->statut, ['accepte', 'refuse', 'facture'])) {
            return Response::deny('Ce devis ne peut plus être modifié car il a été ' . $devis->statut . '.');
        }

        // Admin : peut modifier tous les devis
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Commercial : ses devis uniquement
        if ($user->isCommercial() && $chantier->commercial_id === $user->id) {
            return Response::allow();
        }

        return Response::deny('Vous n\'avez pas l\'autorisation de modifier ce devis.');
    }

    /**
     * Peut supprimer un devis
     */
    public function delete(User $user, Devis $devis): Response
    {
        // Seul l'admin peut supprimer
        if (!$user->isAdmin()) {
            return Response::deny('Seul un administrateur peut supprimer un devis.');
        }

        // Vérifier si le devis peut être supprimé
        if (in_array($devis->statut, ['accepte', 'facture'])) {
            return Response::deny('Impossible de supprimer un devis accepté ou facturé.');
        }

        return Response::allow();
    }

    /**
     * Peut envoyer le devis au client
     */
    public function envoyer(User $user, Devis $devis): Response
    {
        $chantier = $devis->chantier;

        // Seule un devis en brouillon peut être envoyé
        if ($devis->statut !== 'brouillon') {
            return Response::deny('Seul un devis en brouillon peut être envoyé.');
        }

        // Admin : autorisé
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Commercial qui a créé le devis
        if ($user->isCommercial() && $chantier->commercial_id === $user->id) {
            return Response::allow();
        }

        return Response::deny('Vous ne pouvez pas envoyer ce devis.');
    }

    /**
     * Peut accepter/refuser le devis (ACTION CLIENT)
     */
    public function respond(User $user, Devis $devis): Response
    {
        $chantier = $devis->chantier;

        // Seul le client propriétaire peut accepter/refuser
        if (!$user->isClient() || $chantier->client_id !== $user->id) {
            return Response::deny('Seul le client propriétaire peut accepter ou refuser ce devis.');
        }

        // Vérifier le statut
        if ($devis->statut !== 'envoye') {
            return Response::deny('Ce devis ne peut pas être modifié dans son état actuel.');
        }

        return Response::allow();
    }

    /**
     * Peut convertir en facture
     */
    public function convertToFacture(User $user, Devis $devis): Response
    {
        $chantier = $devis->chantier;

        // Seuls admin et commercial peuvent convertir
        if (!($user->isAdmin() || $user->isCommercial())) {
            return Response::deny('Seuls les administrateurs et commerciaux peuvent convertir un devis en facture.');
        }

        // Le devis doit être accepté
        if ($devis->statut !== 'accepte') {
            return Response::deny('Seul un devis accepté peut être converti en facture.');
        }

        // Admin : autorisé
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Commercial du chantier
        if ($user->isCommercial() && $chantier->commercial_id === $user->id) {
            return Response::allow();
        }

        return Response::deny('Vous ne pouvez pas convertir ce devis.');
    }

    /**
     * Peut dupliquer le devis
     */
    public function dupliquer(User $user, Devis $devis): bool
    {
        $chantier = $devis->chantier;

        // Admin : autorisé
        if ($user->isAdmin()) {
            return true;
        }

        // Commercial du chantier
        return $user->isCommercial() && $chantier->commercial_id === $user->id;
    }

    /**
     * Peut télécharger le PDF
     */
    public function downloadPdf(User $user, Devis $devis): bool
    {
        // Même autorisation que pour voir le devis
        return $this->view($user, $devis);
    }
}