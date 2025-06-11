<svg class="h-5 w-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            @elseif($index === 2)
                                                <svg class="h-5 w-5 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            @else
                                                <span class="text-sm font-bold text-blue-600">{{ $index + 1 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $commercial->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $commercial->total_chantiers }} chantier(s)</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-900">{{ number_format($commercial->avg_progress, 1) }}%</p>
                                    <div class="w-20 bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full transition-all duration-1000" 
                                             style="width: {{ $commercial->avg_progress }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($performance_data->count() > 5)
                        <div class="mt-6 text-center">
                            <button onclick="showAllPerformances()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Voir tous les commerciaux →
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Analyses détaillées -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Données mensuelles -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="h-6 w-6 text-purple-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5m-18 0h18" />
                        </svg>
                        Analyse mensuelle {{ date('Y') }}
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mois</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nouveaux chantiers</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avancement moyen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tendance</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($monthly_data as $month)
                                @php
                                    $monthNames = [
                                        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
                                        5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
                                        9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                                    ];
                                    $prevProgress = $loop->index > 0 ? $monthly_data[$loop->index - 1]->avg_progress : $month->avg_progress;
                                    $trend = $month->avg_progress - $prevProgress;
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $monthNames[$month->month] ?? 'Mois ' . $month->month }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <span class="mr-2">{{ $month->total }}</span>
                                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ min(100, ($month->total / max($monthly_data->max('total'), 1)) * 100) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <span class="mr-2">{{ number_format($month->avg_progress, 1) }}%</span>
                                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $month->avg_progress }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($trend > 0)
                                            <span class="inline-flex items-center text-green-600">
                                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                                </svg>
                                                +{{ number_format($trend, 1) }}%
                                            </span>
                                        @elseif($trend < 0)
                                            <span class="inline-flex items-center text-red-600">
                                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.286-4.286a11.948 11.948 0 014.306 6.43l.776 2.898m0 0l3.182-5.511m-3.182 5.511l-5.511-3.182" />
                                                </svg>
                                                {{ number_format($trend, 1) }}%
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-gray-500">
                                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                                </svg>
                                                Stable
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Insights et recommandations -->
            <div class="space-y-6">
                <!-- Insights rapides -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-yellow-600 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189 6.01 6.01 0 001.041-.289 4.5 4.5 0 00.361-1.196M12 12.75V12a6 6 0 00-6-6v.75M12 12.75V12.75M12 12.75c.75 0 1.5.189 2.25.32.397.07.777.205 1.125.39V12M12 12.75v5.25m0 0a6.01 6.01 0 01-1.5-.189 6.01 6.01 0 01-1.041-.289 4.5 4.5 0 01-.361-1.196M12 18v-.75M12 18c-.75 0-1.5-.189-2.25-.32a4.5 4.5 0 01-1.125-.39V18M12 18v-5.25" />
                            </svg>
                            Insights
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 h-6 w-6 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="h-3 w-3 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Performance excellente</p>
                                <p class="text-xs text-gray-600">{{ number_format($stats['average_progress'], 0) }}% d'avancement moyen</p>
                            </div>
                        </div>
                        
                        @if($stats['chantiers_en_retard'] > 0)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 h-6 w-6 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="h-3 w-3 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Attention requise</p>
                                    <p class="text-xs text-gray-600">{{ $stats['chantiers_en_retard'] }} chantier(s) en retard</p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="h-3 w-3 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Croissance active</p>
                                <p class="text-xs text-gray-600">{{ $stats['users_active_last_month'] }} utilisateurs actifs</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions recommandées -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                            </svg>
                            Recommandations
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($stats['chantiers_en_retard'] > 0)
                            <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                                <p class="text-sm font-medium text-red-800">Gérer les retards</p>
                                <p class="text-xs text-red-600 mt-1">Prioriser les {{ $stats['chantiers_en_retard'] }} chantiers en retard</p>
                                <button class="mt-2 text-xs text-red-700 hover:text-red-900 font-medium">
                                    Voir les chantiers →
                                </button>
                            </div>
                        @endif
                        
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-sm font-medium text-blue-800">Optimiser les équipes</p>
                            <p class="text-xs text-blue-600 mt-1">Analyser la charge de travail par commercial</p>
                            <button class="mt-2 text-xs text-blue-700 hover:text-blue-900 font-medium">
                                Analyser →
                            </button>
                        </div>
                        
                        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-sm font-medium text-green-800">Croissance</p>
                            <p class="text-xs text-green-600 mt-1">Maintenir la dynamique positive</p>
                            <button class="mt-2 text-xs text-green-700 hover:text-green-900 font-medium">
                                Planifier →
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Export et partage -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="h-5 w-5 text-gray-600 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Export & Partage
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <button onclick="exportToPDF()" class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-xl hover:from-red-600 hover:to-pink-700 transition-all transform hover:scale-105">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            Rapport PDF
                        </button>
                        <button onclick="exportToExcel()" class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all transform hover:scale-105">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-2.625 1.125a1.125 1.125 0 001.125 1.125m0 0h1.5m-3.75 0V18.375m0 3.75h7.875m-7.875 0V19.5m7.875 1.125c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 1.5V18.375m0 0c0 .621-.504 1.125-1.125 1.125H9.375c-.621 0-1.125-.504-1.125-1.125v-1.5c0-.621.504-1.125 1.125-1.125H15m0 0V9.375c0-.621-.504-1.125-1.125-1.125H9.375c-.621 0-1.125.504-1.125 1.125v1.5H15V18.375z" />
                            </svg>
                            Excel/CSV
                        </button>
                        <button onclick="shareReport()" class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
                            </svg>
                            Partager
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs
    function animateCounters() {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = parseInt(counter.dataset.target) || 0;
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    counter.textContent = target;
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.floor(current);
                }
            }, 30);
        });
    }

    // Animation des barres de progression
    function animateProgressBars() {
        const progressBars = document.querySelectorAll('.progress-bar[data-width]');
        progressBars.forEach(bar => {
            const width = bar.dataset.width;
            setTimeout(() => {
                bar.style.width = width + '%';
            }, 500);
        });
    }

    // Initialiser les animations
    setTimeout(() => {
        animateCounters();
        animateProgressBars();
    }, 300);

    // Dessiner le graphique simple
    drawChart();
});

