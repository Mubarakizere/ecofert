<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight leading-tight">
                    Extension-Validated Formulation Rules
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Deterministic rule matching engine assessing household stock feasibility against approved recipes.
                </p>
            </div>
            <a href="{{ route('household.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition">
                &larr; Back to Dashboard
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

            @if(session('ai_explanation'))
                @php $explanationData = session('ai_explanation'); @endphp
                <div class="p-6 bg-white border border-emerald-200 rounded-xl shadow-sm space-y-3">
                    <div class="flex items-center justify-between border-b border-emerald-100 pb-3">
                        <h3 class="text-base font-bold text-slate-900">
                            AI Extension Chemistry Analysis: {{ $explanationData['title'] }}
                        </h3>
                        <span class="text-xs font-mono px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded">
                            Gemini 3.6 Flash Response
                        </span>
                    </div>
                    <div class="text-sm text-slate-700 space-y-2 leading-relaxed">
                        {!! nl2br(e($explanationData['explanation'])) !!}
                    </div>
                </div>
            @endif

            <!-- Recommendations Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach($recommendations as $rec)
                    @php
                        $f = $rec['formulation'];
                        $status = $rec['status'];
                    @endphp

                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between space-y-6">

                        <div>
                            <!-- Header & Status Badge -->
                            <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                                <div>
                                    <span class="text-xs font-mono font-semibold text-emerald-600 uppercase tracking-wider">
                                        {{ $f->npk_ratio ? "NPK {$f->npk_ratio}" : 'Organic Formula' }}
                                    </span>
                                    <h3 class="text-lg font-bold text-slate-900 mt-0.5">
                                        {{ $f->title ?? $f->target_waste_type }}
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Primary Nutrients: <strong class="text-slate-700">{{ $f->primary_nutrients ?? 'Essential Minerals' }}</strong>
                                    </p>
                                </div>

                                <div>
                                    @if($status === 'ready')
                                        <span class="px-3 py-1 text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-full inline-block">
                                            Ready to Produce ({{ $rec['producible_batches'] }} Batches)
                                        </span>
                                    @elseif($status === 'partial')
                                        <span class="px-3 py-1 text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200 rounded-full inline-block">
                                            Partially Available ({{ $rec['overall_coverage_percent'] }}%)
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 rounded-full inline-block">
                                            Insufficient Stock
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Recipe Metrics Summary -->
                            <div class="grid grid-cols-3 gap-3 my-4 py-3 bg-slate-50 rounded-lg text-center border border-slate-100">
                                <div>
                                    <span class="text-xs text-slate-400 block font-medium">Target Waste</span>
                                    <span class="text-xs font-bold text-slate-800">{{ $f->target_waste_type }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-slate-400 block font-medium">Batch Yield</span>
                                    <span class="text-xs font-bold text-slate-800">{{ $f->yield_quantity }}{{ $f->yield_unit }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-slate-400 block font-medium">Fermentation</span>
                                    <span class="text-xs font-bold text-slate-800">{{ $f->fermentation_days }} Days</span>
                                </div>
                            </div>

                            <!-- Ingredient Rule Breakdown -->
                            <div class="space-y-3">
                                <h4 class="text-xs font-bold uppercase text-slate-700 tracking-wider">Required Ingredients & Stock Availability</h4>
                                @foreach($rec['ingredient_breakdown'] as $ing)
                                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-100 space-y-1.5">
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="font-semibold text-slate-900">{{ $ing['waste_type'] }}</span>
                                            <span class="text-slate-500">
                                                Available: <strong class="text-slate-800">{{ $ing['available_quantity'] }}kg</strong> / Needed: {{ $ing['required_quantity'] }}kg
                                            </span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                                            <div class="h-1.5 rounded-full {{ $ing['is_sufficient'] ? 'bg-emerald-600' : 'bg-amber-500' }}" style="width: {{ $ing['coverage_percent'] }}%"></div>
                                        </div>
                                        @if(!$ing['is_sufficient'])
                                            <p class="text-xs text-amber-700 font-medium">
                                                Requires {{ $ing['missing_quantity'] }}kg more to complete 1 full batch.
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Preparation Steps & Application Guidance -->
                            <div class="mt-4 pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-600">
                                <div>
                                    <strong class="text-slate-900 block font-semibold">Preparation Steps:</strong>
                                    <p class="mt-0.5 text-slate-600 leading-relaxed">{{ $f->preparation_steps }}</p>
                                </div>
                                <div class="mt-2">
                                    <strong class="text-slate-900 block font-semibold">Application Guidance:</strong>
                                    <p class="mt-0.5 text-slate-600 leading-relaxed">{{ $f->application_guidance }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                            <form action="{{ route('household.recommendations.explain', $f->formula_id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold rounded-lg transition">
                                    Explain Chemistry (AI)
                                </button>
                            </form>

                            @if($rec['producible_batches'] >= 1)
                                <form action="{{ route('household.batches.store') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="formulation_id" value="{{ $f->formula_id }}">
                                    <input type="hidden" name="batch_count" value="1">
                                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                        Start Production Batch
                                    </button>
                                </form>
                            @else
                                <button disabled class="px-4 py-2 bg-slate-200 text-slate-400 text-xs font-semibold rounded-lg cursor-not-allowed">
                                    Insufficient Stock
                                </button>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
