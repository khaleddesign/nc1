@extends('layouts.app')

@section('title', 'Devis ' . $devis->numero)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <nav class="flex items-center space-x-2 text-sm text-gray-500">
                        <a href="{{ route('chantiers.index') }}" class="hover:text-gray-700">Chantiers</a>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <a href="{{ route('chantiers.show', $chantier) }}" class="hover:text-gray-700">{{ $chantier->titre }}</a>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <a href="{{ route('chantiers.devis.index', $chantier) }}" class="hover:text-gray-700">Devis</a>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="text-gray-900 font-medium">{{ $devis->numero }}</span>
                    </nav>
                    <div class="mt-2 flex items-center space-x-4">
                        <h1 class="text-2xl font-bold text-gray-900">
                            Devis {{ $devis->numero }}
                        </h1>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $devis->statut_badge_class }}">
                            {{ $devis->statut_texte }}
                        </span>
                    </div>
                    <p class="text-gray-600">{{ $devis->titre }}</p>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center space-x-3">
                    <!-- Bouton PDF -->
                    <a href="{{ route('chantiers.devis.pdf', [$chantier, $devis]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Télécharger PDF
                    </a>

                    <!-- Bouton Prévisualiser -->
                    <a href="{{ route('chantiers.devis.preview', [$chantier, $devis]) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Prévisualiser
                    </a>

                    @can('update', $chantier)
                        <!-- Bouton Modifier (si peut être modifié) -->
                        @if($devis->peutEtreModifie())
                            <a href="{{ route('chantiers.devis.edit', [$chantier, $devis]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Modifier
                            </a>
                        @endif

                        <!-- Bouton Envoyer (si brouillon) -->
                        @if($devis->statut === 'brouillon')
                            <form action="{{ route('chantiers.devis.envoyer', [$chantier, $devis]) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('Envoyer ce devis au client ?')"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Envoyer
                                </button>
                            </form>
                        @endif

                        <!-- Bouton Convertir en facture (si accepté) -->
                        @if($devis->peutEtreConverti())
                            <form action="{{ route('chantiers.devis.convertir-facture', [$chantier, $devis]) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('Convertir ce devis en facture ?')"
                                        class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Convertir en facture
                                </button>
                            </form>
                        @endif

                        <!-- Bouton Dupliquer -->
                        <form action="{{ route('chantiers.devis.dupliquer', [$chantier, $devis]) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Créer une copie de ce devis ?')"
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Dupliquer
                            </button>
                        </form>
                    @endcan

                    @if(Auth::user()->isClient() && Auth::id() === $chantier->client_id)
                        <!-- Actions côté client -->
                        @if($devis->peutEtreAccepte())
                            <div class="flex space-x-2">
                                <form action="{{ route('chantiers.devis.accepter', [$chantier, $devis]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Accepter ce devis ?')"
                                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Accepter
                                    </button>
                                </form>

                                <form action="{{ route('chantiers.devis.refuser', [$chantier, $devis]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Refuser ce devis ?')"
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Refuser
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations du devis -->
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informations du devis</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Numéro</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $devis->numero }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date d'émission</dt>
                                <dd class="mt-1 text-gray-900">{{ $devis->date_emission->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de validité</dt>
                                <dd class="mt-1 text-gray-900">{{ $devis->date_validite->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Commercial</dt>
                                <dd class="mt-1 text-gray-900">{{ $devis->commercial->name }}</dd>
                            </div>
                            @if($devis->date_envoi)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date d'envoi</dt>
                                    <dd class="mt-1 text-gray-900">{{ $devis->date_envoi->format('d/m/Y H:i') }}</dd>
                                </div>
                            @endif
                            @if($devis->delai_realisation)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Délai de réalisation</dt>
                                    <dd class="mt-1 text-gray-900">{{ $devis->delai_realisation }} jours</dd>
                                </div>
                            @endif
                        </div>
                        
                        @if($devis->description)
                            <div class="mt-6">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-2 text-gray-900">{{ $devis->description }}</dd>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Lignes du devis -->
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Détail du devis</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Désignation</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qté</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unité</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire HT</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TVA</th>
                                    @if($devis->lignes->where('remise_pourcentage', '>', 0)->count() > 0)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remise</th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total HT</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($devis->lignes as $ligne)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $ligne->designation }}</div>
                                            @if($ligne->description)
                                                <div class="text-sm text-gray-500">{{ $ligne->description }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($ligne->quantite, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $ligne->unite }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($ligne->prix_unitaire_ht, 2) }} €</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($ligne->taux_tva, 1) }}%</td>
                                        @if($devis->lignes->where('remise_pourcentage', '>', 0)->count() > 0)
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                @if($ligne->remise_pourcentage > 0)
                                                    {{ number_format($ligne->remise_pourcentage, 1) }}%
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ number_format($ligne->montant_ht, 2) }} €</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="{{ $devis->lignes->where('remise_pourcentage', '>', 0)->count() > 0 ? 6 : 5 }}" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                        Total HT :
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ number_format($devis->montant_ht, 2) }} €</td>
                                </tr>
                                <tr>
                                    <td colspan="{{ $devis->lignes->where('remise_pourcentage', '>', 0)->count() > 0 ? 6 : 5 }}" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                        TVA :
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ number_format($devis->montant_tva, 2) }} €</td>
                                </tr>
                                <tr>
                                    <td colspan="{{ $devis->lignes->where('remise_pourcentage', '>', 0)->count() > 0 ? 6 : 5 }}" class="px-6 py-4 text-right text-lg font-bold text-gray-900">
                                        Total TTC :
                                    </td>
                                    <td class="px-6 py-4 text-lg font-bold text-blue-600">{{ number_format($devis->montant_ttc, 2) }} €</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Conditions et modalités -->
                @if($devis->modalites_paiement || $devis->conditions_generales)
                    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Conditions et modalités</h3>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            @if($devis->modalites_paiement)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Modalités de paiement</dt>
                                    <dd class="mt-1 text-gray-900">{{ $devis->modalites_paiement }}</dd>
                                </div>
                            @endif
                            
                            @if($devis->conditions_generales)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Conditions générales</dt>
                                    <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $devis->conditions_generales }}</dd>
                                </div>
                            @endif
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
                                <dd class="mt-1 text-gray-900">{{ $devis->client_nom }}</dd>
                            </div>
                            @if(isset($devis->client_info['email']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-gray-900">{{ $devis->client_info['email'] }}</dd>
                                </div>
                            @endif
                            @if(isset($devis->client_info['telephone']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                                    <dd class="mt-1 text-gray-900">{{ $devis->client_info['telephone'] }}</dd>
                                </div>
                            @endif
                            @if(isset($devis->client_info['adresse']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                                    <dd class="mt-1 text-gray-900">{{ $devis->client_info['adresse'] }}</dd>
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
                                <dd class="mt-1 text-gray-900">{{ $chantier->titre }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $chantier->statut === 'en_cours' ? 'bg-green-100 text-green-800' : ($chantier->statut === 'planifie' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $chantier->statut)) }}
                                    </span>
                                </dd>
                            </div>
                            @if($chantier->date_debut)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date de début</dt>
                                    <dd class="mt-1 text-gray-900">{{ $chantier->date_debut->format('d/m/Y') }}</dd>
                                </div>
                            @endif
                            @if($chantier->budget)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Budget</dt>
                                    <dd class="mt-1 text-gray-900">{{ number_format($chantier->budget, 2) }} €</dd>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('chantiers.show', $chantier) }}" 
                               class="inline-flex items-center text-sm text-blue-600 hover:text-blue-900">
                                Voir le chantier
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                @if($devis->facture_id)
                    <!-- Facture liée -->
                    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Facture générée</h3>
                        </div>
                        
                        <div class="p-6">
                            <p class="text-sm text-gray-600">Ce devis a été converti en facture.</p>
                            <div class="mt-4">
                                <a href="{{ route('chantiers.factures.show', [$chantier, $devis->facture]) }}" 
                                   class="inline-flex items-center text-sm text-green-600 hover:text-green-900">
                                    Voir la facture
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                @can('update', $chantier)
                    @if($devis->notes_internes)
                        <!-- Notes internes -->
                        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-slate-50 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Notes internes</h3>
                            </div>
                            
                            <div class="p-6">
                                <p class="text-sm text-gray-700">{{ $devis->notes_internes }}</p>
                            </div>
                        </div>
                    @endif
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection