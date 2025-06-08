<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notification->titre }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .notification-type {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }
        .type-nouveau_chantier { background-color: #E0F2FE; color: #0369A1; }
        .type-changement_statut { background-color: #FEF3C7; color: #D97706; }
        .type-nouvelle_etape { background-color: #DBEAFE; color: #2563EB; }
        .type-etape_terminee { background-color: #D1FAE5; color: #059669; }
        .type-nouveau_document { background-color: #F3E8FF; color: #7C3AED; }
        .type-nouveau_commentaire_client,
        .type-nouveau_commentaire_commercial { background-color: #FEE2E2; color: #DC2626; }
        .type-chantier_retard { background-color: #FEF2F2; color: #EF4444; }
        
        .notification-title {
            font-size: 20px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 15px;
        }
        .notification-message {
            font-size: 16px;
            color: #4B5563;
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .chantier-info {
            background-color: #F9FAFB;
            border-left: 4px solid #4F46E5;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 0 6px 6px 0;
        }
        .chantier-info h3 {
            margin: 0 0 10px 0;
            color: #1F2937;
            font-size: 16px;
        }
        .chantier-info p {
            margin: 5px 0;
            color: #6B7280;
            font-size: 14px;
        }
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        .footer {
            background-color: #F9FAFB;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #E5E7EB;
        }
        .footer p {
            margin: 5px 0;
            color: #6B7280;
            font-size: 14px;
        }
        .footer a {
            color: #4F46E5;
            text-decoration: none;
        }
        .date-info {
            color: #9CA3AF;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ config('app.name', 'Gestion Chantiers') }}</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Type de notification -->
            <div class="notification-type type-{{ $notification->type }}">
                {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
            </div>

            <!-- Titre de la notification -->
            <h2 class="notification-title">{{ $notification->titre }}</h2>

            <!-- Message principal -->
            <div class="notification-message">
                {{ $notification->message }}
            </div>

            <!-- Informations sur le chantier -->
            @if($chantier)
            <div class="chantier-info">
                <h3>📋 Chantier : {{ $chantier->titre }}</h3>
                <p><strong>Client :</strong> {{ $chantier->client->name }}</p>
                <p><strong>Commercial :</strong> {{ $chantier->commercial->name }}</p>
                <p><strong>Statut :</strong> {{ $chantier->getStatutTexte() }}</p>
                @if($chantier->avancement_global)
                    <p><strong>Avancement :</strong> {{ number_format($chantier->avancement_global, 0) }}%</p>
                @endif
            </div>
            @endif

            <!-- Bouton d'action -->
            @if($chantier)
            <div style="text-align: center;">
                <a href="{{ route('chantiers.show', $chantier) }}" class="action-button">
                    Voir le chantier
                </a>
            </div>
            @endif

            <!-- Date -->
            <div class="date-info">
                📅 {{ $notification->created_at->format('d/m/Y à H:i') }}
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>Gestion professionnelle de vos chantiers</p>
            <p>
                Vous recevez cet email car vous êtes inscrit sur notre plateforme.<br>
                <a href="{{ route('notifications.index') }}">Gérer mes notifications</a>
            </p>
        </div>
    </div>
</body>
</html>