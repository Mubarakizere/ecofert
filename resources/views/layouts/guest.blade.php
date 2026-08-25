<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EcoFert') }}</title>

        <!-- Fonts: Playfair Display (headings) + Montserrat (body) -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:400,600,700|montserrat:300,400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .font-heading { font-family: 'Playfair Display', serif; }
            .font-body    { font-family: 'Montserrat', sans-serif; }

            /* Left-panel organic blob decoration */
            .auth-blob {
                position: absolute;
                border-radius: 50%;
                filter: blur(60px);
                opacity: 0.18;
                pointer-events: none;
            }
        </style>
    </head>
    <body class="font-body antialiased bg-gray-50 text-gray-800">

        <div class="min-h-screen flex">

            {{-- ── Left: branded identity panel ─────────────────────────────── --}}
            <div class="hidden lg:flex lg:w-[45%] xl:w-[42%] relative overflow-hidden flex-col justify-between"
                 style="background: linear-gradient(155deg, #064e3b 0%, #065f46 40%, #047857 70%, #059669 100%);">

                <!-- Decorative blobs -->
                <div class="auth-blob bg-amber-400  w-72 h-72 -top-16 -left-16"></div>
                <div class="auth-blob bg-emerald-300 w-96 h-96  bottom-0  right-0 translate-x-1/3 translate-y-1/4"></div>
                <div class="auth-blob bg-yellow-300  w-52 h-52  top-1/2  left-1/4"></div>

                <!-- Subtle grid overlay -->
                <div class="absolute inset-0 opacity-[0.04]"
                     style="background-image: repeating-linear-gradient(0deg,#fff 0,#fff 1px,transparent 1px,transparent 40px),
                                              repeating-linear-gradient(90deg,#fff 0,#fff 1px,transparent 1px,transparent 40px);">
                </div>

                <!-- Content -->
                <div class="relative z-10 px-10 pt-10">
                    <!-- Logo -->
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-1">
                        <span class="text-2xl font-bold text-white"    style="font-family:'Playfair Display',serif;">Eco</span>
                        <span class="text-2xl font-bold text-amber-300" style="font-family:'Playfair Display',serif;">Fert</span>
                    </a>
                </div>

                <div class="relative z-10 px-10 pb-6">
                    <!-- Tagline -->
                    <h1 class="font-heading text-4xl xl:text-5xl font-bold text-white leading-tight mb-4">
                        Turn kitchen<br>waste into<br>
                        <span class="text-amber-300">liquid gold.</span>
                    </h1>
                    <p class="font-body text-emerald-100 text-sm leading-relaxed max-w-xs">
                        EcoFert helps home gardeners transform everyday organic scraps into powerful, natural fertilizers one log at a time.
                    </p>

                    <!-- Feature pills -->
                    <div class="flex flex-wrap gap-2 mt-6">
                        @foreach(['Banana Peels', 'Eggshells', 'Coffee Grounds'] as $tag)
                            <span class="inline-flex items-center gap-1.5 bg-white/10 border border-white/20 text-white text-xs font-body font-medium px-3 py-1 rounded-full backdrop-blur-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-300 shrink-0"></span>
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>

                    <!-- Footer note -->
                    <p class="mt-10 text-emerald-200 text-xs font-body opacity-70">
                        &copy; {{ date('Y') }} EcoFert &middot; Sustainable Agriculture
                    </p>
                </div>
            </div>

            {{-- ── Right: form panel ──────────────────────────────────────────── --}}
            <div class="flex-1 flex flex-col justify-center items-center px-6 sm:px-10 py-12 bg-white">

                <!-- Mobile-only logo -->
                <div class="lg:hidden mb-8 flex items-center gap-1">
                    <span class="text-2xl font-bold text-emerald-700" style="font-family:'Playfair Display',serif;">Eco</span>
                    <span class="text-2xl font-bold text-amber-600"   style="font-family:'Playfair Display',serif;">Fert</span>
                </div>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>

                <!-- Bottom nav links -->
                <div class="mt-8 flex items-center gap-4 text-xs font-body text-gray-400">
                    @if(Route::has('login'))
                        <a href="{{ route('login') }}" class="hover:text-emerald-600 transition-colors">Sign in</a>
                    @endif
                    @if(Route::has('register'))
                        <span class="text-gray-200">|</span>
                        <a href="{{ route('register') }}" class="hover:text-emerald-600 transition-colors">Create account</a>
                    @endif
                    @if(Route::has('password.request'))
                        <span class="text-gray-200">|</span>
                        <a href="{{ route('password.request') }}" class="hover:text-emerald-600 transition-colors">Forgot password?</a>
                    @endif
                </div>
            </div>

        </div>
    </body>
</html>
