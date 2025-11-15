@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl rounded-3xl border border-slate-800/70 bg-slate-950/90 p-6 text-slate-100 shadow-2xl shadow-slate-900/70 backdrop-blur sm:p-8">
        <div class="flex flex-col gap-2 border-b border-slate-800/70 pb-6 text-center sm:text-left">
            <x-back-button :href="route('dashboard')" />
            <span class="text-xs font-semibold uppercase tracking-[0.4em] text-blue-400/80">Vendas</span>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-3xl font-bold text-white">Histórico de Vendas</h2>
                <a href="{{ route('vendas.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-blue-500/60 bg-blue-600/90 px-5 py-2.5 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-blue-900/40 transition hover:border-blue-400 hover:bg-blue-500">
                    <span class="text-lg">+</span> Nova Venda
                </a>
            </div>
            <p class="text-sm text-slate-400">Filtre as vendas por cliente, data e status para localizar rapidamente o que precisa.</p>
        </div>

        {{-- 🔍 Filtros de pesquisa --}}
        <form method="GET" action="{{ route('vendas.index') }}"
            class="mt-8 grid gap-4 rounded-2xl border border-slate-800/70 bg-slate-900/70 p-5 shadow-inner shadow-slate-950/60 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <label for="cliente" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Cliente</label>
                <input type="text" name="cliente" id="cliente" value="{{ request('cliente') }}"
                    placeholder="Buscar por cliente..."
                    class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
            </div>

            <div>
                <label for="data" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Data</label>
                <input type="date" name="data" id="data" value="{{ request('data') }}"
                    class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
            </div>

            <div>
                <label for="status" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Status</label>
                <select name="status" id="status"
                    class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
                    <option value="">Todos</option>
                    <option value="pago" {{ request('status') == 'pago' ? 'selected' : '' }}>Pago</option>
                    <option value="fiado" {{ request('status') == 'fiado' ? 'selected' : '' }}>Em aberto</option>
                </select>
            </div>

            <div class="flex justify-end lg:col-span-4">
                <button type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-blue-500/60 bg-blue-600/90 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-blue-900/40 transition hover:border-blue-400 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/60 sm:w-auto">
                    Buscar
                </button>
            </div>
        </form>

        {{-- 🧾 Tabela de vendas --}}
        <div class="mt-8 rounded-3xl border border-slate-800/70">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] divide-y divide-slate-800/80">
                <thead class="bg-slate-900/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                        <th class="px-6 py-4">Data</th>
                        <th class="px-6 py-4">Cliente</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 bg-slate-950/70">
                    @forelse ($vendas as $venda)
                        <tr class="transition hover:bg-slate-900/60">
                            <td class="px-6 py-4 text-sm text-slate-300">
                                {{ $venda->data ? date('d/m/Y', strtotime($venda->data)) : '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-100">
                                {{ $venda->client->nome ?? 'Cliente não encontrado' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide
                                    {{ $venda->status === 'fiado'
                                        ? 'border-rose-500/50 bg-rose-500/10 text-rose-200'
                                        : 'border-emerald-500/50 bg-emerald-500/10 text-emerald-200' }}">
                                    {{ $venda->status === 'fiado' ? 'Em aberto' : 'Pago' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-emerald-300">
                                {{ 'R$ ' . number_format($venda->total_geral, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap items-center justify-center gap-3 text-sm font-semibold uppercase tracking-wide sm:justify-end">
                                    <a href="{{ route('vendas.show', $venda->id) }}"
                                        class="rounded-xl border border-blue-500/40 px-3 py-2 text-blue-200 transition hover:border-blue-400 hover:bg-blue-500/10">
                                        Ver
                                    </a>
                                    <a href="{{ route('vendas.edit', $venda->id) }}"
                                        class="rounded-xl border border-amber-500/40 px-3 py-2 text-amber-200 transition hover:border-amber-400 hover:bg-amber-500/10">
                                        Editar
                                    </a>
                                    <form action="{{ route('vendas.destroy', $venda->id) }}" method="POST"
                                        onsubmit="return confirm('Tem certeza que deseja excluir esta venda?')">
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
                            <td colspan="5" class="px-6 py-8 text-center text-sm font-semibold uppercase tracking-wide text-slate-500">
                                Nenhuma venda encontrada.
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
