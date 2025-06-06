<?php

// app/Http/Controllers/DocumentController.php
namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Document;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Chantier $chantier)
    {
        $this->authorize('update', $chantier);
        
        $request->validate([
            'fichiers' => 'required|array|max:10',
            'fichiers.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx',
            'description' => 'nullable|string',
            'type' => 'required|in:image,document,plan,facture,autre',
        ]);

        $documentsUploades = [];

        foreach ($request->file('fichiers') as $fichier) {
            $nomOriginal = $fichier->getClientOriginalName();
            $extension = $fichier->getClientOriginalExtension();
            $nomFichier = Str::uuid() . '.' . $extension;
            
            $chemin = $fichier->storeAs('documents/' . $chantier->id, $nomFichier, 'public');
            
            $document = Document::create([
                'chantier_id' => $chantier->id,
                'user_id' => Auth::id(),
                'nom_original' => $nomOriginal,
                'nom_fichier' => $nomFichier,
                'chemin' => $chemin,
                'type_mime' => $fichier->getMimeType(),
                'taille' => $fichier->getSize(),
                'description' => $request->description,
                'type' => $request->type,
            ]);
            
            $documentsUploades[] = $document;
        }

        // Notification au client
        if (Auth::user()->isCommercial()) {
            Notification::creerNotification(
                $chantier->client_id,
                $chantier->id,
                'nouveau_document',
                'Nouveaux documents ajoutés',
                count($documentsUploades) . " nouveau(x) document(s) ont été ajoutés au chantier '{$chantier->titre}'"
            );
        }

        return redirect()->route('chantiers.show', $chantier)
                        ->with('success', count($documentsUploades) . ' document(s) uploadé(s) avec succès.');
    }

    public function download(Document $document)
    {
        $this->authorize('view', $document->chantier);
        
        return Storage::disk('public')->download($document->chemin, $document->nom_original);
    }

    public function destroy(Document $document)
    {
        $this->authorize('update', $document->chantier);
        
        Storage::disk('public')->delete($document->chemin);
        $document->delete();
        
        return redirect()->route('chantiers.show', $document->chantier)
                        ->with('success', 'Document supprimé avec succès.');
    }
}
