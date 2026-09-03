<section class="space-y-6">
    <header class="border-b border-slate-100 pb-4">
        <h2 class="text-base font-bold text-slate-900">
            Delete Account
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            Permanently delete your user account and purge all associated waste logs and experiment records.
        </p>
    </header>

    <div class="p-4 bg-red-50 border border-red-100 rounded-xl space-y-3">
        <p class="text-xs text-red-800 leading-relaxed font-medium">
            Once your account is deleted, all recorded waste logs, stock balances, active batches, and 4-week growth experiment metrics will be permanently deleted.
        </p>

        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-xs rounded-lg shadow-sm transition">
            Delete Account
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-slate-900">
                Are you sure you want to delete your account?
            </h2>

            <p class="text-xs text-slate-500 leading-relaxed">
                Please enter your password to confirm you would like to permanently delete your account and all associated food waste logs and growth trial records.
            </p>

            <div>
                <label for="password" class="sr-only">Password</label>
                <input id="password" name="password" type="password" placeholder="Confirm Password"
                       class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition">
                    Cancel
                </button>

                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-xs rounded-lg shadow-sm transition">
                    Delete Account
                </button>
            </div>
        </form>
    </x-modal>
</section>
