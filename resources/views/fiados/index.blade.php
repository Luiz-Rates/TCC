@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl rounded-3xl border border-slate-800/70 bg-slate-950/90 p-6 text-slate-100 shadow-2xl shadow-slate-900/70 backdrop-blur sm:p-8">
        <div class="flex flex-col gap-2 border-b border-slate-800/70 pb-6 text-center sm:text-left">
            <x-back-button :href="route('vendas.index')" />
            <span class="text-xs font-semibold uppercase tracking-[0.4em] text-blue-400/80">Contas em Aberto</span>
            <h2 class="text-3xl font-bold text-white">Vendas a Receber</h2>
            <p class="text-sm text-slate-400">Acompanhe as vendas em aberto e faça o registro de recebimento quando os clientes quitarem.</p>
        </div>

        <form method="GET" action="{{ route('fiados.index') }}"
            class="mt-8 flex flex-col gap-4 rounded-2xl border border-slate-800/70 bg-slate-900/70 p-5 shadow-inner shadow-slate-950/60 sm:flex-row sm:items-center">
            <div class="flex-1">
                <label for="search" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Buscar venda em aberto</label>
                <input type="text" id="search" name="search" value="{{ $search }}"
                    placeholder="Digite cliente, data (dd/mm/aaaa) ou produto..."
                    class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
            </div>
            <button type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-blue-500/60 bg-blue-600/90 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-blue-900/40 transition hover:border-blue-400 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/60 sm:w-auto">
                Buscar
            </button>
        </form>

        @if(session('success'))
            <div class="mt-6 rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-200 shadow-inner shadow-emerald-900/40">
                {{ session('success') }}
            </div>
        @endif

        @if($search !== '')
            <p class="mt-6 text-sm text-slate-400">
                Resultados filtrados por: <span class="font-semibold text-slate-200">"{{ $search }}"</span>
            </p>
        @endif

        <div class="mt-8 rounded-3xl border border-slate-800/70">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[620px] divide-y divide-slate-800/80">
                <thead class="bg-slate-900/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                        <th class="px-6 py-4">Data</th>
                        <th class="px-6 py-4">Cliente</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 bg-slate-950/70">
                    @forelse($vendas as $venda)
                        <tr class="transition hover:bg-slate-900/60">
                            <td class="px-6 py-4 text-sm text-slate-300">
                                {{ \Carbon\Carbon::parse($venda->data)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-100">
                                {{ $venda->client->nome }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-amber-200">
                                {{ 'R$ ' . number_format($venda->total_geral, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap items-center justify-center gap-3 sm:justify-end">
                                    <form action="{{ route('fiados.receber', $venda->id) }}" method="POST"
                                        onsubmit="return confirm('Confirmar recebimento dessa venda?')">
                                        @csrf
                                        <button
                                            class="rounded-xl border border-emerald-500/40 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-emerald-200 transition hover:border-emerald-400 hover:bg-emerald-500/10">
                                            Marcar como paga
                                        </button>
                                    </form>

                                    <a href="{{ route('vendas.edit', $venda->id) }}"
                                        class="rounded-xl border border-blue-500/40 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-blue-200 transition hover:border-blue-400 hover:bg-blue-500/10">
                                        Editar
                                    </a>

                                    <form action="{{ route('vendas.destroy', $venda->id) }}" method="POST"
                                        onsubmit="return confirm('Tem certeza que deseja excluir esta venda?')">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="rounded-xl border border-rose-500/40 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-rose-300 transition hover:border-rose-400 hover:bg-rose-500/10">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm font-semibold uppercase tracking-wide text-slate-500">
                                Nenhuma venda em aberto encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $vendas->links() }}
        </div>
    </div>
@endsection
