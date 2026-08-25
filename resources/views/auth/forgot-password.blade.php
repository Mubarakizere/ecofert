<x-guest-layout>

    {{-- Page heading --}}
    <div class="mb-8">
        <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center mb-4">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
            </svg>
        </div>
        <h2 class="font-heading text-3xl font-bold text-gray-800">Reset your password</h2>
        <p class="font-body text-sm text-gray-500 mt-1 leading-relaxed">
            Enter your email and we'll send you a secure link to choose a new password.
        </p>
    </div>

    {{-- Session status (confirmation that email was sent) --}}
    @if(session('status'))
        <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm font-body flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    {{-- Errors --}}
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

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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
                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-body text-gray-800 shadow-sm placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('email') border-red-400 ring-1 ring-red-300 @enderror"
                placeholder="you@example.com"
            >
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="btn-gold w-full rounded-lg px-4 py-3 text-sm font-body font-semibold tracking-wide focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2"
        >
            Send reset link
        </button>
    </form>

    {{-- Back to login --}}
    <p class="mt-6 text-center text-sm font-body text-gray-500">
        Remember your password?
        <a href="{{ route('login') }}" class="font-semibold text-emerald-600 hover:text-emerald-800 transition-colors ml-1">
            Sign in
        </a>
    </p>

</x-guest-layout>
