<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.formulations.index') }}"
               class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 text-gray-500 hover:text-gray-700 hover:border-gray-400 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            </a>
            <div>
                <h2 class="font-heading text-2xl font-bold text-gray-800 tracking-wide">
                    Edit Formulation
                </h2>
                <p class="text-sm text-gray-500 font-body mt-1">
                    Updating <span class="text-amber-700 font-medium">{{ $formulation->target_waste_type }}</span> formulation
                    <span class="text-gray-400">#{{ $formulation->formula_id }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-dark p-8">
                <form method="POST" action="{{ route('admin.formulations.update', $formulation) }}">
                    @csrf
                    @method('PUT')

                    <!-- Target Waste Type -->
                    <div class="mb-6">
                        <label for="target_waste_type" class="block text-sm font-semibold text-gray-700 font-body uppercase tracking-wider mb-2">
                            Target Waste Type
                        </label>
                        <select id="target_waste_type" name="target_waste_type"
                                class="w-full rounded-lg border border-gray-300 bg-white text-gray-800 font-body text-sm px-4 py-3 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50 focus:outline-none transition-all duration-200"
                                required>
                            <option value="Banana Peels" {{ old('target_waste_type', $formulation->target_waste_type) == 'Banana Peels' ? 'selected' : '' }}>Banana Peels</option>
                            <option value="Eggshells" {{ old('target_waste_type', $formulation->target_waste_type) == 'Eggshells' ? 'selected' : '' }}>Eggshells</option>
                            <option value="Coffee Grounds" {{ old('target_waste_type', $formulation->target_waste_type) == 'Coffee Grounds' ? 'selected' : '' }}>Coffee Grounds</option>
                        </select>
                        @error('target_waste_type')
                            <p class="mt-2 text-sm text-red-500 font-body">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preparation Steps -->
                    <div class="mb-6">
                        <label for="preparation_steps" class="block text-sm font-semibold text-gray-700 font-body uppercase tracking-wider mb-2">
                            Preparation Steps
                        </label>
                        <p class="text-xs text-gray-400 font-body mb-2">Update the validated step-by-step preparation guide as per the Extension Officer's latest guidelines.</p>
                        <textarea id="preparation_steps" name="preparation_steps" rows="6"
                                  class="w-full rounded-lg border border-gray-300 bg-white text-gray-800 font-body text-sm px-4 py-3 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50 focus:outline-none transition-all duration-200 resize-y"
                                  required>{{ old('preparation_steps', $formulation->preparation_steps) }}</textarea>
                        @error('preparation_steps')
                            <p class="mt-2 text-sm text-red-500 font-body">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Application Guidance -->
                    <div class="mb-8">
                        <label for="application_guidance" class="block text-sm font-semibold text-gray-700 font-body uppercase tracking-wider mb-2">
                            Application Guidance
                        </label>
                        <p class="text-xs text-gray-400 font-body mb-2">Revise the safe application rules for this fertilizer formulation.</p>
                        <textarea id="application_guidance" name="application_guidance" rows="5"
                                  class="w-full rounded-lg border border-gray-300 bg-white text-gray-800 font-body text-sm px-4 py-3 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50 focus:outline-none transition-all duration-200 resize-y"
                                  required>{{ old('application_guidance', $formulation->application_guidance) }}</textarea>
                        @error('application_guidance')
                            <p class="mt-2 text-sm text-red-500 font-body">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <!-- Delete (left side) -->
                        <div>
                            <button type="button" onclick="document.getElementById('delete-form-{{ $formulation->formula_id }}').submit()"
                                    class="inline-flex items-center gap-1 text-sm text-red-500 hover:text-red-600 font-medium transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                Delete Formulation
                            </button>
                        </div>

                        <!-- Save / Cancel (right side) -->
                        <div class="flex items-center gap-4">
                            <a href="{{ route('admin.formulations.index') }}"
                               class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-500 hover:text-gray-700 hover:border-gray-400 font-body text-sm font-medium transition-all duration-200">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="btn-gold px-6 py-2.5 rounded-lg font-semibold text-sm transition-all duration-300">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Hidden Delete Form -->
                <form id="delete-form-{{ $formulation->formula_id }}"
                      action="{{ route('admin.formulations.destroy', $formulation) }}"
                      method="POST"
                      onsubmit="return confirm('Are you sure you want to permanently delete this formulation? This cannot be undone.')"
                      class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
