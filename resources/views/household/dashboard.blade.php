<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-heading text-2xl font-bold text-gray-800 tracking-wide">
            My Dashboard
        </h2>
        <p class="text-sm text-gray-500 font-body mt-1">Welcome, {{ Auth::user()->name }}</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Welcome Banner --}}
            <div class="card-dark p-6 mb-10">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-body font-semibold text-gray-800 text-lg">Turn waste into fertilizer</h3>
                        <p class="text-sm text-gray-500 font-body mt-1 leading-relaxed max-w-xl">
                            Log your kitchen waste, follow approved formulations, and help build a sustainable community. Track your contributions over time.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Feature Cards --}}
            <h3 class="section-label mb-4">Features</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- Log Waste --}}
                <div class="action-card action-card--disabled relative">
                    <span class="absolute top-3 right-3 text-[0.6rem] font-body font-semibold uppercase tracking-widest text-gray-400 bg-gray-100 px-2 py-0.5 rounded">Soon</span>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-body font-semibold text-gray-700">Log Waste</h4>
                            <p class="text-sm text-gray-400 mt-1 font-body leading-relaxed">Record your banana peels, eggshells, or coffee grounds.</p>
                        </div>
                    </div>
                </div>

                {{-- Formulations --}}
                <div class="action-card action-card--disabled relative">
                    <span class="absolute top-3 right-3 text-[0.6rem] font-body font-semibold uppercase tracking-widest text-gray-400 bg-gray-100 px-2 py-0.5 rounded">Soon</span>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-body font-semibold text-gray-700">Formulations</h4>
                            <p class="text-sm text-gray-400 mt-1 font-body leading-relaxed">View approved preparation guides and application rules.</p>
                        </div>
                    </div>
                </div>

                {{-- My History --}}
                <div class="action-card action-card--disabled relative">
                    <span class="absolute top-3 right-3 text-[0.6rem] font-body font-semibold uppercase tracking-widest text-gray-400 bg-gray-100 px-2 py-0.5 rounded">Soon</span>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-body font-semibold text-gray-700">My History</h4>
                            <p class="text-sm text-gray-400 mt-1 font-body leading-relaxed">Track your waste logging history over time.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-admin-layout>
