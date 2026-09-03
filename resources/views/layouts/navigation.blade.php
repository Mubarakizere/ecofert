<nav x-data="{ open: false }" class="bg-slate-900 border-b border-slate-800 text-white shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold shadow">
                            E
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg font-bold tracking-tight text-white leading-none">EcoFert</span>
                            <span class="text-xs text-emerald-400 font-medium leading-tight">Decision Support System</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-8 sm:flex items-center">
                    @if(Auth::user()->isHousehold())
                        <a href="{{ route('household.dashboard') }}"
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium border-b-2 transition-all duration-200 {{ request()->routeIs('household.dashboard') ? 'border-emerald-500 text-emerald-400' : 'border-transparent text-slate-300 hover:text-white' }}">
                            Dashboard & Stock
                        </a>
                        <a href="{{ route('household.recommendations.index') }}"
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium border-b-2 transition-all duration-200 {{ request()->routeIs('household.recommendations.*') ? 'border-emerald-500 text-emerald-400' : 'border-transparent text-slate-300 hover:text-white' }}">
                            Formulation Rules
                        </a>
                        <a href="{{ route('household.batches.index') }}"
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium border-b-2 transition-all duration-200 {{ request()->routeIs('household.batches.*') ? 'border-emerald-500 text-emerald-400' : 'border-transparent text-slate-300 hover:text-white' }}">
                            Active Batches
                        </a>
                        <a href="{{ route('household.experiments.index') }}"
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium border-b-2 transition-all duration-200 {{ request()->routeIs('household.experiments.*') ? 'border-emerald-500 text-emerald-400' : 'border-transparent text-slate-300 hover:text-white' }}">
                            4-Week Plant Trials
                        </a>
                        <a href="{{ route('household.chat.history') }}"
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium border-b-2 transition-all duration-200 {{ request()->routeIs('household.chat.*') ? 'border-emerald-500 text-emerald-400' : 'border-transparent text-slate-300 hover:text-white' }}">
                            AI Assistant
                        </a>
                    @endif

                    @if(Auth::user()->isExtensionOfficer())
                        <a href="{{ route('officer.dashboard') }}"
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium border-b-2 transition-all duration-200 {{ request()->routeIs('officer.dashboard') ? 'border-emerald-500 text-emerald-400' : 'border-transparent text-slate-300 hover:text-white' }}">
                            Extension Workspace
                        </a>
                        <a href="{{ route('officer.formulations.index') }}"
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium border-b-2 transition-all duration-200 {{ request()->routeIs('officer.formulations.*') ? 'border-emerald-500 text-emerald-400' : 'border-transparent text-slate-300 hover:text-white' }}">
                            Validated Formulations
                        </a>
                        <a href="{{ route('officer.experiments.index') }}"
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium border-b-2 transition-all duration-200 {{ request()->routeIs('officer.experiments.*') ? 'border-emerald-500 text-emerald-400' : 'border-transparent text-slate-300 hover:text-white' }}">
                            Cooperative Trials
                        </a>
                    @endif

                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium border-b-2 transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'border-emerald-500 text-emerald-400' : 'border-transparent text-slate-300 hover:text-white' }}">
                            Admin Control
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="inline-flex items-center px-1 pt-1 text-sm font-medium border-b-2 transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'border-emerald-500 text-emerald-400' : 'border-transparent text-slate-300 hover:text-white' }}">
                            User Management
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-1.5 border border-slate-700 text-sm font-medium rounded-lg text-slate-200 bg-slate-800 hover:bg-slate-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <span class="ms-2 px-2 py-0.5 text-xs rounded bg-slate-900 text-emerald-400 border border-slate-700 font-mono">{{ Auth::user()->role_type }}</span>
                            <svg class="ms-1.5 fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900 border-t border-slate-800">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->isHousehold())
                <x-responsive-nav-link :href="route('household.dashboard')" :active="request()->routeIs('household.dashboard')">Dashboard & Stock</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('household.recommendations.index')" :active="request()->routeIs('household.recommendations.*')">Formulation Rules</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('household.batches.index')" :active="request()->routeIs('household.batches.*')">Active Batches</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('household.experiments.index')" :active="request()->routeIs('household.experiments.*')">4-Week Plant Trials</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('household.chat.history')" :active="request()->routeIs('household.chat.*')">AI Assistant</x-responsive-nav-link>
            @endif

            @if(Auth::user()->isExtensionOfficer())
                <x-responsive-nav-link :href="route('officer.dashboard')" :active="request()->routeIs('officer.dashboard')">Extension Workspace</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('officer.formulations.index')" :active="request()->routeIs('officer.formulations.*')">Validated Formulations</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('officer.experiments.index')" :active="request()->routeIs('officer.experiments.*')">Cooperative Trials</x-responsive-nav-link>
            @endif

            @if(Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Admin Control</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">User Management</x-responsive-nav-link>
            @endif
        </div>
    </div>
</nav>
