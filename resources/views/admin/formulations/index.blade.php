<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-heading text-2xl font-bold text-yellow-500 tracking-wide">
                    Approved Formulations
                </h2>
                <p class="text-sm text-gray-400 font-body mt-1">Manage the organic fertilizer recipes that power EcoFert's recommendation engine.</p>
            </div>
            <a href="{{ route('admin.formulations.create') }}"
               class="btn-gold inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-gray-900 font-semibold text-sm shadow-lg hover:shadow-yellow-500/20 transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                New Formulation
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="spotlight">
                <!-- Stats Bar -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    @php
                        $total = $formulations->count();
                        $wasteTypes = $formulations->groupBy('target_waste_type');
                    @endphp
                    <div class="rounded-xl border border-yellow-900/30 bg-gray-900/60 backdrop-blur-sm p-5">
                        <p class="text-xs text-gray-500 font-body uppercase tracking-wider">Total Formulations</p>
                        <p class="text-3xl font-heading font-bold text-yellow-500 mt-1">{{ $total }}</p>
                    </div>
                    <div class="rounded-xl border border-yellow-900/30 bg-gray-900/60 backdrop-blur-sm p-5">
                        <p class="text-xs text-gray-500 font-body uppercase tracking-wider">Waste Types Covered</p>
                        <p class="text-3xl font-heading font-bold text-emerald-400 mt-1">{{ $wasteTypes->count() }}<span class="text-lg text-gray-600"> / 3</span></p>
                    </div>
                    <div class="rounded-xl border border-yellow-900/30 bg-gray-900/60 backdrop-blur-sm p-5">
                        <p class="text-xs text-gray-500 font-body uppercase tracking-wider">Last Updated</p>
                        <p class="text-lg font-body font-medium text-gray-300 mt-2">
                            {{ $formulations->first()?->updated_at?->diffForHumans() ?? '—' }}
                        </p>
                    </div>
                </div>

                <!-- Formulations Table -->
                <div class="rounded-xl border border-yellow-900/30 bg-gray-900/60 backdrop-blur-sm overflow-hidden">
                    @if($formulations->isEmpty())
                        <div class="p-16 text-center">
                            <div class="text-5xl mb-4 opacity-30">🧪</div>
                            <p class="text-gray-500 font-body text-lg">No formulations have been added yet.</p>
                            <a href="{{ route('admin.formulations.create') }}" class="inline-block mt-4 text-yellow-500 hover:text-yellow-400 text-sm font-semibold underline underline-offset-4 transition-colors">
                                Create your first formulation →
                            </a>
                        </div>
                    @else
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-yellow-900/40">
                                    <th class="px-6 py-4 text-xs font-body font-semibold uppercase tracking-wider text-yellow-600">#</th>
                                    <th class="px-6 py-4 text-xs font-body font-semibold uppercase tracking-wider text-yellow-600">Waste Type</th>
                                    <th class="px-6 py-4 text-xs font-body font-semibold uppercase tracking-wider text-yellow-600">Preparation Steps</th>
                                    <th class="px-6 py-4 text-xs font-body font-semibold uppercase tracking-wider text-yellow-600">Application Guidance</th>
                                    <th class="px-6 py-4 text-xs font-body font-semibold uppercase tracking-wider text-yellow-600 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800/60">
                                @foreach($formulations as $formulation)
                                    <tr class="row-glow hover:bg-yellow-900/5 transition-all duration-300">
                                        <td class="px-6 py-5 text-sm text-gray-500 font-body">{{ $formulation->formula_id }}</td>
                                        <td class="px-6 py-5">
                                            @php
                                                $badge = match($formulation->target_waste_type) {
                                                    'Banana Peels' => 'bg-yellow-900/40 text-yellow-400 border-yellow-700/50',
                                                    'Eggshells' => 'bg-orange-900/40 text-orange-300 border-orange-700/50',
                                                    'Coffee Grounds' => 'bg-amber-900/40 text-amber-300 border-amber-700/50',
                                                    default => 'bg-gray-800 text-gray-400 border-gray-700',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $badge }}">
                                                {{ $formulation->target_waste_type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-sm text-gray-300 font-body max-w-xs">
                                            <p class="line-clamp-2">{{ $formulation->preparation_steps }}</p>
                                        </td>
                                        <td class="px-6 py-5 text-sm text-gray-300 font-body max-w-xs">
                                            <p class="line-clamp-2">{{ $formulation->application_guidance }}</p>
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            <div class="flex items-center justify-end gap-3">
                                                <a href="{{ route('admin.formulations.edit', $formulation) }}"
                                                   class="inline-flex items-center gap-1 text-sm text-yellow-500 hover:text-yellow-400 font-medium transition-colors duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.formulations.destroy', $formulation) }}" method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this formulation? This cannot be undone.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 text-sm text-red-500 hover:text-red-400 font-medium transition-colors duration-200">
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
    </div>
</x-admin-layout>
