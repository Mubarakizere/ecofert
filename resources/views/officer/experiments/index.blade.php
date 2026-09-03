<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight leading-tight">
                    Cooperative Plant Growth Experiments
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Review and evaluate 4-week plant growth trial results submitted by cooperative member households.
                </p>
            </div>
            <a href="{{ route('officer.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition">
                &larr; Extension Workspace
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-4">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Cooperative Farmer Experiments Log</h3>

                @if($experiments->isEmpty())
                    <p class="text-sm text-slate-500 text-center py-8">No growth trials submitted yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3">Farmer Name</th>
                                    <th class="px-4 py-3">Plant Species</th>
                                    <th class="px-4 py-3">Treatment Group</th>
                                    <th class="px-4 py-3">Logged Weeks</th>
                                    <th class="px-4 py-3">Latest Height</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($experiments as $exp)
                                    @php
                                        $latest = $exp->growthMeasurements->sortByDesc('week_number')->first();
                                    @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 font-bold text-slate-900">{{ $exp->user->name ?? 'Cooperative Farmer' }}</td>
                                        <td class="px-4 py-3 text-slate-800">{{ $exp->plant_species }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded {{ $exp->fertilizer_type === 'Organic' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                                {{ $exp->fertilizer_type }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $exp->growthMeasurements->count() }}/4 Weeks</td>
                                        <td class="px-4 py-3 font-mono font-bold text-slate-900">
                                            {{ $latest ? "{$latest->plant_height_cm} cm" : 'No metrics' }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('household.experiments.show', $exp->experiment_id) }}" class="text-xs font-semibold text-emerald-600 hover:underline">
                                                Inspect Trial &rarr;
                                            </a>
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
