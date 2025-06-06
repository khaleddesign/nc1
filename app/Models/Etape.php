<?php
// app/Models/Etape.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etape extends Model
{
    use HasFactory;

    protected $fillable = [
        'chantier_id', 'nom', 'description', 'ordre', 'pourcentage',
        'date_debut', 'date_fin_prevue', 'date_fin_effective', 'notes', 'terminee'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin_prevue' => 'date',
        'date_fin_effective' => 'date',
        'pourcentage' => 'decimal:2',
        'terminee' => 'boolean',
    ];

    // Relations
    public function chantier()
    {
        return $this->belongsTo(Chantier::class);
    }

    // Méthodes
    public function marquerTerminee()
    {
        $this->update([
            'terminee' => true,
            'pourcentage' => 100,
            'date_fin_effective' => now()
        ]);
        
        // Recalculer l'avancement du chantier
        $this->chantier->calculerAvancement();
    }

    public function isEnRetard()
    {
        return $this->date_fin_prevue && 
               $this->date_fin_prevue->isPast() && 
               !$this->terminee;
    }
}