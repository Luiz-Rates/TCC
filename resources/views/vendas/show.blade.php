@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl rounded-3xl border border-slate-800/70 bg-slate-950/90 p-6 text-slate-100 shadow-2xl shadow-slate-900/70 backdrop-blur sm:p-8">
        <div class="flex flex-col gap-2 border-b border-slate-800/70 pb-6 text-center sm:text-left">
            <x-back-button :href="route('vendas.index')" />
            <span class="text-xs font-semibold uppercase tracking-[0.4em] text-blue-400/80">Vendas</span>
            <h2 class="text-3xl font-bold text-white">Detalhes da Venda</h2>
            <p class="text-sm text-slate-400">Visualize os itens vendidos, status do pagamento e o valor total desta venda.</p>
        </div>

        <div class="mt-6 flex flex-col items-center justify-between gap-3 sm:flex-row sm:text-left">
            <a href="{{ route('vendas.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-blue-300 transition hover:text-blue-100">
                ← Voltar para lista
            </a>
            <span class="inline-flex items-center gap-2 rounded-2xl border border-slate-800/60 bg-slate-900/70 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                Código: <span class="text-slate-100">#{{ str_pad($venda->id, 4, '0', STR_PAD_LEFT) }}</span>
            </span>
        </div>

        <div class="mt-8 grid gap-5 sm:grid-cols-2">
            <div class="space-y-3 rounded-2xl border border-slate-800/60 bg-slate-900/70 p-5 shadow-inner shadow-slate-950/60">
                <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Cliente</span>
                <p class="text-lg font-semibold text-white">{{ $venda->client->nome }}</p>
                <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Data</span>
                <p class="text-sm text-slate-300">{{ \Carbon\Carbon::parse($venda->data)->format('d/m/Y') }}</p>
            </div>
            <div class="space-y-4 rounded-2xl border border-slate-800/60 bg-slate-900/70 p-5 shadow-inner shadow-slate-950/60">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Status</span>
                    <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide
                        {{ $venda->status === 'fiado'
                            ? 'border-rose-500/50 bg-rose-500/10 text-rose-200'
                            : 'border-emerald-500/50 bg-emerald-500/10 text-emerald-200' }}">
                        {{ $venda->status === 'fiado' ? 'Em aberto' : 'Pago' }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total</span>
                    <span class="text-2xl font-bold text-emerald-300">
                        {{ 'R$ ' . number_format($venda->total_geral, 2, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <h3 class="mt-10 text-xl font-semibold text-white">Produtos</h3>
        <p class="text-sm text-slate-400">Resumo dos itens que compõem a venda.</p>

        <div class="mt-4 rounded-3xl border border-slate-800/70">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px] divide-y divide-slate-800/80">
                <thead class="bg-slate-900/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                        <th class="px-6 py-4">Produto</th>
                        <th class="px-6 py-4">Quantidade</th>
                        <th class="px-6 py-4">Preço Unitário</th>
                        <th class="px-6 py-4">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 bg-slate-950/70">
                    @foreach($venda->items as $item)
                        <tr class="transition hover:bg-slate-900/60">
                            <td class="px-6 py-4 text-sm font-semibold text-slate-100">
                                {{ $item->product->nome }}
                            </td>
                            <td class="px-6 py-4 text-sm text-blue-200">
                                {{ $item->quantidade }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                {{ 'R$ ' . number_format($item->preco_unitario, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-emerald-300">
                                {{ 'R$ ' . number_format($item->subtotal, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
@endsection
