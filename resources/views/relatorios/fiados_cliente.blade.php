@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl rounded-3xl border border-slate-800/70 bg-slate-950/90 p-6 text-slate-100 shadow-2xl shadow-slate-900/70 backdrop-blur sm:p-8">
        <div class="flex flex-col gap-2 border-b border-slate-800/70 pb-6 text-center sm:text-left">
            <x-back-button :href="route('relatorio.fiados')" />
            <span class="text-xs font-semibold uppercase tracking-[0.4em] text-blue-400/80">Relatórios</span>
            <h2 class="text-3xl font-bold text-white">Clientes com Contas em Aberto</h2>
            <p class="text-sm text-slate-400">Veja rapidamente quem possui valores pendentes e acesse os detalhes de cada cliente.</p>
        </div>

        <div class="mt-8 rounded-3xl border border-slate-800/70">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[560px] divide-y divide-slate-800/80">
                <thead class="bg-slate-900/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                        <th class="px-6 py-4">Cliente</th>
                        <th class="px-6 py-4">Total Devido</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 bg-slate-950/70">
                    @forelse($clientes as $cliente)
                        <tr class="transition hover:bg-slate-900/60">
                            <td class="px-6 py-4 text-sm font-semibold text-slate-100">
                                {{ $cliente->nome }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-amber-200">
                                {{ 'R$ ' . number_format($cliente->sales->sum('total_geral'), 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center sm:justify-end">
                                    <a href="{{ route('relatorio.fiados.cliente', $cliente) }}"
                                        class="rounded-xl border border-blue-500/40 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-blue-200 transition hover:border-blue-400 hover:bg-blue-500/10">
                                        Ver detalhes
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-sm font-semibold uppercase tracking-wide text-slate-500">
                                Nenhum cliente com dívida.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
@endsection
