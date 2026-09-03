<x-guest-layout>

    <div>

        <!-- Page Heading -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create EcoFert Account</h2>
            <p class="text-xs text-slate-500 mt-1">Register your household to start logging food waste and producing organic fertilizer.</p>
        </div>

        <!-- Validation Error Summary -->
        @if($errors->any())
            <div class="mb-4 p-3 rounded-lg border border-red-200 bg-red-50 text-red-700 text-xs space-y-1">
                @foreach($errors->all() as $error)
                    <p class="font-medium">&bull; {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Full Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Full Name
                </label>
                <input id="name"
                       type="text"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       autofocus
                       autocomplete="name"
                       placeholder="e.g. Jean Damascene"
                       class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Email Address
                </label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autocomplete="username"
                       placeholder="user@ecofert.com"
                       class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Password
                </label>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="new-password"
                       placeholder="Minimum 8 characters"
                       class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Confirm Password
                </label>
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Re-type password"
                       class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-lg shadow-sm transition">
                Create Household Account
            </button>
        </form>

        <!-- Login Link -->
        <p class="mt-6 text-center text-xs text-slate-500">
            Already have an account?
            <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:underline ml-1">
                Sign in to portal
            </a>
        </p>

    </div>

</x-guest-layout>
