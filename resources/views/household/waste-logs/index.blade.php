<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-heading text-2xl sm:text-3xl font-bold text-gray-800 tracking-wide">
                    Waste Logging & Recommendations
                </h2>
                <p class="text-sm text-gray-500 font-body mt-1">
                    Welcome back, <span class="font-semibold text-emerald-700">{{ Auth::user()->name }}</span> &middot; Home Gardener Dashboard
                </p>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold font-body">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Log Waste Permission Active
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- ── Banner Notice (no formulation found fallback) ────────────────── --}}
            @if(session('info'))
                <div class="rounded-xl border border-amber-200 bg-amber-50/90 p-4 text-amber-800 text-sm flex items-center gap-3 shadow-sm">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold">Notice</p>
                        <p class="text-xs text-amber-700 mt-0.5">{{ session('info') }}</p>
                    </div>
                </div>
            @endif

            {{-- ── Recommendation Hero Card (Shown dynamically after submission) ── --}}
            @if(session('recommendation'))
                @php
                    /** @var \App\Models\ApprovedFormulation $rec */
                    $rec = session('recommendation');
                    $loggedType = session('logged_waste_type');
                    $theme = match($loggedType) {
                        'Banana Peels'   => ['bg' => 'bg-yellow-500', 'border' => 'border-yellow-200', 'badge' => 'bg-yellow-100 text-yellow-800 border-yellow-300', 'accent' => 'text-yellow-700', 'icon' => '🍌'],
                        'Eggshells'      => ['bg' => 'bg-orange-500', 'border' => 'border-orange-200', 'badge' => 'bg-orange-100 text-orange-800 border-orange-300', 'accent' => 'text-orange-700', 'icon' => '🥚'],
                        'Coffee Grounds' => ['bg' => 'bg-amber-600',  'border' => 'border-amber-200',  'badge' => 'bg-amber-100  text-amber-800  border-amber-300',  'accent' => 'text-amber-700',  'icon' => '☕'],
                        default          => ['bg' => 'bg-emerald-600','border' => 'border-emerald-200','badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300','accent' => 'text-emerald-700','icon' => '🌱'],
                    };
                @endphp

                <div class="card-dark overflow-hidden border-2 border-emerald-500/80 shadow-lg relative">
                    <!-- Top Ribbon Header -->
                    <div class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-900 p-6 text-white relative overflow-hidden">
                        <div class="absolute right-0 top-0 translate-x-4 -translate-y-4 opacity-10 text-8xl pointer-events-none select-none">
                            {{ $theme['icon'] }}
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-4 relative z-10">
                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-xl bg-emerald-700/80 border border-emerald-500/50 flex items-center justify-center text-2xl shrink-0 shadow-inner">
                                    {{ $theme['icon'] }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[0.65rem] font-bold uppercase tracking-widest bg-emerald-500/30 text-emerald-200 px-2 py-0.5 rounded">
                                            Instant Matched Guide
                                        </span>
                                        <span class="text-xs text-emerald-300 font-body">Recorded Today</span>
                                    </div>
                                    <h3 class="font-heading text-xl sm:text-2xl font-bold text-white mt-1">
                                        Fertilizer Recipe for {{ $loggedType }}
                                    </h3>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $theme['badge'] }}">
                                <span>Target:</span> {{ $loggedType }}
                            </span>
                        </div>
                    </div>

                    <!-- Formulation Details Grid -->
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 bg-white">
                        {{-- Preparation Steps --}}
                        <div class="rounded-xl bg-gray-50 border border-gray-200/80 p-5 flex flex-col justify-between hover:border-gray-300 transition-all">
                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center text-amber-700 font-bold text-xs">
                                        1
                                    </div>
                                    <h4 class="font-body font-semibold text-gray-800 text-sm uppercase tracking-wide">
                                        Preparation Steps
                                    </h4>
                                </div>
                                <div class="text-sm text-gray-700 font-body leading-relaxed whitespace-pre-line bg-white rounded-lg p-4 border border-gray-100 shadow-2xs">
                                    {{ $rec->preparation_steps }}
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-gray-200/60 flex items-center justify-between text-xs text-gray-400 font-body">
                                <span>Prep Time: ~10 mins</span>
                                <span class="text-emerald-600 font-medium">Step-by-Step Guide</span>
                            </div>
                        </div>

                        {{-- Application Guidance --}}
                        <div class="rounded-xl bg-emerald-50/60 border border-emerald-200/80 p-5 flex flex-col justify-between hover:border-emerald-300 transition-all">
                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-7 h-7 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold text-xs">
                                        2
                                    </div>
                                    <h4 class="font-body font-semibold text-emerald-900 text-sm uppercase tracking-wide">
                                        Application Guidance
                                    </h4>
                                </div>
                                <div class="text-sm text-emerald-950 font-body leading-relaxed whitespace-pre-line bg-white/90 rounded-lg p-4 border border-emerald-100 shadow-2xs">
                                    {{ $rec->application_guidance }}
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-t border-emerald-200/60 flex items-center justify-between text-xs text-emerald-700 font-body">
                                <span>Optimal Frequency: Weekly</span>
                                <span class="font-semibold text-emerald-700">Ready to Apply</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── Main Two-Column Layout: Record Waste (Left) + Waste History (Right) ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- ── Left Column: Waste Submission Form (5 cols) ──────────────── --}}
                @if(Auth::user()->can('log_waste') || Auth::user()->isHousehold())
                <div class="lg:col-span-5 space-y-6">
                    <div class="card-dark p-6">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <p class="section-label">Action</p>
                                <h3 class="font-heading text-xl font-bold text-gray-800 mt-0.5">Record Kitchen Waste</h3>
                            </div>
                            <span class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                            </span>
                        </div>

                        <form method="POST" action="{{ route('household.waste-logs.store') }}" id="waste-log-form">
                            @csrf

                            {{-- Visual Choice Cards for Waste Type --}}
                            <div class="mb-5">
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-3">
                                    Select Organic Waste Type
                                </label>

                                <div class="space-y-3" x-data="{ selectedType: '{{ old('waste_type', 'Banana Peels') }}' }">
                                    {{-- Banana Peels --}}
                                    <label 
                                        @click="selectedType = 'Banana Peels'"
                                        :class="selectedType === 'Banana Peels' ? 'border-amber-500 bg-amber-50/50 ring-1 ring-amber-400' : 'border-gray-200 bg-white hover:border-gray-300'"
                                        class="flex items-center justify-between p-3.5 rounded-xl border cursor-pointer transition-all duration-150"
                                    >
                                        <div class="flex items-center gap-3">
                                            <span class="text-2xl p-2 rounded-lg bg-yellow-100/80">🍌</span>
                                            <div>
                                                <p class="font-body font-semibold text-sm text-gray-800">Banana Peels</p>
                                                <p class="text-xs text-gray-500 font-body">Rich in Potassium (K) &amp; Phosphorus</p>
                                            </div>
                                        </div>
                                        <input 
                                            type="radio" 
                                            name="waste_type" 
                                            value="Banana Peels" 
                                            x-model="selectedType" 
                                            class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300"
                                        >
                                    </label>

                                    {{-- Eggshells --}}
                                    <label 
                                        @click="selectedType = 'Eggshells'"
                                        :class="selectedType === 'Eggshells' ? 'border-orange-500 bg-orange-50/50 ring-1 ring-orange-400' : 'border-gray-200 bg-white hover:border-gray-300'"
                                        class="flex items-center justify-between p-3.5 rounded-xl border cursor-pointer transition-all duration-150"
                                    >
                                        <div class="flex items-center gap-3">
                                            <span class="text-2xl p-2 rounded-lg bg-orange-100/80">🥚</span>
                                            <div>
                                                <p class="font-body font-semibold text-sm text-gray-800">Eggshells</p>
                                                <p class="text-xs text-gray-500 font-body">High Calcium (Ca) for root strength</p>
                                            </div>
                                        </div>
                                        <input 
                                            type="radio" 
                                            name="waste_type" 
                                            value="Eggshells" 
                                            x-model="selectedType" 
                                            class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300"
                                        >
                                    </label>

                                    {{-- Coffee Grounds --}}
                                    <label 
                                        @click="selectedType = 'Coffee Grounds'"
                                        :class="selectedType === 'Coffee Grounds' ? 'border-amber-700 bg-amber-50/70 ring-1 ring-amber-600' : 'border-gray-200 bg-white hover:border-gray-300'"
                                        class="flex items-center justify-between p-3.5 rounded-xl border cursor-pointer transition-all duration-150"
                                    >
                                        <div class="flex items-center gap-3">
                                            <span class="text-2xl p-2 rounded-lg bg-amber-100/80">☕</span>
                                            <div>
                                                <p class="font-body font-semibold text-sm text-gray-800">Coffee Grounds</p>
                                                <p class="text-xs text-gray-500 font-body">Nitrogen Booster (N) &amp; Soil Aeration</p>
                                            </div>
                                        </div>
                                        <input 
                                            type="radio" 
                                            name="waste_type" 
                                            value="Coffee Grounds" 
                                            x-model="selectedType" 
                                            class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300"
                                        >
                                    </label>
                                </div>

                                @error('waste_type')
                                    <p class="mt-2 text-xs text-red-600 font-body">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Quantity & Unit Inputs --}}
                            <div class="mb-5 grid grid-cols-3 gap-3">
                                <div class="col-span-2">
                                    <label for="quantity" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-1.5">
                                        Quantity / Amount
                                    </label>
                                    <input
                                        id="quantity"
                                        type="number"
                                        name="quantity"
                                        step="0.01"
                                        min="0.01"
                                        max="9999.99"
                                        value="{{ old('quantity') }}"
                                        placeholder="e.g. 0.50"
                                        class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm font-body text-gray-800 shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('quantity') border-red-400 @enderror"
                                    >
                                    @error('quantity')
                                        <p class="mt-1 text-xs text-red-600 font-body">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="unit" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-1.5">
                                        Unit
                                    </label>
                                    <select
                                        id="unit"
                                        name="unit"
                                        class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-body text-gray-800 shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('unit') border-red-400 @enderror"
                                    >
                                        <option value="kg" {{ old('unit') === 'kg' ? 'selected' : '' }}>kg</option>
                                        <option value="g" {{ old('unit') === 'g' ? 'selected' : '' }}>g</option>
                                        <option value="items" {{ old('unit') === 'items' ? 'selected' : '' }}>items</option>
                                    </select>
                                    @error('unit')
                                        <p class="mt-1 text-xs text-red-600 font-body">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Date Indicator --}}
                            <div class="mb-6">
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-1.5">
                                    Date Recorded
                                </label>
                                <div class="flex items-center justify-between rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-body text-gray-700">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5"/>
                                        </svg>
                                        {{ now()->format('l, d M Y') }}
                                    </span>
                                    <span class="text-xs text-gray-400 font-medium">Automatic</span>
                                </div>
                            </div>

                            {{-- Submit CTA --}}
                            <button
                                type="submit"
                                class="btn-gold w-full rounded-xl px-5 py-3 text-sm font-body font-semibold tracking-wide flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all"
                            >
                                <svg class="w-4 h-4 text-amber-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23.693L5 14.5"/>
                                </svg>
                                Log Waste &amp; Get Formulation
                            </button>
                        </form>
                    </div>

                    {{-- Waste Breakdown Stat Cards --}}
                    @php
                        $counts = $wasteLogs->groupBy('waste_type')->map->count();
                    @endphp
                    <div class="grid grid-cols-3 gap-3">
                        <div class="stat-card stat-card--gold p-4 text-center rounded-xl bg-white border border-gray-200 shadow-2xs">
                            <span class="text-xl">🍌</span>
                            <p class="text-2xl font-heading font-bold text-amber-700 mt-1">{{ $counts->get('Banana Peels', 0) }}</p>
                            <p class="text-[0.62rem] text-gray-400 font-body font-semibold uppercase tracking-wider mt-0.5">Banana Peels</p>
                        </div>
                        <div class="stat-card stat-card--emerald p-4 text-center rounded-xl bg-white border border-gray-200 shadow-2xs">
                            <span class="text-xl">🥚</span>
                            <p class="text-2xl font-heading font-bold text-emerald-700 mt-1">{{ $counts->get('Eggshells', 0) }}</p>
                            <p class="text-[0.62rem] text-gray-400 font-body font-semibold uppercase tracking-wider mt-0.5">Eggshells</p>
                        </div>
                        <div class="stat-card stat-card--amber p-4 text-center rounded-xl bg-white border border-gray-200 shadow-2xs">
                            <span class="text-xl">☕</span>
                            <p class="text-2xl font-heading font-bold text-amber-600 mt-1">{{ $counts->get('Coffee Grounds', 0) }}</p>
                            <p class="text-[0.62rem] text-gray-400 font-body font-semibold uppercase tracking-wider mt-0.5">Coffee Grounds</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ── Right Column: Waste Log History Table (7 cols) ───────────── --}}
                <div class="{{ (Auth::user()->can('log_waste') || Auth::user()->isHousehold()) ? 'lg:col-span-7' : 'lg:col-span-12' }} space-y-4">
                    <div class="flex items-center justify-between mb-1">
                        <div>
                            <p class="section-label">Log History</p>
                            <h3 class="font-heading text-xl font-bold text-gray-800 mt-0.5">My Recorded Waste Logs</h3>
                        </div>
                        <span class="text-xs text-gray-500 font-body">
                            Total: <strong class="text-gray-800 font-semibold">{{ $wasteLogs->count() }}</strong> entries
                        </span>
                    </div>

                    @if($wasteLogs->isEmpty())
                        <div class="card-dark p-12 flex flex-col items-center justify-center text-center">
                            <div class="w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center mb-4 shadow-2xs">
                                <span class="text-3xl">🌿</span>
                            </div>
                            <h4 class="font-heading font-bold text-gray-800 text-lg">No Waste Logged Yet</h4>
                            <p class="text-sm text-gray-500 font-body mt-1.5 max-w-sm leading-relaxed">
                                Select an organic waste type on the left to start converting your kitchen scraps into valuable fertilizer recipes.
                            </p>
                        </div>
                    @else
                        <div class="card-dark overflow-hidden shadow-2xs">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50/80 border-b border-gray-200">
                                            <th class="px-5 py-3.5 text-[0.7rem] font-body font-bold uppercase tracking-wider text-gray-400">#</th>
                                            <th class="px-5 py-3.5 text-[0.7rem] font-body font-bold uppercase tracking-wider text-gray-400">Waste Item</th>
                                            <th class="px-5 py-3.5 text-[0.7rem] font-body font-bold uppercase tracking-wider text-gray-400">Amount</th>
                                            <th class="px-5 py-3.5 text-[0.7rem] font-body font-bold uppercase tracking-wider text-gray-400">Date Recorded</th>
                                            <th class="px-5 py-3.5 text-[0.7rem] font-body font-bold uppercase tracking-wider text-gray-400 text-right">Relative</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        @foreach($wasteLogs as $index => $log)
                                            @php
                                                $badge = match($log->waste_type) {
                                                    'Banana Peels'   => ['class' => 'bg-yellow-50 text-yellow-800 border-yellow-200', 'icon' => '🍌'],
                                                    'Eggshells'      => ['class' => 'bg-orange-50 text-orange-800 border-orange-200', 'icon' => '🥚'],
                                                    'Coffee Grounds' => ['class' => 'bg-amber-50 text-amber-800 border-amber-200', 'icon' => '☕'],
                                                    default          => ['class' => 'bg-gray-50 text-gray-700 border-gray-200', 'icon' => '🍃'],
                                                };
                                            @endphp
                                            <tr class="row-glow hover:bg-gray-50/60 transition-colors">
                                                <td class="px-5 py-4 text-xs font-mono text-gray-400">
                                                    {{ sprintf('%02d', $index + 1) }}
                                                </td>
                                                <td class="px-5 py-4">
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $badge['class'] }}">
                                                        <span>{{ $badge['icon'] }}</span>
                                                        {{ $log->waste_type }}
                                                    </span>
                                                </td>
                                                <td class="px-5 py-4 text-sm font-semibold text-gray-800 font-body">
                                                    {{ $log->quantity ? ($log->quantity + 0) . ' ' . ($log->unit ?? 'kg') : '—' }}
                                                </td>
                                                <td class="px-5 py-4 text-sm text-gray-700 font-body">
                                                    {{ $log->date_recorded ? $log->date_recorded->format('M d, Y') : now()->format('M d, Y') }}
                                                </td>
                                                <td class="px-5 py-4 text-xs text-gray-400 font-body text-right">
                                                    {{ $log->created_at ? $log->created_at->diffForHumans() : 'Just now' }}
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
