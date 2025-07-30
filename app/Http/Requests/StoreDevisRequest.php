<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDevisRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // ← CHANGEZ false en true
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type_devis' => 'required|in:nouveau_prospect,chantier_existant',
            'chantier_id' => 'nullable|required_if:type_devis,chantier_existant|exists:chantiers,id',
            'client_nom' => 'required_if:type_devis,nouveau_prospect|string|max:255',
            'client_email' => 'required_if:type_devis,nouveau_prospect|email|unique:users,email',
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
            'reference_externe' => 'nullable|string|max:100',
            'lignes' => 'required|array|min:1',
            'lignes.*.designation' => 'required|string|max:255',
            'lignes.*.description' => 'nullable|string',
            'lignes.*.unite' => 'required|string|max:50',
            'lignes.*.quantite' => 'required|numeric|min:0.01',
            'lignes.*.prix_unitaire_ht' => 'required|numeric|min:0',
            'lignes.*.taux_tva' => 'nullable|numeric|min:0|max:100',
            'lignes.*.remise_pourcentage' => 'nullable|numeric|min:0|max:100',
            'lignes.*.categorie' => 'nullable|string|max:100',
        ];
    }
}