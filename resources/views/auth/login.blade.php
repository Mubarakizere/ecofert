<x-guest-layout>

    <div>

        <!-- Page Heading -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Sign In to EcoFert</h2>
            <p class="text-xs text-slate-500 mt-1">Access your household portal, extension workspace, or admin panel.</p>
        </div>

        <!-- Session Status Alert -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Error Summary -->
        @if($errors->any())
            <div class="mb-4 p-3 rounded-lg border border-red-200 bg-red-50 text-red-700 text-xs space-y-1">
                @foreach($errors->all() as $error)
                    <p class="font-medium">&bull; {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Email Address
                </label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       autocomplete="username"
                       placeholder="user@ecofert.com"
                       class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <!-- Password Input -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Password
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-emerald-600 hover:underline">
                            Forgot password?
                        </a>
                    @endif
                </div>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                       class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-2">
                <input id="remember_me"
                       type="checkbox"
                       name="remember"
                       class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <label for="remember_me" class="text-xs text-slate-600 select-none">
                    Remember my login session
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-lg shadow-sm transition">
                Sign In to Portal
            </button>
        </form>

        <!-- Register Link -->
        @if(Route::has('register'))
            <p class="mt-6 text-center text-xs text-slate-500">
                Need a new user account?
                <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:underline ml-1">
                    Register household account
                </a>
            </p>
        @endif

    </div>

</x-guest-layout>
