<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-heading text-2xl sm:text-3xl font-bold text-gray-800 tracking-wide">
                    4-Week Plant Monitoring
                </h2>
                <p class="text-sm font-body text-gray-500 mt-1">
                    Comparative Growth Trials &middot; Organic vs Commercial Control
                </p>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold font-body">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Active Trial Tracker
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 text-sm flex items-center gap-3 shadow-2xs">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    <span class="font-body font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- ── Two-Column Layout: Start New Trial (Left) + Active Experiments (Right) ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- Left Column: Start New Trial Form (5 cols) --}}
                <div class="lg:col-span-5 space-y-6">
                    <div class="card-dark p-6">
                        <div class="flex items-center justify-between mb-5 pb-3 border-b border-gray-100">
                            <div>
                                <p class="section-label">Trial Registration</p>
                                <h3 class="font-heading text-xl font-bold text-gray-800 mt-0.5">Start 4-Week Trial</h3>
                            </div>
                            <span class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 font-bold">
                                🌿
                            </span>
                        </div>

                        <form method="POST" action="{{ route('admin.experiments.store') }}" class="space-y-5">
                            @csrf

                            {{-- Plant Species --}}
                            <div>
                                <label for="plant_species" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-1.5">
                                    Plant Species / Name
                                </label>
                                <input
                                    id="plant_species"
                                    type="text"
                                    name="plant_species"
                                    value="{{ old('plant_species') }}"
                                    required
                                    placeholder="e.g. Cherry Tomato (Solanum lycopersicum)"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-body text-gray-800 placeholder-gray-400 shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('plant_species') border-red-400 @enderror"
                                >
                                @error('plant_species')
                                    <p class="mt-1.5 text-xs text-red-600 font-body">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Fertilizer Type --}}
                            <div>
                                <label for="fertilizer_type" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-1.5">
                                    Trial Formula Type
                                </label>
                                <select
                                    id="fertilizer_type"
                                    name="fertilizer_type"
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-body text-gray-800 shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('fertilizer_type') border-red-400 @enderror"
                                >
                                    <option value="Organic" {{ old('fertilizer_type') === 'Organic' ? 'selected' : '' }}>🌱 Organic Bio-Fertilizer (EcoFert Recipe)</option>
                                    <option value="Commercial Control" {{ old('fertilizer_type') === 'Commercial Control' ? 'selected' : '' }}>🧪 Commercial Synthetic Control</option>
                                </select>
                                @error('fertilizer_type')
                                    <p class="mt-1.5 text-xs text-red-600 font-body">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Start Date --}}
                            <div>
                                <label for="start_date" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-1.5">
                                    Trial Start Date
                                </label>
                                <input
                                    id="start_date"
                                    type="date"
                                    name="start_date"
                                    value="{{ old('start_date', now()->format('Y-m-d')) }}"
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-body text-gray-800 shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('start_date') border-red-400 @enderror"
                                >
                                @error('start_date')
                                    <p class="mt-1.5 text-xs text-red-600 font-body">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <button
                                type="submit"
                                class="btn-gold w-full rounded-xl py-3 px-5 text-sm font-body font-semibold tracking-wide flex items-center justify-center gap-2 shadow-sm transition-all"
                            >
                                <svg class="w-4 h-4 text-amber-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                Launch 4-Week Trial
                            </button>
                        </form>
                    </div>

                    <!-- Quick Summary Stat Cards -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="stat-card stat-card--gold text-center p-4">
                            <p class="text-2xl font-heading font-bold text-amber-700">{{ $experiments->count() }}</p>
                            <p class="text-[0.62rem] font-body font-semibold text-gray-400 uppercase tracking-wider mt-1">Total Experiments</p>
                        </div>
                        <div class="stat-card stat-card--emerald text-center p-4">
                            <p class="text-2xl font-heading font-bold text-emerald-700">{{ $experiments->where('fertilizer_type', 'Organic')->count() }}</p>
                            <p class="text-[0.62rem] font-body font-semibold text-gray-400 uppercase tracking-wider mt-1">Organic Formulas</p>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Active Experiments Cards (7 cols) --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-200">
                        <div>
                            <p class="section-label">Trial Monitoring</p>
                            <h3 class="font-heading text-xl font-bold text-gray-800 mt-0.5">My Plant Growth Trials</h3>
                        </div>
                        <span class="text-xs font-body text-gray-500">
                            Total: <strong class="text-gray-800 font-semibold">{{ $experiments->count() }}</strong> {{ Str::plural('trial', $experiments->count()) }}
                        </span>
                    </div>

                    @if($experiments->isEmpty())
                        <div class="card-dark p-12 text-center flex flex-col items-center justify-center">
                            <div class="w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-3xl mb-4 shadow-2xs">
                                🌱
                            </div>
                            <h4 class="font-heading font-bold text-gray-800 text-lg">No Active Experiments</h4>
                            <p class="text-sm font-body text-gray-500 mt-1.5 max-w-md leading-relaxed">
                                Register your first 4-week plant growth trial on the left to track weekly height, soil pH, and leaf vitality.
                            </p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($experiments as $exp)
                                @php
                                    $measurementsCount = $exp->growthMeasurements->count();
                                    $latestMeasurement = $exp->growthMeasurements->last();
                                    $progressPercent = min(100, ($measurementsCount / 4) * 100);
                                @endphp

                                <div class="card-dark p-6 transition-all hover:border-gray-300 shadow-2xs relative">
                                    
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100">
                                        <div class="flex items-center gap-3.5">
                                            <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-2xl shrink-0">
                                                {{ $exp->fertilizer_type === 'Organic' ? '🌿' : '🧪' }}
                                            </div>
                                            <div>
                                                <h4 class="font-heading font-bold text-gray-800 text-lg">
                                                    {{ $exp->plant_species ?: 'Unspecified Plant' }}
                                                </h4>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $exp->fertilizer_type === 'Organic' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-200' }}">
                                                        {{ $exp->fertilizer_type }}
                                                    </span>
                                                    <span class="text-xs text-gray-400 font-body">
                                                        Started {{ $exp->start_date ? $exp->start_date->format('M d, Y') : $exp->created_at->format('M d, Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="{{ route('admin.experiments.show', $exp->experiment_id) }}" 
                                           class="btn-gold px-4 py-2 rounded-xl text-xs font-body font-semibold tracking-wide inline-flex items-center justify-center gap-1.5 shrink-0 shadow-2xs">
                                            View Timeline &amp; Log
                                            <svg class="w-3.5 h-3.5 text-amber-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                                            </svg>
                                        </a>
                                    </div>

                                    <!-- 4-Week Progress Indicator -->
                                    <div class="mt-4">
                                        <div class="flex items-center justify-between text-xs font-body mb-1.5">
                                            <span class="text-gray-500 font-medium">Trial Progress</span>
                                            <span class="text-emerald-700 font-semibold">{{ $measurementsCount }} / 4 Weeks Logged</span>
                                        </div>
                                        <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden border border-gray-200">
                                            <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-600 transition-all duration-500 rounded-full" 
                                                 style="width: {{ $progressPercent }}%;"></div>
                                        </div>
                                    </div>

                                    <!-- Latest Metric Highlights -->
                                    <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-3 gap-3 text-center text-xs font-body">
                                        <div class="bg-gray-50 rounded-lg p-2.5 border border-gray-100">
                                            <p class="text-gray-400 text-[0.65rem] font-semibold uppercase">Height</p>
                                            <p class="font-bold text-gray-800 text-sm mt-0.5">
                                                {{ $latestMeasurement ? ($latestMeasurement->plant_height_cm + 0) . ' cm' : 'Pending' }}
                                            </p>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-2.5 border border-gray-100">
                                            <p class="text-gray-400 text-[0.65rem] font-semibold uppercase">Soil pH</p>
                                            <p class="font-bold text-emerald-700 text-sm mt-0.5">
                                                {{ $latestMeasurement ? ($latestMeasurement->soil_pH + 0) : 'Pending' }}
                                            </p>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-2.5 border border-gray-100">
                                            <p class="text-gray-400 text-[0.65rem] font-semibold uppercase">Vitality</p>
                                            <p class="font-bold text-amber-700 text-sm mt-0.5">
                                                {{ $latestMeasurement ? $latestMeasurement->leaf_vitality : 'Pending' }}
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>{{-- /grid --}}

        </div>
    </div>
</x-admin-layout>
