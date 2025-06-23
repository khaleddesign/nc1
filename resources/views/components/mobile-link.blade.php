@props(['route', 'icon' => null])

@php
    $active = request()->routeIs($route) || request()->routeIs($route . '.*');
@endphp

<a href="{{ route($route) }}" 
   class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors duration-150
          {{ $active 
             ? 'bg-blue-50 border-blue-500 text-blue-700' 
             : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
    @if($icon)
        <i class="{{ $icon }} mr-3 text-sm"></i>
    @endif
    {{ $slot }}
</a>