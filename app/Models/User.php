<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'telephone', // Ajout pour compatibilité
        'address',
        'adresse', // Ajout pour compatibilité
        'city',
        'postal_code',
        'country',
        'company_name',
        'siret',
        'avatar',
        'last_seen_at',
        'email_verified_at',
        'notification_preferences',
        'email_notifications_enabled',
        'role',
        'active', // Ajout du champ active manquant
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_seen_at' => 'datetime',
        'notification_preferences' => 'array',
        'email_notifications_enabled' => 'boolean',
        'active' => 'boolean', // Cast pour le champ active
    ];

    // Relations pour les chantiers
    public function chantiers(): HasMany
    {
        return $this->hasMany(Chantier::class, 'client_id');
    }

    // CORRECTION: Les bonnes relations pour AdminController
    public function chantiersClient(): HasMany
    {
        return $this->hasMany(Chantier::class, 'client_id');
    }

    public function chantiersCommercial(): HasMany
    {
        return $this->hasMany(Chantier::class, 'commercial_id');
    }

    // Alias pour compatibilité
    public function chantiersAssignes(): HasMany
    {
        return $this->chantiersCommercial();
    }

    // Relations pour les devis, factures, etc.
    public function devis(): HasMany
    {
        return $this->hasMany(Devis::class, 'commercial_id');
    }

    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class, 'commercial_id');
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    // Relations pour les messages
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function messages()
    {
        return Message::where('sender_id', $this->id)
            ->orWhere('recipient_id', $this->id);
    }

    public function unreadMessages()
    {
        return $this->receivedMessages()->where('is_read', false);
    }

    public function getUnreadMessagesCount(): int
    {
        return $this->unreadMessages()->count();
    }

    // Autres relations
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function evaluationsDonnees(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'evaluateur_id');
    }

    public function evaluationsRecues(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'evalue_id');
    }

    // Méthodes de vérification des rôles
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->email === 'admin@example.com';
    }

    public function isClient(): bool
    {
        return $this->role === 'client' || (!$this->role && !$this->isAdmin() && !$this->isArtisan() && !$this->isCommercial());
    }

    public function isArtisan(): bool
    {
        return $this->role === 'artisan';
    }

    public function isCommercial(): bool
    {
        return $this->role === 'commercial';
    }

    public function canCreateChantiers(): bool
    {
        return $this->isAdmin() || $this->isCommercial();
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    // Accesseurs et méthodes utilitaires
    public function getDisplayNameAttribute(): string
    {
        return $this->company_name ?: $this->name;
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0D8ABC&color=fff';
    }

    public function getDisplayRoleAttribute(): string
    {
        $roles = [
            'admin' => 'Administrateur',
            'commercial' => 'Commercial',
            'artisan' => 'Artisan',
            'client' => 'Client'
        ];

        return $roles[$this->role] ?? 'Client';
    }

    // Méthodes pour les notifications
    public function getNotificationsNonLues(): int
    {
        return $this->notifications()->where('lu', false)->count();
    }

    public function getTotalNotifications(): int
    {
        return $this->notifications()->count();
    }

    public function markAllNotificationsAsRead(): void
    {
        $this->notifications()->where('lu', false)->update(['lu' => true]);
    }

    // Statistiques pour le dashboard
    public function getDashboardStats(): array
    {
        $stats = [
            'total_devis' => $this->devis()->count(),
            'devis_en_attente' => $this->devis()->where('statut', 'en_attente')->count(),
            'total_factures' => $this->factures()->count(),
            'factures_impayees' => $this->factures()->where('statut', 'impayee')->count(),
            'total_messages' => $this->sentMessages()->count() + $this->receivedMessages()->count(),
            'messages_non_lus' => $this->unreadMessages()->count(),
            'total_photos' => $this->photos()->count(),
        ];

        if ($this->isClient()) {
            $stats['total_chantiers'] = $this->chantiers()->count();
        } elseif ($this->isCommercial()) {
            $stats['total_chantiers'] = $this->chantiersCommercial()->count();
        } elseif ($this->isAdmin()) {
            $stats['total_chantiers'] = Chantier::count();
        }

        return $stats;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (!$user->role) {
                $user->role = 'client';
            }
            if (!isset($user->active)) {
                $user->active = true;
            }
        });
    }
}