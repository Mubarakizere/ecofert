<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight leading-tight">
                    Active & Historic Production Batches
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Track the aging and fermentation lifecycle of your organic fertilizer batches.
                </p>
            </div>
            <a href="{{ route('household.recommendations.index') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                Start New Batch
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('info'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-sm font-medium rounded-r-lg shadow-sm">
                    {{ session('info') }}
                </div>
            @endif

            @if($batches->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
                    <h3 class="text-base font-bold text-slate-900">No Production Batches Found</h3>
                    <p class="text-sm text-slate-500 mt-1">You have not produced any organic fertilizer batches yet.</p>
                    <a href="{{ route('household.recommendations.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                        View Formulation Rules & Start Production
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 gap-6">
                    @foreach($batches as $batch)
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row md:items-center justify-between gap-6">

                            <div class="space-y-2">
                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-xs font-bold text-slate-900 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded">
                                        {{ $batch->batch_code }}
                                    </span>
                                    <h3 class="text-base font-bold text-slate-900">
                                        {{ $batch->formulation->title ?? 'Organic Batch' }}
                                    </h3>
                                </div>

                                <p class="text-xs text-slate-500">
                                    Production Started: <strong class="text-slate-700">{{ $batch->start_date->format('M d, Y') }}</strong> &bull; Estimated Ready Date: <strong class="text-slate-700">{{ $batch->estimated_ready_date->format('M d, Y') }}</strong>
                                </p>

                                <div class="pt-2 flex flex-wrap gap-2 text-xs">
                                    <span class="font-semibold text-slate-400">Ingredients Used:</span>
                                    @foreach($batch->used_ingredients ?? [] as $ing)
                                        <span class="px-2 py-0.5 bg-slate-50 text-slate-700 border border-slate-200 rounded font-mono">
                                            {{ $ing['quantity'] }}{{ $ing['unit'] ?? 'kg' }} {{ $ing['waste_type'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex flex-col md:items-end gap-3 border-t md:border-t-0 border-slate-100 pt-4 md:pt-0">
                                <div>
                                    @if($batch->status === 'ready')
                                        <span class="px-3 py-1 text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-full">
                                            Ready for Application
                                        </span>
                                    @elseif($batch->status === 'applied')
                                        <span class="px-3 py-1 text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200 rounded-full">
                                            Applied to Plants
                                        </span>
                                    @elseif($batch->status === 'discarded')
                                        <span class="px-3 py-1 text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 rounded-full">
                                            Discarded
                                        </span>
                                    @else
                                        @if($batch->isReady())
                                            <span class="px-3 py-1 text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-full">
                                                Ready to Use (Fermentation Complete)
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200 rounded-full">
                                                Aging ({{ now()->diffInDays($batch->estimated_ready_date, false) }} Days Remaining)
                                            </span>
                                        @endif
                                    @endif
                                </div>

                                <div class="flex items-center gap-2">
                                    @if($batch->status === 'aging')
                                        <form action="{{ route('household.batches.update-status', $batch->batch_id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="ready">
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition">
                                                Mark Ready
                                            </button>
                                        </form>
                                    @endif

                                    @if(in_array($batch->status, ['aging', 'ready']))
                                        <form action="{{ route('household.batches.update-status', $batch->batch_id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="applied">
                                            <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition">
                                                Mark Applied
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
