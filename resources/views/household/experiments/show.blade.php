<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 text-xs font-mono font-bold rounded {{ $experiment->fertilizer_type === 'Organic' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-200 text-slate-700' }}">
                        {{ $experiment->fertilizer_type }} Treatment
                    </span>
                    <h2 class="font-bold text-2xl text-slate-900 tracking-tight leading-tight">
                        {{ $experiment->plant_species }} Trial
                    </h2>
                </div>
                <p class="text-sm text-slate-500 mt-1">
                    Start Date: {{ $experiment->start_date }} &bull; Total Measurements Recorded: {{ $measurements->count() }}/4
                </p>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('household.experiments.ai-summary', $experiment->experiment_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        Generate AI Trial Summary
                    </button>
                </form>
                <a href="{{ route('household.experiments.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition">
                    &larr; Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('info'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-sm font-medium rounded-r-lg shadow-sm">
                    {{ session('info') }}
                </div>
            @endif

            @if(session('ai_summary'))
                <div class="p-6 bg-white border border-emerald-200 rounded-xl shadow-sm space-y-3">
                    <div class="flex items-center justify-between border-b border-emerald-100 pb-3">
                        <h3 class="text-base font-bold text-slate-900">
                            AI Executive Growth Evaluation Summary
                        </h3>
                        <span class="text-xs font-mono px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded">
                            Gemini 3.6 Flash Analysis
                        </span>
                    </div>
                    <div class="text-sm text-slate-700 space-y-3 leading-relaxed">
                        {!! nl2br(e(session('ai_summary'))) !!}
                    </div>
                </div>
            @endif

            <!-- Add Weekly Measurement & Table Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Log Measurement Form -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 lg:col-span-1">
                    <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">
                        Record Weekly Measurement
                    </h3>

                    <form action="{{ route('household.experiments.measurements.store', $experiment->experiment_id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="week_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Week Number</label>
                            <select id="week_number" name="week_number" required class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="1">Week 1</option>
                                <option value="2">Week 2</option>
                                <option value="3">Week 3</option>
                                <option value="4">Week 4</option>
                            </select>
                        </div>

                        <div>
                            <label for="plant_height_cm" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Plant Height (cm)</label>
                            <input type="number" step="0.1" min="0.1" max="500.0" id="plant_height_cm" name="plant_height_cm" required placeholder="e.g. 12.5" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label for="soil_pH" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Soil pH Reading</label>
                            <input type="number" step="0.1" min="1.0" max="14.0" id="soil_pH" name="soil_pH" required placeholder="e.g. 6.5" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label for="leaf_vitality" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Leaf Vitality Rating</label>
                            <select id="leaf_vitality" name="leaf_vitality" required class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="Healthy Green">Healthy Green</option>
                                <option value="Deep Green">Deep Green</option>
                                <option value="Moderate Yellowing">Moderate Yellowing</option>
                                <option value="Slight Wilting">Slight Wilting</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-lg shadow-sm transition">
                            Save Measurement Log
                        </button>
                    </form>
                </div>

                <!-- 4-Week Recorded Measurements Table -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 lg:col-span-2">
                    <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">
                        Recorded Weekly Metrics ({{ $experiment->plant_species }})
                    </h3>

                    @if($measurements->isEmpty())
                        <p class="text-sm text-slate-500 text-center py-8">No weekly metrics recorded yet. Submit Week 1 data using the form.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-3">Week</th>
                                        <th class="px-4 py-3">Plant Height</th>
                                        <th class="px-4 py-3">Soil pH</th>
                                        <th class="px-4 py-3">Leaf Vitality</th>
                                        <th class="px-4 py-3">Recorded At</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-mono text-xs">
                                    @foreach($measurements as $m)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-4 py-3 font-bold text-slate-900">Week {{ $m->week_number }}</td>
                                            <td class="px-4 py-3 font-bold text-slate-900">{{ $m->plant_height_cm }} cm</td>
                                            <td class="px-4 py-3 text-slate-800">{{ $m->soil_pH }}</td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-0.5 font-sans font-medium text-xs bg-slate-100 text-slate-800 rounded border border-slate-200">
                                                    {{ $m->leaf_vitality }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-slate-400">{{ $m->created_at->format('M d, H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Organic vs Commercial Comparative Analysis Table --}}
            @if($companionTrial)
                {{-- Comparison Table --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="text-base font-bold text-slate-900">
                            Side-by-Side Comparative Growth Analysis: {{ $experiment->plant_species }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Comparing Organic Fertilizer Treatment Group vs Commercial Fertilizer Control Group
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 bg-slate-900 text-white text-xs uppercase font-semibold">Week</th>
                                    <th class="px-4 py-3 bg-emerald-700 text-white text-xs uppercase font-semibold">Organic Height</th>
                                    <th class="px-4 py-3 bg-slate-600 text-white text-xs uppercase font-semibold">Commercial Height</th>
                                    <th class="px-4 py-3 bg-emerald-700 text-white text-xs uppercase font-semibold">Organic pH</th>
                                    <th class="px-4 py-3 bg-slate-600 text-white text-xs uppercase font-semibold">Commercial pH</th>
                                    <th class="px-4 py-3 bg-amber-600 text-white text-xs uppercase font-semibold">Height Delta</th>
                                </tr>
                            </thead>
                            <tbody class="font-mono text-xs">
                                @for($w = 1; $w <= 4; $w++)
                                    @php
                                        $orgM = $experiment->fertilizer_type === 'Organic'
                                            ? $measurements->firstWhere('week_number', $w)
                                            : $companionTrial->growthMeasurements->firstWhere('week_number', $w);

                                        $comM = $experiment->fertilizer_type === 'Commercial Control'
                                            ? $measurements->firstWhere('week_number', $w)
                                            : $companionTrial->growthMeasurements->firstWhere('week_number', $w);

                                        $orgH = $orgM ? (float) $orgM->plant_height_cm : null;
                                        $comH = $comM ? (float) $comM->plant_height_cm : null;
                                        $delta = ($orgH !== null && $comH !== null) ? round($orgH - $comH, 2) : null;
                                        $rowBg = $w % 2 === 1 ? 'bg-white' : 'bg-slate-50';
                                    @endphp
                                    <tr class="{{ $rowBg }} hover:bg-slate-100 transition-colors border-b border-slate-200">
                                        <td class="px-4 py-3.5 font-bold text-slate-900">Week {{ $w }}</td>
                                        <td class="px-4 py-3.5 font-bold text-emerald-700 bg-emerald-50/50">{{ $orgH ? "{$orgH} cm" : '-' }}</td>
                                        <td class="px-4 py-3.5 font-bold text-slate-700 bg-slate-50/50">{{ $comH ? "{$comH} cm" : '-' }}</td>
                                        <td class="px-4 py-3.5 text-emerald-800 bg-emerald-50/50">{{ $orgM ? $orgM->soil_pH : '-' }}</td>
                                        <td class="px-4 py-3.5 text-slate-800 bg-slate-50/50">{{ $comM ? $comM->soil_pH : '-' }}</td>
                                        <td class="px-4 py-3.5 font-bold bg-amber-50/50">
                                            @if($delta !== null)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold {{ $delta >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                                    {{ $delta >= 0 ? "+{$delta} cm" : "{$delta} cm" }}
                                                </span>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Visual Comparison Charts --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-6">
                    <div class="border-b border-slate-100 pb-3">
                        <h3 class="text-base font-bold text-slate-900">
                            Growth Comparison Charts: {{ $experiment->plant_species }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Visual comparison of Organic vs Commercial fertilizer performance over 4 weeks
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Plant Height Chart --}}
                        <div class="bg-slate-50 rounded-xl border border-slate-100 p-5">
                            <h4 class="text-sm font-bold text-slate-700 mb-4">Plant Height (cm)</h4>
                            <div class="relative" style="height: 280px;">
                                <canvas id="heightComparisonChart"></canvas>
                            </div>
                        </div>

                        {{-- Soil pH Chart --}}
                        <div class="bg-slate-50 rounded-xl border border-slate-100 p-5">
                            <h4 class="text-sm font-bold text-slate-700 mb-4">Soil pH Level</h4>
                            <div class="relative" style="height: 280px;">
                                <canvas id="phComparisonChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Chart.js Script --}}
                @php
                    $chartOrgHeights = [];
                    $chartComHeights = [];
                    $chartOrgPh = [];
                    $chartComPh = [];

                    for ($w = 1; $w <= 4; $w++) {
                        $orgM = $experiment->fertilizer_type === 'Organic'
                            ? $measurements->firstWhere('week_number', $w)
                            : $companionTrial->growthMeasurements->firstWhere('week_number', $w);

                        $comM = $experiment->fertilizer_type === 'Commercial Control'
                            ? $measurements->firstWhere('week_number', $w)
                            : $companionTrial->growthMeasurements->firstWhere('week_number', $w);

                        $chartOrgHeights[] = $orgM ? (float) $orgM->plant_height_cm : null;
                        $chartComHeights[] = $comM ? (float) $comM->plant_height_cm : null;
                        $chartOrgPh[] = $orgM ? (float) $orgM->soil_pH : null;
                        $chartComPh[] = $comM ? (float) $comM->soil_pH : null;
                    }
                @endphp

                <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const weeks = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                        const orgHeights = @json($chartOrgHeights);
                        const comHeights = @json($chartComHeights);
                        const orgPh = @json($chartOrgPh);
                        const comPh = @json($chartComPh);

                        const sharedOptions = {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 800,
                                easing: 'easeOutQuart',
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        padding: 20,
                                        font: { size: 11, weight: '600' },
                                    },
                                },
                                tooltip: {
                                    backgroundColor: '#1e293b',
                                    titleFont: { weight: '700' },
                                    bodyFont: { size: 12 },
                                    cornerRadius: 8,
                                    padding: 12,
                                },
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 11, weight: '600' }, color: '#64748b' },
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(148,163,184,0.15)' },
                                    ticks: { font: { size: 11 }, color: '#94a3b8' },
                                },
                            },
                        };

                        // Plant Height Chart
                        new Chart(document.getElementById('heightComparisonChart'), {
                            type: 'bar',
                            data: {
                                labels: weeks,
                                datasets: [
                                    {
                                        label: 'Organic (cm)',
                                        data: orgHeights,
                                        backgroundColor: 'rgba(16,185,129,0.75)',
                                        borderColor: 'rgb(16,185,129)',
                                        borderWidth: 2,
                                        borderRadius: 6,
                                        borderSkipped: false,
                                    },
                                    {
                                        label: 'Commercial (cm)',
                                        data: comHeights,
                                        backgroundColor: 'rgba(100,116,139,0.55)',
                                        borderColor: 'rgb(100,116,139)',
                                        borderWidth: 2,
                                        borderRadius: 6,
                                        borderSkipped: false,
                                    },
                                ],
                            },
                            options: {
                                ...sharedOptions,
                                scales: {
                                    ...sharedOptions.scales,
                                    y: {
                                        ...sharedOptions.scales.y,
                                        title: { display: true, text: 'Height (cm)', font: { size: 11, weight: '600' }, color: '#64748b' },
                                    },
                                },
                            },
                        });

                        // Soil pH Chart
                        new Chart(document.getElementById('phComparisonChart'), {
                            type: 'bar',
                            data: {
                                labels: weeks,
                                datasets: [
                                    {
                                        label: 'Organic pH',
                                        data: orgPh,
                                        backgroundColor: 'rgba(16,185,129,0.75)',
                                        borderColor: 'rgb(16,185,129)',
                                        borderWidth: 2,
                                        borderRadius: 6,
                                        borderSkipped: false,
                                    },
                                    {
                                        label: 'Commercial pH',
                                        data: comPh,
                                        backgroundColor: 'rgba(100,116,139,0.55)',
                                        borderColor: 'rgb(100,116,139)',
                                        borderWidth: 2,
                                        borderRadius: 6,
                                        borderSkipped: false,
                                    },
                                ],
                            },
                            options: {
                                ...sharedOptions,
                                scales: {
                                    ...sharedOptions.scales,
                                    y: {
                                        ...sharedOptions.scales.y,
                                        title: { display: true, text: 'pH Level', font: { size: 11, weight: '600' }, color: '#64748b' },
                                        suggestedMin: 4,
                                        suggestedMax: 9,
                                    },
                                },
                            },
                        });
                    });
                </script>
            @endif

        </div>
    </div>

</x-app-layout>
