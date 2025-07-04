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
        // Admin : toujours autorisé
        if ($user->isAdmin()) {
            return true;
        }

        // Commercial assigné
        if ($user->isCommercial() && $devis->commercial_id === $user->id) {
            return true;
        }

        // Client : peut voir les devis liés à ses chantiers OU les prospects qui lui sont adressés
        if ($user->isClient()) {
            // Devis lié à un chantier du client
            if ($devis->chantier && $devis->chantier->client_id === $user->id) {
                return true;
            }
            
            // Prospect adressé au client (via email dans client_info)
            if ($devis->isProspect() && 
                isset($devis->client_info['email']) && 
                $devis->client_info['email'] === $user->email) {
                return true;
            }
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
        // Vérifier si le devis est modifiable selon son type
        if (!$devis->peutEtreModifie()) {
            $message = $devis->isProspect() 
                ? 'Ce prospect ne peut plus être modifié car il a été ' . $devis->statut_prospect . '.'
                : 'Ce devis ne peut plus être modifié car il a été ' . $devis->statut . '.';
            return Response::deny($message);
        }

        // Admin : peut modifier tous les devis
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Commercial : ses devis uniquement
        if ($user->isCommercial() && $devis->commercial_id === $user->id) {
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

        // Vérifier si le devis peut être supprimé selon son statut
        if ($devis->isProspect()) {
            if (in_array($devis->statut_prospect, ['accepte', 'converti'])) {
                return Response::deny('Impossible de supprimer un prospect accepté ou converti.');
            }
        } else {
            if (in_array($devis->statut, ['accepte', 'facture'])) {
                return Response::deny('Impossible de supprimer un devis accepté ou facturé.');
            }
        }

        // Vérifier s'il y a une facture liée
        if ($devis->facture_id) {
            return Response::deny('Impossible de supprimer un devis lié à une facture.');
        }

        return Response::allow();
    }

    /**
     * Peut envoyer le devis au client
     */
    public function envoyer(User $user, Devis $devis): Response
    {
        // Vérifier le statut selon le type
        if ($devis->isProspect()) {
            if (!in_array($devis->statut_prospect, ['brouillon', 'negocie'])) {
                return Response::deny('Seuls les prospects en brouillon ou en négociation peuvent être envoyés.');
            }
        } else {
            if ($devis->statut !== 'brouillon') {
                return Response::deny('Seuls les devis en brouillon peuvent être envoyés.');
            }
        }

        // Admin : autorisé
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Commercial qui a créé le devis
        if ($user->isCommercial() && $devis->commercial_id === $user->id) {
            return Response::allow();
        }

        return Response::deny('Vous ne pouvez pas envoyer ce devis.');
    }

    /**
     * Peut accepter/refuser le devis (ACTION CLIENT)
     */
    public function respond(User $user, Devis $devis): Response
    {
        // Vérifier les permissions de vue d'abord
        if (!$this->view($user, $devis)) {
            return Response::deny('Vous n\'avez pas accès à ce devis.');
        }

        // Seul le client peut accepter/refuser
        if (!$user->isClient()) {
            return Response::deny('Seuls les clients peuvent accepter ou refuser un devis.');
        }

        // Vérifier le statut selon le type
        if ($devis->isProspect()) {
            if (!in_array($devis->statut_prospect, ['envoye', 'negocie'])) {
                return Response::deny('Ce prospect ne peut pas être modifié dans son état actuel.');
            }
        } else {
            if ($devis->statut !== 'envoye') {
                return Response::deny('Ce devis ne peut pas être modifié dans son état actuel.');
            }
        }

        // Vérifier l'expiration
        if ($devis->isExpire()) {
            return Response::deny('Ce devis a expiré et ne peut plus être accepté ou refusé.');
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
        // Admin : autorisé
        if ($user->isAdmin()) {
            return true;
        }

        // Commercial du devis
        return $user->isCommercial() && $devis->commercial_id === $user->id;
    }

    /**
     * Peut télécharger le PDF
     */
    public function downloadPdf(User $user, Devis $devis): bool
    {
        // Même autorisation que pour voir le devis
        return $this->view($user, $devis);
    }

    // ====================================================
    // GESTION DES DEVIS PROSPECTS
    // ====================================================

    /**
     * Peut convertir un devis prospect en chantier
     */
    public function convertToChantier(User $user, Devis $devis): Response
    {
        // Seuls admin et commercial peuvent convertir
        if (!($user->isAdmin() || $user->isCommercial())) {
            return Response::deny('Seuls les administrateurs et commerciaux peuvent convertir des prospects.');
        }

        // Le devis doit être un prospect
        if (!$devis->isProspect()) {
            return Response::deny('Seuls les devis prospects peuvent être convertis en chantier.');
        }

        // Le prospect doit être accepté et non déjà converti
        if (!$devis->peutEtreConverti()) {
            return Response::deny('Ce prospect ne peut pas être converti (statut invalide ou déjà converti).');
        }

        // Admin : toujours autorisé
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Commercial : seulement ses propres prospects
        if ($user->isCommercial() && $devis->commercial_id === $user->id) {
            return Response::allow();
        }

        return Response::deny('Vous ne pouvez convertir que vos propres prospects.');
    }

    /**
     * Peut voir l'historique de négociation
     */
    public function viewNegotiationHistory(User $user, Devis $devis): bool
    {
        // Doit être un prospect
        if (!$devis->isProspect()) {
            return false;
        }

        // Admin : toujours autorisé
        if ($user->isAdmin()) {
            return true;
        }

        // Commercial : ses prospects seulement
        if ($user->isCommercial() && $devis->commercial_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Peut ajouter une version de négociation
     */
    public function addNegotiationVersion(User $user, Devis $devis): Response
    {
        // Doit être un prospect
        if (!$devis->isProspect()) {
            return Response::deny('Cette action n\'est disponible que pour les prospects.');
        }

        // Le prospect doit être en cours de négociation
        if (!in_array($devis->statut_prospect, ['envoye', 'negocie'])) {
            return Response::deny('Le prospect doit être envoyé ou en négociation pour ajouter une version.');
        }

        // Admin : autorisé
        if ($user->isAdmin()) {
            return Response::allow();
        }

        // Commercial : ses prospects seulement
        if ($user->isCommercial() && $devis->commercial_id === $user->id) {
            return Response::allow();
        }

        return Response::deny('Vous ne pouvez modifier que vos propres prospects.');
    }
}