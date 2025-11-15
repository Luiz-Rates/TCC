@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl rounded-3xl border border-slate-800/70 bg-slate-950/90 p-6 text-slate-100 shadow-2xl shadow-slate-900/70 backdrop-blur sm:p-8">
        <div class="flex flex-col gap-2 border-b border-slate-800/70 pb-6 text-center sm:text-left">
            <x-back-button :href="route('dashboard')" />
            <span class="text-xs font-semibold uppercase tracking-[0.4em] text-blue-400/80">Administração</span>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-3xl font-bold text-white">Gestão de Usuários</h2>
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-emerald-500/60 bg-emerald-500/20 px-5 py-2.5 text-sm font-semibold uppercase tracking-wide text-emerald-200 shadow-lg shadow-emerald-900/40 transition hover:bg-emerald-500/30">
                    <span class="text-lg">+</span> Novo usuário
                </a>
            </div>
            <p class="text-sm text-slate-400">
                Controle quem pode acessar o sistema, defina permissões de administrador e mantenha o cadastro sempre em dia.
            </p>
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}"
            class="mt-8 flex flex-col gap-4 rounded-2xl border border-slate-800/70 bg-slate-900/70 p-5 shadow-inner shadow-slate-950/60 sm:flex-row sm:items-center">
            <div class="flex-1">
                <label for="search" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Buscar usuário</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Digite nome ou e-mail..."
                    class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40"
                >
            </div>
            <button type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-blue-500/60 bg-blue-600/90 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-blue-900/40 transition hover:border-blue-400 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/60 sm:w-auto">
                Buscar
            </button>
        </form>

        @if ($search !== '')
            <p class="mt-6 text-sm text-slate-400">
                Resultados filtrados por: <span class="font-semibold text-slate-200">"{{ $search }}"</span>
            </p>
        @endif

        <div class="mt-8 rounded-3xl border border-slate-800/70">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px] divide-y divide-slate-800/80">
                    <thead class="bg-slate-900/80">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <th class="px-6 py-4">Nome</th>
                            <th class="px-6 py-4">E-mail</th>
                            <th class="px-6 py-4">Perfil</th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 bg-slate-950/70">
                        @forelse ($users as $user)
                            <tr class="transition hover:bg-slate-900/60">
                                <td class="px-6 py-4 text-sm font-semibold text-slate-100">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-300">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($user->is_admin)
                                        <span class="inline-flex items-center rounded-full border border-blue-500/40 bg-blue-600/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-200">
                                            Administrador
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full border border-slate-700 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-400">
                                            Usuário
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center justify-center gap-3 text-sm font-semibold uppercase tracking-wide sm:justify-end">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="rounded-xl border border-blue-500/40 px-3 py-2 text-blue-200 transition hover:border-blue-400 hover:bg-blue-500/10">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="rounded-xl border border-rose-500/40 px-3 py-2 text-rose-300 transition hover:border-rose-400 hover:bg-rose-500/10">
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm font-semibold uppercase tracking-wide text-slate-500">
                                    Nenhum usuário encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
@endsection
