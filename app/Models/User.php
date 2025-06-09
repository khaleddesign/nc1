<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'password' => 'hashed',
    ];

    protected $attributes = [
        'role' => 'client',
        'active' => true,
    ];

    // Relations
    public function chantiersClient()
    {
        return $this->hasMany(\App\Models\Chantier::class, 'client_id');
    }

    public function chantiersCommercial()
    {
        return $this->hasMany(\App\Models\Chantier::class, 'commercial_id');
    }

    public function commentaires()
    {
        return $this->hasMany(\App\Models\Commentaire::class);
    }

    public function documents()
    {
        return $this->hasMany(\App\Models\Document::class);
    }

    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    // Méthodes d'assistance
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

    // AJOUT DE LA MÉTHODE MANQUANTE
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function getNotificationsNonLues()
    {
        return $this->notifications()->where('lu', false)->count();
    }

    public function getFullNameWithRoleAttribute()
    {
        return $this->name . ' (' . ucfirst($this->role) . ')';
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
