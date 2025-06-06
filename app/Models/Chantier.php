<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ** On n’importe qu’une seule fois chaque classe externe **
use App\Models\User;
use App\Models\Etape;
use App\Models\Document;
use App\Models\Commentaire;
use App\Models\Notification;

class Chantier extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'client_id',
        'commercial_id',
        'statut',
        'date_debut',
        'date_fin_prevue',
        'date_fin_effective',
        'budget',
        'notes',
        'avancement_global',
        'active', // si ce champ existe bien dans votre migration
    ];

    protected $casts = [
        'date_debut'         => 'date',
        'date_fin_prevue'    => 'date',
        'date_fin_effective' => 'date',
        'budget'             => 'decimal:2',
        'avancement_global'  => 'decimal:2',
        'active'             => 'boolean',
    ];

    // Relations
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function commercial()
    {
        return $this->belongsTo(User::class, 'commercial_id');
    }

    public function etapes()
    {
        return $this->hasMany(Etape::class)->orderBy('ordre');
    }

    public function documents()
    {
        return $this->hasMany(Document::class)->orderBy('created_at', 'desc');
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class)->orderBy('created_at', 'desc');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Méthodes métier
    public function calculerAvancement()
    {
        $etapes = $this->etapes;
        if ($etapes->count() === 0) {
            return 0;
        }

        $total   = $etapes->sum('pourcentage');
        $moyenne = $total / $etapes->count();

        $this->update(['avancement_global' => $moyenne]);
        return $moyenne;
    }

    public function getStatutBadgeClass()
    {
        return match ($this->statut) {
            'planifie' => 'badge-secondary',
            'en_cours' => 'badge-primary',
            'termine'  => 'badge-success',
            default    => 'badge-secondary',
        };
    }

    public function getStatutTexte()
    {
        return match ($this->statut) {
            'planifie' => 'Planifié',
            'en_cours' => 'En cours',
            'termine'  => 'Terminé',
            default    => 'Inconnu',
        };
    }

    public function isEnRetard()
    {
        return $this->date_fin_prevue
            && $this->date_fin_prevue->isPast()
            && $this->statut !== 'termine';
    }
}
