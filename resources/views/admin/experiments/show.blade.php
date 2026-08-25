<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.experiments.index') }}" 
                   class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:text-emerald-700 hover:border-gray-300 transition-colors shadow-2xs shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-heading text-2xl sm:text-3xl font-bold text-gray-800 tracking-wide">
                        {{ $experiment->plant_species ?: 'Plant Trial Timeline' }}
                    </h2>
                    <p class="text-sm font-body text-gray-500 mt-0.5">
                        Formula: <span class="text-emerald-700 font-semibold">{{ $experiment->fertilizer_type }}</span> &middot; Started {{ $experiment->start_date ? $experiment->start_date->format('M d, Y') : $experiment->created_at->format('M d, Y') }}
                    </p>
                </div>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold font-body">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Trial ID #{{ $experiment->experiment_id }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Flash Notification --}}
            @if(session('success'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 text-sm flex items-center gap-3 shadow-2xs font-body">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- ── 4-Week Timeline Overview Bar ─────────────────────────────── --}}
            @php
                $measurementsByWeek = $experiment->growthMeasurements->keyBy('week_number');
                $nextAvailableWeek = 1;
                for ($w = 1; $w <= 4; $w++) {
                    if (!$measurementsByWeek->has($w)) {
                        $nextAvailableWeek = $w;
                        break;
                    }
                    if ($w === 4 && $measurementsByWeek->has(4)) {
                        $nextAvailableWeek = 4;
                    }
                }
            @endphp

            <div class="card-dark p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <div>
                        <p class="section-label">Trial Progress</p>
                        <h3 class="font-heading text-xl font-bold text-gray-800 mt-0.5">4-Week Growth Timeline</h3>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-heading font-bold text-emerald-700">{{ $measurementsByWeek->count() }}/4</span>
                        <span class="text-xs text-gray-400 font-body block">Weeks Logged</span>
                    </div>
                </div>

                {{-- Timeline Cards Grid (Weeks 1 to 4) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @for($w = 1; $w <= 4; $w++)
                        @php
                            $m = $measurementsByWeek->get($w);
                            $isLogged = $m !== null;
                        @endphp

                        <div class="rounded-xl p-5 border transition-all relative overflow-hidden {{ $isLogged ? 'bg-emerald-50/50 border-emerald-200 shadow-2xs' : 'bg-gray-50/80 border-gray-200' }}">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-heading font-bold text-xs uppercase tracking-wider {{ $isLogged ? 'text-emerald-900' : 'text-gray-400' }}">
                                    Week {{ $w }}
                                </span>
                                @if($isLogged)
                                    <span class="inline-flex items-center gap-1 text-[0.65rem] font-semibold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full border border-emerald-200">
                                        ✓ Recorded
                                    </span>
                                @else
                                    <span class="text-[0.65rem] text-gray-400 font-body bg-gray-100 px-2 py-0.5 rounded-full border border-gray-200">
                                        Pending
                                    </span>
                                @endif
                            </div>

                            @if($isLogged)
                                <div class="space-y-2 mt-2 font-body text-xs">
                                    <div class="flex items-center justify-between bg-white p-2 rounded-lg border border-emerald-100 shadow-2xs">
                                        <span class="text-gray-500">Height:</span>
                                        <span class="font-bold text-gray-800 text-sm">{{ $m->plant_height_cm + 0 }} cm</span>
                                    </div>
                                    <div class="flex items-center justify-between bg-white p-2 rounded-lg border border-emerald-100 shadow-2xs">
                                        <span class="text-gray-500">Soil pH:</span>
                                        <span class="font-bold text-emerald-700 text-sm">{{ $m->soil_pH + 0 }}</span>
                                    </div>
                                    <div class="flex items-center justify-between bg-white p-2 rounded-lg border border-emerald-100 shadow-2xs">
                                        <span class="text-gray-500">Vitality:</span>
                                        <span class="font-bold text-amber-700">{{ $m->leaf_vitality }}</span>
                                    </div>
                                    <p class="text-[0.65rem] text-gray-400 text-right pt-1">
                                        Logged: {{ $m->created_at->format('M d') }}
                                    </p>
                                </div>
                            @else
                                <div class="py-6 flex flex-col items-center justify-center text-center">
                                    <span class="text-2xl text-gray-300 mb-2">⏱️</span>
                                    <p class="text-xs text-gray-400 font-body">Awaiting Week {{ $w }} log</p>
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>

            {{-- ── Two-Column Section: Log Weekly Reading (Left) + Detailed History (Right) ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- Left Column: Weekly Measurement Form (5 cols) --}}
                <div class="lg:col-span-5 space-y-6">
                    <div class="card-dark p-6">
                        <div class="flex items-center justify-between mb-5 pb-3 border-b border-gray-100">
                            <div>
                                <p class="section-label">Weekly Entry</p>
                                <h3 class="font-heading text-xl font-bold text-gray-800 mt-0.5">Submit Measurement</h3>
                            </div>
                            <span class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 font-bold">
                                📏
                            </span>
                        </div>

                        <form method="POST" action="{{ route('admin.experiments.measurements.store', $experiment->experiment_id) }}" class="space-y-5">
                            @csrf

                            {{-- Week Number Select --}}
                            <div>
                                <label for="week_number" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-1.5">
                                    Select Week Number
                                </label>
                                <select
                                    id="week_number"
                                    name="week_number"
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-body text-gray-800 shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('week_number') border-red-400 @enderror"
                                >
                                    @for($w = 1; $w <= 4; $w++)
                                        <option value="{{ $w }}" {{ old('week_number', $nextAvailableWeek) == $w ? 'selected' : '' }}>
                                            Week {{ $w }} {{ $measurementsByWeek->has($w) ? '(Update existing)' : '(New entry)' }}
                                        </option>
                                    @endfor
                                </select>
                                @error('week_number')
                                    <p class="mt-1.5 text-xs text-red-600 font-body">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Plant Height in cm --}}
                            <div>
                                <label for="plant_height_cm" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-1.5">
                                    Plant Height (cm)
                                </label>
                                <input
                                    id="plant_height_cm"
                                    type="number"
                                    name="plant_height_cm"
                                    step="0.1"
                                    min="0"
                                    max="500"
                                    value="{{ old('plant_height_cm') }}"
                                    required
                                    placeholder="e.g. 14.5"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-body text-gray-800 placeholder-gray-400 shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('plant_height_cm') border-red-400 @enderror"
                                >
                                @error('plant_height_cm')
                                    <p class="mt-1.5 text-xs text-red-600 font-body">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Soil pH --}}
                            <div>
                                <label for="soil_pH" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-1.5">
                                    Soil pH Level (0.0 - 14.0)
                                </label>
                                <input
                                    id="soil_pH"
                                    type="number"
                                    name="soil_pH"
                                    step="0.1"
                                    min="0"
                                    max="14"
                                    value="{{ old('soil_pH', '6.5') }}"
                                    required
                                    placeholder="e.g. 6.5"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-body text-gray-800 placeholder-gray-400 shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('soil_pH') border-red-400 @enderror"
                                >
                                @error('soil_pH')
                                    <p class="mt-1.5 text-xs text-red-600 font-body">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Leaf Vitality Rating --}}
                            <div>
                                <label for="leaf_vitality" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-1.5">
                                    Leaf Vitality Health Rating
                                </label>
                                <select
                                    id="leaf_vitality"
                                    name="leaf_vitality"
                                    required
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-body text-gray-800 shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('leaf_vitality') border-red-400 @enderror"
                                >
                                    <option value="Vibrant" {{ old('leaf_vitality') === 'Vibrant' ? 'selected' : '' }}>🌿 Vibrant (Lush & Deep Green)</option>
                                    <option value="Excellent" {{ old('leaf_vitality', 'Excellent') === 'Excellent' ? 'selected' : '' }}>✨ Excellent (Healthy Growth)</option>
                                    <option value="Good" {{ old('leaf_vitality') === 'Good' ? 'selected' : '' }}>🌱 Good (Normal Development)</option>
                                    <option value="Fair" {{ old('leaf_vitality') === 'Fair' ? 'selected' : '' }}>🍂 Fair (Slight Yellowing)</option>
                                    <option value="Poor" {{ old('leaf_vitality') === 'Poor' ? 'selected' : '' }}>🥀 Poor (Stunted / Wilting)</option>
                                </select>
                                @error('leaf_vitality')
                                    <p class="mt-1.5 text-xs text-red-600 font-body">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Submit CTA --}}
                            <button
                                type="submit"
                                class="btn-gold w-full rounded-xl py-3 px-5 text-sm font-body font-semibold tracking-wide flex items-center justify-center gap-2 shadow-sm transition-all"
                            >
                                <svg class="w-4 h-4 text-amber-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                Save Weekly Reading
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Right Column: Measurement History Table (7 cols) --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-200">
                        <div>
                            <p class="section-label">Log Audit</p>
                            <h3 class="font-heading text-xl font-bold text-gray-800 mt-0.5">Recorded Weekly Metrics</h3>
                        </div>
                        <span class="text-xs font-body text-gray-500">
                            Total: <strong class="text-gray-800 font-semibold">{{ $experiment->growthMeasurements->count() }}</strong> recorded
                        </span>
                    </div>

                    @if($experiment->growthMeasurements->isEmpty())
                        <div class="card-dark p-10 text-center flex flex-col items-center justify-center">
                            <span class="text-3xl mb-3">📊</span>
                            <h4 class="font-heading font-bold text-gray-800 text-base">No Readings Recorded Yet</h4>
                            <p class="text-xs font-body text-gray-500 mt-1 max-w-sm">
                                Submit Week 1 measurement using the form on the left to start tracking growth progress.
                            </p>
                        </div>
                    @else
                        <div class="card-dark overflow-hidden shadow-2xs">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50/80 border-b border-gray-200">
                                            <th class="px-5 py-3.5 text-[0.7rem] font-body font-bold uppercase tracking-wider text-gray-400">Week</th>
                                            <th class="px-5 py-3.5 text-[0.7rem] font-body font-bold uppercase tracking-wider text-gray-400">Height (cm)</th>
                                            <th class="px-5 py-3.5 text-[0.7rem] font-body font-bold uppercase tracking-wider text-gray-400">Soil pH</th>
                                            <th class="px-5 py-3.5 text-[0.7rem] font-body font-bold uppercase tracking-wider text-gray-400">Vitality</th>
                                            <th class="px-5 py-3.5 text-[0.7rem] font-body font-bold uppercase tracking-wider text-gray-400 text-right">Logged</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white font-body text-sm">
                                        @foreach($experiment->growthMeasurements as $m)
                                            <tr class="row-glow hover:bg-gray-50/60 transition-colors">
                                                <td class="px-5 py-4 font-bold text-emerald-800 font-heading">
                                                    Week {{ $m->week_number }}
                                                </td>
                                                <td class="px-5 py-4 font-semibold text-gray-800">
                                                    {{ $m->plant_height_cm + 0 }} cm
                                                </td>
                                                <td class="px-5 py-4 text-emerald-700">
                                                    {{ $m->soil_pH + 0 }}
                                                </td>
                                                <td class="px-5 py-4">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border bg-amber-50 text-amber-800 border-amber-200">
                                                        {{ $m->leaf_vitality }}
                                                    </span>
                                                </td>
                                                <td class="px-5 py-4 text-xs text-gray-400 text-right">
                                                    {{ $m->created_at->diffForHumans() }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

            </div>{{-- /grid --}}

        </div>
    </div>
</x-admin-layout>
