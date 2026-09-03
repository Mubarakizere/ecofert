<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight leading-tight">
                    4-Week Plant Growth Monitoring Suite
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Structured 4-week growth trial comparing homemade organic fertilizer against commercial fertilizer control.
                </p>
            </div>
            <a href="{{ route('household.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition">
                &larr; Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('info'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-sm font-medium rounded-r-lg shadow-sm">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Create New Plant Trial Form -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">
                    Initialize New Plant Growth Trial
                </h3>

                <form action="{{ route('household.experiments.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf
                    <div>
                        <label for="plant_species" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Plant Species</label>
                        <input type="text" id="plant_species" name="plant_species" required placeholder="e.g. Red Radish, Tomato, Maize" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label for="fertilizer_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Fertilizer Treatment Group</label>
                        <select id="fertilizer_type" name="fertilizer_type" required class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="Organic">Homemade Organic Fertilizer</option>
                            <option value="Commercial Control">Commercial Fertilizer Control</option>
                        </select>
                    </div>

                    <div>
                        <label for="start_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Start Date</label>
                        <input type="date" id="start_date" name="start_date" required value="{{ date('Y-m-d') }}" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-lg shadow-sm transition">
                            Start Trial
                        </button>
                    </div>
                </form>
            </div>

            <!-- Existing Plant Experiments Grid -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-4">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Active & Completed Growth Trials</h3>

                @if($experiments->isEmpty())
                    <p class="text-sm text-slate-500 text-center py-8">No plant growth trials recorded yet. Initialize a trial above to track height, pH, and vitality.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($experiments as $exp)
                            @php
                                $mCount = $exp->growthMeasurements->count();
                                $latest = $exp->growthMeasurements->sortByDesc('week_number')->first();
                            @endphp

                            <div class="p-5 border border-slate-200 rounded-xl bg-slate-50 flex flex-col justify-between space-y-4">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-mono font-semibold px-2 py-0.5 rounded {{ $exp->fertilizer_type === 'Organic' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-200 text-slate-700' }}">
                                            {{ $exp->fertilizer_type }}
                                        </span>
                                        <span class="text-xs text-slate-400 font-mono">{{ $exp->start_date }}</span>
                                    </div>

                                    <h4 class="text-lg font-bold text-slate-900 mt-2">{{ $exp->plant_species }}</h4>
                                    <p class="text-xs text-slate-500 mt-1">Logged Weeks: <strong class="text-slate-800">{{ $mCount }}/4 Weeks</strong></p>

                                    @if($latest)
                                        <div class="mt-3 p-3 bg-white rounded-lg border border-slate-200 grid grid-cols-3 gap-2 text-center">
                                            <div>
                                                <span class="text-xs text-slate-400 block font-medium">Height</span>
                                                <span class="text-xs font-bold text-slate-900">{{ $latest->plant_height_cm }} cm</span>
                                            </div>
                                            <div>
                                                <span class="text-xs text-slate-400 block font-medium">Soil pH</span>
                                                <span class="text-xs font-bold text-slate-900">{{ $latest->soil_pH }}</span>
                                            </div>
                                            <div>
                                                <span class="text-xs text-slate-400 block font-medium">Vitality</span>
                                                <span class="text-xs font-bold text-slate-900">{{ $latest->leaf_vitality }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <a href="{{ route('household.experiments.show', $exp->experiment_id) }}" class="w-full text-center py-2 px-3 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-lg transition">
                                    View Trial & Log Weekly Data &rarr;
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
