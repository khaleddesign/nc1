<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
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
        'role', // Ajout du champ role
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_seen_at' => 'datetime',
        'notification_preferences' => 'array',
        'email_notifications_enabled' => 'boolean',
    ];

    /**
     * Relation avec les devis (depuis gestion 2)
     */
    public function devis(): HasMany
    {
        return $this->hasMany(Devis::class);
    }

    /**
     * Relation avec les factures (depuis gestion 2)
     */
    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class);
    }

    /**
     * Relation avec les paiements (depuis gestion 2)
     */
    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    /**
     * Relation avec les messages envoyés (depuis gestion)
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Relation avec les messages reçus (depuis gestion)
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    /**
     * Tous les messages (envoyés et reçus)
     */
    public function messages()
    {
        return Message::where('sender_id', $this->id)
            ->orWhere('recipient_id', $this->id);
    }

    /**
     * Messages non lus
     */
    public function unreadMessages()
    {
        return $this->receivedMessages()->where('is_read', false);
    }

    /**
     * Obtenir le nombre de messages non lus
     */
    public function getUnreadMessagesCount(): int
    {
        return $this->unreadMessages()->count();
    }

    /**
     * Relation avec les photos (depuis gestion)
     */
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    /**
     * Relation avec les chantiers en tant que client
     */
    public function chantiers(): HasMany
    {
        return $this->hasMany(Chantier::class, 'client_id');
    }

    /**
     * Relation avec les chantiers assignés (pour commercial)
     */
    public function chantiersAssignes(): HasMany
    {
        return $this->hasMany(Chantier::class, 'commercial_id');
    }

    /**
     * Relation avec les notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Relation avec les documents
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Relation avec les évaluations données
     */
    public function evaluationsDonnees(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'evaluateur_id');
    }

    /**
     * Relation avec les évaluations reçues
     */
    public function evaluationsRecues(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'evalue_id');
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->email === 'admin@example.com';
    }

    /**
     * Vérifier si l'utilisateur est client
     */
    public function isClient(): bool
    {
        return $this->role === 'client' || (!$this->role && !$this->isAdmin() && !$this->isArtisan() && !$this->isCommercial());
    }

    /**
     * Vérifier si l'utilisateur est artisan
     */
    public function isArtisan(): bool
    {
        return $this->role === 'artisan';
    }

    /**
     * Vérifier si l'utilisateur est commercial
     */
    public function isCommercial(): bool
    {
        return $this->role === 'commercial';
    }

    /**
     * Obtenir le nom complet ou le nom de l'entreprise
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->company_name ?: $this->name;
    }

    /**
     * Obtenir l'URL de l'avatar
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0D8ABC&color=fff';
    }

    /**
     * Obtenir le rôle affiché
     */
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

    /**
     * Statistiques pour le dashboard
     */
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

        // Stats spécifiques selon le rôle
        if ($this->isClient()) {
            $stats['total_chantiers'] = $this->chantiers()->count();
        } elseif ($this->isCommercial()) {
            $stats['total_chantiers'] = $this->chantiersAssignes()->count();
        } elseif ($this->isAdmin()) {
            $stats['total_chantiers'] = Chantier::count();
        }

        return $stats;
    }

    /**
     * Obtenir le nombre de notifications non lues
     */
    public function getNotificationsNonLues(): int
    {
        return $this->notifications()->where('lu', false)->count();
    }

    /**
     * Vérifier si l'utilisateur peut créer des chantiers
     */
    public function canCreateChantiers(): bool
    {
        return $this->isAdmin() || $this->isCommercial();
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Obtenir le nombre total de notifications
     */
    public function getTotalNotifications(): int
    {
        return $this->notifications()->count();
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllNotificationsAsRead(): void
    {
        $this->notifications()->where('lu', false)->update(['lu' => true]);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Définir le rôle par défaut lors de la création
        static::creating(function ($user) {
            if (!$user->role) {
                $user->role = 'client';
            }
        });
    }
}