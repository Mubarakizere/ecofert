<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EcoFert') }} - Decision Support System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">

        <div class="min-h-screen flex">

            <!-- Left Executive Identity Panel (Desktop) -->
            <div class="hidden lg:flex lg:w-[45%] xl:w-[40%] bg-slate-900 text-white relative overflow-hidden flex-col justify-between p-12 border-r border-slate-800">
                
                <div class="space-y-6 relative z-10">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                            E
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold tracking-tight text-white leading-none">EcoFert</span>
                            <span class="text-xs text-emerald-400 font-medium leading-tight mt-0.5">Decision Support System</span>
                        </div>
                    </a>
                </div>

                <div class="space-y-6 relative z-10 my-auto py-12">
                    <div class="inline-block px-3 py-1 bg-emerald-900/60 border border-emerald-700/50 text-emerald-300 font-mono text-xs font-semibold rounded-full">
                        Rikolto Kungahara Case Study &bull; Musanze/Nyabihu
                    </div>

                    <h1 class="text-3xl xl:text-4xl font-bold tracking-tight text-white leading-tight">
                        Web-Based Decision Support System for Organic Fertilizer Production
                    </h1>

                    <p class="text-sm text-slate-300 leading-relaxed">
                        Guiding households in valorising food waste into nutrient-balanced organic fertilizers using validated extension officer formulation rules.
                    </p>

                    <div class="space-y-3 text-xs text-slate-400 border-t border-slate-800 pt-6">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Rule-Based Multi-Ingredient Stock Matching</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>4-Week Plant Growth Trajectory Tracking</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Extension Officer Validated Formulas</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 text-xs text-slate-500 pt-6 border-t border-slate-800 flex items-center justify-between">
                    <span>University of Kigali &bull; BIT Honours Research</span>
                    <span>August 2026</span>
                </div>

            </div>

            <!-- Right Form Container -->
            <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-12 bg-slate-50">
                
                <!-- Mobile Brand Header -->
                <div class="lg:hidden mb-8 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-bold text-xl shadow">
                        E
                    </div>
                    <div class="flex flex-col text-left">
                        <span class="text-xl font-bold tracking-tight text-slate-900 leading-none">EcoFert</span>
                        <span class="text-xs text-emerald-700 font-medium leading-tight mt-0.5">Decision Support System</span>
                    </div>
                </div>

                <div class="w-full max-w-md bg-white rounded-2xl p-8 border border-slate-200 shadow-sm">
                    {{ $slot }}
                </div>

            </div>

        </div>

    </body>
</html>
