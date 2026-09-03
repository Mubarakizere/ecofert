<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight leading-tight">
                    System Administration Analytics Control
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Platform access, cooperative member metrics, and agricultural case study evaluation.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    Create User Account
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- System Statistics Row -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Accounts</span>
                        <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $userCount }}</h3>
                        <p class="text-xs text-slate-500 mt-1">Platform user credentials</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-700 font-bold">
                        UA
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Households</span>
                        <h3 class="text-3xl font-bold text-emerald-700 mt-1">{{ $householdCount }}</h3>
                        <p class="text-xs text-slate-500 mt-1">Cooperative waste loggers</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-700 font-bold">
                        HH
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Extension Officers</span>
                        <h3 class="text-3xl font-bold text-amber-700 mt-1">{{ $officerCount }}</h3>
                        <p class="text-xs text-slate-500 mt-1">Agricultural advisors</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-700 font-bold">
                        EO
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Waste Logged</span>
                        <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $wasteAnalytics['totals']['aggregate'] }}<span class="text-sm font-normal text-slate-500">kg</span></h3>
                        <p class="text-xs text-slate-500 mt-1">Valorised food waste</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-700 font-bold">
                        KG
                    </div>
                </div>
            </div>

            <!-- Analytics Charts Grid (Row 1) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Chart 1: Food Waste Collection Breakdown -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Food Waste Valorisation by Material Type</h3>
                            <p class="text-xs text-slate-500">Musanze/Nyabihu district cooperative waste deposits (kg)</p>
                        </div>
                        <span class="text-xs font-mono font-semibold px-2 py-1 bg-slate-100 text-slate-700 rounded">
                            Aggregate: {{ $wasteAnalytics['totals']['aggregate'] }}kg
                        </span>
                    </div>

                    <div class="h-64 relative flex items-center justify-center">
                        <canvas id="wasteChart"></canvas>
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

                <!-- Chart 2: 4-Week Growth Trajectory Comparison -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">4-Week Plant Growth Trajectory</h3>
                            <p class="text-xs text-slate-500">Average plant height progression: Organic vs Commercial Control (cm)</p>
                        </div>
                        <span class="text-xs font-mono font-semibold px-2 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded">
                            Trial Comparison
                        </span>
                    </div>

                    <div class="h-64 relative">
                        <canvas id="growthChart"></canvas>
                    </div>

                    <div class="flex items-center justify-around text-xs text-slate-600 pt-2 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                            <span>Homemade Organic Fertilizer</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-slate-400"></span>
                            <span>Commercial Fertilizer Control</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Quick Management Navigation Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">User Account Provisioning & Roles</h3>
                        <p class="text-xs text-slate-500 mt-1">Manage user credentials, grant Spatie roles, and inspect system permissions.</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        Manage Users &rarr;
                    </a>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Cooperative Farmer Experiments Log</h3>
                        <p class="text-xs text-slate-500 mt-1">Review plant trial measurements and height delta reports across member gardens.</p>
                    </div>
                    <a href="{{ route('officer.experiments.index') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        Review Trials &rarr;
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Initialize Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart 1: Food Waste Doughnut
            const wasteCtx = document.getElementById('wasteChart').getContext('2d');
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

            // Chart 2: Growth Trajectory Multi-Line Chart
            const growthCtx = document.getElementById('growthChart').getContext('2d');
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
