@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl rounded-3xl border border-slate-800/70 bg-slate-950/90 p-6 text-slate-100 shadow-2xl shadow-slate-900/70 backdrop-blur sm:p-8">
        <div class="flex flex-col gap-2 border-b border-slate-800/70 pb-6 text-center sm:text-left">
            <x-back-button :href="route('clientes.index')" />
            <span class="text-xs font-semibold uppercase tracking-[0.4em] text-blue-400/80">Cliente</span>
            <h2 class="text-3xl font-bold text-white">{{ $cliente->nome }}</h2>
            <p class="text-sm text-slate-400">Informações de contato e histórico de vendas registradas.</p>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-800/70 bg-slate-900/80 p-5 shadow-inner shadow-slate-950/60">
                <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Telefone</span>
                <p class="mt-2 text-lg font-semibold text-white">{{ $cliente->telefone ?: 'Não informado' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-800/70 bg-slate-900/80 p-5 shadow-inner shadow-slate-950/60">
                <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Email</span>
                <p class="mt-2 text-lg font-semibold text-white break-words">{{ $cliente->email ?: 'Não informado' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-800/70 bg-slate-900/80 p-5 shadow-inner shadow-slate-950/60">
                <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Endereço</span>
                <p class="mt-2 text-lg font-semibold text-white">{{ $cliente->endereco ?: 'Não informado' }}</p>
            </div>
        </div>

        <div class="mt-10 flex flex-col items-center justify-between gap-3 text-center sm:flex-row sm:items-center sm:text-left">
            <h3 class="text-2xl font-semibold text-white">Vendas realizadas</h3>
            <span class="rounded-2xl border border-blue-500/50 bg-blue-500/10 px-4 py-1 text-sm font-semibold uppercase tracking-wide text-blue-200">
                {{ $cliente->sales->count() }} vendas
            </span>
        </div>
        <p class="text-sm text-slate-400 text-center sm:text-left">Cada registro mostra o status de pagamento e os itens vendidos.</p>

        <div class="mt-6 rounded-3xl border border-slate-800/70">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] divide-y divide-slate-800/80">
                <thead class="bg-slate-900/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                        <th class="px-6 py-4">Data</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Itens</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 bg-slate-950/70">
                    @forelse ($cliente->sales as $venda)
                        <tr class="align-top transition hover:bg-slate-900/60">
                            <td class="px-6 py-4 text-sm text-slate-300">
                                {{ $venda->data ? \Carbon\Carbon::parse($venda->data)->format('d/m/Y') : '—' }}
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
                                <div class="space-y-3">
                                    <div class="space-y-2">
                                        @foreach ($venda->items->take(4) as $item)
                                            <div class="flex items-start justify-between rounded-2xl border border-slate-800/60 bg-slate-900/70 px-4 py-2 text-xs text-slate-300">
                                                <div>
                                                    <p class="font-semibold text-slate-100" title="{{ $item->product->nome ?? 'Produto removido' }}">{{ Str::limit($item->product->nome ?? 'Produto removido', 36) }}</p>
                                                    <p class="text-[11px] text-slate-400">Qtd: {{ $item->quantidade }}</p>
                                                </div>
                                                <span class="font-semibold text-emerald-200">
                                                    {{ 'R$ ' . number_format($item->subtotal, 2, ',', '.') }}
                                                </span>
                                            </div>
                                        @endforeach

                                        @if ($venda->items->count() > 4)
                                            <div class="rounded-2xl border border-slate-800/60 bg-slate-900/50 px-4 py-2 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-400">
                                                + {{ $venda->items->count() - 4 }} item(s) restantes
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap items-center justify-center gap-3 sm:justify-end">
                                        @if ($venda->status === 'fiado')
                                            <form action="{{ route('fiados.receber', $venda->id) }}" method="POST"
                                                onsubmit="return confirm('Confirmar recebimento dessa venda?')">
                                                @csrf
                                                <input type="hidden" name="redirect_to" value="{{ route('clientes.show', $cliente) }}">
                                                <button
                                                    class="rounded-xl border border-emerald-500/40 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-emerald-200 transition hover:border-emerald-400 hover:bg-emerald-500/10">
                                                    Marcar como paga
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('vendas.edit', ['venda' => $venda->id, 'redirect_to' => route('clientes.show', $cliente)]) }}"
                                            class="rounded-xl border border-blue-500/40 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-blue-200 transition hover:border-blue-400 hover:bg-blue-500/10">
                                            Editar
                                        </a>

                                        <form action="{{ route('vendas.destroy', $venda->id) }}" method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta venda?')">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="redirect_to" value="{{ route('clientes.show', $cliente) }}">
                                            <button
                                                class="rounded-xl border border-rose-500/40 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-rose-300 transition hover:border-rose-400 hover:bg-rose-500/10">
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm font-semibold uppercase tracking-wide text-slate-500">
                                Nenhuma venda registrada para este cliente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
@endsection
