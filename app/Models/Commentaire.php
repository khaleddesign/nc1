<?php
// app/Models/Commentaire.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'chantier_id', 'user_id', 'contenu', 'lu'
    ];

    protected $casts = [
        'lu' => 'boolean',
    ];

    // Relations
    public function chantier()
    {
        return $this->belongsTo(Chantier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Méthodes
    public function marquerLu()
    {
        $this->update(['lu' => true]);
    }
}
