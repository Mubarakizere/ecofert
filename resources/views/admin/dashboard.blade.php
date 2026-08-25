<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-heading text-2xl font-bold text-gray-800 tracking-wide">
            Dashboard
        </h2>
        <p class="text-sm text-gray-500 font-body mt-1">Welcome back, {{ Auth::user()->name }}</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Stats Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-10">
                <div class="stat-card stat-card--gold">
                    <p class="section-label">Formulations</p>
                    <p class="text-3xl font-heading font-bold text-amber-700 mt-2">{{ $formulationsCount }}</p>
                    <p class="text-xs text-gray-400 mt-1 font-body">Approved recipes</p>
                </div>
                <div class="stat-card stat-card--emerald">
                    <p class="section-label">Households</p>
                    <p class="text-3xl font-heading font-bold text-emerald-700 mt-2">{{ $householdCount }}</p>
                    <p class="text-xs text-gray-400 mt-1 font-body">Registered users</p>
                </div>
                <div class="stat-card stat-card--amber">
                    <p class="section-label">Experiments</p>
                    <p class="text-3xl font-heading font-bold text-amber-600 mt-2">{{ $experimentsCount }}</p>
                    <p class="text-xs text-gray-400 mt-1 font-body">Field trials</p>
                </div>
            </div>

            {{-- Quick Actions --}}
            <h3 class="section-label mb-4">Manage</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">
                <a href="{{ route('admin.formulations.index') }}" class="action-card group">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-body font-semibold text-gray-800 group-hover:text-amber-700 transition-colors">Formulations</h4>
                            <p class="text-sm text-gray-500 mt-1 font-body leading-relaxed">Create and manage approved fertilizer preparation guides.</p>
                        </div>
                    </div>
                </a>

                <div class="action-card action-card--disabled relative">
                    <span class="absolute top-3 right-3 text-[0.6rem] font-body font-semibold uppercase tracking-widest text-gray-400 bg-gray-100 px-2 py-0.5 rounded">Soon</span>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-body font-semibold text-gray-700">Experiments</h4>
                            <p class="text-sm text-gray-400 mt-1 font-body leading-relaxed">Run 4-week comparative trials: organic vs. commercial.</p>
                        </div>
                    </div>
                </div>

                <div class="action-card action-card--disabled relative">
                    <span class="absolute top-3 right-3 text-[0.6rem] font-body font-semibold uppercase tracking-widest text-gray-400 bg-gray-100 px-2 py-0.5 rounded">Soon</span>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-body font-semibold text-gray-700">Growth Data</h4>
                            <p class="text-sm text-gray-400 mt-1 font-body leading-relaxed">Record weekly plant height, soil pH, and leaf vitality.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Formulations --}}
            @if($recentFormulations->isNotEmpty())
                <h3 class="section-label mb-4">Recent formulations</h3>
                <div class="card-dark overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-5 py-3 text-xs font-body font-semibold uppercase tracking-wider text-gray-400">Waste Type</th>
                                <th class="px-5 py-3 text-xs font-body font-semibold uppercase tracking-wider text-gray-400 hidden sm:table-cell">Preparation</th>
                                <th class="px-5 py-3 text-xs font-body font-semibold uppercase tracking-wider text-gray-400 text-right">Updated</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentFormulations as $formulation)
                                <tr class="row-glow">
                                    <td class="px-5 py-3.5">
                                        @php
                                            $badge = match($formulation->target_waste_type) {
                                                'Banana Peels' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                                'Eggshells' => 'bg-orange-50 text-orange-700 border-orange-200',
                                                'Coffee Grounds' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                default => 'bg-gray-50 text-gray-600 border-gray-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badge }}">
                                            {{ $formulation->target_waste_type }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-sm text-gray-600 font-body max-w-xs hidden sm:table-cell">
                                        <p class="line-clamp-1">{{ $formulation->preparation_steps }}</p>
                                    </td>
                                    <td class="px-5 py-3.5 text-sm text-gray-400 font-body text-right">
                                        {{ $formulation->updated_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-admin-layout>
