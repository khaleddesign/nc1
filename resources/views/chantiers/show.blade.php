@extends('layouts.app')

@section('title', $chantier->titre)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="breadcrumb mb-6">
            <a href="{{ route('chantiers.index') }}" class="breadcrumb-item">Chantiers</a>
            <span class="breadcrumb-separator">/</span>
            <span class="text-gray-900">{{ $chantier->titre }}</span>
        </nav>

        <!-- En-tête -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $chantier->titre }}</h1>
                        <div class="mt-2 flex items-center space-x-4">
                            <span class="{{ $chantier->getStatutBadgeClass() }}">
                                {{ $chantier->getStatutTexte() }}
                            </span>
                            @if ($chantier->isEnRetard())
                                <span class="badge badge-danger">En retard</span>
                            @endif
                            <span class="text-sm text-gray-500">
                                Créé le {{ $chantier->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>

                    <div class="flex space-x-2">
                        <!-- Bouton Message -->
                        <button onclick="openModal('modal-message')" class="btn-outline">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Envoyer un message
                        </button>

                        @can('update', $chantier)
                            <a href="{{ route('chantiers.edit', $chantier) }}" class="btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Modifier
                            </a>
                        @endcan

                    </div>
                </div>
            </div>

            <!-- Informations principales -->
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Client</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $chantier->client->name }}</p>
                        @if ($chantier->client->telephone)
                            <p class="text-xs text-gray-500">{{ $chantier->client->telephone }}</p>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Commercial</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $chantier->commercial->name }}</p>
                        @if ($chantier->commercial->telephone)
                            <p class="text-xs text-gray-500">{{ $chantier->commercial->telephone }}</p>
                        @endif
                    </div>

                    @if ($chantier->date_debut)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Dates</h3>
                            <p class="mt-1 text-sm text-gray-900">
                                Du {{ $chantier->date_debut->format('d/m/Y') }}
                                @if ($chantier->date_fin_prevue)
                                    <br>au <span
                                        class="{{ $chantier->getRetardClass() }}">{{ $chantier->date_fin_prevue->format('d/m/Y') }}</span>
                                @endif
                            </p>
                        </div>
                    @endif

                    @if ($chantier->budget)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Budget</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($chantier->budget, 0, ',', ' ') }} €</p>
                        </div>
                    @endif
                </div>

                @if ($chantier->description)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Description</h3>
                        <p class="text-sm text-gray-900">{{ $chantier->description }}</p>
                    </div>
                @endif

                <!-- Barre de progression -->
                <div class="mt-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span class="font-medium">Avancement global</span>
                        <span class="font-semibold">{{ number_format($chantier->avancement_global, 1) }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar {{ $chantier->getProgressBarColor() }}"
                            style="width: {{ $chantier->avancement_global }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION PHOTOS COMPACTE -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Photos du projet
                    <span
                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                        id="photos-count">{{ $chantier->photos_count }}</span>
                </h2>
                <button onclick="openModal('modal-upload-photos')" class="btn-sm btn-primary">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Ajouter
                </button>
            </div>

            <div class="px-6 py-4">
                <div id="galerie-photos">
                    @if ($chantier->hasPhotos())
                        <div class="flex space-x-2">
                            <!-- Photo principale (format 16:9) -->
                            <div class="w-full md:w-2/3 relative cursor-pointer" onclick="ouvrirLightbox(0)">
                                <img src="{{ asset($chantier->photo_couverture->thumbnail_url) }}" alt="Photo principale"
                                    class="w-full h-32 object-cover rounded-lg">
                            </div>

                            <!-- Mini-photos en grille 2x2 -->
                            <div class="w-full md:w-1/3 grid grid-cols-2 grid-rows-2 gap-2">
                                @foreach ($chantier->photos_recentes->skip(1)->take(3) as $index => $photo)
                                    <div class="relative cursor-pointer" onclick="ouvrirLightbox({{ $index + 1 }})">
                                        <img src="{{ asset($photo->thumbnail_url) }}" alt="Photo {{ $index + 2 }}"
                                            class="w-full h-12 object-cover rounded-lg">
                                    </div>
                                @endforeach

                                @if ($chantier->photos_count > 4)
                                    <div class="relative cursor-pointer bg-gray-800 h-12 rounded-lg flex items-center justify-center text-white font-medium"
                                        onclick="voirToutesPhotos()">
                                        +{{ $chantier->photos_count - 4 }}
                                    </div>
                                @elseif($chantier->photos_recentes->count() > 3)
                                    <div class="relative cursor-pointer" onclick="ouvrirLightbox(4)">
                                        <img src="{{ $chantier->photos_recentes[3]->thumbnail_url }}" alt="Photo 5"
                                            class="w-full h-12 object-cover rounded-lg">
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune photo</h3>
                            <p class="mt-1 text-sm text-gray-500">Commencez par ajouter des photos de ce chantier.</p>
                            <button onclick="openModal('modal-upload-photos')" class="mt-4 btn-sm btn-primary">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Ajouter des photos
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Étapes -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-medium text-gray-900">Étapes du projet</h2>
                            @can('update', $chantier)
                                <button onclick="openModal('modal-nouvelle-etape')" class="btn-sm btn-primary">
                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Ajouter
                                </button>
                            @endcan
                        </div>
                    </div>

                    <div class="px-6 py-4">
                        @forelse($chantier->etapes as $etape)
                            <div
                                class="etape-item-{{ $etape->terminee ? 'completed' : ($etape->pourcentage > 0 ? 'in-progress' : 'pending') }} mb-4 last:mb-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $etape->nom }}</h4>
                                        @if ($etape->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ $etape->description }}</p>
                                        @endif
                                        <div class="flex items-center mt-2 space-x-4 text-xs text-gray-500">
                                            @if ($etape->date_debut)
                                                <span>Début: {{ $etape->date_debut->format('d/m/Y') }}</span>
                                            @endif
                                            @if ($etape->date_fin_prevue)
                                                <span
                                                    class="{{ $etape->isEnRetard() ? 'text-red-600 font-semibold' : '' }}">
                                                    Fin prévue: {{ $etape->date_fin_prevue->format('d/m/Y') }}
                                                </span>
                                            @endif
                                            @if ($etape->date_fin_effective)
                                                <span class="text-green-600">Terminé le
                                                    {{ $etape->date_fin_effective->format('d/m/Y') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <div class="text-sm font-medium">{{ $etape->pourcentage }}%</div>
                                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-500 h-2 rounded-full transition-all duration-300"
                                                    style="width: {{ $etape->pourcentage }}%"></div>
                                            </div>
                                        </div>
                                        {{-- 
                                        @can('update', $chantier)
                                            <div class="flex space-x-1">
                                                <button class="text-gray-400 hover:text-gray-600" title="Modifier">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                <form method="POST"
                                                    action="{{ route('etapes.destroy', [$chantier, $etape]) }}"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-600"
                                                        title="Supprimer" onclick="return confirm('Supprimer cette étape ?')">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endcan --}}


                                        <!-- Actions -->
                                        @can('update', $chantier)
                                            <div class="flex-shrink-0 ml-6">
                                                <div class="flex items-center space-x-2">
                                                    <!-- Modification rapide de progression -->
                                                    <button class="btn-outline btn-sm"
                                                        onclick="modifierProgressionEtape({{ $etape->id }}, {{ $etape->pourcentage }})"
                                                        title="Modifier progression">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor">
                                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </svg>
                                                    </button>

                                                    <!-- Édition CORRIGÉE -->
                                                    <button class="btn-outline btn-sm" {{-- onclick="modifierEtape({{ json_encode([
                                                            'id' => $etape->id,
                                                            'nom' => $etape->nom,
                                                            'description' => $etape->description,
                                                            'ordre' => $etape->ordre,
                                                            'pourcentage' => $etape->pourcentage,
                                                            'date_debut' => $etape->date_debut?->format('Y-m-d'),
                                                            'date_fin_prevue' => $etape->date_fin_prevue?->format('Y-m-d'),
                                                            'date_fin_effective' => $etape->date_fin_effective?->format('Y-m-d'),
                                                            'notes' => $etape->notes,
                                                            'terminee' => $etape->terminee,
                                                        ]) }})" --}}
                                                        onclick="modifierEtape({{ $etape->toJson() }})"
                                                        title="Modifier l'étape">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                        </svg>
                                                    </button>

                                                    <!-- Suppression -->
                                                    <form method="POST"
                                                        action="{{ route('etapes.destroy', [$chantier, $etape]) }}"
                                                        class="inline"
                                                        onsubmit="return confirm('Supprimer cette étape : {{ $etape->nom }} ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn-outline btn-sm text-red-600 border-red-200 hover:bg-red-50"
                                                            title="Supprimer">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune étape</h3>
                                <p class="mt-1 text-sm text-gray-500">Commencez par ajouter une première étape à ce
                                    chantier.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Commentaires -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Commentaires</h2>
                    </div>

                    <div class="px-6 py-4">
                        <!-- Formulaire nouveau commentaire -->
                        <form method="POST" action="{{ route('commentaires.store', $chantier) }}" class="mb-6">
                            @csrf
                            <div>
                                <label for="contenu" class="sr-only">Votre commentaire</label>
                                <textarea name="contenu" id="contenu" rows="3" class="form-textarea"
                                    placeholder="Ajouter un commentaire..." required></textarea>
                            </div>
                            <div class="mt-3 flex justify-end">
                                <button type="submit" class="btn-primary">
                                    Publier
                                </button>
                            </div>
                        </form>

                        <!-- Liste des commentaires -->
                        <div class="space-y-4">
                            @forelse($chantier->commentaires as $commentaire)
                                <div class="flex space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ substr($commentaire->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $commentaire->user->name }}
                                            </h4>
                                            <span
                                                class="badge badge-{{ $commentaire->user->role === 'client' ? 'primary' : 'warning' }}">
                                                {{ ucfirst($commentaire->user->role) }}
                                            </span>
                                            <span
                                                class="text-xs text-gray-500">{{ $commentaire->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-700">{{ $commentaire->contenu }}</p>

                                        @if (Auth::id() === $commentaire->user_id || Auth::user()->isAdmin())
                                            <div class="mt-2">
                                                <form method="POST"
                                                    action="{{ route('commentaires.destroy', $commentaire) }}"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800"
                                                        onclick="return confirm('Supprimer ce commentaire ?')">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 italic">Aucun commentaire pour le moment.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Documents -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-medium text-gray-900">Documents</h2>
                            @can('update', $chantier)
                                <button onclick="openModal('modal-upload-document')" class="btn-sm btn-primary">
                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    Upload
                                </button>
                            @endcan
                        </div>
                    </div>

                    <div class="px-6 py-4">
                        @forelse($chantier->documents as $document)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            @if ($document->isImage())
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            @endif
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $document->nom_original }}</p>
                                        <p class="text-xs text-gray-500">{{ $document->getTailleFormatee() }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('documents.download', $document) }}"
                                        class="text-blue-600 hover:text-blue-800" title="Télécharger">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </a>
                                    @can('update', $chantier)
                                        <form method="POST" action="{{ route('documents.destroy', $document) }}"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600" title="Supprimer"
                                                onclick="return confirm('Supprimer ce document ?')">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Aucun document</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Informations complémentaires -->
                @if ($chantier->notes)
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Notes</h2>
                        </div>
                        <div class="px-6 py-4">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $chantier->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal - Nouvelle étape -->
    @can('update', $chantier)
        <div id="modal-nouvelle-etape" class="modal-overlay hidden"
            onclick="if(event.target === this) closeModal('modal-nouvelle-etape')">
            <div class="modal-content max-w-md">
                <div class="modal-header flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Nouvelle étape</h3>
                    <button onclick="closeModal('modal-nouvelle-etape')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('etapes.store', $chantier) }}">
                    @csrf
                    <div class="modal-body space-y-4">
                        <div>
                            <label for="nom" class="form-label">Nom de l'étape *</label>
                            <input type="text" name="nom" id="nom" class="form-input" required>
                        </div>

                        <div>
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-textarea"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="ordre" class="form-label">Ordre *</label>
                                <input type="number" name="ordre" id="ordre"
                                    value="{{ $chantier->etapes->count() + 1 }}" class="form-input" required min="1">
                            </div>

                            <div>
                                <label for="pourcentage" class="form-label">Avancement (%)</label>
                                <input type="number" name="pourcentage" id="pourcentage" value="0" class="form-input"
                                    min="0" max="100">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="date_debut" class="form-label">Date début</label>
                                <input type="date" name="date_debut" id="date_debut" class="form-input">
                            </div>

                            <div>
                                <label for="date_fin_prevue" class="form-label">Date fin prévue</label>
                                <input type="date" name="date_fin_prevue" id="date_fin_prevue" class="form-input">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" onclick="closeModal('modal-nouvelle-etape')"
                            class="btn-outline">Annuler</button>
                        <button type="submit" class="btn-primary">Créer l'étape</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal - Upload document -->
        <div id="modal-upload-document" class="modal-overlay hidden"
            onclick="if(event.target === this) closeModal('modal-upload-document')">
            <div class="modal-content max-w-md">
                <div class="modal-header flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Ajouter des documents</h3>
                    <button onclick="closeModal('modal-upload-document')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('documents.store', $chantier) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body space-y-4">
                        <div>
                            <label for="fichiers" class="form-label">Fichiers *</label>
                            <input type="file" name="fichiers[]" id="fichiers" class="form-input" multiple required
                                accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx">
                            <p class="text-xs text-gray-500 mt-1">
                                Formats acceptés : JPG, PNG, PDF, DOC, XLS (max 10MB par fichier)
                            </p>
                        </div>

                        <div>
                            <label for="type" class="form-label">Type de document</label>
                            <select name="type" id="type" class="form-select">
                                <option value="document">Document général</option>
                                <option value="image">Image/Photo</option>
                                <option value="plan">Plan</option>
                                <option value="facture">Facture</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div>
                            <label for="description_doc" class="form-label">Description</label>
                            <textarea name="description" id="description_doc" rows="3" class="form-textarea"
                                placeholder="Description optionnelle des documents..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" onclick="closeModal('modal-upload-document')"
                            class="btn-outline">Annuler</button>
                        <button type="submit" class="btn-primary">Uploader</button>
                    </div>
                </form>
            </div>
        </div>
    @endcan

    <!-- Modal - Upload photos -->
    <div id="modal-upload-photos" class="modal-overlay hidden"
        onclick="if(event.target === this) closeModal('modal-upload-photos')">
        <div class="modal-content max-w-lg">
            <div class="modal-header flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Ajouter des photos</h3>
                <button onclick="closeModal('modal-upload-photos')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="form-upload-photos" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="chantier_id" value="{{ $chantier->id }}">

                <div class="modal-body space-y-4">
                    <div>
                        <label for="photos" class="form-label">Photos *</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors"
                            id="drop-zone">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="photos"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Choisir des fichiers</span>
                                        <input id="photos" name="photos[]" type="file" multiple accept="image/*"
                                            class="sr-only">
                                    </label>
                                    <p class="pl-1">ou glisser-déposer</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 10MB par fichier</p>
                            </div>
                        </div>
                    </div>

                    <!-- Prévisualisation -->
                    <div id="preview-photos" class="hidden">
                        <label class="form-label">Aperçu des photos sélectionnées</label>
                        <div id="preview-grid" class="grid grid-cols-3 gap-2 mt-2"></div>
                    </div>

                    <!-- Barre de progression -->
                    <div id="upload-progress" class="hidden">
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                            <span>Upload en cours...</span>
                            <span id="progress-text">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="progress-bar" class="bg-blue-500 h-2 rounded-full transition-all duration-300"
                                style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeModal('modal-upload-photos')" class="btn-outline"
                        id="btn-cancel">Annuler</button>
                    <button type="submit" class="btn-primary" id="btn-upload" disabled>
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Uploader <span id="file-count">(0)</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal - Lightbox photos -->
    <div id="modal-lightbox" class="fixed inset-0 z-50 bg-black bg-opacity-90 hidden" onclick="fermerLightbox()">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
                <!-- Navigation -->
                <button onclick="photoPrecedente()"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button onclick="photoSuivante()"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Fermer -->
                <button onclick="fermerLightbox()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Image -->
                <img id="lightbox-image" src="" alt="" class="max-w-full max-h-screen object-contain">

                <!-- Informations -->
                <div class="absolute bottom-4 left-4 right-4 text-white">
                    <div class="bg-black bg-opacity-50 rounded-lg p-4">
                        <h3 id="lightbox-title" class="font-medium"></h3>
                        <p id="lightbox-info" class="text-sm text-gray-300 mt-1"></p>
                        <div class="flex items-center justify-between mt-2">
                            <span id="lightbox-counter" class="text-sm"></span>
                            <div class="flex space-x-2">
                                <button onclick="telechargerPhoto()" class="text-white hover:text-gray-300">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </button>
                                @can('update', $chantier)
                                    <button onclick="supprimerPhoto()" class="text-red-400 hover:text-red-300">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - Toutes les photos -->
    <div id="modal-toutes-photos" class="modal-overlay hidden"
        onclick="if(event.target === this) closeModal('modal-toutes-photos')">
        <div class="modal-content max-w-4xl">
            <div class="modal-header flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Toutes les photos du chantier</h3>
                <button onclick="closeModal('modal-toutes-photos')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="modal-body">
                <div id="toutes-photos-grid" class="grid grid-cols-3 md:grid-cols-4 gap-3">
                    <!-- Chargé dynamiquement -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - Message -->
    <div id="modal-message" class="modal-overlay hidden" onclick="if(event.target === this) closeModal('modal-message')">
        <div class="modal-content max-w-lg">
            <div class="modal-header flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Envoyer un message</h3>
                <button onclick="closeModal('modal-message')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('messages.store') }}">
                @csrf
                <input type="hidden" name="chantier_id" value="{{ $chantier->id }}">
                <input type="hidden" name="recipient_id"
                    value="{{ Auth::user()->isClient() ? $chantier->commercial->id : $chantier->client->id }}">

                <div class="modal-body space-y-4">
                    <div>
                        <label class="form-label">Destinataire</label>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ substr(Auth::user()->isClient() ? $chantier->commercial->name : $chantier->client->name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ Auth::user()->isClient() ? $chantier->commercial->name : $chantier->client->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ Auth::user()->isClient() ? 'Commercial' : 'Client' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="form-label">Sujet *</label>
                        <input type="text" name="subject" id="subject" class="form-input"
                            value="À propos du chantier : {{ $chantier->titre }}" required>
                    </div>

                    <div>
                        <label for="body" class="form-label">Message *</label>
                        <textarea name="body" id="body" rows="6" class="form-textarea" placeholder="Votre message..."
                            required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeModal('modal-message')" class="btn-outline">Annuler</button>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal - Modifier Étape -->
    <div id="modal-modifier-etape" class="modal-overlay hidden"
        onclick="if(event.target === this) closeModal('modal-modifier-etape')">
        <div class="modal-content max-w-2xl">
            <div class="modal-header flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Modifier l'étape</h3>
                <button onclick="closeModal('modal-modifier-etape')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" id="form-modifier-etape">
                @csrf
                @method('PUT')
                <div class="modal-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_nom" class="form-label">Nom de l'étape *</label>
                            <input type="text" name="nom" id="edit_nom" class="form-input" required>
                        </div>
                        <div>
                            <label for="edit_ordre" class="form-label">Ordre *</label>
                            <input type="number" name="ordre" id="edit_ordre" class="form-input" required
                                min="1">
                        </div>
                    </div>

                    <div>
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea name="description" id="edit_description" rows="3" class="form-textarea"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="edit_date_debut" class="form-label">Date début</label>
                            <input type="date" name="date_debut" id="edit_date_debut" class="form-input">
                        </div>
                        <div>
                            <label for="edit_date_fin_prevue" class="form-label">Date fin prévue</label>
                            <input type="date" name="date_fin_prevue" id="edit_date_fin_prevue" class="form-input">
                        </div>
                        <div>
                            <label for="edit_date_fin_effective" class="form-label">Date fin effective</label>
                            <input type="date" name="date_fin_effective" id="edit_date_fin_effective"
                                class="form-input">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_pourcentage" class="form-label">Pourcentage d'avancement</label>
                            <input type="range" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                name="pourcentage" id="edit_pourcentage" min="0" max="100" step="5"
                                oninput="document.getElementById('edit_pourcentage_value').textContent = this.value + '%'">
                            <div class="text-center mt-2">
                                <span id="edit_pourcentage_value"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">0%</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="flex items-center h-5">
                                <input class="form-checkbox" type="checkbox" name="terminee" id="edit_terminee"
                                    value="1">
                            </div>
                            <div class="ml-3">
                                <label for="edit_terminee" class="font-medium text-gray-700">
                                    Marquer comme terminée
                                </label>
                                <p class="text-gray-500 text-sm">Cela mettra automatiquement la progression à 100%</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="edit_notes" class="form-label">Notes</label>
                        <textarea name="notes" id="edit_notes" rows="2" class="form-textarea"
                            placeholder="Notes additionnelles..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeModal('modal-modifier-etape')"
                        class="btn-outline">Annuler</button>
                    <button type="submit" class="btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal - Progression Rapide -->
    <div id="modal-progression-etape" class="modal-overlay hidden"
        onclick="if(event.target === this) closeModal('modal-progression-etape')">
        <div class="modal-content max-w-md">
            <div class="modal-header flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Modifier l'avancement</h3>
                <button onclick="closeModal('modal-progression-etape')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" id="form-progression-etape">
                @csrf
                @method('PUT')
                <div class="modal-body space-y-4">
                    <div>
                        <label for="quick_pourcentage" class="form-label">Pourcentage d'avancement</label>
                        <input type="range" class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                            name="pourcentage" id="quick_pourcentage" min="0" max="100" step="5"
                            oninput="document.getElementById('quick_pourcentage_value').textContent = this.value + '%'">
                        <div class="text-center mt-3">
                            <span id="quick_pourcentage_value"
                                class="inline-flex items-center px-4 py-2 rounded-full text-lg font-bold bg-blue-100 text-blue-800">0%</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-5 gap-2">
                        <button type="button" class="btn-outline btn-sm" onclick="setProgression(0)">0%</button>
                        <button type="button" class="btn-outline btn-sm" onclick="setProgression(25)">25%</button>
                        <button type="button" class="btn-outline btn-sm" onclick="setProgression(50)">50%</button>
                        <button type="button" class="btn-outline btn-sm" onclick="setProgression(75)">75%</button>
                        <button type="button"
                            class="btn-outline btn-sm bg-green-50 border-green-200 text-green-700 hover:bg-green-100"
                            onclick="setProgression(100)">100%</button>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeModal('modal-progression-etape')"
                        class="btn-outline">Annuler</button>
                    <button type="submit" class="btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Variables globales pour les étapes
            let etapeEnCours = null;

            // Fonction pour modifier une étape
            function modifierEtape(etapeData) {
                console.log('Modification étape:', etapeData);
                etapeEnCours = etapeData;

                const form = document.getElementById('form-modifier-etape');
                form.action = "/chantiers/{{ $chantier->id }}/etapes/" + etapeData.id;

                // Remplir les champs
                document.getElementById('edit_nom').value = etapeData.nom || '';
                document.getElementById('edit_ordre').value = etapeData.ordre || '';
                document.getElementById('edit_description').value = etapeData.description || '';
                document.getElementById('edit_date_debut').value = etapeData.date_debut || '';
                document.getElementById('edit_date_fin_prevue').value = etapeData.date_fin_prevue || '';
                document.getElementById('edit_date_fin_effective').value = etapeData.date_fin_effective || '';
                document.getElementById('edit_pourcentage').value = etapeData.pourcentage || 0;
                document.getElementById('edit_terminee').checked = etapeData.terminee || false;
                document.getElementById('edit_notes').value = etapeData.notes || '';

                // Mettre à jour l'affichage du pourcentage
                document.getElementById('edit_pourcentage_value').textContent = (etapeData.pourcentage || 0) + '%';

                openModal('modal-modifier-etape');
            }

            // Fonction pour modifier rapidement la progression
            function modifierProgressionEtape(etapeId, currentProgress) {
                etapeEnCours = {
                    id: etapeId
                };

                const form = document.getElementById('form-progression-etape');
                form.action = "/chantiers/{{ $chantier->id }}/etapes/" + etapeId + "/progress";

                document.getElementById('quick_pourcentage').value = currentProgress;
                document.getElementById('quick_pourcentage_value').textContent = currentProgress + '%';

                openModal('modal-progression-etape');
            }

            // Fonction pour définir un pourcentage rapide
            function setProgression(value) {
                document.getElementById('quick_pourcentage').value = value;
                document.getElementById('quick_pourcentage_value').textContent = value + '%';
            }

            // Événement pour checkbox "terminée"
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('edit_terminee')?.addEventListener('change', function() {
                    if (this.checked) {
                        document.getElementById('edit_pourcentage').value = 100;
                        document.getElementById('edit_pourcentage_value').textContent = '100%';
                    }
                });
            });

            console.log('JavaScript étapes chargé avec succès');
        </script>

        <script>
            // Variables globales pour la galerie
            let photosData = [];
            let currentPhotoIndex = 0;

            // Gestion des modales existantes
            window.openModal = function(modalId) {
                document.getElementById(modalId).classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };

            window.closeModal = function(modalId) {
                document.getElementById(modalId).classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            // Initialisation
            document.addEventListener('DOMContentLoaded', function() {
                initializePhotoGallery();
                initializeExistingModals();
            });

            function initializeExistingModals() {
                // Fermer modal avec Escape
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        const modals = document.querySelectorAll('.modal-overlay:not(.hidden)');
                        modals.forEach(modal => {
                            modal.classList.add('hidden');
                        });
                        document.body.classList.remove('overflow-hidden');

                        // Fermer aussi le lightbox
                        const lightbox = document.getElementById('modal-lightbox');
                        if (lightbox && !lightbox.classList.contains('hidden')) {
                            fermerLightbox();
                        }
                    }
                });

                // Auto-focus sur le premier champ des modales
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === 'class') {
                            const modal = mutation.target;
                            if (!modal.classList.contains('hidden')) {
                                const firstInput = modal.querySelector(
                                    'input:not([type="hidden"]), textarea, select');
                                if (firstInput) {
                                    setTimeout(() => firstInput.focus(), 100);
                                }
                            }
                        }
                    });
                });

                document.querySelectorAll('.modal-overlay').forEach(modal => {
                    observer.observe(modal, {
                        attributes: true
                    });
                });

                // Validation fichiers documents
                const fichiersInput = document.getElementById('fichiers');
                if (fichiersInput) {
                    fichiersInput.addEventListener('change', function(e) {
                        const files = Array.from(e.target.files);
                        const maxSize = 10 * 1024 * 1024; // 10MB
                        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ];

                        let hasError = false;
                        const errors = [];

                        files.forEach(file => {
                            if (file.size > maxSize) {
                                errors.push(`${file.name} dépasse la taille maximale de 10MB`);
                                hasError = true;
                            }

                            if (!allowedTypes.includes(file.type)) {
                                errors.push(`${file.name} n'est pas un format accepté`);
                                hasError = true;
                            }
                        });

                        if (hasError) {
                            alert('Erreurs détectées:\n' + errors.join('\n'));
                            e.target.value = '';
                        }
                    });
                }
            }

            // Galerie photos
            function initializePhotoGallery() {
                const photoInput = document.getElementById('photos');
                const dropZone = document.getElementById('drop-zone');
                const uploadForm = document.getElementById('form-upload-photos');

                if (!photoInput || !dropZone || !uploadForm) return;

                // Gestion drag & drop
                dropZone.addEventListener('dragover', handleDragOver);
                dropZone.addEventListener('dragleave', handleDragLeave);
                dropZone.addEventListener('drop', handleDrop);

                // Gestion sélection fichiers
                photoInput.addEventListener('change', handleFileSelect);

                // Gestion upload
                uploadForm.addEventListener('submit', handleUpload);

                // Charger les photos existantes
                chargerPhotos();
            }

            function handleDragOver(e) {
                e.preventDefault();
                e.stopPropagation();
                e.currentTarget.classList.add('border-blue-400', 'bg-blue-50');
            }

            function handleDragLeave(e) {
                e.preventDefault();
                e.stopPropagation();
                e.currentTarget.classList.remove('border-blue-400', 'bg-blue-50');
            }

            function handleDrop(e) {
                e.preventDefault();
                e.stopPropagation();
                e.currentTarget.classList.remove('border-blue-400', 'bg-blue-50');

                const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
                if (files.length > 0) {
                    const photoInput = document.getElementById('photos');
                    photoInput.files = createFileList(files);
                    previewFiles(files);
                }
            }

            function handleFileSelect(e) {
                const files = Array.from(e.target.files);
                previewFiles(files);
            }

            function createFileList(files) {
                const dt = new DataTransfer();
                files.forEach(file => dt.items.add(file));
                return dt.files;
            }

            function previewFiles(files) {
                const preview = document.getElementById('preview-photos');
                const grid = document.getElementById('preview-grid');
                const uploadBtn = document.getElementById('btn-upload');
                const fileCount = document.getElementById('file-count');

                if (files.length === 0) {
                    preview.classList.add('hidden');
                    uploadBtn.disabled = true;
                    return;
                }

                preview.classList.remove('hidden');
                uploadBtn.disabled = false;
                fileCount.textContent = `(${files.length})`;

                grid.innerHTML = '';

                files.forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'relative group';
                            div.innerHTML = `
                    <img src="${e.target.result}" alt="${file.name}" class="w-full h-20 object-cover rounded-lg">
                    <button type="button" onclick="supprimerPreview(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">×</button>
                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b-lg truncate">${file.name}</div>
                `;
                            grid.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            function supprimerPreview(index) {
                const photoInput = document.getElementById('photos');
                const files = Array.from(photoInput.files);
                files.splice(index, 1);

                const dt = new DataTransfer();
                files.forEach(file => dt.items.add(file));
                photoInput.files = dt.files;

                previewFiles(files);
            }

            async function handleUpload(e) {
                e.preventDefault();

                const formData = new FormData();
                const photoInput = document.getElementById('photos');
                const chantierId = document.querySelector('input[name="chantier_id"]').value;

                if (photoInput.files.length === 0) return;

                Array.from(photoInput.files).forEach(file => {
                    formData.append('photos[]', file);
                });
                formData.append('chantier_id', chantierId);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                const progressContainer = document.getElementById('upload-progress');
                const progressBar = document.getElementById('progress-bar');
                const progressText = document.getElementById('progress-text');
                const uploadBtn = document.getElementById('btn-upload');
                const cancelBtn = document.getElementById('btn-cancel');

                progressContainer.classList.remove('hidden');
                uploadBtn.disabled = true;
                cancelBtn.disabled = true;

                try {
                    const xhr = new XMLHttpRequest();

                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            const percentComplete = (e.loaded / e.total) * 100;
                            progressBar.style.width = percentComplete + '%';
                            progressText.textContent = Math.round(percentComplete) + '%';
                        }
                    });

                    xhr.addEventListener('load', function() {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                showToast('Photos uploadées avec succès !', 'success');
                                closeModal('modal-upload-photos');
                                chargerPhotos();
                                resetUploadForm();
                            } else {
                                showToast('Erreur: ' + response.message, 'error');
                            }
                        } else {
                            showToast('Erreur lors de l\'upload', 'error');
                        }

                        progressContainer.classList.add('hidden');
                        uploadBtn.disabled = false;
                        cancelBtn.disabled = false;
                    });

                    xhr.addEventListener('error', function() {
                        showToast('Erreur réseau lors de l\'upload', 'error');
                        progressContainer.classList.add('hidden');
                        uploadBtn.disabled = false;
                        cancelBtn.disabled = false;
                    });

                    xhr.open('POST', '/api/v2/photos/upload');
                    xhr.send(formData);

                } catch (error) {
                    console.error('Erreur upload:', error);
                    showToast('Erreur lors de l\'upload', 'error');
                }
            }

            function resetUploadForm() {
                document.getElementById('photos').value = '';
                document.getElementById('preview-photos').classList.add('hidden');
                document.getElementById('preview-grid').innerHTML = '';
                document.getElementById('btn-upload').disabled = true;
                document.getElementById('file-count').textContent = '(0)';
                document.getElementById('upload-progress').classList.add('hidden');
                document.getElementById('progress-bar').style.width = '0%';
                document.getElementById('progress-text').textContent = '0%';
            }

            async function chargerPhotos() {
                const chantierId = document.querySelector('input[name="chantier_id"]')?.value;
                if (!chantierId) return;

                try {
                    const response = await fetch(`/api/v2/chantiers/${chantierId}/photos`);
                    const data = await response.json();

                    if (data.success) {
                        photosData = data.photos;
                        mettreAJourGalerie();
                    }
                } catch (error) {
                    console.error('Erreur chargement photos:', error);
                }
            }

            function mettreAJourGalerie() {
                const galerieContainer = document.getElementById('galerie-photos');
                const photosCount = document.getElementById('photos-count');

                if (!galerieContainer) return;

                photosCount.textContent = photosData.length;

                if (photosData.length === 0) {
                    galerieContainer.innerHTML = `
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune photo</h3>
                <p class="mt-1 text-sm text-gray-500">Commencez par ajouter des photos de ce chantier.</p>
                <button onclick="openModal('modal-upload-photos')" class="mt-4 btn-sm btn-primary">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Ajouter des photos
                </button>
            </div>
        `;
                    return;
                }

                let html = `
        <div class="flex space-x-2">
            <!-- Photo principale (format 16:9) -->
            <div class="w-full md:w-2/3 relative cursor-pointer" onclick="ouvrirLightbox(0)">
                <img src="${photosData[0].thumbnail}" 
                     alt="${photosData[0].nom}" 
                     class="w-full h-32 object-cover rounded-lg">
            </div>
            
            <!-- Mini-photos en grille 2x2 -->
            <div class="w-full md:w-1/3 grid grid-cols-2 grid-rows-2 gap-2">
    `;

                // Ajouter les 3 miniatures suivantes
                for (let i = 1; i < Math.min(4, photosData.length); i++) {
                    if (i === 3 && photosData.length > 4) {
                        // Case "+X" pour indiquer qu'il y a plus de photos
                        html += `
                <div class="relative cursor-pointer bg-gray-800 h-12 rounded-lg flex items-center justify-center text-white font-medium"
                     onclick="voirToutesPhotos()">
                    +${photosData.length - 3}
                </div>
            `;
                    } else {
                        html += `
                <div class="relative cursor-pointer" onclick="ouvrirLightbox(${i})">
                    <img src="${photosData[i].thumbnail}" 
                         alt="${photosData[i].nom}" 
                         class="w-full h-12 object-cover rounded-lg">
                </div>
            `;
                    }
                }

                html += `
            </div>
        </div>
    `;

                galerieContainer.innerHTML = html;
            }

            function ouvrirLightbox(index) {
                currentPhotoIndex = index;
                const modal = document.getElementById('modal-lightbox');
                const img = document.getElementById('lightbox-image');
                const title = document.getElementById('lightbox-title');
                const info = document.getElementById('lightbox-info');
                const counter = document.getElementById('lightbox-counter');

                const photo = photosData[index];

                img.src = photo.url;
                img.alt = photo.nom;
                title.textContent = photo.nom;
                info.textContent = `Ajouté le ${photo.date}`;
                counter.textContent = `${index + 1} / ${photosData.length}`;

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function fermerLightbox() {
                document.getElementById('modal-lightbox').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function photoPrecedente() {
                if (currentPhotoIndex > 0) {
                    ouvrirLightbox(currentPhotoIndex - 1);
                }
            }

            function photoSuivante() {
                if (currentPhotoIndex < photosData.length - 1) {
                    ouvrirLightbox(currentPhotoIndex + 1);
                }
            }

            function telechargerPhoto() {
                const photo = photosData[currentPhotoIndex];
                const link = document.createElement('a');
                link.href = `/api/v2/photos/${photo.id}/download`;
                link.download = photo.nom;
                link.click();
            }

            async function supprimerPhoto() {
                if (!confirm('Êtes-vous sûr de vouloir supprimer cette photo ?')) return;

                const photo = photosData[currentPhotoIndex];

                try {
                    const response = await fetch(`/api/v2/photos/${photo.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        showToast('Photo supprimée avec succès', 'success');
                        fermerLightbox();
                        chargerPhotos();
                    } else {
                        showToast('Erreur lors de la suppression', 'error');
                    }
                } catch (error) {
                    console.error('Erreur suppression:', error);
                    showToast('Erreur lors de la suppression', 'error');
                }
            }

            function voirToutesPhotos() {
                const modal = document.getElementById('modal-toutes-photos');
                const grid = document.getElementById('toutes-photos-grid');

                let html = '';
                photosData.forEach((photo, index) => {
                    html += `
            <div class="relative group cursor-pointer" onclick="ouvrirLightbox(${index}); closeModal('modal-toutes-photos');">
                <img src="${photo.thumbnail}" alt="${photo.nom}" class="w-full h-24 object-cover rounded-lg hover:opacity-75 transition-opacity">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-opacity rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                    </svg>
                </div>
                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 text-white text-xs p-1 rounded-b-lg">
                    <p class="truncate">${photo.nom}</p>
                </div>
            </div>
        `;
                });

                grid.innerHTML = html;
                openModal('modal-toutes-photos');
            }

            // Navigation clavier pour lightbox
            document.addEventListener('keydown', function(e) {
                const lightbox = document.getElementById('modal-lightbox');
                if (!lightbox.classList.contains('hidden')) {
                    switch (e.key) {
                        case 'Escape':
                            fermerLightbox();
                            break;
                        case 'ArrowLeft':
                            photoPrecedente();
                            break;
                        case 'ArrowRight':
                            photoSuivante();
                            break;
                    }
                }
            });

            function showToast(message, type = 'info') {
                const toastContainer = document.getElementById('toast-container') || (() => {
                    const container = document.createElement('div');
                    container.id = 'toast-container';
                    container.className = 'fixed top-4 right-4 z-50 space-y-2';
                    document.body.appendChild(container);
                    return container;
                })();

                const toast = document.createElement('div');
                const bgColor = {
                    'success': 'bg-green-100 border-green-400 text-green-700',
                    'error': 'bg-red-100 border-red-400 text-red-700',
                    'warning': 'bg-yellow-100 border-yellow-400 text-yellow-700',
                    'info': 'bg-blue-100 border-blue-400 text-blue-700'
                } [type] || 'bg-gray-100 border-gray-400 text-gray-700';

                toast.className =
                    `max-w-sm w-full ${bgColor} border-l-4 p-4 shadow-lg rounded-md transform transition-all duration-300 translate-x-full`;
                toast.innerHTML = `
        <div class="flex justify-between items-center">
            <p class="text-sm font-medium">${message}</p>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-current hover:opacity-75">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    `;

                toastContainer.appendChild(toast);
                setTimeout(() => toast.classList.remove('translate-x-full'), 100);
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }

            console.log('Chantier show avec galerie photos chargé avec succès');
        </script>
    @endpush
@endsection
