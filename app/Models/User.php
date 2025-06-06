<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Si tu utilises Sanctum, importe-le ; sinon tu peux le retirer
use Laravel\Sanctum\HasApiTokens;

// Les relations vers Chantier, Commentaire, Document, Notification
use App\Models\Chantier;
use App\Models\Etape;
use App\Models\Commentaire;
use App\Models\Document;
use App\Models\Notification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'telephone', 'adresse', 'active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'active'            => 'boolean',
    ];

    public function chantiersClient()
    {
        return $this->hasMany(Chantier::class, 'client_id');
    }

    public function chantiersCommercial()
    {
        return $this->hasMany(Chantier::class, 'commercial_id');
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCommercial()
    {
        return $this->role === 'commercial';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }

    public function getNotificationsNonLues()
    {
        return $this->notifications()->where('lu', false)->count();
    }
}
