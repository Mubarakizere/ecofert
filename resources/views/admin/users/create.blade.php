<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                    &larr; Back to Users
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-slate-900 tracking-tight leading-tight">
                        Provision New System User
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">Add a new gardener household, agricultural extension officer, or system administrator.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Full Name
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                               placeholder="e.g. Marie Uwimana"
                               required autofocus />
                        @error('name')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Email Address
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                               placeholder="user@ecofert.com"
                               required />
                        @error('email')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password & Confirmation -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Password
                            </label>
                            <input type="password" id="password" name="password"
                                   class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                   placeholder="Minimum 8 characters"
                                   required />
                            @error('password')
                                <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Confirm Password
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                                   placeholder="Re-type password"
                                   required />
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label for="role" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Assign System Role
                        </label>
                        <select id="role" name="role" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select assigned role...</option>
                            @foreach($roles as $id => $roleName)
                                <option value="{{ $roleName }}" {{ old('role') == $roleName ? 'selected' : '' }}>
                                    {{ $roleName }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Reference Box -->
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Role Access Privileges</span>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                            <div class="space-y-1">
                                <span class="font-bold text-emerald-700 block">Household</span>
                                <p class="text-slate-500">Log waste, start fertilizer batches, track 4-week plant growth.</p>
                            </div>
                            <div class="space-y-1">
                                <span class="font-bold text-amber-700 block">Extension Officer</span>
                                <p class="text-slate-500">Manage validated formulas, NPK ratios, review member trials.</p>
                            </div>
                            <div class="space-y-1">
                                <span class="font-bold text-slate-900 block">Admin</span>
                                <p class="text-slate-500">Full system access, user account & role management.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                            Provision User Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
