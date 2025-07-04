@extends('layouts.app')

@section('title', 'Facture ' . $facture->numero)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <nav class="flex items-center space-x-2 text-sm text-gray-500">
                        <a href="{{ route('factures.index') }}" class="hover:text-gray-700">Factures</a>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="text-gray-900 font-medium">{{ $facture->numero }}</span>
                    </nav>
                    <h1 class="mt-2 text-2xl font-bold text-gray-900">
                        Facture {{ $facture->numero }}
                    </h1>
                    <p class="text-gray-600">{{ $facture->titre }}</p>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('chantiers.factures.show', [$facture->chantier, $facture]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"></path>
                        </svg>
                        Voir dans le chantier
                    </a>
                    
                    @if($facture->statut !== 'brouillon')
                        <a href="{{ route('chantiers.factures.pdf', [$facture->chantier, $facture]) }}" 
                           class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Télécharger PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations de la facture -->
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informations de la facture</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Numéro</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $facture->numero }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date d'émission</dt>
                                <dd class="mt-1 text-gray-900">{{ $facture->date_emission->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date d'échéance</dt>
                                <dd class="mt-1 text-gray-900">{{ $facture->date_echeance->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Commercial</dt>
                                <dd class="mt-1 text-gray-900">{{ $facture->commercial->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Montant TTC</dt>
                                <dd class="mt-1 text-lg font-bold text-green-600">{{ number_format($facture->montant_ttc, 2, ',', ' ') }} €</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Montant restant</dt>
                                <dd class="mt-1 text-lg font-bold text-blue-600">{{ number_format($facture->montant_restant, 2, ',', ' ') }} €</dd>
                            </div>
                        </div>
                        
                        @if($facture->description)
                            <div class="mt-6">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-2 text-gray-900">{{ $facture->description }}</dd>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Lignes de la facture -->
                @if($facture->lignes->count() > 0)
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Détail de la facture</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Désignation</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qté</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire HT</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total HT</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($facture->lignes as $ligne)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $ligne->designation }}</div>
                                            @if($ligne->description)
                                                <div class="text-sm text-gray-500">{{ $ligne->description }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($ligne->quantite, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($ligne->prix_unitaire_ht, 2) }} €</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ number_format($ligne->montant_ht, 2) }} €</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Informations client -->
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Client</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nom</dt>
                                <dd class="mt-1 text-gray-900">{{ $facture->chantier->client->name }}</dd>
                            </div>
                            @if($facture->chantier->client->email)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-gray-900">{{ $facture->chantier->client->email }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informations chantier -->
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-yellow-50 to-orange-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Chantier</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Titre</dt>
                                <dd class="mt-1 text-gray-900">{{ $facture->chantier->titre }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $facture->chantier->statut === 'en_cours' ? 'bg-green-100 text-green-800' : ($facture->chantier->statut === 'planifie' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $facture->chantier->statut)) }}
                                    </span>
                                </dd>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('chantiers.show', $facture->chantier) }}" 
                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-900">
                                Voir le chantier
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
