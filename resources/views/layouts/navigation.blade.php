<nav x-data="{ open: false }" class="relative z-50 border-b border-slate-800/60 bg-slate-950/70 backdrop-blur">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex">
                <!-- Logo -->
              <div class="flex shrink-0 items-center -ml-19 pr-6 lg:-ml-40 lg:pr-20">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-60 w-30" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden items-center gap-2 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Painel') }}
                    </x-nav-link>
                    <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')">
                        {{ __('Clientes') }}
                    </x-nav-link>
                    <x-nav-link :href="route('produtos.index')" :active="request()->routeIs('produtos.*')">
                        {{ __('Produtos') }}
                    </x-nav-link>
                    <x-nav-link :href="route('vendas.index')" :active="request()->routeIs('vendas.*')">
                        {{ __('Vendas') }}
                    </x-nav-link>
                    <x-nav-link :href="route('fiados.index')" :active="request()->routeIs('fiados.*', 'relatorio.fiados*')">
                        {{ __('Contas em Aberto') }}
                    </x-nav-link>
                    <x-nav-link :href="route('relatorio.vendas')" :active="request()->routeIs('relatorio.*')">
                        {{ __('Relatórios') }}
                    </x-nav-link>
                    @if (Auth::user()?->is_admin)
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Usuários') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="relative hidden sm:ms-6 sm:flex sm:items-center">
                <a href="{{ route('vendas.create') }}" class="me-4 inline-flex items-center gap-2 rounded-2xl border border-blue-500/70 bg-blue-600/20 px-4 py-2 text-sm font-semibold text-blue-100 shadow-sm shadow-blue-900/40 transition hover:border-blue-400 hover:bg-blue-600/30 focus:outline-none focus:ring-2 focus:ring-blue-500/40">
                    <svg class="h-4 w-4 text-blue-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    {{ __('Nova venda') }}
                </a>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex max-w-[14rem] items-center gap-2 rounded-2xl border border-slate-800/60 bg-slate-900/80 px-3 py-2 text-sm font-medium text-slate-300 transition hover:border-blue-500/60 hover:text-blue-200 focus:outline-none focus:border-blue-500/60">
                            <div class="truncate" title="{{ Auth::user()->name }}">{{ \Illuminate\Support\Str::limit(Auth::user()->name, 14) }}</div>

                            <div class="ms-1">
                                <svg class="h-4 w-4 fill-current text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Sair') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-xl p-2 text-slate-400 transition hover:bg-slate-900/70 hover:text-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-800/60 bg-slate-950/70 sm:hidden">
        <div class="space-y-1 px-4 pb-3 pt-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Painel') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')">
                {{ __('Clientes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('produtos.index')" :active="request()->routeIs('produtos.*')">
                {{ __('Produtos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('vendas.index')" :active="request()->routeIs('vendas.*')">
                {{ __('Vendas') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('fiados.index')" :active="request()->routeIs('fiados.*', 'relatorio.fiados*')">
                {{ __('Contas em Aberto') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('relatorio.vendas')" :active="request()->routeIs('relatorio.*')">
                {{ __('Relatórios') }}
            </x-responsive-nav-link>
            @if (Auth::user()?->is_admin)
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Usuários') }}
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('vendas.create')" :active="request()->routeIs('vendas.create')">
                {{ __('Nova venda') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="border-t border-slate-800/60 px-4 pb-4 pt-4">
            <div class="px-4">
                <div class="text-base font-medium text-slate-200 break-words">{{ \Illuminate\Support\Str::limit(Auth::user()->name, 18) }}</div>
                <div class="text-sm font-medium text-slate-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Sair') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
