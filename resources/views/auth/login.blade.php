<x-guest-layout>

    {{-- Page heading --}}
    <div class="mb-8">
        <h2 class="font-heading text-3xl font-bold text-gray-800">Welcome back</h2>
        <p class="font-body text-sm text-gray-500 mt-1">Sign in to your EcoFert account</p>
    </div>

    {{-- Session status (e.g. "password reset link sent") --}}
    <x-auth-session-status class="mb-5" :status="session('status')" />

    {{-- Validation error summary --}}
    @if($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm font-body flex items-start gap-2">
            <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
            </svg>
            <div>
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-1.5">
                Email address
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-body text-gray-800 shadow-sm placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('email') border-red-400 ring-1 ring-red-300 @enderror"
                placeholder="you@example.com"
            >
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body">
                    Password
                </label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs font-body text-emerald-600 hover:text-emerald-800 transition-colors">
                        Forgot password?
                    </a>
                @endif
            </div>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-body text-gray-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('password') border-red-400 ring-1 ring-red-300 @enderror"
                placeholder="••••••••"
            >
        </div>

        {{-- Remember me --}}
        <div class="flex items-center gap-2.5">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-400 transition-colors"
            >
            <label for="remember_me" class="text-sm font-body text-gray-600 select-none cursor-pointer">
                Keep me signed in
            </label>
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="btn-gold w-full rounded-lg px-4 py-3 text-sm font-body font-semibold tracking-wide focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 mt-2"
        >
            Sign in to EcoFert
        </button>
    </form>

    {{-- Register link --}}
    @if(Route::has('register'))
        <p class="mt-6 text-center text-sm font-body text-gray-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold text-emerald-600 hover:text-emerald-800 transition-colors ml-1">
                Create one free
            </a>
        </p>
    @endif

</x-guest-layout>
