<x-guest-layout>

    <div>

        <!-- Page Heading -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Reset Password</h2>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                Enter your registered account email and we'll send a password reset link.
            </p>
        </div>

        <!-- Session Status Alert -->
        @if(session('status'))
            <div class="mb-4 p-3 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-800 text-xs font-medium">
                {{ session('status') }}
            </div>
        @endif

        <!-- Validation Error Summary -->
        @if($errors->any())
            <div class="mb-4 p-3 rounded-lg border border-red-200 bg-red-50 text-red-700 text-xs space-y-1">
                @foreach($errors->all() as $error)
                    <p class="font-medium">&bull; {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
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
                       placeholder="user@ecofert.com"
                       class="w-full text-sm border-slate-300 rounded-lg shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-lg shadow-sm transition">
                Send Password Reset Link
            </button>
        </form>

        <!-- Back to Login -->
        <p class="mt-6 text-center text-xs text-slate-500">
            Remembered your password?
            <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:underline ml-1">
                Sign in to portal
            </a>
        </p>

    </div>

</x-guest-layout>
