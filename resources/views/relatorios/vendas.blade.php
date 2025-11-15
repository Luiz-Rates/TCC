@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl rounded-3xl border border-slate-800/70 bg-slate-950/90 p-6 text-slate-100 shadow-2xl shadow-slate-900/70 backdrop-blur sm:p-8">
        <div class="flex flex-col gap-2 border-b border-slate-800/70 pb-6 text-center md:text-left">
            <x-back-button :href="route('dashboard')" />
            <span class="text-xs font-semibold uppercase tracking-[0.4em] text-blue-400/80">Relatórios</span>
            <h2 class="text-3xl font-bold text-white text-center md:text-left">Relatório de Vendas</h2>
            <p class="text-sm text-slate-400 text-center md:text-left">Selecione o mês inicial e final para acompanhar os principais indicadores financeiros do seu negócio.</p>
        </div>

        {{-- 🔎 Filtros de Período --}}
        <form method="GET" class="mt-8 grid gap-4 rounded-2xl border border-slate-800/70 bg-slate-900/70 p-5 shadow-inner shadow-slate-950/60 md:grid-cols-4">
            <div class="md:col-span-2">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400">Mês inicial</label>
                <input type="month" name="mes_inicio" value="{{ $mesInicio }}" min="{{ $limitePassadoMes }}"
                    class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
            </div>

            <div class="md:col-span-2">
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-400">Mês final</label>
                <input type="month" name="mes_fim" value="{{ $mesFim }}" min="{{ $limitePassadoMes }}"
                    class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
            </div>

            <div class="flex justify-center md:col-span-4 md:items-end md:justify-end">
                <button type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-blue-500/60 bg-blue-600/90 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-blue-900/40 transition hover:border-blue-400 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/60 md:w-auto">
                    📊 Gerar Relatório
                </button>
            </div>
        </form>

        @if($erroPeriodo)
            <div class="mt-4 rounded-2xl border border-rose-500/50 bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-100">
                {{ $erroPeriodo }}
            </div>
        @endif

        @if(($mesInicio || $mesFim) && ! $erroPeriodo)
            {{-- 🧾 Resumo do Período --}}
            <div class="mt-8 grid gap-5 md:grid-cols-3">
                <div class="rounded-2xl border border-slate-800/70 bg-slate-900/70 p-5 shadow-inner shadow-slate-950/60">
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Período</span>
                    <p class="mt-2 text-lg font-semibold text-white">
                        {{ $dataInicio ? date('m/Y', strtotime($dataInicio)) : 'Início' }}
                        <span class="text-sm font-normal text-slate-500">até</span>
                        {{ $dataFim ? date('m/Y', strtotime($dataFim)) : 'Atual' }}
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-800/70 bg-slate-900/70 p-5 shadow-inner shadow-slate-950/60">
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total de Vendas</span>
                    <p class="mt-2 text-3xl font-bold text-blue-200">{{ $quantidade }}</p>
                </div>

                <div class="rounded-2xl border border-slate-800/70 bg-slate-900/70 p-5 shadow-inner shadow-slate-950/60">
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">Valor Total Vendido</span>
                    <p class="mt-2 text-3xl font-bold text-emerald-300">
                        {{ 'R$ ' . number_format($total, 2, ',', '.') }}
                    </p>
                    @if(isset($media))
                        <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Média diária:
                            <span class="text-sm font-bold text-emerald-200">
                                {{ 'R$ ' . number_format($media, 2, ',', '.') }}
                            </span>
                        </p>
                    @endif
                </div>
            </div>

            {{-- 📊 Gráfico de Total de Vendas por Mês do Ano --}}
            <div class="mt-8 rounded-3xl border border-slate-800/70 bg-slate-900/70 p-6 shadow-inner shadow-slate-950/60">
                <h3 class="text-center text-lg font-semibold text-blue-200">📅 Total de Vendas por Mês</h3>
                <canvas id="chartVendas" class="mt-6"></canvas>
            </div>

            {{-- 📦 Tabela Detalhada --}}
            <div class="mt-8 rounded-3xl border border-slate-800/70">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] divide-y divide-slate-800/80">
                    <thead class="bg-slate-900/80">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <th class="px-6 py-4">Data</th>
                            <th class="px-6 py-4">Cliente</th>
                            <th class="px-6 py-4">Produto</th>
                            <th class="px-6 py-4">Qtd</th>
                            <th class="px-6 py-4">Valor Unit.</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Pagamento</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 bg-slate-950/70">
                        @forelse($vendas as $venda)
                            <tr class="transition hover:bg-slate-900/60">
                                <td class="px-6 py-4 text-sm text-slate-300">
                                    {{ date('d/m/Y', strtotime($venda->data)) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-100">
                                    {{ $venda->cliente->nome ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-300">
                                    {{ $venda->produto->nome ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-blue-200">
                                    {{ $venda->quantidade }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-300">
                                    {{ 'R$ ' . number_format($venda->preco_unitario, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-emerald-300">
                                    {{ 'R$ ' . number_format($venda->total, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($venda->fiado)
                                        <span class="rounded-full border border-amber-500/60 bg-amber-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-amber-200">Em aberto</span>
                                    @else
                                        <span class="rounded-full border border-emerald-500/60 bg-emerald-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-200">Pago</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm font-semibold uppercase tracking-wide text-slate-500">
                                    Nenhuma venda encontrada nesse período.
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

            {{-- 📊 Totais gerais no final --}}
            <div class="mt-8 grid gap-5 md:grid-cols-2">
                <div class="rounded-2xl border border-emerald-500/40 bg-emerald-500/10 p-5 text-center shadow-inner shadow-emerald-900/40">
                    <h3 class="text-lg font-semibold text-emerald-200">Total de Vendas Pagas</h3>
                    <p class="mt-2 text-2xl font-bold text-emerald-100">
                        {{ 'R$ ' . number_format($totalPagas ?? 0, 2, ',', '.') }}
                    </p>
                </div>
                <div class="rounded-2xl border border-amber-500/40 bg-amber-500/10 p-5 text-center shadow-inner shadow-amber-900/40">
                    <h3 class="text-lg font-semibold text-amber-200">Total em Aberto</h3>
                    <p class="mt-2 text-2xl font-bold text-amber-100">
                        {{ 'R$ ' . number_format($totalEmAberto ?? 0, 2, ',', '.') }}
                    </p>
                </div>
            </div>
        @endif
    </div>

{{-- 📊 Script do Gráfico --}}
@if(isset($dadosGrafico))
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chartVendas').getContext('2d');
        const chartVendas = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($dadosGrafico)) !!},
                datasets: [{
                    label: 'Total por Mês (R$)',
                    data: {!! json_encode(array_values($dadosGrafico)) !!},
                    backgroundColor: 'rgba(37, 99, 235, 0.6)',
                    borderColor: 'rgba(37, 99, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: { color: '#ccc' },
                        grid: { color: 'rgba(75,75,75,0.2)' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#ccc' },
                        grid: { color: 'rgba(75,75,75,0.2)' }
                    }
                },
                plugins: {
                    legend: { labels: { color: '#fff' } }
                }
            }
        });
    </script>
@endif
@endsection
