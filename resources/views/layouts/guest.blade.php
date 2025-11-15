<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-100">
        <div class="flex min-h-screen flex-col items-center justify-center bg-gradient-to-b from-slate-950 via-slate-950/95 to-slate-900/90 px-4 py-12">
            <div class="mb-8">
                <a href="/">
                    <x-application-logo />
                </a>
            </div>

            <div class="w-full max-w-md overflow-hidden rounded-3xl border border-slate-800/70 bg-slate-900/80 px-6 py-8 shadow-2xl shadow-slate-950/80 backdrop-blur">
                {{ $slot }}
            </div>
        </div>

        @include('layouts.partials.footer')
    </body>
</html>
