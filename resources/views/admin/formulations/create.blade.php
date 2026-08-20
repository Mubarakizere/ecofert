<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.formulations.index') }}"
               class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-yellow-900/40 text-yellow-600 hover:text-yellow-400 hover:border-yellow-600/60 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            </a>
            <div>
                <h2 class="font-heading text-2xl font-bold text-yellow-500 tracking-wide">
                    New Formulation
                </h2>
                <p class="text-sm text-gray-400 font-body mt-1">Add a validated organic fertilizer recipe to the EcoFert platform.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="spotlight">
                <div class="rounded-xl border border-yellow-900/30 bg-gray-900/60 backdrop-blur-sm p-8">
                    <form method="POST" action="{{ route('admin.formulations.store') }}">
                        @csrf

                        <!-- Target Waste Type -->
                        <div class="mb-6">
                            <label for="target_waste_type" class="block text-sm font-semibold text-yellow-600 font-body uppercase tracking-wider mb-2">
                                Target Waste Type
                            </label>
                            <select id="target_waste_type" name="target_waste_type"
                                    class="w-full rounded-lg border border-yellow-900/40 bg-gray-800/80 text-gray-200 font-body text-sm px-4 py-3 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/50 focus:outline-none transition-all duration-200"
                                    required>
                                <option value="" disabled {{ old('target_waste_type') ? '' : 'selected' }}>Select a waste type...</option>
                                <option value="Banana Peels" {{ old('target_waste_type') == 'Banana Peels' ? 'selected' : '' }}>🍌 Banana Peels</option>
                                <option value="Eggshells" {{ old('target_waste_type') == 'Eggshells' ? 'selected' : '' }}>🥚 Eggshells</option>
                                <option value="Coffee Grounds" {{ old('target_waste_type') == 'Coffee Grounds' ? 'selected' : '' }}>☕ Coffee Grounds</option>
                            </select>
                            @error('target_waste_type')
                                <p class="mt-2 text-sm text-red-400 font-body">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preparation Steps -->
                        <div class="mb-6">
                            <label for="preparation_steps" class="block text-sm font-semibold text-yellow-600 font-body uppercase tracking-wider mb-2">
                                Preparation Steps
                            </label>
                            <p class="text-xs text-gray-500 font-body mb-2">Provide the validated step-by-step preparation guide approved by the Extension Officer.</p>
                            <textarea id="preparation_steps" name="preparation_steps" rows="6"
                                      class="w-full rounded-lg border border-yellow-900/40 bg-gray-800/80 text-gray-200 font-body text-sm px-4 py-3 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/50 focus:outline-none transition-all duration-200 resize-y"
                                      placeholder="Step 1: Collect and rinse the waste material...&#10;Step 2: Dry in indirect sunlight for 48 hours...&#10;Step 3: Grind into fine powder using a mortar..."
                                      required>{{ old('preparation_steps') }}</textarea>
                            @error('preparation_steps')
                                <p class="mt-2 text-sm text-red-400 font-body">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Application Guidance -->
                        <div class="mb-8">
                            <label for="application_guidance" class="block text-sm font-semibold text-yellow-600 font-body uppercase tracking-wider mb-2">
                                Application Guidance
                            </label>
                            <p class="text-xs text-gray-500 font-body mb-2">Specify the rules for safely applying this fertilizer to plants and soil.</p>
                            <textarea id="application_guidance" name="application_guidance" rows="5"
                                      class="w-full rounded-lg border border-yellow-900/40 bg-gray-800/80 text-gray-200 font-body text-sm px-4 py-3 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/50 focus:outline-none transition-all duration-200 resize-y"
                                      placeholder="Apply 2 tablespoons per plant weekly...&#10;Mix into topsoil at a depth of 2-3cm...&#10;Avoid direct contact with plant stems..."
                                      required>{{ old('application_guidance') }}</textarea>
                            @error('application_guidance')
                                <p class="mt-2 text-sm text-red-400 font-body">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-800/60">
                            <a href="{{ route('admin.formulations.index') }}"
                               class="px-5 py-2.5 rounded-lg border border-gray-700 text-gray-400 hover:text-gray-200 hover:border-gray-500 font-body text-sm font-medium transition-all duration-200">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="btn-gold px-6 py-2.5 rounded-lg text-gray-900 font-semibold text-sm shadow-lg hover:shadow-yellow-500/20 transition-all duration-300">
                                Create Formulation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
