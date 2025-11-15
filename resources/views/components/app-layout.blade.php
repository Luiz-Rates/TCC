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

        <style>
            .select2-container.select2-tailwind {
                width: 100% !important;
                margin-top: 0.5rem;
            }

            .select2-container.select2-tailwind .select2-selection--single,
            .select2-container.select2-tailwind .select2-selection--multiple {
                background-color: #0f172a;
                border: 1px solid #1f2937;
                border-radius: 0.75rem;
                min-height: 2.75rem;
                padding: 0.5rem 0.75rem;
                display: flex;
                align-items: center;
                transition: border-color 0.15s ease, box-shadow 0.15s ease;
            }

            .select2-container.select2-tailwind .select2-selection__rendered {
                color: #f8fafc;
                font-size: 0.95rem;
            }

            .select2-container.select2-tailwind .select2-selection__placeholder {
                color: #94a3b8;
            }

            .select2-container.select2-tailwind .select2-selection--single .select2-selection__arrow {
                height: 100%;
                right: 0.75rem;
            }

            .select2-container.select2-tailwind.select2-container--open .select2-selection--single,
            .select2-container.select2-tailwind.select2-container--focus .select2-selection--single,
            .select2-container.select2-tailwind.select2-container--open .select2-selection--multiple,
            .select2-container.select2-tailwind.select2-container--focus .select2-selection--multiple {
                border-color: #2563eb;
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.35);
            }

            .select2-container.select2-tailwind .select2-selection__choice {
                background-color: rgba(37, 99, 235, 0.15);
                border: none;
                color: #bfdbfe;
            }

            .select2-container.select2-tailwind .select2-selection__choice__remove {
                color: #60a5fa;
            }

            .select2-dropdown {
                background-color: #0b1120;
                border: 1px solid #1f2937;
                border-radius: 0.75rem;
                overflow: hidden;
            }

            .select2-results__option {
                padding: 0.6rem 0.8rem;
                color: #e2e8f0;
            }

            .select2-results__option--highlighted[aria-selected],
            .select2-results__option--highlighted {
                background-color: #2563eb;
                color: #f8fafc;
            }

            .select2-search--dropdown .select2-search__field {
                padding: 0.5rem 0.75rem;
                background-color: #0f172a;
                border: 1px solid #1f2937;
                color: #f8fafc;
                border-radius: 0.5rem;
            }

            .select2-search--dropdown .select2-search__field:focus-visible {
                outline: none;
                border-color: #2563eb;
                box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.35);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-100">
        <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-950/95 to-slate-900/90">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-slate-900/70 backdrop-blur border-b border-slate-800/70">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="py-10 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
