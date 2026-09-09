<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight leading-tight">
                    User Accounts & Platform Access
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Manage system user credentials, assign Spatie roles, control account status, and remove user accounts.
                </p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Provision New User
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen"
         x-data="{
             statusModalOpen: false,
             statusUserName: '',
             statusUserAction: '',
             statusFormUrl: '',

             openStatusModal(name, action, url) {
                 this.statusUserName = name;
                 this.statusUserAction = action;
                 this.statusFormUrl = url;
                 this.statusModalOpen = true;
             },

             deleteModalOpen: false,
             deleteUserName: '',
             deleteFormUrl: '',

             openDeleteModal(name, url) {
                 this.deleteUserName = name;
                 this.deleteFormUrl = url;
                 this.deleteModalOpen = true;
             }
         }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-sm font-medium rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 text-sm font-medium rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Metric Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                @php
                    $totalUsers = $users->count();
                    $householdCount = $users->where('role_type', 'Household')->count();
                    $officerCount = $users->where('role_type', 'Extension Officer')->count();
                    $adminCount = $users->where('role_type', 'Admin')->count();
                    $suspendedCount = $users->where('status', 'suspended')->count();
                @endphp
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Accounts</span>
                        <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $totalUsers }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Registered credentials</p>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 font-bold text-xs">
                        ALL
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Households</span>
                        <h3 class="text-2xl font-bold text-sky-700 mt-1">{{ $householdCount }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Home waste loggers</p>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-sky-50 flex items-center justify-center text-sky-700 font-bold text-xs">
                        HH
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Extension Officers</span>
                        <h3 class="text-2xl font-bold text-amber-700 mt-1">{{ $officerCount }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Field advisors</p>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-700 font-bold text-xs">
                        EO
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Administrators</span>
                        <h3 class="text-2xl font-bold text-emerald-700 mt-1">{{ $adminCount }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">System controllers</p>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-700 font-bold text-xs">
                        ADM
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Suspended</span>
                        <h3 class="text-2xl font-bold text-rose-600 mt-1">{{ $suspendedCount }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Disabled access</p>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-rose-50 flex items-center justify-center text-rose-700 font-bold text-xs">
                        SUS
                    </div>
                </div>
            </div>

            <!-- Search & Filter Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row items-center gap-4">
                    <div class="flex-1 w-full">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by user name or email..."
                               class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div class="w-full md:w-48">
                        <select name="role" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">All Roles</option>
                            <option value="Household" {{ request('role') === 'Household' ? 'selected' : '' }}>Household</option>
                            <option value="Extension Officer" {{ request('role') === 'Extension Officer' ? 'selected' : '' }}>Extension Officer</option>
                            <option value="Admin" {{ request('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div class="w-full md:w-48">
                        <select name="status" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-lg shadow-sm transition">
                            Apply Filter
                        </button>
                        @if(request()->hasAny(['search', 'role', 'status']))
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-lg transition">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Users Data Table Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                @if($users->isEmpty())
                    <div class="p-12 text-center space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mx-auto text-slate-400 font-bold">
                            UA
                        </div>
                        <h3 class="text-base font-bold text-slate-900">No user accounts found</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">Try adjusting your search criteria or create a new user credential.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4">#</th>
                                    <th class="px-6 py-4">User Details</th>
                                    <th class="px-6 py-4">Email Address</th>
                                    <th class="px-6 py-4">System Role</th>
                                    <th class="px-6 py-4">Account Status</th>
                                    <th class="px-6 py-4">Role Management</th>
                                    <th class="px-6 py-4 text-right">Account Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($users as $user)
                                    <tr class="hover:bg-slate-50 transition {{ $user->isSuspended() ? 'bg-rose-50/30' : '' }}">
                                        <td class="px-6 py-4 text-xs font-mono text-slate-400">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                @if($user->avatar_url)
                                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-300 shrink-0">
                                                @else
                                                    @php
                                                        $badgeColor = match($user->role_type) {
                                                            'Admin' => 'bg-emerald-700 text-white',
                                                            'Extension Officer' => 'bg-amber-700 text-white',
                                                            'Household' => 'bg-sky-700 text-white',
                                                            default => 'bg-slate-700 text-white',
                                                        };
                                                    @endphp
                                                    <div class="w-10 h-10 rounded-full {{ $badgeColor }} flex items-center justify-center text-xs font-bold font-mono shrink-0 shadow-sm">
                                                        {{ $user->initials }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-bold text-slate-900 block leading-tight">{{ $user->name }}</span>
                                                        @if($user->id === auth()->id())
                                                            <span class="px-1.5 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-semibold rounded">You</span>
                                                        @endif
                                                    </div>
                                                    <span class="text-[11px] text-slate-400 font-mono leading-tight">ID: #{{ $user->id }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-xs text-slate-700">{{ $user->email }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $roleBadge = match($user->role_type) {
                                                    'Admin' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                                    'Extension Officer' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                    'Household' => 'bg-sky-50 text-sky-800 border-sky-200',
                                                    default => 'bg-slate-50 text-slate-700 border-slate-200',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $roleBadge }}">
                                                {{ $user->role_type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($user->isSuspended())
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border bg-rose-50 text-rose-700 border-rose-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    Suspended
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border bg-emerald-50 text-emerald-700 border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Active
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role_type" class="text-xs border-slate-300 rounded-md py-1 px-2 focus:border-emerald-500 focus:ring-emerald-500">
                                                    <option value="Household" {{ $user->role_type === 'Household' ? 'selected' : '' }}>Household</option>
                                                    <option value="Extension Officer" {{ $user->role_type === 'Extension Officer' ? 'selected' : '' }}>Extension Officer</option>
                                                    <option value="Admin" {{ $user->role_type === 'Admin' ? 'selected' : '' }}>Admin</option>
                                                </select>
                                                <button type="submit" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold rounded transition">
                                                    Update
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                @if($user->id === auth()->id())
                                                    <span class="text-xs text-slate-400 italic">Self Account</span>
                                                @else
                                                    <!-- Toggle Status Modal Trigger -->
                                                    @if($user->isSuspended())
                                                        <button type="button"
                                                                @click="openStatusModal('{{ addslashes($user->name) }}', 'reactivate', '{{ route('admin.users.toggle-status', $user) }}')"
                                                                class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-300 text-xs font-semibold rounded transition inline-flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                            Reactivate
                                                        </button>
                                                    @else
                                                        <button type="button"
                                                                @click="openStatusModal('{{ addslashes($user->name) }}', 'suspend', '{{ route('admin.users.toggle-status', $user) }}')"
                                                                class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-300 text-xs font-semibold rounded transition inline-flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                            Suspend
                                                        </button>
                                                    @endif

                                                    <!-- Delete Account Modal Trigger -->
                                                    <button type="button"
                                                            @click="openDeleteModal('{{ addslashes($user->name) }}', '{{ route('admin.users.destroy', $user) }}')"
                                                            class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-300 text-xs font-semibold rounded transition inline-flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        Delete
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>

        <!-- Status Confirmation Modal (Suspend / Reactivate) -->
        <div x-show="statusModalOpen"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
             style="display: none;">
            <div x-show="statusModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="statusModalOpen = false"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-all"></div>

            <div x-show="statusModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-2xl shadow-2xl overflow-hidden max-w-md w-full p-6 border border-slate-100 z-10">

                <div class="flex items-start gap-4">
                    <template x-if="statusUserAction === 'suspend'">
                        <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        </div>
                    </template>
                    <template x-if="statusUserAction === 'reactivate'">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </template>

                    <div>
                        <h3 class="text-lg font-bold text-slate-900" x-text="statusUserAction === 'suspend' ? 'Suspend User Account' : 'Reactivate User Account'"></h3>
                        <p class="text-xs text-slate-600 mt-1">
                            <template x-if="statusUserAction === 'suspend'">
                                <span>Are you sure you want to suspend <strong class="text-slate-900" x-text="statusUserName"></strong>? This user will be immediately logged out and blocked from logging in.</span>
                            </template>
                            <template x-if="statusUserAction === 'reactivate'">
                                <span>Are you sure you want to reactivate <strong class="text-slate-900" x-text="statusUserName"></strong>? This user will regain full access to their account.</span>
                            </template>
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="statusModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                        Cancel
                    </button>
                    <form :action="statusFormUrl" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                :class="statusUserAction === 'suspend' ? 'bg-amber-600 hover:bg-amber-700 text-white' : 'bg-emerald-600 hover:bg-emerald-700 text-white'"
                                class="px-4 py-2 text-xs font-semibold rounded-lg shadow-sm transition">
                            <span x-text="statusUserAction === 'suspend' ? 'Yes, Suspend Account' : 'Yes, Reactivate Account'"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Account Confirmation Modal -->
        <div x-show="deleteModalOpen"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
             style="display: none;">
            <div x-show="deleteModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="deleteModalOpen = false"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-all"></div>

            <div x-show="deleteModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-2xl shadow-2xl overflow-hidden max-w-md w-full p-6 border border-slate-100 z-10">

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Delete Account</h3>
                        <p class="text-xs text-slate-600 mt-1">
                            Are you sure you want to permanently delete <strong class="text-slate-900 font-semibold" x-text="deleteUserName"></strong>'s account?
                        </p>
                        <div class="mt-3 p-3 bg-rose-50 border border-rose-200 rounded-lg text-xs text-rose-800">
                            <strong>Warning:</strong> All user records including waste logs, fertilizer batches, growth measurements, and AI chats will be permanently deleted. <strong>This action cannot be undone.</strong>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                        Cancel
                    </button>
                    <form :action="deleteFormUrl" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg shadow-sm transition inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Permanently Delete Account
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
