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
                    <div class="mt-2 flex items-center space-x-4 flex-wrap">
                        <h1 class="text-2xl font-bold text-gray-900">
                            Devis {{ $devis->numero }}
                        </h1>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $devis->getStatutBadgeClass() }}">
                            {{ $devis->getStatutTexte() }}
                        </span>
                        
                        {{-- 🆕 Indicateur de conformité électronique --}}
                        @if($devis->conforme_loi)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $devis->statut_conformite_badge }}">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Conforme Loi {{ $devis->numero_chronologique }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Non conforme
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-600">{{ $devis->titre }}</p>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center space-x-3 flex-wrap">
                    <!-- Bouton PDF -->
                    <a href="{{ route('chantiers.devis.pdf', [$chantier, $devis]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Télécharger PDF
                    </a>

                    {{-- 🆕 Boutons export électronique ou génération conformité --}}
                    @if($devis->conforme_loi)
                        <!-- Dropdown pour export électronique -->
                        <div class="relative inline-block text-left" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                                Export Électronique
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                <div class="py-1">
                                    <a href="{{ route('chantiers.devis.export-electronique', [$chantier, $devis, 'json']) }}" 
                                       class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 mr-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                        </svg>
                                        Export JSON
                                    </a>
                                    <a href="{{ route('chantiers.devis.export-electronique', [$chantier, $devis, 'xml']) }}" 
                                       class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 mr-3 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15.586 13H14a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Export XML
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <a href="{{ route('chantiers.devis.verifier-integrite', [$chantier, $devis]) }}" 
                                       class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Vérifier intégrité
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Bouton pour générer la conformité si pas encore fait -->
                        <form action="{{ route('chantiers.devis.generer-conformite', [$chantier, $devis]) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Rendre conforme
                            </button>
                        </form>
                    @endif

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
                        <!-- Autres boutons d'action existants -->
                        @if($devis->peutEtreModifie())
                            <a href="{{ route('chantiers.devis.edit', [$chantier, $devis]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Modifier
                            </a>
                        @endif

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

                {{-- 🆕 Section conformité électronique --}}
                @if($devis->conforme_loi)
                    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Conformité Électronique
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $devis->statut_conformite_badge }}">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Conforme
                                        </span>
                                    </dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Numéro chronologique</dt>
                                    <dd class="mt-1 text-sm font-mono text-gray-900">{{ $devis->numero_chronologique }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Format électronique</dt>
                                    <dd class="mt-1 text-sm text-gray-900 uppercase">{{ $devis->format_electronique }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Hash d'intégrité</dt>
                                    <dd class="mt-1 text-xs font-mono text-gray-600 break-all">{{ substr($devis->hash_integrite, 0, 32) }}...</dd>
                                </div>
                                
                                @if($devis->donnees_structurees)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Sections générées</dt>
                                        <dd class="mt-1">
                                            @php
                                                $sections = array_keys($devis->donnees_structurees);
                                            @endphp
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($sections as $section)
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ ucfirst(str_replace('_', ' ', $section)) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </dd>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

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