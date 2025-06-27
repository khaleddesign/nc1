<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\NotificationCreated;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'chantier_id', 'devis_id', 'facture_id', 
        'type', 'titre', 'message', 'lu', 'lu_at'
    ];

    protected $casts = [
        'lu' => 'boolean',
        'lu_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::created(function ($notification) {
            event(new NotificationCreated($notification));
        });
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chantier()
    {
        return $this->belongsTo(Chantier::class);
    }

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    // Méthodes
    public function marquerLue()
    {
        $this->update([
            'lu' => true,
            'lu_at' => now()
        ]);
    }

    /**
     * Obtenir la route de redirection dynamique selon le type de notification
     */
    public function getRedirectRoute(): array
    {
        return match($this->type) {
            // Notifications liées aux devis
            'nouveau_devis', 'devis_envoye', 'devis_accepte', 'devis_refuse', 'devis_expire' => [
                'name' => 'chantiers.devis.show',
                'params' => [$this->chantier_id, $this->devis_id]
            ],
            
            // Notifications liées aux factures
            'nouvelle_facture', 'facture_envoyee', 'facture_payee', 'facture_en_retard', 'nouveau_paiement' => [
                'name' => 'chantiers.factures.show',
                'params' => [$this->chantier_id, $this->facture_id]
            ],
            
            // Notifications liées aux chantiers générales
            'nouveau_chantier', 'changement_statut', 'nouvelle_etape', 'etape_terminee', 
            'chantier_retard', 'nouveau_document', 'nouveau_commentaire_client', 'nouveau_commentaire_commercial' => [
                'name' => 'chantiers.show',
                'params' => [$this->chantier_id]
            ],
            
            // Notifications générales (sans chantier)
            'message_recu', 'rappel_systeme' => [
                'name' => 'dashboard',
                'params' => []
            ],
            
            // Fallback par défaut
            default => [
                'name' => $this->chantier_id ? 'chantiers.show' : 'dashboard',
                'params' => $this->chantier_id ? [$this->chantier_id] : []
            ]
        };
    }

    /**
     * Créer une notification avec références dynamiques
     */
    public static function creerNotification($userId, $type, $titre, $message, $references = [])
    {
        return static::create([
            'user_id' => $userId,
            'chantier_id' => $references['chantier_id'] ?? null,
            'devis_id' => $references['devis_id'] ?? null,
            'facture_id' => $references['facture_id'] ?? null,
            'type' => $type,
            'titre' => $titre,
            'message' => $message
        ]);
    }

    /**
     * Raccourci pour notifications liées aux devis
     */
    public static function creerNotificationDevis($userId, $devis, $type, $titre, $message)
    {
        return static::creerNotification($userId, $type, $titre, $message, [
            'chantier_id' => $devis->chantier_id,
            'devis_id' => $devis->id
        ]);
    }

    /**
     * Raccourci pour notifications liées aux factures
     */
    public static function creerNotificationFacture($userId, $facture, $type, $titre, $message)
    {
        return static::creerNotification($userId, $type, $titre, $message, [
            'chantier_id' => $facture->chantier_id,
            'facture_id' => $facture->id
        ]);
    }

    /**
     * Raccourci pour notifications liées aux chantiers uniquement
     */
    public static function creerNotificationChantier($userId, $chantier, $type, $titre, $message)
    {
        return static::creerNotification($userId, $type, $titre, $message, [
            'chantier_id' => $chantier->id
        ]);
    }
}