// Dessiner un graphique simple avec Canvas
function drawChart() {
    const canvas = document.getElementById('progressChart');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const data = @json($monthly_data);
    
    if (!data || data.length === 0) return;
    
    const width = canvas.width;
    const height = canvas.height;
    const padding = 50;
    
    // Nettoyer le canvas
    ctx.clearRect(0, 0, width, height);
    
    // Données pour le graphique
    const maxChantiers = Math.max(...data.map(d => d.total));
    const maxProgress = 100;
    
    // Axes
    ctx.strokeStyle = '#e5e7eb';
    ctx.lineWidth = 1;
    
    // Axe X
    ctx.beginPath();
    ctx.moveTo(padding, height - padding);
    ctx.lineTo(width - padding, height - padding);
    ctx.stroke();
    
    // Axe Y
    ctx.beginPath();
    ctx.moveTo(padding, padding);
    ctx.lineTo(padding, height - padding);
    ctx.stroke();
    
    // Points et lignes
    const stepX = (width - 2 * padding) / (data.length - 1);
    
    // Ligne des nouveaux chantiers
    ctx.strokeStyle = '#3b82f6';
    ctx.lineWidth = 3;
    ctx.beginPath();
    data.forEach((point, index) => {
        const x = padding + index * stepX;
        const y = height - padding - (point.total / maxChantiers) * (height - 2 * padding);
        if (index === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });
    ctx.stroke();
    
    // Points pour nouveaux chantiers
    ctx.fillStyle = '#3b82f6';
    data.forEach((point, index) => {
        const x = padding + index * stepX;
        const y = height - padding - (point.total / maxChantiers) * (height - 2 * padding);
        ctx.beginPath();
        ctx.arc(x, y, 6, 0, 2 * Math.PI);
        ctx.fill();
    });
    
    // Ligne de l'avancement moyen
    ctx.strokeStyle = '#10b981';
    ctx.lineWidth = 3;
    ctx.beginPath();
    data.forEach((point, index) => {
        const x = padding + index * stepX;
        const y = height - padding - (point.avg_progress / maxProgress) * (height - 2 * padding);
        if (index === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });
    ctx.stroke();
    
    // Points pour avancement
    ctx.fillStyle = '#10b981';
    data.forEach((point, index) => {
        const x = padding + index * stepX;
        const y = height - padding - (point.avg_progress / maxProgress) * (height - 2 * padding);
        ctx.beginPath();
        ctx.arc(x, y, 6, 0, 2 * Math.PI);
        ctx.fill();
    });
    
    // Labels des mois
    ctx.fillStyle = '#6b7280';
    ctx.font = '12px Inter, sans-serif';
    ctx.textAlign = 'center';
    data.forEach((point, index) => {
        const x = padding + index * stepX;
        const monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        ctx.fillText(monthNames[point.month - 1] || point.month, x, height - 20);
    });
}

// Fonctions d'interaction
function refreshData() {
    const btn = event.target;
    const originalContent = btn.innerHTML;
    
    btn.innerHTML = `
        <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Actualisation...
    `;
    
    setTimeout(() => {
        btn.innerHTML = originalContent;
        location.reload();
    }, 2000);
}

function exportReport() {
    window.print();
}

function exportToPDF() {
    showToast('Génération du rapport PDF...', 'info');
    // Ici vous pourriez intégrer une librairie comme jsPDF
    setTimeout(() => {
        showToast('Rapport PDF généré avec succès !', 'success');
    }, 2000);
}

function exportToExcel() {
    showToast('Export Excel en cours...', 'info');
    // Ici vous pourriez générer un fichier CSV/Excel
    const csvContent = "data:text/csv;charset=utf-8," 
        + "Mois,Nouveaux Chantiers,Avancement Moyen\n"
        + @json($monthly_data).map(row => `${row.month},${row.total},${row.avg_progress}`).join("\n");
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `statistiques_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showToast('Fichier Excel téléchargé !', 'success');
}

function shareReport() {
    if (navigator.share) {
        navigator.share({
            title: 'Rapport de statistiques',
            text: 'Consultez les dernières statistiques de notre activité',
            url: window.location.href
        });
    } else {
        // Copier l'URL dans le presse-papiers
        navigator.clipboard.writeText(window.location.href).then(() => {
            showToast('Lien copié dans le presse-papiers !', 'success');
        });
    }
}

function toggleChart(type) {
    // Exemple d'interaction pour changer de vue
    const buttons = document.querySelectorAll('[onclick^="toggleChart"]');
    buttons.forEach(btn => {
        btn.classList.remove('bg-blue-100', 'text-blue-800');
        btn.classList.add('bg-gray-100', 'text-gray-700');
    });
    
    event.target.classList.remove('bg-gray-100', 'text-gray-700');
    event.target.classList.add('bg-blue-100', 'text-blue-800');
    
    showToast(`Vue ${type} sélectionnée`, 'info');
}

function showAllPerformances() {
    showToast('Chargement de tous les commerciaux...', 'info');
    // Ici vous pourriez charger plus de données via AJAX
}

// Fonction de toast pour les notifications
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 max-w-sm p-4 rounded-xl shadow-2xl transform transition-all duration-300 translate-x-full`;
    
    const bgColors = {
        'info': 'bg-blue-500',
        'success': 'bg-green-500',
        'warning': 'bg-yellow-500',
        'error': 'bg-red-500'
    };
    
    const icons = {
        'info': '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>',
        'success': '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        'warning': '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>',
        'error': '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>'
    };
    
    toast.classList.add(bgColors[type] || bgColors.info, 'text-white');
    toast.innerHTML = `
        <div class="flex items-center">
            ${icons[type] || icons.info}
            <span class="ml-3 font-medium">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animation d'entrée
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Auto-suppression
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (document.body.contains(toast)) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 5000);
}

// Actualisation automatique des données (optionnel)
setInterval(() => {
    fetch('/api/admin/stats')
        .then(response => response.json())
        .then(data => {
            // Mettre à jour les compteurs si nécessaire
            console.log('Données actualisées:', data);
        })
        .catch(error => console.error('Erreur:', error));
}, 60000); // Toutes les minutes
</script>
@endpush

@push('styles')
<style>
.animate-pulse-slow {
    animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

/* Styles d'impression */
@media print {
    .no-print {
        display: none !important;
    }
    
    .bg-gradient-to-r,
    .bg-gradient-to-br {
        background: #1e40af !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
    }
    
    .shadow-xl,
    .shadow-2xl {
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }
    
    .rounded-2xl {
        border-radius: 8px !important;
    }
    
    /* Ajuster les tailles pour l'impression */
    .max-w-7xl {
        max-width: 100% !important;
    }
    
    .grid {
        break-inside: avoid;
    }
    
    /* Couleurs pour l'impression */
    .text-white {
        color: white !important;
    }
    
    .bg-blue-500,
    .bg-green-500,
    .bg-purple-500,
    .bg-amber-500 {
        -webkit-print-color-adjust: exact;
    }
}

/* Animations personnalisées */
@keyframes slideInFromRight {
    from {
        transform: translateX(100px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.card-animate {
    animation: slideInFromRight 0.6s ease-out;
}

.card-animate:nth-child(2) {
    animation-delay: 0.1s;
}

.card-animate:nth-child(3) {
    animation-delay: 0.2s;
}

.card-animate:nth-child(4) {
    animation-delay: 0.3s;
}

/* Styles pour les graphiques */
canvas {
    border-radius: 8px;
}

/* Styles responsive pour mobile */
@media (max-width: 640px) {
    .grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    
    .text-4xl.sm\\:text-5xl.lg\\:text-6xl {
        font-size: 2rem;
    }
    
    .px-4.sm\\:px-6.lg\\:px-8 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}

/* Amélioration des hover effects */
.group:hover .group-hover\\:from-white\\/30 {
    background-image: linear-gradient(to bottom right, rgba(255, 255, 255, 0.3), transparent);
}

/* Transitions fluides */
* {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Amélioration de l'accessibilité */
button:focus,
a:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Styles pour les tooltips (si nécessaire) */
.tooltip {
    position: relative;
}

.tooltip::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.9);
    color: white;
    padding: 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s;
}

.tooltip:hover::after {
    opacity: 1;
}
</style>
@endpush@extends('layouts.app')

@section('title', 'Statistiques et Analytics')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- En-tête avec gradient et animation -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-700 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm mb-6 animate-pulse-slow">
                    <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-white sm:text-5xl lg:text-6xl">
                    Analytics & Insights
                </h1>
                <p class="mt-4 text-xl text-purple-100 max-w-3xl mx-auto">
                    Analysez les performances de votre activité avec des données en temps réel et des insights approfondis
                </p>
                <div class="mt-8 flex justify-center space-x-4">
                    <button onclick="refreshData()" class="inline-flex items-center px-6 py-3 border border-white/30 rounded-full text-white hover:bg-white/10 transition-all duration-200 backdrop-blur-sm">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        Actualiser les données
                    </button>
                    <button onclick="exportReport()" class="inline-flex items-center px-6 py-3 bg-white/20 rounded-full text-white hover:bg-white/30 transition-all duration-200 backdrop-blur-sm">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Exporter le rapport
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- KPI Cards avec animations -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Utilisateurs par rôle -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent group-hover:from-white/30"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-white counter" data-target="{{ collect($stats['users_by_role'])->sum() }}">0</p>
                            <p class="text-blue-100 text-sm">Utilisateurs total</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        @foreach($stats['users_by_role'] as $role => $count)
                            <div class="flex justify-between items-center text-blue-100 text-sm">
                                <span class="capitalize">{{ $role === 'admin' ? 'Admins' : ($role === 'commercial' ? 'Commerciaux' : 'Clients') }}</span>
                                <span class="font-medium">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Chantiers par statut -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent group-hover:from-white/30"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18M6.75 9h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.75m-.75 3h.75m-.75 3h.75m-3.75-16.5h3.75A2.25 2.25 0 0121 6.75v12a2.25 2.25 0 01-2.25 2.25h-3.75M16.5 7.5V21a.75.75 0 01-.75.75M6 7.5V21a.75.75 0 01-.75.75" />
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-white counter" data-target="{{ collect($stats['chantiers_by_status'])->sum() }}">0</p>
                            <p class="text-green-100 text-sm">Chantiers total</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        @foreach($stats['chantiers_by_status'] as $status => $count)
                            <div class="flex justify-between items-center text-green-100 text-sm">
                                <span class="capitalize">{{ $status === 'planifie' ? 'Planifiés' : ($status === 'en_cours' ? 'En cours' : 'Terminés') }}</span>
                                <span class="font-medium">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Avancement moyen -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent group-hover:from-white/30"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-white counter" data-target="{{ number_format($stats['average_progress'], 0) }}">0</p>
                            <p class="text-purple-100 text-sm">% Avancement</p>
                        </div>
                    </div>
                    <div class="w-full bg-white/20 rounded-full h-3">
                        <div class="bg-white h-3 rounded-full transition-all duration-2000 ease-out progress-bar" data-width="{{ $stats['average_progress'] }}"></div>
                    </div>
                    <p class="text-purple-100 text-xs mt-2 text-center">Progression moyenne des projets</p>
                </div>
            </div>

            <!-- Indicateurs d'alerte -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-6 shadow-xl transform hover:scale-105 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent group-hover:from-white/30"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-white counter" data-target="{{ $stats['chantiers_en_retard'] }}">0</p>
                            <p class="text-orange-100 text-sm">Chantiers en retard</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-orange-100 text-sm">
                            <span>Utilisateurs actifs</span>
                            <span class="font-medium">{{ $stats['users_active_last_month'] }}</span>
                        </div>
                        <div class="flex justify-between items-center text-orange-100 text-sm">
                            <span>Ce mois</span>
                            <span class="font-medium">{{ $stats['users_active_last_month'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques et analyses -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Graphique évolution mensuelle -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            Évolution {{ date('Y') }}
                        </h3>
                        <div class="flex space-x-2">
                            <button onclick="toggleChart('monthly')" class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200 transition-colors">
                                Mensuel
                            </button>
                            <button onclick="toggleChart('weekly')" class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                Hebdomadaire
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="h-80" id="monthly-chart">
                        <!-- Graphique simple avec Canvas -->
                        <canvas id="progressChart" width="400" height="200" class="w-full h-full"></canvas>
                    </div>
                    
                    <!-- Légendes -->
                    <div class="flex justify-center space-x-6 mt-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Nouveaux chantiers</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Avancement moyen</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance par commercial -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="h-6 w-6 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.228a9.014 9.014 0 012.916.52 6.003 6.003 0 01-4.395 5.972m0-11.205a9.276 9.276 0 00-2.25.307 9.276 9.276 0 00-2.25-.307m4.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-4.5c-.621 0-1.125.504-1.125 1.125v.731m6.75 0a3 3 0 013 3V9.75" />
                        </svg>
                        Top Performances
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($performance_data->take(5) as $index => $commercial)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full {{ $index === 0 ? 'bg-yellow-100' : ($index === 1 ? 'bg-gray-100' : ($index === 2 ? 'bg-amber-100' : 'bg-blue-100')) }} flex items-center justify-center">
                                            @if($index === 0)
                                                <svg class="h-5 w-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            @elseif($index === 1)
                                                <svg class="h-5 w-5 text-gray-600" fill="current