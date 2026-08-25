<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}"
               id="btn-back-to-users"
               class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 text-gray-500 hover:text-gray-700 hover:border-gray-400 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            </a>
            <div>
                <h2 class="font-heading text-2xl font-bold text-gray-800 tracking-wide">
                    Create New User
                </h2>
                <p class="text-sm text-gray-500 font-body mt-1">Add a new team member, gardener, or extension officer to the EcoFert platform.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-dark p-8">
                <form method="POST" action="{{ route('admin.users.store') }}" id="create-user-form">
                    @csrf

                    <!-- Full Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-gray-700 font-body uppercase tracking-wider mb-2">
                            Full Name
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                            </div>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                   class="w-full rounded-lg border border-gray-300 bg-white text-gray-800 font-body text-sm pl-11 pr-4 py-3 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50 focus:outline-none transition-all duration-200"
                                   placeholder="e.g. Jane Uwimana"
                                   required autofocus />
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-500 font-body">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-semibold text-gray-700 font-body uppercase tracking-wider mb-2">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                   class="w-full rounded-lg border border-gray-300 bg-white text-gray-800 font-body text-sm pl-11 pr-4 py-3 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50 focus:outline-none transition-all duration-200"
                                   placeholder="user@ecofert.com"
                                   required />
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-500 font-body">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password & Confirmation in grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 font-body uppercase tracking-wider mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                                </div>
                                <input type="password" id="password" name="password"
                                       class="w-full rounded-lg border border-gray-300 bg-white text-gray-800 font-body text-sm pl-11 pr-4 py-3 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50 focus:outline-none transition-all duration-200"
                                       placeholder="Min. 8 characters"
                                       required />
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-500 font-body">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 font-body uppercase tracking-wider mb-2">
                                Confirm Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                                </div>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full rounded-lg border border-gray-300 bg-white text-gray-800 font-body text-sm pl-11 pr-4 py-3 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50 focus:outline-none transition-all duration-200"
                                       placeholder="Re-type password"
                                       required />
                            </div>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-8">
                        <label for="role" class="block text-sm font-semibold text-gray-700 font-body uppercase tracking-wider mb-2">
                            Assign Role
                        </label>
                        <p class="text-xs text-gray-400 font-body mb-2">The role determines which permissions the user inherits across the platform.</p>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                            </div>
                            <select id="role" name="role"
                                    class="w-full rounded-lg border border-gray-300 bg-white text-gray-800 font-body text-sm pl-11 pr-4 py-3 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50 focus:outline-none transition-all duration-200 appearance-none"
                                    required>
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select a role...</option>
                                @foreach($roles as $id => $roleName)
                                    <option value="{{ $roleName }}" {{ old('role') == $roleName ? 'selected' : '' }}>
                                        {{ $roleName }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- Custom dropdown arrow --}}
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                            </div>
                        </div>
                        @error('role')
                            <p class="mt-2 text-sm text-red-500 font-body">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Permissions Info -->
                    <div class="mb-8 rounded-lg border border-gray-200 bg-gray-50/50 p-5">
                        <h4 class="section-label mb-3">Role Permissions Reference</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs font-body">
                            <div class="space-y-1.5">
                                <p class="font-semibold text-emerald-700">Admin</p>
                                <p class="text-gray-500">• Manage formulations</p>
                                <p class="text-gray-500">• View reports</p>
                            </div>
                            <div class="space-y-1.5">
                                <p class="font-semibold text-amber-700">Extension Officer</p>
                                <p class="text-gray-500">• Manage formulations</p>
                                <p class="text-gray-500">• View reports</p>
                            </div>
                            <div class="space-y-1.5">
                                <p class="font-semibold text-sky-700">Household</p>
                                <p class="text-gray-500">• Log waste</p>
                                <p class="text-gray-500">• Track experiments</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.users.index') }}"
                           id="btn-cancel-create"
                           class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-500 hover:text-gray-700 hover:border-gray-400 font-body text-sm font-medium transition-all duration-200">
                            Cancel
                        </a>
                        <button type="submit"
                                id="btn-submit-user"
                                class="btn-gold px-6 py-2.5 rounded-lg font-semibold text-sm transition-all duration-300">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
