<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Household Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-green-700 mb-2">🌱 Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">
                        Turn your kitchen waste into organic fertilizer. Log your waste, follow approved formulations,
                        and help build a sustainable community.
                    </p>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Log Waste -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl mb-3">🗑️</div>
                    <h4 class="font-semibold text-gray-800 mb-1">Log Waste</h4>
                    <p class="text-sm text-gray-500">Record your banana peels, eggshells, or coffee grounds.</p>
                </div>

                <!-- View Formulations -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl mb-3">📋</div>
                    <h4 class="font-semibold text-gray-800 mb-1">Formulations</h4>
                    <p class="text-sm text-gray-500">View approved preparation guides and application rules.</p>
                </div>

                <!-- My History -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl mb-3">📊</div>
                    <h4 class="font-semibold text-gray-800 mb-1">My History</h4>
                    <p class="text-sm text-gray-500">Track your waste logging history over time.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
