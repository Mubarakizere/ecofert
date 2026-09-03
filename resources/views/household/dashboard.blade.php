<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight leading-tight">
                    Household Waste & Fertilizer Portal
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Record kitchen food waste, view nutrient potential, and manage organic fertilizer batches.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('household.recommendations.index') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition">
                    View Rule Recommendations
                </a>
                <a href="{{ route('household.experiments.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-900 border border-transparent rounded-lg text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition">
                    4-Week Plant Trials
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

            <!-- Top Row: Stock Inventory & Nutrient Potential Gauges -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Waste Stock Inventory Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 md:col-span-1">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                        <h3 class="text-base font-bold text-slate-900">Current Waste Inventory</h3>
                        <span class="text-xs font-mono px-2 py-1 bg-slate-100 text-slate-600 rounded">Raw Stock</span>
                    </div>

                    <div class="space-y-4">
                        @php
                            $bananaQty = $wasteStocks->get('Banana Peels')?->quantity ?? 0;
                            $eggshellQty = $wasteStocks->get('Eggshells')?->quantity ?? 0;
                            $coffeeQty = $wasteStocks->get('Coffee Grounds')?->quantity ?? 0;
                        @endphp

                        <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg border border-amber-100">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Banana Peels</p>
                                <p class="text-sm text-slate-500">Rich in Potassium (K)</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-bold text-slate-900">{{ number_format($bananaQty, 2) }}</span>
                                <span class="text-xs text-slate-500">kg</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-stone-50 rounded-lg border border-stone-200">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-stone-700">Eggshells</p>
                                <p class="text-sm text-slate-500">Rich in Calcium (Ca)</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-bold text-slate-900">{{ number_format($eggshellQty, 2) }}</span>
                                <span class="text-xs text-slate-500">kg</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-800">Coffee Grounds</p>
                                <p class="text-sm text-slate-500">Rich in Nitrogen (N)</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-bold text-slate-900">{{ number_format($coffeeQty, 2) }}</span>
                                <span class="text-xs text-slate-500">kg</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nutrient Potential Gauges Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 md:col-span-2">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Soil Nutrient Stock Potential</h3>
                            <p class="text-xs text-slate-500">Calculated N-P-K nutrient capacity from logged household waste</p>
                        </div>
                        <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">Rule Analysis</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-2">
                        <!-- Nitrogen -->
                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-bold text-slate-700 uppercase">Nitrogen (N)</span>
                                <span class="text-xs font-bold text-slate-900">{{ $nutrientMeters['nitrogen']['score'] }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2 mb-3">
                                <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $nutrientMeters['nitrogen']['score'] }}%"></div>
                            </div>
                            <div class="flex justify-between items-center text-xs text-slate-500">
                                <span>Status: <strong class="text-slate-900">{{ $nutrientMeters['nitrogen']['rating'] }}</strong></span>
                                <span>{{ $nutrientMeters['nitrogen']['source_qty'] }}kg</span>
                            </div>
                        </div>

                        <!-- Potassium -->
                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-bold text-slate-700 uppercase">Potassium (K)</span>
                                <span class="text-xs font-bold text-slate-900">{{ $nutrientMeters['potassium']['score'] }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2 mb-3">
                                <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $nutrientMeters['potassium']['score'] }}%"></div>
                            </div>
                            <div class="flex justify-between items-center text-xs text-slate-500">
                                <span>Status: <strong class="text-slate-900">{{ $nutrientMeters['potassium']['rating'] }}</strong></span>
                                <span>{{ $nutrientMeters['potassium']['source_qty'] }}kg</span>
                            </div>
                        </div>

                        <!-- Calcium -->
                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-bold text-slate-700 uppercase">Calcium (Ca)</span>
                                <span class="text-xs font-bold text-slate-900">{{ $nutrientMeters['calcium']['score'] }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2 mb-3">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $nutrientMeters['calcium']['score'] }}%"></div>
                            </div>
                            <div class="flex justify-between items-center text-xs text-slate-500">
                                <span>Status: <strong class="text-slate-900">{{ $nutrientMeters['calcium']['rating'] }}</strong></span>
                                <span>{{ $nutrientMeters['calcium']['source_qty'] }}kg</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Rule Engine Recommendation Summary:</span>
                        <a href="{{ route('household.recommendations.index') }}" class="font-semibold text-emerald-600 hover:text-emerald-700 underline">
                            View {{ count($recommendations) }} Extension Formulations &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Second Row: Record Waste Form & Active Batches -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Log Waste Form Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 lg:col-span-1">
                    <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Record Food Waste</h3>

                    <form action="{{ route('household.waste-logs.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="waste_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Waste Material</label>
                            <select id="waste_type" name="waste_type" required class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="Banana Peels">Banana Peels</option>
                                <option value="Eggshells">Eggshells</option>
                                <option value="Coffee Grounds">Coffee Grounds</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="quantity" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Quantity</label>
                                <input type="number" step="0.01" min="0.01" max="9999.99" id="quantity" name="quantity" required placeholder="e.g. 1.5" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label for="unit" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Unit</label>
                                <select id="unit" name="unit" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="kg">Kilograms (kg)</option>
                                    <option value="g">Grams (g)</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-lg shadow-sm transition duration-150">
                            Add to Stock Inventory
                        </button>
                    </form>
                </div>

                <!-- Active Fermentation / Production Batches Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 lg:col-span-2">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Active Production Batches</h3>
                            <p class="text-xs text-slate-500">Fertilizer in aging or fermentation phase</p>
                        </div>
                        <a href="{{ route('household.batches.index') }}" class="text-xs font-semibold text-emerald-600 hover:underline">
                            Manage All Batches
                        </a>
                    </div>

                    @if($activeBatches->isEmpty())
                        <div class="text-center py-8 border-2 border-dashed border-slate-200 rounded-xl">
                            <p class="text-sm text-slate-500">No active fertilizer production batches.</p>
                            <p class="text-xs text-slate-400 mt-1">Check Rule Recommendations to start your first aging batch.</p>
                            <a href="{{ route('household.recommendations.index') }}" class="mt-3 inline-flex items-center text-xs font-semibold text-emerald-600 hover:text-emerald-700 underline">
                                Browse Recommended Formulations
                            </a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($activeBatches as $batch)
                                <div class="p-4 border border-slate-200 rounded-xl flex items-center justify-between bg-slate-50">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-xs font-bold text-slate-900 bg-white border border-slate-200 px-2 py-0.5 rounded">
                                                {{ $batch->batch_code }}
                                            </span>
                                            <h4 class="text-sm font-bold text-slate-900">{{ $batch->formulation->title ?? 'Organic Batch' }}</h4>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1">
                                            Started: {{ $batch->start_date->format('M d, Y') }} &bull; Ready: {{ $batch->estimated_ready_date->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <div>
                                        @if($batch->isReady())
                                            <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full border border-emerald-200">
                                                Ready to Use
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-semibold bg-amber-100 text-amber-800 rounded-full border border-amber-200">
                                                Aging ({{ now()->diffInDays($batch->estimated_ready_date, false) }} days left)
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            <!-- Third Row: Waste Log History Table -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Waste Log History</h3>

                @if($wasteLogs->isEmpty())
                    <p class="text-sm text-slate-500 text-center py-6">No waste logs recorded yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Waste Material</th>
                                    <th class="px-4 py-3">Transaction</th>
                                    <th class="px-4 py-3 text-right">Quantity</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($wasteLogs as $log)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ $log->date_recorded }}</td>
                                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $log->waste_type }}</td>
                                        <td class="px-4 py-3">
                                            @if($log->transaction_type === 'added')
                                                <span class="px-2 py-0.5 text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 rounded">Deposit</span>
                                            @else
                                                <span class="px-2 py-0.5 text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 rounded">Batch Usage</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono font-bold text-slate-900">
                                            {{ $log->transaction_type === 'added' ? '+' : '-' }}{{ number_format($log->quantity, 2) }} kg
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
