{{-- resources/views/components/stat-card.blade.php --}}
@props([
    'title', 
    'value', 
    'icon' => null, 
    'color' => 'indigo', 
    'trend' => null,
    'trendDirection' => 'up',
    'subtitle' => null
])

@php
    $colorClasses = [
        'indigo' => 'from-indigo-500 to-purple-600',
        'emerald' => 'from-emerald-500 to-green-600',
        'amber' => 'from-amber-500 to-orange-600',
        'red' => 'from-red-500 to-pink-600',
        'cyan' => 'from-cyan-500 to-blue-600'
    ];
    $gradientClass = $colorClasses[$color] ?? $colorClasses['indigo'];
@endphp

<div class="relative card-elevated hover-lift bg-gradient-to-br {{ $gradientClass }} text-white overflow-hidden group">
    <!-- Éléments décoratifs d'arrière-plan -->
    <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
    <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full transform translate-x-16 -translate-y-16 group-hover:scale-110 transition-transform duration-500"></div>
    
    <div class="relative card-body">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-white/80 text-sm font-medium uppercase tracking-wide mb-1">{{ $title }}</p>
                <p class="text-4xl font-bold mb-2 counter" data-target="{{ $value }}">{{ $value }}</p>
                
                @if($trend)
                    <div class="flex items-center mt-2">
                        @if($trendDirection === 'up')
                            <svg class="h-4 w-4 text-emerald-300 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                            </svg>
                        @else
                            <svg class="h-4 w-4 text-red-300 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.286-4.286a11.948 11.948 0 014.306 6.43l.776 2.898m0 0l3.182-5.511m-3.182 5.511l-5.511-3.182" />
                            </svg>
                        @endif
                        <span class="text-white/90 text-sm">{{ $trend }}</span>
                    </div>
                @endif
                
                @if($subtitle)
                    <p class="text-white/70 text-sm mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            
            @if($icon)
                <div class="h-16 w-16 rounded-2xl bg-white/20 flex items-center justify-center ml-4">
                    <i class="{{ $icon }} text-2xl text-white"></i>
                </div>
            @endif
        </div>
    </div>
</div>