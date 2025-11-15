<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NexKeep') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-100">
        <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">

            <div class="relative flex min-h-screen flex-col lg:flex-row">
                <div class="flex w-full lg:w-[420px] flex-col justify-center px-6 py-12 sm:px-10 lg:px-14">
                    <div class="space-y-4">
                        <div class="flex justify-center sm:justify-start mt-2">
                            <img src="{{ asset('images/nexkeep-full.png') }}" alt="NexKeep" class="w-40 sm:w-52 drop-shadow-xl">
                        </div>

                        <div class="max-w-md">
                            {{ $slot }}
                        </div>
                    </div>
                </div>

                <div class="hidden flex-1 items-center justify-center px-8 py-12 lg:flex">
                    <div class="max-w-3xl rounded-[2.5rem] border border-white/10 bg-white/5 p-10 shadow-[0_25px_70px_-30px_rgba(37,99,235,0.5)] backdrop-blur-xl">
                        <div class="grid gap-6 text-left sm:grid-cols-2">
                            <div>
                                <h2 class="text-lg font-semibold text-blue-200">Gestão integrada</h2>
                                <p class="mt-2 text-sm text-slate-200/70">
                                    Organize produtos, clientes e vendas do NexKeep em um fluxo simples e conectado.
                                </p>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-emerald-200">Contas em aberto</h2>
                                <p class="mt-2 text-sm text-slate-200/70">
                                    Acompanhe valores pendentes e registre recebimentos com rapidez.
                                </p>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-amber-200">Relatórios visuais</h2>
                                <p class="mt-2 text-sm text-slate-200/70">
                                    Visualize métricas claras para entender o desempenho financeiro.
                                </p>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-fuchsia-200">Acesso seguro</h2>
                                <p class="mt-2 text-sm text-slate-200/70">
                                    Compartilhe o NexKeep com seus parceiros usando perfis dedicados e protegidos.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.partials.footer')
    </body>
</html>
