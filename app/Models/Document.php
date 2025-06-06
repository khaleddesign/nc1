<?php

// app/Models/Document.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'chantier_id', 'user_id', 'nom_original', 'nom_fichier',
        'chemin', 'type_mime', 'taille', 'description', 'type'
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
    public function getUrl()
    {
        return Storage::url($this->chemin);
    }

    public function getTailleFormatee()
    {
        $bytes = $this->taille;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function isImage()
    {
        return strpos($this->type_mime, 'image/') === 0;
    }

    public function getIconeType()
    {
        if ($this->isImage()) {
            return 'fas fa-image';
        } elseif ($this->type_mime === 'application/pdf') {
            return 'fas fa-file-pdf';
        } elseif (strpos($this->type_mime, 'word') !== false) {
            return 'fas fa-file-word';
        }
        return 'fas fa-file';
    }
}

