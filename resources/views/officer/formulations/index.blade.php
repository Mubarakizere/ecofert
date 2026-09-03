<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight leading-tight">
                    Extension-Validated Formulations Management
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Submit, edit, and validate organic fertilizer formulation rules and ratio guidelines.
                </p>
            </div>
            <a href="{{ route('officer.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition">
                &larr; Extension Workspace
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="{
        deleteModalOpen: false,
        deleteUrl: '',
        deleteTitle: '',

        confirmDelete(url, title) {
            this.deleteUrl = url;
            this.deleteTitle = title;
            this.deleteModalOpen = true;
        },

        ingredients: [
            { waste_type: 'Banana Peels', quantity: 1.0, unit: 'kg' },
            { waste_type: 'Eggshells', quantity: 0.5, unit: 'kg' }
        ],
        addIngredient() {
            this.ingredients.push({ waste_type: 'Coffee Grounds', quantity: 0.5, unit: 'kg' });
        },
        removeIngredient(index) {
            if (this.ingredients.length > 1) {
                this.ingredients.splice(index, 1);
            }
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('info'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-sm font-medium rounded-r-lg shadow-sm">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Create New Formulation Form -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-6">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">
                    Add & Validate New Organic Formulation Guide
                </h3>

                <form action="{{ route('officer.formulations.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Formulation Title</label>
                            <input type="text" id="title" name="title" required placeholder="e.g. High-Potassium Liquid Brew" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label for="target_waste_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Primary Target Waste</label>
                            <select id="target_waste_type" name="target_waste_type" required class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="Banana Peels">Banana Peels</option>
                                <option value="Eggshells">Eggshells</option>
                                <option value="Coffee Grounds">Coffee Grounds</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="npk_ratio" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">NPK Ratio</label>
                            <input type="text" id="npk_ratio" name="npk_ratio" required placeholder="e.g. 0-1-5" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label for="primary_nutrients" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Primary Nutrients</label>
                            <input type="text" id="primary_nutrients" name="primary_nutrients" required placeholder="e.g. Potassium (K), Calcium (Ca)" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label for="fermentation_days" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Fermentation Days</label>
                            <input type="number" id="fermentation_days" name="fermentation_days" required min="1" max="90" value="7" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="yield_quantity" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Yield Qty</label>
                                <input type="number" step="0.1" id="yield_quantity" name="yield_quantity" required value="2.5" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label for="yield_unit" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Unit</label>
                                <select id="yield_unit" name="yield_unit" required class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="L">Liters (L)</option>
                                    <option value="kg">Kilograms (kg)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Required Ingredient Ratios Section -->
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-slate-800 uppercase tracking-wider">Required Ingredient Ratios per Batch</label>
                            <button type="button" @click="addIngredient()" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                                + Add Ingredient
                            </button>
                        </div>

                        <template x-for="(ing, index) in ingredients" :key="index">
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-center">
                                <div class="sm:col-span-2">
                                    <select :name="`required_ingredients[${index}][waste_type]`" x-model="ing.waste_type" required class="w-full text-xs border-slate-300 rounded-lg focus:ring-emerald-500">
                                        <option value="Banana Peels">Banana Peels</option>
                                        <option value="Eggshells">Eggshells</option>
                                        <option value="Coffee Grounds">Coffee Grounds</option>
                                    </select>
                                </div>
                                <div>
                                    <input type="number" step="0.01" min="0.01" :name="`required_ingredients[${index}][quantity]`" x-model="ing.quantity" required placeholder="Qty" class="w-full text-xs border-slate-300 rounded-lg focus:ring-emerald-500">
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="text" :name="`required_ingredients[${index}][unit]`" x-model="ing.unit" required readonly class="w-20 text-xs bg-slate-100 border-slate-300 rounded-lg">
                                    <button type="button" @click="removeIngredient(index)" class="text-xs text-red-600 font-semibold hover:underline">Remove</button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="preparation_steps" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Preparation Steps</label>
                            <textarea id="preparation_steps" name="preparation_steps" rows="3" required placeholder="Step by step preparation instructions..." class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                        </div>

                        <div>
                            <label for="application_guidance" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Application Guidance</label>
                            <textarea id="application_guidance" name="application_guidance" rows="3" required placeholder="Rules for applying fertilizer safely..." class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-lg shadow-sm transition">
                        Validate & Publish Formulation
                    </button>
                </form>
            </div>

            <!-- Approved Formulations List -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-6">
                <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Validated Formulations Database</h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($formulations as $form)
                        <div class="p-5 border border-slate-200 rounded-xl bg-slate-50 flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-mono font-bold text-emerald-700 bg-emerald-100 border border-emerald-200 px-2.5 py-0.5 rounded">
                                        NPK: {{ $form->npk_ratio ?? 'Standard' }}
                                    </span>
                                    <span class="text-xs text-slate-400 font-mono">Yield: {{ $form->yield_quantity }}{{ $form->yield_unit }}</span>
                                </div>

                                <h4 class="text-base font-bold text-slate-900 mt-2">{{ $form->title ?? $form->target_waste_type }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Primary Nutrients: {{ $form->primary_nutrients }}</p>

                                <div class="mt-3 p-3 bg-white rounded-lg border border-slate-200 space-y-1">
                                    <span class="text-xs font-bold text-slate-700 block uppercase">Required Ratio Ingredients:</span>
                                    @foreach($form->required_ingredients ?? [] as $ri)
                                        <span class="inline-block text-xs font-mono text-slate-700 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded me-1 mb-1">
                                            {{ $ri['quantity'] }}{{ $ri['unit'] ?? 'kg' }} {{ $ri['waste_type'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-slate-200">
                                <span class="text-xs text-slate-500 font-mono">Fermentation: {{ $form->fermentation_days }} Days</span>
                                <button type="button"
                                        @click="confirmDelete('{{ route('officer.formulations.destroy', $form->formula_id) }}', '{{ addslashes($form->title ?? $form->target_waste_type) }}')"
                                        class="px-3 py-1.5 bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 font-semibold text-xs rounded-lg transition">
                                    Delete Formula
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div x-show="deleteModalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 overflow-y-auto"
                 style="display: none;">
                
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="deleteModalOpen = false"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                    <div class="inline-block px-6 pt-5 pb-6 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-red-600 font-bold text-lg">
                                !
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-slate-900 leading-snug">Confirm Formulation Removal</h3>
                                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                    Are you sure you want to delete the formulation <strong class="text-slate-900 font-semibold" x-text="deleteTitle"></strong>? Household gardeners will no longer receive match recommendations for this rule set.
                                </p>
                            </div>

                            <form :action="deleteUrl" method="POST" class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                                @csrf
                                @method('DELETE')
                                <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-lg shadow-sm transition">
                                    Delete Formulation
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
