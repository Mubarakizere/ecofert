<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-heading text-2xl font-bold text-gray-800 tracking-wide">
                    Approved Formulations
                </h2>
                <p class="text-sm text-gray-500 font-body mt-1">Manage the organic fertilizer recipes that power EcoFert's recommendation engine.</p>
            </div>
            <a href="{{ route('admin.formulations.create') }}"
               class="btn-gold inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                New Formulation
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Bar -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                @php
                    $total = $formulations->count();
                    $wasteTypes = $formulations->groupBy('target_waste_type');
                @endphp
                <div class="stat-card stat-card--gold">
                    <p class="section-label">Total Formulations</p>
                    <p class="text-3xl font-heading font-bold text-amber-700 mt-1">{{ $total }}</p>
                </div>
                <div class="stat-card stat-card--emerald">
                    <p class="section-label">Waste Types Covered</p>
                    <p class="text-3xl font-heading font-bold text-emerald-700 mt-1">{{ $wasteTypes->count() }}<span class="text-lg text-gray-400"> / 3</span></p>
                </div>
                <div class="stat-card stat-card--amber">
                    <p class="section-label">Last Updated</p>
                    <p class="text-lg font-body font-medium text-gray-600 mt-2">
                        {{ $formulations->first()?->updated_at?->diffForHumans() ?? '—' }}
                    </p>
                </div>
            </div>

            <!-- Formulations Table -->
            <div class="card-dark overflow-hidden">
                @if($formulations->isEmpty())
                    <div class="p-16 text-center">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-body text-lg">No formulations have been added yet.</p>
                        <a href="{{ route('admin.formulations.create') }}" class="inline-block mt-4 text-amber-600 hover:text-amber-700 text-sm font-semibold underline underline-offset-4 transition-colors">
                            Create your first formulation →
                        </a>
                    </div>
                @else
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-6 py-4 text-xs font-body font-semibold uppercase tracking-wider text-gray-400">#</th>
                                <th class="px-6 py-4 text-xs font-body font-semibold uppercase tracking-wider text-gray-400">Waste Type</th>
                                <th class="px-6 py-4 text-xs font-body font-semibold uppercase tracking-wider text-gray-400">Preparation Steps</th>
                                <th class="px-6 py-4 text-xs font-body font-semibold uppercase tracking-wider text-gray-400">Application Guidance</th>
                                <th class="px-6 py-4 text-xs font-body font-semibold uppercase tracking-wider text-gray-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($formulations as $formulation)
                                <tr class="row-glow">
                                    <td class="px-6 py-5 text-sm text-gray-400 font-body">{{ $formulation->formula_id }}</td>
                                    <td class="px-6 py-5">
                                        @php
                                            $badge = match($formulation->target_waste_type) {
                                                'Banana Peels' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                                'Eggshells' => 'bg-orange-50 text-orange-700 border-orange-200',
                                                'Coffee Grounds' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                default => 'bg-gray-50 text-gray-600 border-gray-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $badge }}">
                                            {{ $formulation->target_waste_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-gray-600 font-body max-w-xs">
                                        <p class="line-clamp-2">{{ $formulation->preparation_steps }}</p>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-gray-600 font-body max-w-xs">
                                        <p class="line-clamp-2">{{ $formulation->application_guidance }}</p>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.formulations.edit', $formulation) }}"
                                               class="inline-flex items-center gap-1 text-sm text-amber-600 hover:text-amber-700 font-medium transition-colors duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.formulations.destroy', $formulation) }}" method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this formulation? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 text-sm text-red-500 hover:text-red-600 font-medium transition-colors duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
