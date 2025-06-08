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
use Illuminate\Validation\Rules\File;

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
            'fichiers.*' => [
                'required',
                'file',
                'max:10240', // 10MB
                File::types(['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'])
                    ->max(10 * 1024), // 10MB en KB
            ],
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:image,document,plan,facture,autre',
        ], [
            'fichiers.*.max' => 'Chaque fichier ne doit pas dépasser 10 MB.',
            'fichiers.*.mimes' => 'Format de fichier non autorisé.',
        ]);

        $documentsUploades = [];
        $errors = [];

        foreach ($request->file('fichiers') as $index => $fichier) {
            try {
                // Vérification supplémentaire du MIME type réel
                $mimeType = $fichier->getMimeType();
                $allowedMimes = [
                    'image/jpeg', 'image/png', 'image/gif',
                    'application/pdf',
                    'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ];

                if (!in_array($mimeType, $allowedMimes)) {
                    $errors[] = "Le fichier {$fichier->getClientOriginalName()} a un type MIME non autorisé: {$mimeType}";
                    continue;
                }

                // Génération d'un nom de fichier sécurisé
                $nomOriginal = $fichier->getClientOriginalName();
                $extension = $fichier->getClientOriginalExtension();
                $nomFichier = Str::uuid() . '.' . $extension;
                
                // Stockage sécurisé
                $chemin = $fichier->storeAs(
                    'documents/' . $chantier->id, 
                    $nomFichier, 
                    'public'
                );
                
                if (!$chemin) {
                    $errors[] = "Erreur lors de l'upload de {$nomOriginal}";
                    continue;
                }
                
                $document = Document::create([
                    'chantier_id' => $chantier->id,
                    'user_id' => Auth::id(),
                    'nom_original' => $nomOriginal,
                    'nom_fichier' => $nomFichier,
                    'chemin' => $chemin,
                    'type_mime' => $mimeType,
                    'taille' => $fichier->getSize(),
                    'description' => $request->description,
                    'type' => $request->type,
                ]);
                
                $documentsUploades[] = $document;
                
            } catch (\Exception $e) {
                $errors[] = "Erreur lors du traitement de {$fichier->getClientOriginalName()}: " . $e->getMessage();
            }
        }

        // Notification au client si des documents ont été uploadés
        if (count($documentsUploades) > 0 && Auth::user()->isCommercial()) {
            Notification::creerNotification(
                $chantier->client_id,
                $chantier->id,
                'nouveau_document',
                'Nouveaux documents ajoutés',
                count($documentsUploades) . " nouveau(x) document(s) ont été ajoutés au chantier '{$chantier->titre}'"
            );
        }

        $message = count($documentsUploades) . ' document(s) uploadé(s) avec succès.';
        if (!empty($errors)) {
            $message .= ' Erreurs: ' . implode(', ', $errors);
        }

        return redirect()->route('chantiers.show', $chantier)
                        ->with(count($documentsUploades) > 0 ? 'success' : 'warning', $message);
    }

    public function download(Document $document)
    {
        $this->authorize('view', $document->chantier);
        
        if (!Storage::disk('public')->exists($document->chemin)) {
            abort(404, 'Fichier non trouvé');
        }
        
        return Storage::disk('public')->download($document->chemin, $document->nom_original);
    }

    public function destroy(Document $document)
    {
        $this->authorize('update', $document->chantier);
        
        // Suppression du fichier physique
        if (Storage::disk('public')->exists($document->chemin)) {
            Storage::disk('public')->delete($document->chemin);
        }
        
        $document->delete();
        
        return redirect()->route('chantiers.show', $document->chantier)
                        ->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Méthode pour nettoyer les fichiers orphelins
     */
    public function cleanupOrphanedFiles()
    {
        $this->authorize('admin-only');
        
        $directories = Storage::disk('public')->directories('documents');
        $deletedFiles = 0;
        
        foreach ($directories as $dir) {
            $chantierId = basename($dir);
            
            // Vérifier si le chantier existe encore
            if (!Chantier::find($chantierId)) {
                Storage::disk('public')->deleteDirectory($dir);
                $deletedFiles++;
                continue;
            }
            
            // Vérifier les fichiers dans le dossier
            $files = Storage::disk('public')->files($dir);
            foreach ($files as $file) {
                $filename = basename($file);
                
                // Vérifier si le document existe en base
                if (!Document::where('nom_fichier', $filename)->exists()) {
                    Storage::disk('public')->delete($file);
                    $deletedFiles++;
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'deleted_files' => $deletedFiles,
            'message' => "{$deletedFiles} fichiers orphelins supprimés."
        ]);
    }
}