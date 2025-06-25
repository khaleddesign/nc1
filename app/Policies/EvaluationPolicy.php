<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Evaluation;

class EvaluationPolicy
{
    /**
     * Peut voir la liste des évaluations
     */
    public function viewAny(User $user): bool
    {
        return true; // Tous les utilisateurs peuvent voir leurs évaluations
    }

    /**
     * Peut voir une évaluation spécifique
     */
    public function view(User $user, Evaluation $evaluation): bool
    {
        // Admin : toujours autorisé
        if ($user->isAdmin()) {
            return true;
        }

        // L'évaluateur ou l'évalué peuvent voir
        return $evaluation->evaluateur_id === $user->id || $evaluation->evalue_id === $user->id;
    }

    /**
     * Peut créer une évaluation
     */
    public function create(User $user): bool
    {
        // Tous les utilisateurs authentifiés peuvent créer des évaluations
        return true;
    }

    /**
     * Peut modifier une évaluation
     */
    public function update(User $user, Evaluation $evaluation): bool
    {
        // Admin : autorisé
        if ($user->isAdmin()) {
            return true;
        }

        // Seul l'évaluateur peut modifier son évaluation
        return $evaluation->evaluateur_id === $user->id;
    }

    /**
     * Peut supprimer une évaluation
     */
    public function delete(User $user, Evaluation $evaluation): bool
    {
        // Admin : autorisé
        if ($user->isAdmin()) {
            return true;
        }

        // Seul l'évaluateur peut supprimer son évaluation
        return $evaluation->evaluateur_id === $user->id;
    }
}
