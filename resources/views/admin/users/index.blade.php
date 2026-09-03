<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight leading-tight">
                    User Accounts & Platform Access
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Manage system user credentials, assign Spatie roles, and control access permissions.
                </p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                + Provision New User
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-sm font-medium rounded-r-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Metric Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                @php
                    $totalUsers = $users->count();
                    $householdCount = $users->where('role_type', 'Household')->count();
                    $officerCount = $users->where('role_type', 'Extension Officer')->count();
                    $adminCount = $users->where('role_type', 'Admin')->count();
                @endphp
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Accounts</span>
                        <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $totalUsers }}</h3>
                        <p class="text-xs text-slate-500 mt-1">Active platform users</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 font-bold text-sm">
                        ALL
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Households</span>
                        <h3 class="text-3xl font-bold text-sky-700 mt-1">{{ $householdCount }}</h3>
                        <p class="text-xs text-slate-500 mt-1">Home waste loggers</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center text-sky-700 font-bold text-sm">
                        HH
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Extension Officers</span>
                        <h3 class="text-3xl font-bold text-amber-700 mt-1">{{ $officerCount }}</h3>
                        <p class="text-xs text-slate-500 mt-1">Field advisors</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-700 font-bold text-sm">
                        EO
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Administrators</span>
                        <h3 class="text-3xl font-bold text-emerald-700 mt-1">{{ $adminCount }}</h3>
                        <p class="text-xs text-slate-500 mt-1">System controllers</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-700 font-bold text-sm">
                        ADM
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

                    <div class="w-full md:w-56">
                        <select name="role" class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">All Roles</option>
                            <option value="Household" {{ request('role') === 'Household' ? 'selected' : '' }}>Household</option>
                            <option value="Extension Officer" {{ request('role') === 'Extension Officer' ? 'selected' : '' }}>Extension Officer</option>
                            <option value="Admin" {{ request('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-lg shadow-sm transition">
                            Apply Filter
                        </button>
                        @if(request()->hasAny(['search', 'role']))
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
                                    <th class="px-6 py-4">Account Action</th>
                                    <th class="px-6 py-4">Joined Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($users as $user)
                                    <tr class="hover:bg-slate-50 transition">
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
                                                    <span class="font-bold text-slate-900 block leading-tight">{{ $user->name }}</span>
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
                                        <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                            {{ $user->created_at->format('M d, Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
