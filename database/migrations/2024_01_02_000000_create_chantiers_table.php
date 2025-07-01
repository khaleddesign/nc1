<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Notification;
use App\Models\User;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DevisController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->middleware('auth');
        $this->pdfService = $pdfService;
    }

    // ====================================================
    // GESTION DEVIS PAR CHANTIER
    // ====================================================

    /**
     * Liste des devis d'un chantier
     */
    public function index(Chantier $chantier)
    {
        $this->authorize('view', $chantier);

        $devis = $chantier->devis()
            ->with(['commercial', 'lignes'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('devis.index', compact('chantier', 'devis'));
    }

    /**
     * Formulaire de création d'un devis pour un chantier
     */
    public function create(Chantier $chantier)
    {
        $this->authorize('update', $chantier);
        $this->checkDevisCreationPermission();

        $devis = $this->createBaseDevis($chantier);

        return view('devis.create', compact('chantier', 'devis'));
    }

    /**
     * Sauvegarder un nouveau devis pour un chantier
     */
    public function store(Request $request, Chantier $chantier)
    {
        $this->authorize('update', $chantier);

        $validated = $this->validateDevisData($request);

        DB::beginTransaction();
        
        try {
            $devis = $this->createDevisWithLines($chantier, $validated);
            
            DB::commit();

            return redirect()
                ->route('chantiers.devis.show', [$chantier, $devis])
                ->with('success', 'Devis créé avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du devis : ' . $e->getMessage());
        }
    }

    /**
     * Affichage d'un devis dans le contexte d'un chantier
     */
    public function show(Chantier $chantier, Devis $devis)
    {
        $this->authorize('view', $chantier);
        $this->ensureDevisBelongsToChantier($devis, $chantier);

        $devis->load(['commercial', 'lignes', 'facture']);

        return view('devis.show', compact('chantier', 'devis'));
    }

    /**
     * Formulaire d'édition d'un devis
     */
    public function edit(Chantier $chantier, Devis $devis)
    {
        $this->authorize('update', $chantier);
        $this->ensureDevisBelongsToChantier($devis, $chantier);

        if (!$devis->peutEtreModifie()) {
            return back()->with('error', 'Ce devis ne peut plus être modifié.');
        }

        $devis->load('lignes');

        return view('devis.edit', compact('chantier', 'devis'));
    }

    /**
     * Mettre à jour un devis
     */
    public function update(Request $request, Chantier $chantier, Devis $devis)
    {
        $this->authorize('update', $chantier);
        $this->ensureDevisBelongsToChantier($devis, $chantier);

        if (!$devis->peutEtreModifie()) {
            return back()->with('error', 'Ce devis ne peut plus être modifié.');
        }

        $validated = $this->validateDevisData($request);

        DB::beginTransaction();
        
        try {
            $this->updateDevisWithLines($devis, $validated);
            
            DB::commit();

            return redirect()
                ->route('chantiers.devis.show', [$chantier, $devis])
                ->with('success', 'Devis modifié avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un devis
     */
    public function destroy(Chantier $chantier, Devis $devis)
    {
        $this->authorize('update', $chantier);
        $this->ensureDevisBelongsToChantier($devis, $chantier);

        if ($devis->statut === 'accepte' || $devis->facture_id) {
            return back()->with('error', 'Ce devis ne peut pas être supprimé car il a été accepté ou converti en facture.');
        }

        $numero = $devis->numero;
        $devis->delete();

        return redirect()
            ->route('chantiers.devis.index', $chantier)
            ->with('success', "Devis {$numero} supprimé avec succès.");
    }

    // ====================================================
    // GESTION GLOBALE DES DEVIS
    // ====================================================

    /**
     * Vue globale de tous les devis
     */
    public function globalIndex(Request $request)
    {
        $this->checkDevisAccessPermission();

        $query = $this->buildDevisQuery($request);
        $devis = $query->paginate(20)->withQueryString();
        $stats = $this->getDevisStats();
        $commerciaux = $this->getCommerciauxForFilter();

        return view('devis.global-index', compact('devis', 'stats', 'commerciaux'));
    }

    /**
     * Formulaire de création d'un devis global
     */
    public function globalCreate()
    {
        $this->checkDevisCreationPermission();

        $chantiers = $this->getChantiersForCommercial();
        $devis = $this->createBaseDevis();

        return view('devis.global-create', compact('devis', 'chantiers'));
    }

    /**
     * Sauvegarder un devis global
     */
    public function globalStore(Request $request)
    {
        $this->checkDevisCreationPermission();

        $validated = $this->validateGlobalDevisData($request);

        DB::beginTransaction();
        
        try {
            $devis = $this->createGlobalDevisWithLines($validated, $request);
            
            DB::commit();

            $message = $request->input('action') === 'save_and_send' 
                ? 'Devis créé et envoyé avec succès.' 
                : 'Devis créé avec succès.';

            return redirect()
                ->route('devis.show', $devis)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du devis : ' . $e->getMessage());
        }
    }

    /**
     * Affichage d'un devis depuis la vue globale
     */
    public function globalShow(Devis $devis)
    {
        $this->checkDevisViewPermission($devis);
        
        $devis->load(['chantier.client', 'commercial', 'lignes', 'facture']);

        return view('devis.global-show', compact('devis'));
    }

    // ====================================================
    // ACTIONS SUR LES DEVIS
    // ====================================================

    /**
     * Envoyer le devis au client
     */
    public function envoyer(Chantier $chantier, Devis $devis)
    {
        $this->authorize('update', $chantier);
        $this->ensureDevisBelongsToChantier($devis, $chantier);

        if ($devis->statut !== 'brouillon') {
            return back()->with('error', 'Seuls les devis en brouillon peuvent être envoyés.');
        }

        try {
            $devis->marquerEnvoye();

            Notification::creerNotificationDevis(
                $chantier->client_id,
                $devis,
                'nouveau_devis',
                'Nouveau devis reçu',
                "Un nouveau devis '{$devis->titre}' vous a été envoyé pour le chantier '{$chantier->titre}'."
            );

            return back()->with('success', 'Devis envoyé au client avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi : ' . $e->getMessage());
        }
    }

    /**
     * Accepter un devis (côté client)
     */
    public function accepter(Request $request, Chantier $chantier, Devis $devis)
    {
        $this->checkClientPermission($chantier);
        $this->ensureDevisBelongsToChantier($devis, $chantier);

        if (!$devis->peutEtreAccepte()) {
            return back()->with('error', 'Ce devis ne peut plus être accepté (expiré ou déjà traité).');
        }

        $request->validate([
            'signature' => 'nullable|string',
            'commentaire_client' => 'nullable|string|max:1000',
        ]);

        try {
            $devis->accepter();

            if ($request->filled('signature')) {
                $devis->signerElectroniquement($request->signature, $request->ip());
            }

            Notification::creerNotificationDevis(
                $devis->commercial_id,
                $devis,
                'devis_accepte',
                'Devis accepté',
                "Le client {$chantier->client->name} a accepté le devis '{$devis->titre}'."
            );

            return back()->with('success', 'Devis accepté avec succès. Merci pour votre confiance !');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'acceptation : ' . $e->getMessage());
        }
    }

    /**
     * Refuser un devis (côté client)
     */
    public function refuser(Request $request, Chantier $chantier, Devis $devis)
    {
        $this->checkClientPermission($chantier);
        $this->ensureDevisBelongsToChantier($devis, $chantier);

        if (!$devis->peutEtreAccepte()) {
            return back()->with('error', 'Ce devis ne peut plus être refusé.');
        }

        $request->validate([
            'raison_refus' => 'nullable|string|max:1000',
        ]);

        try {
            $devis->refuser();

            $message = "Le client {$chantier->client->name} a refusé le devis '{$devis->titre}'.";
            if ($request->raison_refus) {
                $message .= " Raison : {$request->raison_refus}";
            }

            Notification::creerNotificationDevis(
                $devis->commercial_id,
                $devis,
                'devis_refuse',
                'Devis refusé',
                $message
            );

            return back()->with('success', 'Devis refusé. Votre retour a été transmis.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du refus : ' . $e->getMessage());
        }
    }

    /**
     * Convertir un devis en facture
     */
    public function convertirEnFacture(Chantier $chantier, Devis $devis)
    {
        $this->authorize('update', $chantier);
        $this->ensureDevisBelongsToChantier($devis, $chantier);

        if (!$devis->peutEtreConverti()) {
            return back()->with('error', 'Ce devis ne peut pas être converti en facture.');
        }

        DB::beginTransaction();

        try {
            $facture = $this->createFactureFromDevis($chantier, $devis);
            
            $devis->update([
                'facture_id' => $facture->id,
                'converted_at' => now(),
            ]);

            Notification::creerNotificationFacture(
                $chantier->client_id,
                $facture,
                'nouvelle_facture',
                'Nouvelle facture générée',
                "Une facture '{$facture->numero}' a été générée à partir du devis '{$devis->numero}'."
            );

            DB::commit();

            return redirect()
                ->route('chantiers.factures.show', [$chantier, $facture])
                ->with('success', "Devis converti en facture {$facture->numero} avec succès.");

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la conversion : ' . $e->getMessage());
        }
    }

    /**
     * Dupliquer un devis
     */
    public function dupliquer(Chantier $chantier, Devis $devis)
    {
        $this->authorize('update', $chantier);
        $this->ensureDevisBelongsToChantier($devis, $chantier);

        DB::beginTransaction();

        try {
            $nouveauDevis = $this->duplicateDevis($devis);
            
            DB::commit();

            return redirect()
                ->route('chantiers.devis.edit', [$chantier, $nouveauDevis])
                ->with('success', "Devis dupliqué avec succès ({$nouveauDevis->numero}).");

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la duplication : ' . $e->getMessage());
        }
    }

    // ====================================================
    // GÉNÉRATION PDF
    // ====================================================

    /**
     * Télécharger le PDF du devis
     */
    public function downloadPdf(Chantier $chantier, Devis $devis)
    {
        $this->authorize('view', $chantier);
        $this->ensureDevisBelongsToChantier($devis, $chantier);

        return $this->generatePdfResponse($devis, 'attachment');
    }

    /**
     * Prévisualiser le PDF du devis
     */
    public function previewPdf(Chantier $chantier, Devis $devis)
    {
        $this->authorize('view', $chantier);
        $this->ensureDevisBelongsToChantier($devis, $chantier);

        return $this->generatePdfResponse($devis, 'inline');
    }

    // ====================================================
    // MÉTHODES PRIVÉES
    // ====================================================

    /**
     * Vérifier les permissions de création de devis
     */
    private function checkDevisCreationPermission()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isCommercial()) {
            abort(403, 'Seuls les commerciaux et admins peuvent créer des devis.');
        }
    }

    /**
     * Vérifier les permissions d'accès aux devis
     */
    private function checkDevisAccessPermission()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isCommercial()) {
            abort(403, 'Accès non autorisé.');
        }
    }

    /**
     * Vérifier les permissions pour voir un devis spécifique
     */
    private function checkDevisViewPermission(Devis $devis)
    {
        if (!Auth::user()->isAdmin() && 
            (!Auth::user()->isCommercial() || $devis->commercial_id !== Auth::id()) &&
            (!Auth::user()->isClient() || $devis->chantier->client_id !== Auth::id())) {
            abort(403, 'Accès non autorisé.');
        }
    }

    /**
     * Vérifier les permissions client
     */
    private function checkClientPermission(Chantier $chantier)
    {
        if (!Auth::user()->isAdmin() && Auth::id() !== $chantier->client_id) {
            abort(403, 'Seul le client peut effectuer cette action.');
        }
    }

    /**
     * S'assurer que le devis appartient au chantier
     */
    private function ensureDevisBelongsToChantier(Devis $devis, Chantier $chantier)
    {
        if ($devis->chantier_id !== $chantier->id) {
            abort(404);
        }
    }

    /**
     * Créer un devis de base
     */
    private function createBaseDevis(Chantier $chantier = null)
    {
        $baseData = [
            'commercial_id' => Auth::user()->isCommercial() ? Auth::id() : ($chantier ? $chantier->commercial_id : Auth::id()),
            'taux_tva' => 20.00,
            'delai_realisation' => 30,
            'modalites_paiement' => 'Paiement à 30 jours fin de mois',
            'conditions_generales' => config('chantiers.devis.conditions_generales_defaut', ''),
        ];

        if ($chantier) {
            $baseData += [
                'chantier_id' => $chantier->id,
                'titre' => "Devis pour {$chantier->titre}",
                'client_info' => [
                    'nom' => $chantier->client->name,
                    'email' => $chantier->client->email,
                    'telephone' => $chantier->client->telephone,
                    'adresse' => $chantier->client->adresse,
                ],
            ];
        }

        return new Devis($baseData);
    }

    /**
     * Valider les données d'un devis
     */
    private function validateDevisData(Request $request)
    {
        return $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_validite' => 'required|date|after:today',
            'taux_tva' => 'required|numeric|min:0|max:100',
            'delai_realisation' => 'nullable|integer|min:1',
            'modalites_paiement' => 'nullable|string',
            'conditions_generales' => 'nullable|string',
            'notes_internes' => 'nullable|string',
            
            'lignes' => 'required|array|min:1',
            'lignes.*.designation' => 'required|string|max:255',
            'lignes.*.description' => 'nullable|string',
            'lignes.*.unite' => 'required|string|max:50',
            'lignes.*.quantite' => 'required|numeric|min:0.01',
            'lignes.*.prix_unitaire_ht' => 'required|numeric|min:0',
            'lignes.*.taux_tva' => 'nullable|numeric|min:0|max:100',
            'lignes.*.remise_pourcentage' => 'nullable|numeric|min:0|max:100',
            'lignes.*.categorie' => 'nullable|string|max:100',
        ]);
    }

    /**
     * Valider les données d'un devis global
     */
    private function validateGlobalDevisData(Request $request)
    {
        return $request->validate([
            'type_devis' => 'required|in:nouveau_prospect,chantier_existant',
            'chantier_id' => 'nullable|exists:chantiers,id',
            
            'client_nom' => 'required_if:type_devis,nouveau_prospect|string|max:255',
            'client_email' => 'required_if:type_devis,nouveau_prospect|email',
            'client_telephone' => 'nullable|string|max:20',
            'client_adresse' => 'nullable|string',
            
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_validite' => 'required|date|after:today',
            'taux_tva' => 'required|numeric|min:0|max:100',
            'delai_realisation' => 'nullable|integer|min:1',
            'modalites_paiement' => 'nullable|string',
            'conditions_generales' => 'nullable|string',
            'notes_internes' => 'nullable|string',
            
            'lignes' => 'required|array|min:1',
            'lignes.*.designation' => 'required|string|max:255',
            'lignes.*.description' => 'nullable|string',
            'lignes.*.unite' => 'required|string|max:50',
            'lignes.*.quantite' => 'required|numeric|min:0.01',
            'lignes.*.prix_unitaire_ht' => 'required|numeric|min:0',
            'lignes.*.taux_tva' => 'nullable|numeric|min:0|max:100',
            'lignes.*.remise_pourcentage' => 'nullable|numeric|min:0|max:100',
            'lignes.*.categorie' => 'nullable|string|max:100',
        ]);
    }

    /**
     * Créer un devis avec ses lignes
     */
    private function createDevisWithLines(Chantier $chantier, array $validated)
    {
        $devis = $chantier->devis()->create([
            'commercial_id' => Auth::user()->isCommercial() ? Auth::id() : $chantier->commercial_id,
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'date_validite' => $validated['date_validite'],
            'taux_tva' => $validated['taux_tva'],
            'delai_realisation' => $validated['delai_realisation'],
            'modalites_paiement' => $validated['modalites_paiement'],
            'conditions_generales' => $validated['conditions_generales'],
            'notes_internes' => $validated['notes_internes'],
            'client_info' => [
                'nom' => $chantier->client->name,
                'email' => $chantier->client->email,
                'telephone' => $chantier->client->telephone,
                'adresse' => $chantier->client->adresse,
            ],
        ]);

        $this->createLignesForDevis($devis, $validated['lignes'], $validated['taux_tva']);
        $devis->calculerMontants();

        return $devis;
    }

    /**
     * Mettre à jour un devis avec ses lignes
     */
    private function updateDevisWithLines(Devis $devis, array $validated)
    {
        $devis->update([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'date_validite' => $validated['date_validite'],
            'taux_tva' => $validated['taux_tva'],
            'delai_realisation' => $validated['delai_realisation'],
            'modalites_paiement' => $validated['modalites_paiement'],
            'conditions_generales' => $validated['conditions_generales'],
            'notes_internes' => $validated['notes_internes'],
        ]);

        $devis->lignes()->delete();
        $this->createLignesForDevis($devis, $validated['lignes'], $validated['taux_tva']);
        $devis->calculerMontants();
    }

    /**
     * Créer un devis global avec ses lignes - VERSION CORRIGÉE
     */
    private function createGlobalDevisWithLines(array $validated, Request $request)
    {
        if ($validated['type_devis'] === 'chantier_existant') {
            $chantier = Chantier::with('client')->findOrFail($validated['chantier_id']);
            $clientInfo = [
                'nom' => $chantier->client->name,
                'email' => $chantier->client->email,
                'telephone' => $chantier->client->telephone,
                'adresse' => $chantier->client->adresse,
            ];
            $chantier_id = $chantier->id;
        } else {
            // 🔥 SOLUTION TEMPORAIRE : Créer un chantier "prospect"
            $clientInfo = [
                'nom' => $validated['client_nom'],
                'email' => $validated['client_email'],
                'telephone' => $validated['client_telephone'] ?? null,
                'adresse' => $validated['client_adresse'] ?? null,
            ];
            
            // Créer ou récupérer un client prospect
            $client = User::firstOrCreate(
                ['email' => $validated['client_email']],
                [
                    'name' => $validated['client_nom'],
                    'role' => 'client',
                    'telephone' => $validated['client_telephone'],
                    'adresse' => $validated['client_adresse'],
                    'password' => bcrypt('temp_password_' . time()),
                    'email_verified_at' => now(),
                ]
            );

            // Créer un chantier temporaire pour le prospect
            $chantier = Chantier::create([
                'client_id' => $client->id,
                'commercial_id' => Auth::id(),
                'titre' => 'Prospect - ' . $validated['titre'],
                'description' => 'Chantier créé automatiquement pour devis prospect',
                'statut' => 'planifie', // 🎯 Utiliser une valeur autorisée par l'ENUM
                'date_debut' => now()->addDays(30), // 🎯 Corriger le nom du champ aussi
            ]);
            
            $chantier_id = $chantier->id;
        }

        $devis = Devis::create([
            'chantier_id' => $chantier_id, // 🎯 Maintenant toujours défini
            'commercial_id' => Auth::id(),
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'date_validite' => $validated['date_validite'],
            'taux_tva' => $validated['taux_tva'],
            'delai_realisation' => $validated['delai_realisation'],
            'modalites_paiement' => $validated['modalites_paiement'],
            'conditions_generales' => $validated['conditions_generales'],
            'notes_internes' => $validated['notes_internes'],
            'client_info' => $clientInfo,
        ]);

        $this->createLignesForDevis($devis, $validated['lignes'], $validated['taux_tva']);
        $devis->calculerMontants();

        if ($request->input('action') === 'save_and_send') {
            $devis->marquerEnvoye();
            
            Notification::creerNotificationDevis(
                $chantier->client_id,
                $devis,
                'nouveau_devis',
                'Nouveau devis reçu',
                "Un nouveau devis '{$devis->titre}' vous a été envoyé."
            );
        }

        return $devis;
    }

    /**
     * Créer les lignes d'un devis
     */
    private function createLignesForDevis(Devis $devis, array $lignesData, float $tauxTvaDefaut)
    {
        foreach ($lignesData as $index => $ligneData) {
            $quantite = floatval($ligneData['quantite']);
            $prixUnitaire = floatval($ligneData['prix_unitaire_ht']);
            $tauxTva = floatval($ligneData['taux_tva'] ?? $tauxTvaDefaut);
            $remisePourcentage = floatval($ligneData['remise_pourcentage'] ?? 0);
            
            $montantBrut = $quantite * $prixUnitaire;
            $remiseMontant = $montantBrut * ($remisePourcentage / 100);
            $montantHt = $montantBrut - $remiseMontant;
            $montantTva = $montantHt * ($tauxTva / 100);
            $montantTtc = $montantHt + $montantTva;
            
            $devis->lignes()->create([
                'ordre' => $index + 1,
                'designation' => $ligneData['designation'],
                'description' => $ligneData['description'] ?? null,
                'unite' => $ligneData['unite'],
                'quantite' => $quantite,
                'prix_unitaire_ht' => $prixUnitaire,
                'taux_tva' => $tauxTva,
                'remise_pourcentage' => $remisePourcentage,
                'remise_montant' => $remiseMontant,
                'categorie' => $ligneData['categorie'] ?? null,
                'montant_ht' => $montantHt,
                'montant_tva' => $montantTva,
                'montant_ttc' => $montantTtc,
            ]);
        }
    }

    /**
     * Construire la requête pour les devis globaux
     */
    private function buildDevisQuery(Request $request)
    {
        $query = Devis::with(['chantier.client', 'commercial']);

        if (Auth::user()->isCommercial()) {
            $query->where('commercial_id', Auth::id());
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('commercial_id') && Auth::user()->isAdmin()) {
            $query->where('commercial_id', $request->commercial_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('numero', 'like', "%{$search}%")
                  ->orWhereHas('chantier', function($sq) use ($search) {
                      $sq->where('titre', 'like', "%{$search}%");
                  })
                  ->orWhereHas('chantier.client', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('created_at', [$request->date_debut, $request->date_fin]);
        }

        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        return $query;
    }

    /**
     * Obtenir les statistiques des devis
     */
    private function getDevisStats()
    {
        $baseQuery = Auth::user()->isAdmin() ? 
            Devis::query() : 
            Devis::where('commercial_id', Auth::id());

        return [
            'total' => $baseQuery->count(),
            'brouillon' => (clone $baseQuery)->where('statut', 'brouillon')->count(),
            'envoye' => (clone $baseQuery)->where('statut', 'envoye')->count(),
            'accepte' => (clone $baseQuery)->where('statut', 'accepte')->count(),
            'refuse' => (clone $baseQuery)->where('statut', 'refuse')->count(),
        ];
    }

    /**
     * Obtenir la liste des commerciaux pour le filtre
     */
    private function getCommerciauxForFilter()
    {
        return Auth::user()->isAdmin() ? 
            User::where('role', 'commercial')->get() : 
            collect();
    }

    /**
     * Obtenir les chantiers pour un commercial
     */
    private function getChantiersForCommercial()
    {
        return Auth::user()->isAdmin() ? 
            Chantier::with('client')->get() : 
            Auth::user()->chantiersCommercial()->with('client')->get();
    }

    /**
     * Créer une facture à partir d'un devis
     */
    private function createFactureFromDevis(Chantier $chantier, Devis $devis)
    {
        $facture = Facture::create([
            'chantier_id' => $chantier->id,
            'commercial_id' => $devis->commercial_id,
            'devis_id' => $devis->id,
            'titre' => $devis->titre,
            'description' => $devis->description,
            'client_info' => $devis->client_info,
            'montant_ht' => $devis->montant_ht,
            'montant_tva' => $devis->montant_tva,
            'montant_ttc' => $devis->montant_ttc,
            'taux_tva' => $devis->taux_tva,
            'conditions_reglement' => $devis->modalites_paiement,
            'delai_paiement' => 30,
        ]);

        // Copier les lignes du devis vers la facture
        foreach ($devis->lignes as $ligneDevis) {
            $facture->lignes()->create([
                'ordre' => $ligneDevis->ordre,
                'designation' => $ligneDevis->designation,
                'description' => $ligneDevis->description,
                'unite' => $ligneDevis->unite,
                'quantite' => $ligneDevis->quantite,
                'prix_unitaire_ht' => $ligneDevis->prix_unitaire_ht,
                'taux_tva' => $ligneDevis->taux_tva,
                'montant_ht' => $ligneDevis->montant_ht,
                'montant_tva' => $ligneDevis->montant_tva,
                'montant_ttc' => $ligneDevis->montant_ttc,
                'remise_pourcentage' => $ligneDevis->remise_pourcentage,
                'remise_montant' => $ligneDevis->remise_montant,
                'categorie' => $ligneDevis->categorie,
            ]);
        }

        return $facture;
    }

    /**
     * Dupliquer un devis
     */
    private function duplicateDevis(Devis $devis)
    {
        $nouveauDevis = $devis->replicate([
            'id', 'numero', 'created_at', 'updated_at', 
            'statut', 'date_envoi', 'date_reponse', 
            'signature_client', 'signed_at', 'signature_ip',
            'facture_id', 'converted_at'
        ]);

        $nouveauDevis->titre = $devis->titre . ' (Copie)';
        $nouveauDevis->statut = 'brouillon';
        $nouveauDevis->date_validite = now()->addDays(30);
        $nouveauDevis->save();

        // Dupliquer les lignes
        foreach ($devis->lignes as $ligne) {
            $nouvelleLigne = $ligne->replicate();
            $nouveauDevis->lignes()->save($nouvelleLigne);
        }

        $nouveauDevis->calculerMontants();

        return $nouveauDevis;
    }

    /**
     * Générer une réponse PDF
     */
    private function generatePdfResponse(Devis $devis, string $disposition = 'attachment')
    {
        try {
            $pdf = $this->pdfService->genererDevisPdf($devis);
            $filename = "devis_{$devis->numero}.pdf";
            
            return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "{$disposition}; filename=\"{$filename}\"");

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la génération du PDF : ' . $e->getMessage());
        }
    }
}