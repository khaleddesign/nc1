@extends('layouts.app')

@section('title', 'Devis ' . $devis->numero)

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-4">
                            <li>
                                <a href="{{ route('devis.index') }}" class="text-gray-400 hover:text-gray-500">
                                    <svg class="flex-shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621 0 1.125-.504 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    <span class="sr-only">Devis</span>
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <a href="{{ route('chantiers.show', $devis->chantier) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                        {{ $devis->chantier->titre }}
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="ml-4 text-sm font-medium text-gray-500">{{ $devis->numero }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        Devis {{ $devis->numero }}
                    </h1>
                    <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <span class="font-medium">{{ $devis->titre }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex space-x-3 md:mt-0 md:ml-4">
                    <a href="{{ route('chantiers.devis.show', [$devis->chantier, $devis]) }}" 
                       class="btn btn-outline">
                        Voir dans le chantier
                    </a>
                    @if($devis->statut !== 'brouillon')
                        <a href="{{ route('chantiers.devis.pdf', [$devis->chantier, $devis]) }}" 
                           class="btn btn-secondary" target="_blank">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            Télécharger PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Contenu principal --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Colonne principale --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Informations du devis --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Informations du devis</h2>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Numéro</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $devis->numero }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Statut</dt>
                            <dd class="mt-1">
                                @php
                                    $colors = [
                                        'brouillon' => 'bg-gray-100 text-gray-800',
                                        'envoye' => 'bg-yellow-100 text-yellow-800',
                                        'accepte' => 'bg-green-100 text-green-800',
                                        'refuse' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="badge {{ $colors[$devis->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($devis->statut) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $devis->created_at->format('d/m/Y à H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date de validité</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $devis->date_validite->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Commercial</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $devis->commercial->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Délai de réalisation</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $devis->delai_realisation ?? 'N/A' }} jours</dd>
                        </div>
                    </dl>
                    
                    @if($devis->description)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $devis->description }}</dd>
                        </div>
                    @endif
                </div>

                {{-- Lignes du devis --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Détail du devis</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Désignation</th>
                                    <th>Unité</th>
                                    <th>Quantité</th>
                                    <th>Prix unitaire HT</th>
                                    <th>TVA</th>
                                    <th>Montant HT</th>
                                    <th>Montant TTC</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($devis->lignes as $ligne)
                                    <tr>
                                        <td>
                                            <div class="font-medium text-gray-900">{{ $ligne->designation }}</div>
                                            @if($ligne->description)
                                                <div class="text-sm text-gray-500">{{ $ligne->description }}</div>
                                            @endif
                                        </td>
                                        <td class="text-sm text-gray-900">{{ $ligne->unite }}</td>
                                        <td class="text-sm text-gray-900">{{ number_format($ligne->quantite, 2, ',', ' ') }}</td>
                                        <td class="text-sm text-gray-900">{{ number_format($ligne->prix_unitaire_ht, 2, ',', ' ') }} €</td>
                                        <td class="text-sm text-gray-900">{{ $ligne->taux_tva }}%</td>
                                        <td class="text-sm text-gray-900 font-medium">{{ number_format($ligne->montant_ht, 2, ',', ' ') }} €</td>
                                        <td class="text-sm text-gray-900 font-medium">{{ number_format($ligne->montant_ttc, 2, ',', ' ') }} €</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="5" class="px-6 py-3 text-right font-medium text-gray-900">Total HT :</td>
                                    <td class="px-6 py-3 font-bold text-gray-900">{{ number_format($devis->montant_ht ?? 0, 2, ',', ' ') }} €</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-6 py-3 text-right font-medium text-gray-900">TVA :</td>
                                    <td class="px-6 py-3 font-bold text-gray-900">{{ number_format($devis->montant_tva ?? 0, 2, ',', ' ') }} €</td>
                                    <td></td>
                                </tr>
                                <tr class="bg-blue-50">
                                    <td colspan="5" class="px-6 py-3 text-right font-bold text-gray-900">Total TTC :</td>
                                    <td class="px-6 py-3 font-bold text-blue-700 text-lg">{{ number_format($devis->montant_ttc ?? 0, 2, ',', ' ') }} €</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Colonne latérale --}}
            <div class="space-y-6">
                {{-- Informations du chantier --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Chantier associé</h3>
                    <div class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Titre</dt>
                            <dd class="mt-1">
                                <a href="{{ route('chantiers.show', $devis->chantier) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    {{ $devis->chantier->titre }}
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Statut du chantier</dt>
                            <dd class="mt-1">
                                @php
                                    $statutColors = [
                                        'planifie' => 'bg-gray-100 text-gray-800',
                                        'en_cours' => 'bg-blue-100 text-blue-800',
                                        'termine' => 'bg-green-100 text-green-800',
                                        'annule' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="badge {{ $statutColors[$devis->chantier->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $devis->chantier->statut)) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dates</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div>Début : {{ $devis->chantier->date_debut?->format('d/m/Y') ?? 'N/A' }}</div>
                                <div>Fin prévue : {{ $devis->chantier->date_fin_prevue?->format('d/m/Y') ?? 'N/A' }}</div>
                            </dd>
                        </div>
                    </div>
                </div>

                {{-- Informations du client --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Client</h3>
                    <div class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nom</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $devis->chantier->client->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1">
                                <a href="mailto:{{ $devis->chantier->client->email }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    {{ $devis->chantier->client->email }}
                                </a>
                            </dd>
                        </div>
                        @if($devis->chantier->client->telephone)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                                <dd class="mt-1">
                                    <a href="tel:{{ $devis->chantier->client->telephone }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        {{ $devis->chantier->client->telephone }}
                                    </a>
                                </dd>
                            </div>
                        @endif
                        @if($devis->chantier->client->adresse)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $devis->chantier->client->adresse }}</dd>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Actions rapides --}}
                @can('commercial-or-admin')
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('chantiers.devis.edit', [$devis->chantier, $devis]) }}" 
                               class="block w-full btn btn-outline text-center">
                                <svg class="w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                Modifier le devis
                            </a>

                            @if($devis->statut === 'accepte' && !$devis->facture)
                                <form method="POST" action="{{ route('chantiers.devis.convertir-facture', [$devis->chantier, $devis]) }}" 
                                      onsubmit="return confirm('Voulez-vous vraiment convertir ce devis en facture ?')">
                                    @csrf
                                    <button type="submit" class="w-full btn btn-success">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M16.5 18.75h-9A2.25 2.25 0 015.25 16.5v-10.5A2.25 2.25 0 017.5 3.75h1.5m0 0h6m-6 0v1.5m6-1.5v1.5m6 1.5v10.5a2.25 2.25 0 01-2.25 2.25H13.5m-6-0h6m-6 3h6" />
                                        </svg>
                                        Convertir en facture
                                    </button>
                                </form>
                            @endif

                            @if($devis->facture)
                                <a href="{{ route('factures.show', $devis->facture) }}" 
                                   class="block w-full btn btn-secondary text-center">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25M16.5 18.75h-9A2.25 2.25 0 015.25 16.5v-10.5A2.25 2.25 0 017.5 3.75h1.5m0 0h6m-6 0v1.5m6-1.5v1.5m6 1.5v10.5a2.25 2.25 0 01-2.25 2.25H13.5m-6-0h6m-6 3h6" />
                                    </svg>
                                    Voir la facture {{ $devis->facture->numero }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endcan

                {{-- Historique --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Historique</h3>
                    <div class="space-y-3">
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                            <div>
                                <div class="font-medium text-gray-900">Création du devis</div>
                                <div class="text-gray-500">{{ $devis->created_at->format('d/m/Y à H:i') }}</div>
                            </div>
                        </div>

                        @if($devis->date_envoi)
                            <div class="flex items-center text-sm">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                                <div>
                                    <div class="font-medium text-gray-900">Envoi au client</div>
                                    <div class="text-gray-500">{{ $devis->date_envoi->format('d/m/Y à H:i') }}</div>
                                </div>
                            </div>
                        @endif

                        @if($devis->date_reponse)
                            <div class="flex items-center text-sm">
                                <div class="w-2 h-2 {{ $devis->statut === 'accepte' ? 'bg-green-500' : 'bg-red-500' }} rounded-full mr-3"></div>
                                <div>
                                    <div class="font-medium text-gray-900">
                                        {{ $devis->statut === 'accepte' ? 'Acceptation' : 'Refus' }} du client
                                    </div>
                                    <div class="text-gray-500">{{ $devis->date_reponse->format('d/m/Y à H:i') }}</div>
                                </div>
                            </div>
                        @endif

                        @if($devis->converted_at)
                            <div class="flex items-center text-sm">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                                <div>
                                    <div class="font-medium text-gray-900">Conversion en facture</div>
                                    <div class="text-gray-500">{{ $devis->converted_at->format('d/m/Y à H:i') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection