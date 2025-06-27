<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Analytics - {{ $user->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .meta-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }
        
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .kpi-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .kpi-card h3 {
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .kpi-card .value {
            font-size: 20px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 5px;
        }
        
        .kpi-card .subtitle {
            font-size: 10px;
            color: #64748b;
        }
        
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #3b82f6;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table th {
            background: #f1f5f9;
            padding: 12px 8px;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        
        .table tr:last-child td {
            border-bottom: none;
        }
        
        .table tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .money {
            font-weight: 600;
            color: #059669;
        }
        
        .percentage {
            font-weight: 600;
        }
        
        .positive {
            color: #059669;
        }
        
        .negative {
            color: #dc2626;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-success {
            background: #dcfce7;
            color: #166534;
        }
        
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .chart-placeholder {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            color: #64748b;
            font-style: italic;
            margin: 15px 0;
        }
        
        .footer {
            background: #f1f5f9;
            padding: 15px;
            margin-top: 30px;
            border-radius: 8px;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .highlight-box {
            background: #eff6ff;
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .highlight-box h4 {
            color: #1e40af;
            margin-bottom: 8px;
        }
        
        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>📊 Rapport Analytics</h1>
        <p>Analyse des performances commerciales et financières</p>
    </div>
    
    <!-- Meta informations -->
    <div class="meta-info">
        <div>
            <strong>Période :</strong> {{ \Carbon\Carbon::parse($filters['date_debut'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($filters['date_fin'])->format('d/m/Y') }}
        </div>
        <div>
            <strong>Généré le :</strong> {{ now()->format('d/m/Y à H:i') }}
        </div>
        <div>
            <strong>Par :</strong> {{ $user->name }} ({{ ucfirst($user->role) }})
        </div>
    </div>
    
    <!-- KPIs principaux -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <h3>Chiffre d'Affaires</h3>
            <div class="value money">{{ number_format($data['ca_total'] ?? 0, 0, ',', ' ') }} €</div>
            <div class="subtitle">
                @if(($data['evolution_ca'] ?? 0) >= 0)
                    <span class="positive">+{{ $data['evolution_ca'] }}%</span>
                @else
                    <span class="negative">{{ $data['evolution_ca'] }}%</span>
                @endif
                vs période précédente
            </div>
        </div>
        
        <div class="kpi-card">
            <h3>Taux de Conversion</h3>
            <div class="value percentage">{{ $data['taux_conversion'] ?? 0 }}%</div>
            <div class="subtitle">Devis → Acceptés</div>
        </div>
        
        <div class="kpi-card">
            <h3>DSO</h3>
            <div class="value">{{ $data['dso'] ?? 0 }} jours</div>
            <div class="subtitle">Délai moyen paiement</div>
        </div>
        
        <div class="kpi-card">
            <h3>CA en Attente</h3>
            <div class="value money">{{ number_format($data['ca_en_attente'] ?? 0, 0, ',', ' ') }} €</div>
            <div class="subtitle">Factures impayées</div>
        </div>
    </div>
    
    <!-- Résumé exécutif -->
    <div class="highlight-box">
        <h4>🎯 Résumé Exécutif</h4>
        <p>
            <strong>Performance globale :</strong> 
            @if(($data['evolution_ca'] ?? 0) >= 0)
                Croissance positive de {{ $data['evolution_ca'] }}% par rapport à la période précédente.
            @else
                Baisse de {{ abs($data['evolution_ca']) }}% par rapport à la période précédente.
            @endif
            
            Le taux de conversion de {{ $data['taux_conversion'] ?? 0 }}% 
            @if(($data['taux_conversion'] ?? 0) >= 25)
                est excellent et témoigne d'une bonne qualité commerciale.
            @elseif(($data['taux_conversion'] ?? 0) >= 15)
                est correct mais peut être amélioré.
            @else
                nécessite une attention particulière.
            @endif
        </p>
    </div>
    
    <!-- Évolution du CA -->
    <div class="section">
        <h2 class="section-title">📈 Évolution du Chiffre d'Affaires</h2>
        
        <div class="chart-placeholder">
            [Graphique d'évolution du CA mensuel]<br>
            <small>Les graphiques nécessitent une version interactive du rapport</small>
        </div>
        
        @if(isset($data['chiffre_affaires']['evolution']) && $data['chiffre_affaires']['evolution']->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Période</th>
                    <th>Montant</th>
                    <th>Évolution</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['chiffre_affaires']['evolution'] as $index => $periode)
                <tr>
                    <td>{{ $periode->periode }}</td>
                    <td class="money">{{ number_format($periode->montant, 0, ',', ' ') }} €</td>
                    <td>
                        @if($index > 0)
                            @php
                                $previous = $data['chiffre_affaires']['evolution'][$index - 1];
                                $evolution = $previous->montant > 0 ? round((($periode->montant - $previous->montant) / $previous->montant) * 100, 1) : 0;
                            @endphp
                            @if($evolution >= 0)
                                <span class="positive">+{{ $evolution }}%</span>
                            @else
                                <span class="negative">{{ $evolution }}%</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    
    <!-- Performance commerciale -->
    <div class="section page-break">
        <h2 class="section-title">👥 Performance Commerciale</h2>
        
        @if(isset($data['performance_commerciale']['classement']) && $data['performance_commerciale']['classement']->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Rang</th>
                    <th>Commercial</th>
                    <th>CA Réalisé</th>
                    <th>Nb Factures</th>
                    <th>Panier Moyen</th>
                    <th>Performance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['performance_commerciale']['classement']->take(10) as $index => $commercial)
                <tr>
                    <td>
                        @if($index === 0)
                            <span class="badge badge-warning">🏆 1</span>
                        @elseif($index < 3)
                            <span class="badge badge-success">{{ $index + 1 }}</span>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </td>
                    <td><strong>{{ $commercial->name }}</strong></td>
                    <td class="money">{{ number_format($commercial->ca_realise, 0, ',', ' ') }} €</td>
                    <td>{{ $commercial->nb_factures }}</td>
                    <td class="money">{{ number_format($commercial->ca_realise / max($commercial->nb_factures, 1), 0, ',', ' ') }} €</td>
                    <td>
                        @php
                            $score = round(($commercial->ca_realise / $data['performance_commerciale']['classement']->max('ca_realise')) * 100);
                        @endphp
                        @if($score >= 80)
                            <span class="badge badge-success">Excellent</span>
                        @elseif($score >= 60)
                            <span class="badge badge-warning">Bon</span>
                        @else
                            <span class="badge badge-danger">À améliorer</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    
    <!-- Santé financière -->
    <div class="section">
        <h2 class="section-title">💰 Santé Financière</h2>
        
        <div class="two-columns">
            <div>
                <h4>💸 Impayés</h4>
                <table class="table">
                    <tr>
                        <td>Nombre de factures</td>
                        <td><strong>{{ $data['impayees']->nb_factures ?? 0 }}</strong></td>
                    </tr>
                    <tr>
                        <td>Montant total</td>
                        <td class="money">{{ number_format($data['impayees']->montant_total ?? 0, 0, ',', ' ') }} €</td>
                    </tr>
                    <tr>
                        <td>Dont en retard</td>
                        <td class="money">{{ number_format($data['impayees']->montant_en_retard ?? 0, 0, ',', ' ') }} €</td>
                    </tr>
                </table>
            </div>
            
            <div>
                <h4>⏱️ Indicateurs</h4>
                <table class="table">
                    <tr>
                        <td>DSO moyen</td>
                        <td><strong>{{ $data['dso'] ?? 0 }} jours</strong></td>
                    </tr>
                    <tr>
                        <td>Taux de recouvrement</td>
                        <td class="percentage">
                            @php
                                $total = ($data['total_encaisse'] ?? 0) + ($data['impayees']->montant_total ?? 0);
                                $taux = $total > 0 ? round((($data['total_encaisse'] ?? 0) / $total) * 100, 1) : 100;
                            @endphp
                            {{ $taux }}%
                        </td>
                    </tr>
                    <tr>
                        <td>Total encaissé</td>
                        <td class="money">{{ number_format($data['total_encaisse'] ?? 0, 0, ',', ' ') }} €</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Recommandations -->
    <div class="section">
        <h2 class="section-title">💡 Recommandations</h2>
        
        <div style="margin-top: 15px;">
            @if(($data['evolution_ca'] ?? 0) < 0)
            <div style="background: #fee2e2; border-left: 4px solid #dc2626; padding: 12px; margin-bottom: 10px;">
                <strong>🚨 Priorité Haute :</strong> Le CA est en baisse de {{ abs($data['evolution_ca']) }}%. 
                Analyser les causes et mettre en place un plan d'action commercial.
            </div>
            @endif
            
            @if(($data['taux_conversion'] ?? 0) < 15)
            <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px; margin-bottom: 10px;">
                <strong>⚠️ Attention :</strong> Taux de conversion faible ({{ $data['taux_conversion'] }}%). 
                Formation commerciale et amélioration du processus de vente recommandées.
            </div>
            @endif
            
            @if(($data['dso'] ?? 0) > 45)
            <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px; margin-bottom: 10px;">
                <strong>⏰ Délais :</strong> DSO élevé ({{ $data['dso'] }} jours). 
                Renforcer le processus de recouvrement et revoir les conditions de paiement.
            </div>
            @endif
            
            @if(($data['impayees']->montant_en_retard ?? 0) > 0)
            <div style="background: #fee2e2; border-left: 4px solid #dc2626; padding: 12px; margin-bottom: 10px;">
                <strong>🔴 Urgent :</strong> {{ number_format($data['impayees']->montant_en_retard, 0, ',', ' ') }} € 
                de factures en retard nécessitent une action immédiate de recouvrement.
            </div>
            @endif
            
            @if(($data['evolution_ca'] ?? 0) >= 10)
            <div style="background: #dcfce7; border-left: 4px solid #16a34a; padding: 12px; margin-bottom: 10px;">
                <strong>✅ Excellent :</strong> Forte croissance du CA (+{{ $data['evolution_ca'] }}%). 
                Maintenir cette dynamique et identifier les facteurs de succès à reproduire.
            </div>
            @endif
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>
            <strong>{{ config('app.name') }}</strong> - Rapport généré automatiquement le {{ now()->format('d/m/Y à H:i') }}
            <br>
            Ce document est confidentiel et destiné uniquement à l'usage interne de l'entreprise.
        </p>
    </div>
</body>
</html>