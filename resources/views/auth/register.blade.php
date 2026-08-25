<x-guest-layout>

    {{-- Page heading --}}
    <div class="mb-8">
        <h2 class="font-heading text-3xl font-bold text-gray-800">Create your account</h2>
        <p class="font-body text-sm text-gray-500 mt-1">Join EcoFert and start turning waste into fertilizer</p>
    </div>

    {{-- Validation errors --}}
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

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Full name --}}
        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-1.5">
                Full name
            </label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-body text-gray-800 shadow-sm placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('name') border-red-400 ring-1 ring-red-300 @enderror"
                placeholder="Jane Doe"
            >
        </div>

        {{-- Role type --}}
        <div>
            <label for="role_type" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-1.5">
                Account type
            </label>
            <select
                id="role_type"
                name="role_type"
                required
                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-body text-gray-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('role_type') border-red-400 ring-1 ring-red-300 @enderror"
            >
                <option value="" disabled {{ old('role_type') ? '' : 'selected' }}>Choose your role…</option>
                <option value="Household"        {{ old('role_type') === 'Household'        ? 'selected' : '' }}>Household</option>
                <option value="Extension Officer" {{ old('role_type') === 'Extension Officer' ? 'selected' : '' }}>Extension Officer</option>
                <option value="Admin"            {{ old('role_type') === 'Admin'            ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

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
                autocomplete="username"
                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-body text-gray-800 shadow-sm placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('email') border-red-400 ring-1 ring-red-300 @enderror"
                placeholder="you@example.com"
            >
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-1.5">
                Password
            </label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-body text-gray-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('password') border-red-400 ring-1 ring-red-300 @enderror"
                placeholder="Min. 8 characters"
            >
        </div>

        {{-- Confirm password --}}
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 font-body mb-1.5">
                Confirm password
            </label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-body text-gray-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all @error('password_confirmation') border-red-400 ring-1 ring-red-300 @enderror"
                placeholder="Repeat your password"
            >
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="btn-gold w-full rounded-lg px-4 py-3 text-sm font-body font-semibold tracking-wide focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 mt-2"
        >
            Create my account
        </button>
    </form>

    {{-- Login link --}}
    <p class="mt-6 text-center text-sm font-body text-gray-500">
        Already have an account?
        <a href="{{ route('login') }}" class="font-semibold text-emerald-600 hover:text-emerald-800 transition-colors ml-1">
            Sign in
        </a>
    </p>

</x-guest-layout>
