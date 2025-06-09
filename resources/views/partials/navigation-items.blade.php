<!-- Dashboard -->
<a href="{{ route('dashboard') }}" 
   class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
    <svg class="text-gray-400 mr-3 h-5 w-5 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
    </svg>
    Dashboard
</a>

<!-- Chantiers -->
<a href="{{ route('chantiers.index') }}" 
   class="nav-link {{ request()->routeIs('chantiers.*') ? 'nav-link-active' : '' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
    <svg class="text-gray-400 mr-3 h-5 w-5 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18M6.75 9h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-3.75-16.5h3.75A2.25 2.25 0 0121 6.75v12a2.25 2.25 0 01-2.25 2.25h-3.75M16.5 7.5V21a.75.75 0 01-.75.75M6 7.5V21a.75.75 0 01-.75.75" />
    </svg>
    Chantiers
</a>

@if(Auth::user()->isAdmin() || Auth::user()->isCommercial())
    <!-- Calendrier -->
    <a href="{{ route('chantiers.calendrier') }}" 
       class="nav-link {{ request()->routeIs('chantiers.calendrier') ? 'nav-link-active' : '' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
        <svg class="text-gray-400 mr-3 h-5 w-5 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5m-18 0h18" />
        </svg>
        Calendrier
    </a>

    <!-- Nouveau chantier -->
    <a href="{{ route('chantiers.create') }}" 
       class="nav-link group flex items-center px-2 py-2 text-sm font-medium rounded-md">
        <svg class="text-gray-400 mr-3 h-5 w-5 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Nouveau Chantier
    </a>
@endif

@if(Auth::user()->isAdmin())
    <!-- Divider -->
    <div class="border-t border-gray-200 my-3"></div>
    
    <!-- Administration -->
    <div class="mb-2">
        <h3 class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
            Administration
        </h3>
    </div>
    
    <!-- Utilisateurs -->
    <a href="{{ route('admin.users') }}" 
       class="nav-link {{ request()->routeIs('admin.users*') ? 'nav-link-active' : '' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
        <svg class="text-gray-400 mr-3 h-5 w-5 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
        </svg>
        Utilisateurs
    </a>

    <!-- Statistiques -->
    <a href="{{ route('admin.statistics') }}" 
       class="nav-link {{ request()->routeIs('admin.statistics') ? 'nav-link-active' : '' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
        <svg class="text-gray-400 mr-3 h-5 w-5 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
        </svg>
        Statistiques
    </a>
@endif

<!-- Divider -->
<div class="border-t border-gray-200 my-3"></div>

<!-- Notifications -->
<a href="{{ route('notifications.index') }}" 
   class="nav-link {{ request()->routeIs('notifications.*') ? 'nav-link-active' : '' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
    <svg class="text-gray-400 mr-3 h-5 w-5 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
    </svg>
    Notifications
    @php
        $unreadCount = Auth::user()->getNotificationsNonLues();
    @endphp
    @if($unreadCount > 0)
        <span class="ml-auto bg-primary-600 text-white text-xs rounded-full px-2 py-0.5">
            {{ $unreadCount }}
        </span>
    @endif
</a>