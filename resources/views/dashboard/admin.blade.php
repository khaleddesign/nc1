@extends('layouts.app')

@section('title', 'Dashboard Administrateur')

@section('content')
<div class="min-h-full">
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        Dashboard Administrateur
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Bonjour {{ Auth::user()->name }} ! Vue d'ensemble de tous les chantiers
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <a href="{{ route('chantiers.create') }}" class="btn btn-primary">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Nouveau Chantier
                    </a>
                </div>
            </div>

            <!-- Stats -->
            <div class="mt-8">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Total Chantiers -->
                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-primary-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18M6.75 9h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-3.75-16.5h3.75A2.25 2.25 0 0121 6.75v12a2.25 2.25 0 01-2.25 2.25h-3.75M16.5 7.5V21a.75.75 0 01-.75.75M6 7.5V21a.75.75 0 01-.75.75" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Chantiers</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $stats['total_chantiers'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- En Cours -->
                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-warning-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">En Cours</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $stats['chantiers_en_cours'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terminés -->
                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-success-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Terminés</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $stats['chantiers_termines'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Avancement Moyen -->
                    <div class="card">
                        <div class="card-body">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-info-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Avancement Moyen</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['avancement_moyen'], 1) }}%</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Chantiers récents -->
                <div class="lg:col-span-2">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Chantiers Récents
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            @if($chantiers_recents->count() > 0)
                                <div class="overflow-hidden">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Chantier</th>
                                                <th>Client</th>
                                                <th>Commercial</th>
                                                <th>Statut</th>
                                                <th>Avancement</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach($chantiers_recents as $chantier)
                                            <tr class="hover:bg-gray-50">
                                                <td>
                                                    <div class="font-medium text-gray-900">{{ $chantier->titre }}</div>
                                                    @if($chantier->description)
                                                        <div class="text-sm text-gray-500">{{ Str::limit($chantier->description, 50) }}</div>
                                                    @endif
                                                </td>
                                                <td class="text-sm text-gray-900">{{ $chantier->client->name }}</td>
                                                <td class="text-sm text-gray-900">{{ $chantier->commercial->name }}</td>
                                                <td>
                                                    @php
                                                        $badgeClass = match($chantier->statut) {
                                                            'planifie' => 'badge-secondary',
                                                            'en_cours' => 'badge-warning',
                                                            'termine' => 'badge-success',
                                                            default => 'badge-secondary'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">
                                                        {{ $chantier->getStatutTexte() }}
                                                    </span>
                                                    @if($chantier->isEnRetard())
                                                        <div class="text-xs text-danger-600 mt-1">
                                                            <svg class="inline h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                            </svg>
                                                            En retard
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                                        <div class="bg-primary-600 h-2 rounded-full transition-all duration-300" 
                                                             style="width: {{ $chantier->avancement_global }}%"></div>
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">{{ number_format($chantier->avancement_global, 0) }}%</div>
                                                </td>
                                                <td>
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('chantiers.show', $chantier) }}" 
                                                           class="text-primary-600 hover:text-primary-900">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </a>
                                                        <a href="{{ route('chantiers.edit', $chantier) }}" 
                                                           class="text-gray-600 hover:text-gray-900">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18M6.75 9h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-3.75-16.5h3.75A2.25 2.25 0 0121 6.75v12a2.25 2.25 0 01-2.25 2.25h-3.75M16.5 7.5V21a.75.75 0 01-.75.75M6 7.5V21a.75.75 0 01-.75.75" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun chantier</h3>
                                    <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouveau chantier.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Chantiers en retard -->
                    @if($chantiers_retard->count() > 0)
                    <div class="card border-l-4 border-danger-400">
                        <div class="card-header bg-danger-50">
                            <h3 class="text-lg leading-6 font-medium text-danger-800">
                                <svg class="inline h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Chantiers en Retard
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="space-y-3">
                                @foreach($chantiers_retard as $chantier)
                                    <div class="block">
                                        <a href="{{ route('chantiers.show', $chantier) }}" 
                                           class="block hover:bg-gray-50 rounded-md p-2 transition-colors">
                                            <div class="font-medium text-gray-900">{{ $chantier->titre }}</div>
                                            <div class="text-sm text-gray-500">
                                                Client: {{ $chantier->client->name }}
                                            </div>
                                            <div class="text-sm text-danger-600">
                                                Fin prévue: {{ $chantier->date_fin_prevue->format('d/m/Y') }}
                                                ({{ $chantier->date_fin_prevue->diffForHumans() }})
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Actions rapides -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                <svg class="inline h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                </svg>
                                Actions Rapides
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="space-y-3">
                                <a href="{{ route('chantiers.create') }}" class="btn btn-primary w-full justify-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    Nouveau Chantier
                                </a>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary w-full justify-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                    </svg>
                                    Ajouter Utilisateur
                                </a>
                                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary w-full justify-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                    Gérer Utilisateurs
                                </a>
                                <a href="{{ route('admin.statistics') }}" class="btn btn-outline-info w-full justify-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                    </svg>
                                    Statistiques Détaillées
                                </a>
                                <a href="{{ route('chantiers.calendrier') }}" class="btn btn-outline-success w-full justify-center">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5m-18 0h18" />
                                    </svg>
                                    Calendrier
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques utilisateurs -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                <svg class="inline h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                                Utilisateurs
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-2xl font-bold text-primary-600">{{ $stats['total_clients'] }}</div>
                                    <div class="text-sm text-gray-500">Clients</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-warning-600">{{ $stats['total_commerciaux'] }}</div>
                                    <div class="text-sm text-gray-500">Commerciaux</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-danger-600">{{ \App\Models\User::where('role', 'admin')->count() }}</div>
                                    <div class="text-sm text-gray-500">Admins</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js pour les graphiques si nécessaire -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ici vous pouvez ajouter des graphiques avec Chart.js
    // ou d'autres interactions JavaScript
});
</script>
@endsection