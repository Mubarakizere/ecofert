<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-heading text-2xl font-bold text-gray-800 tracking-wide">
                    User Management
                </h2>
                <p class="text-sm text-gray-500 font-body mt-1">Manage system users, assign roles, and control platform access.</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
               id="btn-create-user"
               class="btn-gold inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/></svg>
                New User
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Bar -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                @php
                    $totalUsers = $users->count();
                    $adminCount = $users->filter(fn($u) => $u->roles->pluck('name')->contains('Admin'))->count();
                    $householdCount = $users->filter(fn($u) => $u->roles->pluck('name')->contains('Household'))->count();
                @endphp
                <div class="stat-card stat-card--emerald">
                    <p class="section-label">Total Users</p>
                    <p class="text-3xl font-heading font-bold text-emerald-700 mt-1">{{ $totalUsers }}</p>
                </div>
                <div class="stat-card stat-card--gold">
                    <p class="section-label">Administrators</p>
                    <p class="text-3xl font-heading font-bold text-amber-700 mt-1">{{ $adminCount }}</p>
                </div>
                <div class="stat-card stat-card--amber">
                    <p class="section-label">Household Members</p>
                    <p class="text-3xl font-heading font-bold text-amber-600 mt-1">{{ $householdCount }}</p>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card-dark overflow-hidden">
                @if($users->isEmpty())
                    <div class="p-16 text-center">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-body text-lg">No users found in the system.</p>
                        <a href="{{ route('admin.users.create') }}" class="inline-block mt-4 text-amber-600 hover:text-amber-700 text-sm font-semibold underline underline-offset-4 transition-colors">
                            Create the first user →
                        </a>
                    </div>
                @else
                    <table class="w-full text-left" id="users-table">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-6 py-4 text-xs font-body font-semibold uppercase tracking-wider text-gray-400">#</th>
                                <th class="px-6 py-4 text-xs font-body font-semibold uppercase tracking-wider text-gray-400">Name</th>
                                <th class="px-6 py-4 text-xs font-body font-semibold uppercase tracking-wider text-gray-400">Email</th>
                                <th class="px-6 py-4 text-xs font-body font-semibold uppercase tracking-wider text-gray-400">Role</th>
                                <th class="px-6 py-4 text-xs font-body font-semibold uppercase tracking-wider text-gray-400">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($users as $user)
                                <tr class="row-glow">
                                    <td class="px-6 py-5 text-sm text-gray-400 font-body">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            {{-- Avatar circle with initials --}}
                                            @php
                                                $initials = collect(explode(' ', $user->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('');
                                                $roleName = $user->roles->first()?->name ?? 'Unknown';
                                                $avatarColor = match($roleName) {
                                                    'Admin' => 'bg-emerald-100 text-emerald-700',
                                                    'Extension Officer' => 'bg-amber-100 text-amber-700',
                                                    'Household' => 'bg-sky-100 text-sky-700',
                                                    default => 'bg-gray-100 text-gray-600',
                                                };
                                            @endphp
                                            <div class="w-9 h-9 rounded-full {{ $avatarColor }} flex items-center justify-center text-xs font-bold font-body shrink-0">
                                                {{ $initials }}
                                            </div>
                                            <span class="text-sm font-medium text-gray-800 font-body">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-gray-500 font-body">{{ $user->email }}</td>
                                    <td class="px-6 py-5">
                                        @php
                                            $badgeStyle = match($roleName) {
                                                'Admin' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'Extension Officer' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Household' => 'bg-sky-50 text-sky-700 border-sky-200',
                                                default => 'bg-gray-50 text-gray-600 border-gray-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeStyle }}">
                                            {{ $roleName }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-gray-400 font-body">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
