<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-heading text-2xl font-bold text-yellow-500 tracking-wide">
            Admin Dashboard
        </h2>
        <p class="text-sm text-gray-400 font-body mt-1">Welcome back, {{ Auth::user()->name }} — {{ Auth::user()->role_type }}</p>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="spotlight">
                <!-- Admin Quick Actions Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Manage Formulations -->
                    <a href="{{ route('admin.formulations.index') }}"
                       class="group rounded-xl border border-yellow-900/30 bg-gray-900/60 backdrop-blur-sm p-8 hover:border-yellow-600/50 hover:bg-gray-900/80 transition-all duration-300">
                        <div class="text-4xl mb-4">🧪</div>
                        <h4 class="font-heading text-lg font-semibold text-yellow-500 group-hover:text-yellow-400 transition-colors">Formulations</h4>
                        <p class="text-sm text-gray-400 font-body mt-2">Create and manage approved fertilizer preparation guides.</p>
                        <div class="mt-4 text-xs text-yellow-600 font-semibold font-body uppercase tracking-wider group-hover:text-yellow-400 transition-colors">
                            Manage →
                        </div>
                    </a>

                    <!-- Experiments -->
                    <div class="rounded-xl border border-gray-800/50 bg-gray-900/40 backdrop-blur-sm p-8 opacity-60">
                        <div class="text-4xl mb-4">🌿</div>
                        <h4 class="font-heading text-lg font-semibold text-gray-500">Experiments</h4>
                        <p class="text-sm text-gray-600 font-body mt-2">Run 4-week comparative trials: organic vs. commercial.</p>
                        <div class="mt-4 text-xs text-gray-600 font-semibold font-body uppercase tracking-wider">
                            Coming Soon
                        </div>
                    </div>

                    <!-- Growth Measurements -->
                    <div class="rounded-xl border border-gray-800/50 bg-gray-900/40 backdrop-blur-sm p-8 opacity-60">
                        <div class="text-4xl mb-4">📈</div>
                        <h4 class="font-heading text-lg font-semibold text-gray-500">Growth Data</h4>
                        <p class="text-sm text-gray-600 font-body mt-2">Record weekly plant height, soil pH, and leaf vitality.</p>
                        <div class="mt-4 text-xs text-gray-600 font-semibold font-body uppercase tracking-wider">
                            Coming Soon
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
