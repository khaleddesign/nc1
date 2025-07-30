{{-- resources/views/components/sidebar.blade.php --}}
@auth
<div class="w-64 h-full bg-white border-r border-gray-200 flex flex-col">
    {{-- Header --}}
    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center mr-3">
                <span class="text-white text-sm font-bold">NC</span>
            </div>
            <span class="text-lg font-semibold text-gray-900">N-C Gestion</span>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="flex-1 px-3 py-4">
        {{-- PRINCIPAL --}}
        <div class="mb-6">
            <h3 class="px-3 mb-2 text-xs font-medium text-gray-500 uppercase">PRINCIPAL</h3>
            <nav class="space-y-1">
                <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-indigo-100 text-indigo-700' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    Dashboard
                </a>
                
                <a href="{{ route('chantiers.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 {{ request()->routeIs('chantiers.*') ? 'bg-indigo-100 text-indigo-700' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Chantiers
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">3</span>
                </a>
            </nav>
        </div>

        {{-- COMMERCIAL --}}
        @php $user = auth()->user(); @endphp
        @if($user && in_array($user->role ?? '', ['admin', 'commercial']))
        <div class="mb-6">
            <h3 class="px-3 mb-2 text-xs font-medium text-gray-500 uppercase">COMMERCIAL</h3>
            <nav class="space-y-1">
                <a href="{{ route('devis.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 {{ request()->routeIs('devis.*') ? 'bg-indigo-100 text-indigo-700' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Devis
                </a>
                
                <a href="{{ route('factures.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 {{ request()->routeIs('factures.*') ? 'bg-indigo-100 text-indigo-700' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Factures
                </a>
                
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                    </svg>
                    Clients
                </a>
            </nav>
        </div>
        @endif

        {{-- SYSTÈME --}}
        @if($user && ($user->role ?? '') === 'admin')
        <div>
            <h3 class="px-3 mb-2 text-xs font-medium text-gray-500 uppercase">SYSTÈME</h3>
            <nav class="space-y-1">
                <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Paramètres
                </a>
            </nav>
        </div>
        @endif
    </div>
</div>
@endauth