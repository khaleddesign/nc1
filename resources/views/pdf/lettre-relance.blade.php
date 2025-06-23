<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lettre de relance - Facture {{ $facture->numero }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 30px;
        }

        /* En-tête entreprise */
        .company-header {
            margin-bottom: 40px;
        }

        .company-info {
            font-size: 11px;
            line-height: 1.4;
        }

        .company-info strong {
            display: block;
            font-size: 16px;
            color: #dc2626;
            margin-bottom: 8px;
        }

        .logo {
            float: right;
            margin-left: 20px;
        }

        /* Informations client */
        .client-address {
            margin: 60px 0 40px 0;
            float: right;
            width: 300px;
            padding: 20px;
            border: 1px solid #e5e7eb;
        }

        .client-address h3 {
            font-size: 12px;
            color: #dc2626;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .client-address p {
            margin: 3px 0;
        }

        /* Date et lieu */
        .letter-date {
            clear: both;
            text-align: right;
            margin: 40px 0;
            font-size: 11px;
        }

        /* Objet de la lettre */
        .letter-subject {
            margin: 30px 0;
            font-weight: bold;
            text-decoration: underline;
        }

        /* Corps de la lettre */
        .letter-body {
            line-height: 1.8;
            text-align: justify;
        }

        .letter-body p {
            margin: 15px 0;
        }

        .letter-body .greeting {
            margin-bottom: 20px;
        }

        .letter-body .closing {
            margin-top: 30px;
        }

        /* Encadré facture */
        .invoice-details {
            background: #fef2f2;
            border: 2px solid #dc2626;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }

        .invoice-details h3 {
            color: #dc2626;
            font-size: 16px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .invoice-table {
            width: 100%;
            margin: 15px 0;
        }

        .invoice-table td {
            padding: 5px 10px;
        }

        .invoice-table .label {
            font-weight: bold;
            text-align: right;
            width: 50%;
        }

        .invoice-table .value {
            text-align: left;
            width: 50%;
        }

        .amount-due {
            font-size: 20px;
            font-weight: bold;
            color: #dc2626;
            background: #ffffff;
            padding: 10px;
            border: 2px dashed #dc2626;
            margin: 15px 0;
        }

        /* Urgence */
        .urgency-level {
            padding: 15px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
            border-radius: 6px;
        }

        .urgency-low {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
        }

        .urgency-medium {
            background: #fed7aa;
            border: 1px solid #ea580c;
            color: #c2410c;
        }

        .urgency-high {
            background: #fee2e2;
            border: 1px solid #dc2626;
            color: #991b1b;
        }

        /* Coordonnées bancaires */
        .bank-details {
            background: #f0f9ff;
            border: 1px solid #0284c7;
            padding: 15px;
            margin: 20px 0;
        }

        .bank-details h4 {
            color: #0284c7;
            margin-bottom: 10px;
            font-size: 13px;
            text-transform: uppercase;
        }

        /* Actions à entreprendre */
        .action-box {
            background: #f8fafc;
            border-left: 4px solid #64748b;
            padding: 15px;
            margin: 20px 0;
        }

        .action-box h4 {
            color: #475569;
            margin-bottom: 10px;
            font-size: 13px;
            text-transform: uppercase;
        }

        .action-box ul {
            margin-left: 20px;
        }

        .action-box li {
            margin: 5px 0;
        }

        /* Signature */
        .signature-section {
            margin-top: 50px;
        }

        .signature-box {
            text-align: right;
            margin-top: 40px;
        }

        /* Pied de page */
        .footer {
            position: fixed;
            bottom: 20px;
            left: 30px;
            right: 30px;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }

        /* Styles spéciaux */
        .highlight {
            background: #fef3c7;
            padding: 2px 4px;
        }

        .text-red {
            color: #dc2626;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        /* Responsive pour PDF */
        @media print {
            .container {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête entreprise -->
        <div class="company-header">
            @if(isset($entreprise['logo']) && $entreprise['logo'])
                <div class="logo">
                    <img src="{{ public_path($entreprise['logo']) }}" alt="Logo" style="max-height: 80px;">
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
                @if(isset($entreprise['siret']))
                    <div>SIRET : {{ $entreprise['siret'] }}</div>
                @endif
            </div>
        </div>

        <!-- Adresse client -->
        <div class="client-address">
            <h3>Destinataire</h3>
            <p><strong>{{ $facture->client_nom }}</strong></p>
            @if(isset($facture->client_info['adresse']) && $facture->client_info['adresse'])
                <p>{{ $facture->client_info['adresse'] }}</p>
            @endif
            @if(isset($facture->client_info['email']) && $facture->client_info['email'])
                <p>{{ $facture->client_info['email'] }}</p>
            @endif
        </div>

        <!-- Date et lieu -->
        <div class="letter-date">
            {{ isset($entreprise['ville']) ? $entreprise['ville'] : 'Le' }}, le {{ now()->format('d/m/Y') }}
        </div>

        <!-- Niveau d'urgence -->
        @php
            $jourRetard = now()->diffInDays($facture->date_echeance);
            $niveauUrgence = 'low';
            $texteUrgence = 'RAPPEL AIMABLE';
            
            if ($jourRetard > 60) {
                $niveauUrgence = 'high';
                $texteUrgence = 'MISE EN DEMEURE - DERNIÈRE RELANCE';
            } elseif ($jourRetard > 30) {
                $niveauUrgence = 'medium';
                $texteUrgence = 'RELANCE URGENTE';
            }
        @endphp

        <div class="urgency-level urgency-{{ $niveauUrgence }}">
            {{ $texteUrgence }}
            @if($facture->nb_relances > 1)
                - {{ $facture->nb_relances }}ème relance
            @endif
        </div>

        <!-- Objet -->
        <div class="letter-subject">
            <strong>Objet : Impayé facture n° {{ $facture->numero }} - 
            {{ number_format($facture->montant_restant, 2, ',', ' ') }} € 
            - Échue depuis {{ $jourRetard }} jour{{ $jourRetard > 1 ? 's' : '' }}</strong>
        </div>

        <!-- Corps de la lettre -->
        <div class="letter-body">
            <p class="greeting">{{ $facture->client_info['civilite'] ?? 'Madame, Monsieur' }},</p>

            @if($jourRetard <= 30)
                <!-- Relance aimable -->
                <p>
                    Nous vous informons qu'à ce jour, notre facture référencée ci-dessous n'a pas été réglée 
                    et a dépassé sa date d'échéance du <strong>{{ $facture->date_echeance->format('d/m/Y') }}</strong>.
                </p>
                
                <p>
                    Il est possible que cette situation soit due à un simple oubli de votre part ou à un 
                    retard de traitement de nos services. C'est pourquoi nous nous permettons de vous la rappeler.
                </p>

            @elseif($jourRetard <= 60)
                <!-- Relance ferme -->
                <p>
                    Malgré notre précédente relance, nous constatons que votre facture n° <strong>{{ $facture->numero }}</strong> 
                    d'un montant de <strong>{{ number_format($facture->montant_restant, 2, ',', ' ') }} €</strong> 
                    demeure impayée <strong>{{ $jourRetard }} jours</strong> après son échéance.
                </p>
                
                <p>
                    Cette situation nous préoccupe et nous vous demandons de <strong class="text-red">régulariser 
                    votre situation dans les plus brefs délais</strong>, soit dans un délai maximum de 
                    <strong>8 jours</strong> à compter de la réception de ce courrier.
                </p>

            @else
                <!-- Mise en demeure -->
                <p class="text-red">
                    <strong>MISE EN DEMEURE</strong>
                </p>
                
                <p>
                    Malgré nos relances précédentes, nous constatons avec regret que votre facture 
                    n° <strong>{{ $facture->numero }}</strong> d'un montant de 
                    <strong>{{ number_format($facture->montant_restant, 2, ',', ' ') }} €</strong> 
                    demeure impayée <strong>{{ $jourRetard }} jours</strong> après son échéance.
                </p>
                
                <p>
                    <strong class="text-red">
                    En conséquence, nous vous mettons en demeure de régler le montant dû 
                    dans un délai de 8 jours à compter de la réception du présent courrier.
                    </strong>
                </p>
                
                <p>
                    Passé ce délai, et faute de règlement ou de prise de contact de votre part, 
                    nous nous verrons contraints d'engager contre vous une procédure de recouvrement 
                    contentieux, avec application des pénalités prévues par la loi.
                </p>
            @endif
        </div>

        <!-- Détails de la facture -->
        <div class="invoice-details">
            <h3>Détails de la facture impayée</h3>
            
            <table class="invoice-table">
                <tr>
                    <td class="label">Numéro de facture :</td>
                    <td class="value"><strong>{{ $facture->numero }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Date d'émission :</td>
                    <td class="value">{{ $facture->date_emission->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Date d'échéance :</td>
                    <td class="value"><strong class="text-red">{{ $facture->date_echeance->format('d/m/Y') }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Montant initial :</td>
                    <td class="value">{{ number_format($facture->montant_ttc, 2, ',', ' ') }} €</td>
                </tr>
                @if($facture->montant_paye > 0)
                    <tr>
                        <td class="label">Déjà payé :</td>
                        <td class="value">{{ number_format($facture->montant_paye, 2, ',', ' ') }} €</td>
                    </tr>
                @endif
                <tr>
                    <td class="label">Jours de retard :</td>
                    <td class="value"><strong class="text-red">{{ $jourRetard }} jour{{ $jourRetard > 1 ? 's' : '' }}</strong></td>
                </tr>
            </table>
            
            <div class="amount-due">
                MONTANT À RÉGLER : {{ number_format($facture->montant_restant, 2, ',', ' ') }} €
            </div>
        </div>

        <!-- Pénalités -->
        @if($jourRetard > 0)
            <div class="action-box" style="background: #fef2f2; border-color: #dc2626;">
                <h4 style="color: #dc2626;">Pénalités de retard applicables</h4>
                <p>
                    Conformément à l'article L441-10 du Code de commerce, des pénalités de retard 
                    au taux de <strong>10% par an</strong> sont applicables sur le montant impayé, 
                    soit {{ number_format($facture->montant_restant * 0.10 * $jourRetard / 365, 2, ',', ' ') }} € 
                    à ce jour.
                </p>
                <p>
                    Une indemnité forfaitaire pour frais de recouvrement de <strong>40 €</strong> 
                    sera également facturée en cas de poursuite de l'impayé.
                </p>
            </div>
        @endif

        <!-- Coordonnées bancaires -->
        @if(isset($entreprise['iban']) || isset($entreprise['rib']))
            <div class="bank-details">
                <h4>Nos coordonnées bancaires pour le règlement</h4>
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
                <p style="margin-top: 10px;">
                    <strong>Merci de mentionner la référence "{{ $facture->numero }}" lors de votre virement.</strong>
                </p>
            </div>
        @endif

        <!-- Actions à entreprendre -->
        <div class="action-box">
            <h4>Que faire maintenant ?</h4>
            <ul>
                <li><strong>Si vous avez déjà réglé cette facture</strong> : merci de nous transmettre la preuve de paiement</li>
                <li><strong>Si vous contestez cette facture</strong> : contactez-nous immédiatement pour en discuter</li>
                <li><strong>Si vous rencontrez des difficultés de paiement</strong> : prenez contact avec nous pour étudier un échéancier</li>
                <li><strong>Pour tout autre cas</strong> : nous vous remercions de procéder au règlement dans les meilleurs délais</li>
            </ul>
        </div>

        <!-- Suite de la lettre -->
        <div class="letter-body">
            @if($jourRetard <= 30)
                <p>
                    Nous vous remercions de bien vouloir régulariser cette situation rapidement 
                    et restons à votre disposition pour tout renseignement complémentaire.
                </p>
                
                <p class="closing">
                    En vous remerciant par avance de votre diligence, nous vous prions d'agréer, 
                    {{ $facture->client_info['civilite'] ?? 'Madame, Monsieur' }}, 
                    l'expression de nos salutations distinguées.
                </p>

            @elseif($jourRetard <= 60)
                <p>
                    Nous espérons que ce rappel retiendra toute votre attention et que vous 
                    procéderez au règlement dans les meilleurs délais.
                </p>
                
                <p class="closing">
                    Dans l'attente de votre règlement, nous vous prions d'agréer, 
                    {{ $facture->client_info['civilite'] ?? 'Madame, Monsieur' }}, 
                    l'expression de nos salutations distinguées.
                </p>

            @else
                <p>
                    Nous regrettons d'en arriver à cette procédure et espérons vivement 
                    que vous donnerez suite à cette mise en demeure.
                </p>
                
                <p class="closing">
                    Veuillez agréer, {{ $facture->client_info['civilite'] ?? 'Madame, Monsieur' }}, 
                    nos salutations.
                </p>
            @endif
        </div>

        <!-- Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <p><strong>{{ $entreprise['nom'] ?? 'Votre Entreprise' }}</strong></p>
                @if($facture->commercial)
                    <p>{{ $facture->commercial->name }}</p>
                    @if(isset($facture->commercial->fonction))
                        <p>{{ $facture->commercial->fonction }}</p>
                    @endif
                @endif
                
                <div style="height: 60px; margin: 20px 0;"></div>
                <div style="border-top: 1px solid #333; width: 200px; margin-left: auto;">
                    Signature
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div style="margin-top: 40px; padding: 15px; background: #f8fafc; border: 1px solid #e5e7eb;">
            <p style="text-align: center; font-size: 11px;">
                <strong>Pour nous contacter :</strong>
                @if(isset($entreprise['telephone']))
                    Tél : {{ $entreprise['telephone'] }}
                @endif
                @if(isset($entreprise['email']))
                    - Email : {{ $entreprise['email'] }}
                @endif
            </p>
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
            Lettre de relance générée le {{ now()->format('d/m/Y à H:i') }}
        </div>
    </div>
</body>
</html>