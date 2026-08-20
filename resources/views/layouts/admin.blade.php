<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EcoFert') }} — Admin</title>

        <!-- Fonts: Playfair Display (headers) + Montserrat (body) -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:400,600,700|montserrat:300,400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .font-heading { font-family: 'Playfair Display', serif; }
            .font-body { font-family: 'Montserrat', sans-serif; }

            /* Spotlight radial gradient effect */
            .spotlight {
                position: relative;
            }
            .spotlight::before {
                content: '';
                position: absolute;
                top: -80px;
                left: 50%;
                transform: translateX(-50%);
                width: 600px;
                height: 400px;
                background: radial-gradient(ellipse at center, rgba(202, 168, 79, 0.08) 0%, transparent 70%);
                pointer-events: none;
                z-index: 0;
            }
            .spotlight > * {
                position: relative;
                z-index: 1;
            }

            /* Gold shimmer animation on buttons */
            .btn-gold {
                background: linear-gradient(135deg, #b8860b, #daa520, #b8860b);
                background-size: 200% 200%;
                animation: goldShimmer 3s ease infinite;
            }
            @keyframes goldShimmer {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            /* Subtle glow on table rows */
            .row-glow:hover {
                box-shadow: 0 0 20px rgba(202, 168, 79, 0.1);
            }
        </style>
    </head>
    <body class="font-body antialiased">
        <div class="min-h-screen" style="background: linear-gradient(160deg, #0a0f0d 0%, #0d1a14 40%, #111827 100%);">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="border-b border-yellow-900/30" style="background: rgba(10, 15, 13, 0.8);">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="rounded-lg border border-emerald-600/40 bg-emerald-900/30 px-4 py-3 text-emerald-300 text-sm font-body flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
