<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight leading-tight">
                    Agricultural Extension Officer Workspace
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Rikolto Kungahara Project Case Study &bull; Musanze/Nyabihu Districts
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('officer.formulations.index') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    Manage Formulations
                </a>
                <a href="{{ route('officer.experiments.index') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    Review Member Trials
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('info'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-sm font-medium rounded-r-lg shadow-sm">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Metric Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Validated Formulations</span>
                        <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $formulationsCount }}</h3>
                        <p class="text-xs text-slate-500 mt-1">Ratio guide formulas</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-700 font-bold">
                        EF
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Cooperative Members</span>
                        <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $householdCount }}</h3>
                        <p class="text-xs text-slate-500 mt-1">Household loggers</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-700 font-bold">
                        HH
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Field Experiments</span>
                        <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $experimentsCount }}</h3>
                        <p class="text-xs text-slate-500 mt-1">Active 4-week trials</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-700 font-bold">
                        PT
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Waste Valorised</span>
                        <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $wasteAnalytics['totals']['aggregate'] }}<span class="text-sm font-normal text-slate-500">kg</span></h3>
                        <p class="text-xs text-slate-500 mt-1">Total collected waste</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                        KG
                    </div>
                </div>
            </div>

            <!-- Analytics Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Chart 1: Food Waste Collection Breakdown -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Food Waste Valorisation by Material Type</h3>
                            <p class="text-xs text-slate-500">Cooperative member waste collection totals (kg)</p>
                        </div>
                        <span class="text-xs font-mono font-semibold px-2 py-1 bg-slate-100 text-slate-700 rounded">
                            Aggregate: {{ $wasteAnalytics['totals']['aggregate'] }}kg
                        </span>
                    </div>

                    <div class="h-64 relative flex items-center justify-center">
                        <canvas id="officerWasteChart"></canvas>
                    </div>

                    <div class="grid grid-cols-3 gap-2 pt-2 text-center text-xs border-t border-slate-100">
                        <div class="p-2 rounded bg-amber-50 border border-amber-100">
                            <span class="text-amber-800 font-bold block">{{ $wasteAnalytics['totals']['banana'] }} kg</span>
                            <span class="text-slate-500">Banana Peels (K)</span>
                        </div>
                        <div class="p-2 rounded bg-stone-50 border border-stone-200">
                            <span class="text-stone-800 font-bold block">{{ $wasteAnalytics['totals']['eggshell'] }} kg</span>
                            <span class="text-slate-500">Eggshells (Ca)</span>
                        </div>
                        <div class="p-2 rounded bg-emerald-50 border border-emerald-100">
                            <span class="text-emerald-800 font-bold block">{{ $wasteAnalytics['totals']['coffee'] }} kg</span>
                            <span class="text-slate-500">Coffee Grounds (N)</span>
                        </div>
                    </div>
                </div>

                <!-- Chart 2: 4-Week Plant Growth Trajectory Comparison -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">4-Week Plant Growth Trajectory</h3>
                            <p class="text-xs text-slate-500">Average plant height progression: Organic vs Commercial Control (cm)</p>
                        </div>
                        <span class="text-xs font-mono font-semibold px-2 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded">
                            Field Evaluation
                        </span>
                    </div>

                    <div class="h-64 relative">
                        <canvas id="officerGrowthChart"></canvas>
                    </div>

                    <div class="flex items-center justify-around text-xs text-slate-600 pt-2 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                            <span>Organic Fertilizer Group</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-slate-400"></span>
                            <span>Commercial Fertilizer Control</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Recent Validated Formulations Table -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">Validated Formulations Database</h3>
                    <a href="{{ route('officer.formulations.index') }}" class="text-xs font-semibold text-emerald-600 hover:underline">
                        Manage All Formulations &rarr;
                    </a>
                </div>

                @if($recentFormulations->isEmpty())
                    <p class="text-sm text-slate-500 text-center py-6">No formulations created yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3">Title</th>
                                    <th class="px-4 py-3">Target Waste</th>
                                    <th class="px-4 py-3">NPK Ratio</th>
                                    <th class="px-4 py-3">Fermentation</th>
                                    <th class="px-4 py-3 text-right">Yield</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentFormulations as $rf)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 font-bold text-slate-900">{{ $rf->title ?? $rf->target_waste_type }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $rf->target_waste_type }}</td>
                                        <td class="px-4 py-3 font-mono text-xs text-emerald-700 font-bold">{{ $rf->npk_ratio ?? 'Standard' }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $rf->fermentation_days }} Days</td>
                                        <td class="px-4 py-3 text-right font-mono font-bold text-slate-900">{{ $rf->yield_quantity }}{{ $rf->yield_unit }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Recent Cooperative Experiments Table -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">Cooperative Member Experiments</h3>
                    <a href="{{ route('officer.experiments.index') }}" class="text-xs font-semibold text-emerald-600 hover:underline">
                        View All Experiments &rarr;
                    </a>
                </div>

                @if($recentExperiments->isEmpty())
                    <p class="text-sm text-slate-500 text-center py-6">No member experiments recorded yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3">Member Name</th>
                                    <th class="px-4 py-3">Plant Species</th>
                                    <th class="px-4 py-3">Treatment Group</th>
                                    <th class="px-4 py-3">Recorded Weeks</th>
                                    <th class="px-4 py-3">Start Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentExperiments as $re)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 font-bold text-slate-900">{{ $re->user->name ?? 'Cooperative Farmer' }}</td>
                                        <td class="px-4 py-3 text-slate-800">{{ $re->plant_species }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded {{ $re->fertilizer_type === 'Organic' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                                {{ $re->fertilizer_type }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $re->growthMeasurements->count() }}/4 Weeks</td>
                                        <td class="px-4 py-3 font-mono text-xs text-slate-400">{{ $re->start_date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Initialize Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart 1: Waste Doughnut Chart
            const wasteCtx = document.getElementById('officerWasteChart').getContext('2d');
            new Chart(wasteCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($wasteAnalytics['labels']) !!},
                    datasets: [{
                        data: {!! json_encode($wasteAnalytics['data']) !!},
                        backgroundColor: ['#f59e0b', '#78716c', '#059669'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { size: 11, family: 'Inter' } } }
                    }
                }
            });

            // Chart 2: Growth Comparison Line Chart
            const growthCtx = document.getElementById('officerGrowthChart').getContext('2d');
            new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($growthAnalytics['labels']) !!},
                    datasets: [
                        {
                            label: 'Organic Fertilizer (cm)',
                            data: {!! json_encode($growthAnalytics['organic']) !!},
                            borderColor: '#059669',
                            backgroundColor: 'rgba(5, 150, 105, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: 'Commercial Control (cm)',
                            data: {!! json_encode($growthAnalytics['commercial']) !!},
                            borderColor: '#94a3b8',
                            backgroundColor: 'rgba(148, 163, 184, 0.1)',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            fill: false,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, title: { display: true, text: 'Plant Height (cm)', font: { size: 11 } } },
                        x: { title: { display: true, text: 'Trial Duration', font: { size: 11 } } }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
</x-app-layout>
