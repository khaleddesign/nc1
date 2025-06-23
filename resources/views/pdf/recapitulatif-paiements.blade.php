<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif des paiements - Facture {{ $facture->numero }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        /* En-tête */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .header-left, .header-right {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }

        .logo {
            margin-bottom: 20px;
        }

        .company-info {
            font-size: 11px;
            line-height: 1.3;
        }

        .company-info strong {
            display: block;
            font-size: 14px;
            color: #16a34a;
            margin-bottom: 5px;
        }

        .document-title {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
            color: #16a34a;
            margin-bottom: 10px;
        }

        .document-subtitle {
            text-align: right;
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .document-date {
            text-align: right;
            font-size: 11px;
            color: #666;
        }

        /* Informations facture */
        .invoice-section {
            margin: 30px 0;
            background: #f0fdf4;
            padding: 20px;
            border-left: 4px solid #16a34a;
        }

        .invoice-section h3 {
            font-size: 14px;
            color: #16a34a;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .invoice-info {
            display: table;
            width: 100%;
        }

        .invoice-left, .invoice-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }

        .info-item {
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #374151;
        }

        .info-value {
            color: #6b7280;
        }

        /* Statut de paiement */
        .payment-status {
            background: #f0fdf4;
            border: 2px solid #16a34a;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            border-radius: 8px;
        }

        .payment-status h3 {
            color: #16a34a;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .payment-status.partial {
            background: #fffbeb;
            border-color: #f59e0b;
        }

        .payment-status.partial h3 {
            color: #f59e0b;
        }

        .payment-status.pending {
            background: #fef2f2;
            border-color: #dc2626;
        }

        .payment-status.pending h3 {
            color: #dc2626;
        }

        /* Barre de progression */
        .progress-bar {
            background: #f3f4f6;
            height: 25px;
            border-radius: 12px;
            overflow: hidden;
            margin: 15px 0;
            border: 1px solid #e5e7eb;
        }

        .progress-fill {
            background: linear-gradient(90deg, #16a34a, #22c55e);
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }

        /* Tableau des paiements */
        .payments-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11px;
        }

        .payments-table th {
            background: #16a34a;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        .payments-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .payments-table tr:nth-child(even) {
            background: #f9fafb;
        }

        .payments-table tr.total-row {
            background: #f0fdf4;
            font-weight: bold;
            border-top: 2px solid #16a34a;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Badges de statut */
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-valide {
            background: #dcfce7;
            color: #166534;
        }

        .status-en-attente {
            background: #fef3c7;
            color: #92400e;
        }

        .status-rejete {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Résumé financier */
        .financial-summary {
            display: table;
            width: 100%;
            margin: 30px 0;
        }

        .summary-left, .summary-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
        }

        .summary-box {
            border: 1px solid #e5e7eb;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        .summary-box h4 {
            font-size: 12px;
            color: #374151;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .summary-amount {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }

        .amount-paid {
            color: #16a34a;
        }

        .amount-remaining {
            color: #dc2626;
        }

        .amount-total {
            color: #374151;
        }

        /* Notes et mentions */
        .notes-section {
            margin-top: 30px;
            padding: 15px;
            background: #f8fafc;
            border-left: 4px solid #64748b;
        }

        .notes-section h3 {
            font-size: 13px;
            color: #475569;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .notes-section p {
            font-size: 10px;
            color: #64748b;
            line-height: 1.4;
        }

        /* Pied de page */
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }

        /* Responsive pour PDF */
        @media print {
            .container {
                padding: 0;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <div class="header-left">
                @if(isset($entreprise['logo']) && $entreprise['logo'])
                    <div class="logo">
                        <img src="{{ public_path($entreprise['logo']) }}" alt="Logo" style="max-height: 60px;">
                    </div>
                @endif
                
                <div class="company-info">
                    <strong>{{ $entreprise['nom'] ?? 'Votre Entreprise' }}</strong>
                    @if(isset($entreprise['adresse']))
                        <div>{{ $entreprise['adresse'] }}</div>
                    @endif
                    @if(isset($entreprise['code_postal']) && isset($entreprise['ville']))
                        <div>{{ $entreprise['code_postal'] }} {{ $entreprise['ville'] }}</div>
                    @endif
                    @if(isset($entreprise['telephone']))
                        <div>Tél : {{ $entreprise['telephone'] }}</div>
                    @endif
                    @if(isset($entreprise['email']))
                        <div>Email : {{ $entreprise['email'] }}</div>
                    @endif
                </div>
            </div>
            
            <div class="header-right">
                <div class="document-title">RÉCAPITULATIF DES PAIEMENTS</div>
                <div class="document-subtitle">Facture {{ $facture->numero }}</div>
                <div class="document-date">
                    Édité le {{ now()->format('d/m/Y à H:i') }}
                </div>
            </div>
        </div>

        <!-- Informations facture -->
        <div class="invoice-section">
            <h3>Informations de la facture</h3>
            <div class="invoice-info">
                <div class="invoice-left">
                    <div class="info-item">
                        <span class="info-label">Numéro :</span>
                        <span class="info-value">{{ $facture->numero }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date d'émission :</span>
                        <span class="info-value">{{ $facture->date_emission->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date d'échéance :</span>
                        <span class="info-value">{{ $facture->date_echeance->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Client :</span>
                        <span class="info-value">{{ $facture->client_nom }}</span>
                    </div>
                </div>
                <div class="invoice-right">
                    <div class="info-item">
                        <span class="info-label">Montant total :</span>
                        <span class="info-value">{{ number_format($facture->montant_ttc, 2, ',', ' ') }} €</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Montant payé :</span>
                        <span class="info-value amount-paid">{{ number_format($facture->montant_paye, 2, ',', ' ') }} €</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Reste à payer :</span>
                        <span class="info-value amount-remaining">{{ number_format($facture->montant_restant, 2, ',', ' ') }} €</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Statut :</span>
                        <span class="info-value">{{ $facture->statut_texte }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statut de paiement avec barre de progression -->
        <div class="payment-status {{ $facture->estPayee() ? '' : ($facture->montant_paye > 0 ? 'partial' : 'pending') }}">
            @if($facture->estPayee())
                <h3>✓ FACTURE ENTIÈREMENT PAYÉE</h3>
                <p>Paiement complet le {{ $facture->date_paiement_complet->format('d/m/Y') }}</p>
            @elseif($facture->montant_paye > 0)
                <h3>PAIEMENT PARTIEL</h3>
                <p>{{ number_format($facture->pourcentage_paiement, 1) }}% de la facture payée</p>
            @else
                <h3>EN ATTENTE DE PAIEMENT</h3>
                <p>Aucun paiement reçu à ce jour</p>
            @endif
            
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $facture->pourcentage_paiement }}%;">
                    {{ number_format($facture->pourcentage_paiement, 1) }}%
                </div>
            </div>
        </div>

        <!-- Résumé financier -->
        <div class="financial-summary">
            <div class="summary-left">
                <div class="summary-box">
                    <h4>Montant Total</h4>
                    <div class="summary-amount amount-total">
                        {{ number_format($facture->montant_ttc, 2, ',', ' ') }} €
                    </div>
                </div>
            </div>
            <div class="summary-right">
                <div class="summary-box">
                    <h4>Montant Payé</h4>
                    <div class="summary-amount amount-paid">
                        {{ number_format($facture->montant_paye, 2, ',', ' ') }} €
                    </div>
                </div>
            </div>
        </div>

        <div class="financial-summary">
            <div class="summary-left">
                <div class="summary-box">
                    <h4>Reste à Payer</h4>
                    <div class="summary-amount amount-remaining">
                        {{ number_format($facture->montant_restant, 2, ',', ' ') }} €
                    </div>
                </div>
            </div>
            <div class="summary-right">
                <div class="summary-box">
                    <h4>Nombre de Paiements</h4>
                    <div class="summary-amount">
                        {{ $facture->paiements->where('statut', 'valide')->count() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des paiements -->
        @if($facture->paiements->count() > 0)
            <h3 style="margin: 30px 0 15px 0; color: #16a34a; font-size: 16px;">Historique détaillé des paiements</h3>
            
            <table class="payments-table">
                <thead>
                    <tr>
                        <th style="width: 12%;">Date</th>
                        <th style="width: 15%;">Montant</th>
                        <th style="width: 15%;">Mode de paiement</th>
                        <th style="width: 15%;">Référence</th>
                        <th style="width: 10%;">Statut</th>
                        <th style="width: 15%;">Saisi par</th>
                        <th style="width: 18%;">Commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facture->paiements->sortBy('date_paiement') as $paiement)
                        <tr>
                            <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                            <td class="text-right">
                                <strong>{{ number_format($paiement->montant, 2, ',', ' ') }} €</strong>
                            </td>
                            <td>{{ $paiement->mode_paiement_texte }}</td>
                            <td>
                                {{ $paiement->reference_paiement ?: '-' }}
                                @if($paiement->banque)
                                    <br><small style="color: #6b7280;">{{ $paiement->banque }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="status-badge status-{{ str_replace('_', '-', $paiement->statut) }}">
                                    {{ $paiement->statut_texte }}
                                </span>
                            </td>
                            <td>
                                {{ $paiement->saisieParUser ? $paiement->saisieParUser->name : 'Système' }}
                                @if($paiement->valide_at)
                                    <br><small style="color: #6b7280;">{{ $paiement->valide_at->format('d/m H:i') }}</small>
                                @endif
                            </td>
                            <td>
                                {{ $paiement->commentaire ?: '-' }}
                            </td>
                        </tr>
                    @endforeach
                    
                    <!-- Ligne de total -->
                    <tr class="total-row">
                        <td><strong>TOTAL PAYÉ</strong></td>
                        <td class="text-right">
                            <strong>{{ number_format($facture->paiements->where('statut', 'valide')->sum('montant'), 2, ',', ' ') }} €</strong>
                        </td>
                        <td colspan="5"></td>
                    </tr>
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 40px; color: #6b7280;">
                <p style="font-size: 14px;">Aucun paiement enregistré pour cette facture</p>
            </div>
        @endif

        <!-- Informations de relances -->
        @if($facture->nb_relances > 0)
            <div class="notes-section" style="background: #fef3c7; border-color: #f59e0b;">
                <h3 style="color: #92400e;">Relances effectuées</h3>
                <p style="color: #92400e;">
                    <strong>{{ $facture->nb_relances }}</strong> relance(s) envoyée(s)
                    @if($facture->derniere_relance)
                        - Dernière relance le {{ $facture->derniere_relance->format('d/m/Y') }}
                    @endif
                </p>
            </div>
        @endif

        <!-- Notes et mentions légales -->
        <div class="notes-section">
            <h3>Mentions légales et conditions</h3>
            <p>
                <strong>Conditions de règlement :</strong> {{ $facture->conditions_reglement ?: 'Selon conditions générales de vente' }}
            </p>
            @if($facture->estEnRetard())
                <p style="color: #dc2626; font-weight: bold;">
                    ⚠ Cette facture est en retard de paiement depuis le {{ $facture->date_echeance->format('d/m/Y') }}.
                    Des pénalités de retard peuvent s'appliquer selon nos conditions générales de vente.
                </p>
            @endif
            <p>
                En cas de retard de paiement, des pénalités de retard au taux de 10% par an seront appliquées, 
                ainsi qu'une indemnité forfaitaire pour frais de recouvrement de 40€ minimum (article L441-10 du Code de commerce).
            </p>
        </div>

        <!-- Coordonnées bancaires si facture non payée -->
        @if(!$facture->estPayee() && (isset($entreprise['iban']) || isset($entreprise['rib'])))
            <div class="notes-section" style="background: #f0f9ff; border-color: #0284c7;">
                <h3 style="color: #0284c7;">Coordonnées bancaires pour le règlement</h3>
                @if(isset($entreprise['banque']))
                    <p><strong>{{ $entreprise['banque'] }}</strong></p>
                @endif
                @if(isset($entreprise['iban']))
                    <p><strong>IBAN :</strong> {{ $entreprise['iban'] }}</p>
                @endif
                @if(isset($entreprise['bic']))
                    <p><strong>BIC :</strong> {{ $entreprise['bic'] }}</p>
                @endif
                @if(isset($entreprise['rib']))
                    <p><strong>RIB :</strong> {{ $entreprise['rib'] }}</p>
                @endif
                <p style="margin-top: 10px; font-weight: bold;">
                    Merci de mentionner la référence de facture <strong>{{ $facture->numero }}</strong> lors de votre virement.
                </p>
            </div>
        @endif

        <!-- Informations du chantier -->
        @if($facture->chantier)
            <div class="notes-section" style="background: #f8fafc; border-color: #64748b;">
                <h3>Informations du chantier</h3>
                <p><strong>Chantier :</strong> {{ $facture->chantier->titre }}</p>
                @if($facture->chantier->description)
                    <p><strong>Description :</strong> {{ $facture->chantier->description }}</p>
                @endif
                <p><strong>Commercial responsable :</strong> {{ $facture->commercial->name ?? 'Non défini' }}</p>
                @if($facture->devis)
                    <p><strong>Devis de référence :</strong> {{ $facture->devis->numero }}</p>
                @endif
            </div>
        @endif

        <!-- Récapitulatif de fin -->
        <div style="margin-top: 40px; padding: 20px; background: {{ $facture->estPayee() ? '#f0fdf4' : '#fef2f2' }}; border: 2px solid {{ $facture->estPayee() ? '#16a34a' : '#dc2626' }}; text-align: center;">
            @if($facture->estPayee())
                <h3 style="color: #16a34a; font-size: 18px; margin-bottom: 10px;">
                    ✓ FACTURE SOLDÉE
                </h3>
                <p style="color: #166534;">
                    Cette facture a été entièrement réglée le {{ $facture->date_paiement_complet->format('d/m/Y') }}.
                    Merci pour votre confiance.
                </p>
            @else
                <h3 style="color: #dc2626; font-size: 18px; margin-bottom: 10px;">
                    SOLDE RESTANT À RÉGLER
                </h3>
                <p style="color: #991b1b; font-size: 16px; font-weight: bold;">
                    {{ number_format($facture->montant_restant, 2, ',', ' ') }} €
                </p>
                <p style="color: #991b1b;">
                    À régler avant le {{ $facture->date_echeance->format('d/m/Y') }}
                </p>
            @endif
        </div>

        <!-- Pied de page -->
        <div class="footer">
            {{ $entreprise['nom'] ?? 'Votre Entreprise' }} - 
            @if(isset($entreprise['siret']))
                SIRET : {{ $entreprise['siret'] }} - 
            @endif
            @if(isset($entreprise['email']))
                {{ $entreprise['email'] }} - 
            @endif
            @if(isset($entreprise['telephone']))
                {{ $entreprise['telephone'] }}
            @endif
            <br>
            Document généré automatiquement le {{ now()->format('d/m/Y à H:i:s') }}
        </div>
    </div>
</body>
</html>