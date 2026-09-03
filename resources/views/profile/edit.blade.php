<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 tracking-tight leading-tight">
                    Account Settings & Profile
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Manage your EcoFert credentials, profile picture, and security settings.
                </p>
            </div>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition">
                &larr; Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Profile Summary Card -->
            <div class="bg-slate-900 text-white rounded-2xl p-6 sm:p-8 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border border-slate-800">
                <div class="flex items-center gap-4">
                    @if(Auth::user()->avatar_url)
                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-emerald-500 shadow">
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-emerald-600 flex items-center justify-center text-xl font-bold text-white shadow">
                            {{ Auth::user()->initials }}
                        </div>
                    @endif
                    <div>
                        <div class="flex items-center gap-3">
                            <h3 class="text-xl font-bold tracking-tight text-white">{{ Auth::user()->name }}</h3>
                            <span class="px-2.5 py-0.5 text-xs font-mono font-semibold bg-slate-800 text-emerald-400 border border-slate-700 rounded-full">
                                {{ Auth::user()->role_type }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">
                            {{ Auth::user()->email }} &bull; Member since {{ Auth::user()->created_at->format('M Y') }}
                        </p>
                    </div>
                </div>

                <div class="text-xs text-slate-400 bg-slate-800/60 px-4 py-3 rounded-xl border border-slate-800 max-w-sm">
                    <span class="font-semibold text-slate-200 block mb-0.5">Account Role Access:</span>
                    @if(Auth::user()->isHousehold())
                        Household Member access enabled for logging food waste and tracking 4-week plant growth.
                    @elseif(Auth::user()->isExtensionOfficer())
                        Extension Officer access enabled for managing validated formulations and reviewing member trials.
                    @else
                        System Administrator access enabled for managing user accounts and access control.
                    @endif
                </div>
            </div>

            <!-- Profile Information Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Update Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
