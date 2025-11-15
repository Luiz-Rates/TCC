@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl rounded-3xl border border-slate-800/70 bg-slate-950/90 p-6 text-slate-100 shadow-2xl shadow-slate-900/70 backdrop-blur sm:p-8">
        <div class="flex flex-col gap-2 border-b border-slate-800/70 pb-6 text-center sm:text-left">
            <span class="text-xs font-semibold uppercase tracking-[0.4em] text-blue-400/80">Dashboard</span>
            <h2 class="text-3xl font-bold text-white">Painel de Vendas</h2>
            <p class="text-sm text-slate-400">Escolha um módulo para gerenciar produtos, clientes, vendas e relatórios.</p>
        </div>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('produtos.index') }}"
                class="group rounded-3xl border border-blue-500/40 bg-blue-600/10 p-6 text-white shadow-lg shadow-blue-900/30 transition hover:border-blue-400 hover:bg-blue-600/20">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-500/40 text-2xl">
                    📦
                </div>
                <h3 class="mt-4 text-xl font-semibold">Produtos</h3>
                <p class="mt-2 text-sm text-blue-100/80">Cadastre novos itens, gerencie preços e acompanhe o estoque.</p>
            </a>

            <a href="{{ route('clientes.index') }}"
                class="group rounded-3xl border border-emerald-500/40 bg-emerald-600/10 p-6 text-white shadow-lg shadow-emerald-900/30 transition hover:border-emerald-400 hover:bg-emerald-600/20">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/40 text-2xl">
                    👤
                </div>
                <h3 class="mt-4 text-xl font-semibold">Clientes</h3>
                <p class="mt-2 text-sm text-emerald-100/80">Mantenha os dados dos clientes atualizados e visualize contas em aberto.</p>
            </a>

            <a href="{{ route('vendas.create') }}"
                class="group rounded-3xl border border-amber-500/40 bg-amber-500/10 p-6 text-white shadow-lg shadow-amber-900/30 transition hover:border-amber-400 hover:bg-amber-500/20">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500/40 text-2xl">
                    💰
                </div>
                <h3 class="mt-4 text-xl font-semibold">Nova Venda</h3>
                <p class="mt-2 text-sm text-amber-100/80">Registre rapidamente uma nova venda e gere o total automaticamente.</p>
            </a>

            <a href="{{ route('vendas.index') }}"
                class="group rounded-3xl border border-purple-500/40 bg-purple-500/10 p-6 text-white shadow-lg shadow-purple-900/30 transition hover:border-purple-400 hover:bg-purple-500/20">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-500/40 text-2xl">
                    📄
                </div>
                <h3 class="mt-4 text-xl font-semibold">Todas as Vendas</h3>
                <p class="mt-2 text-sm text-purple-100/80">Acompanhe o histórico completo de vendas e filtre por status.</p>
            </a>

            <a href="{{ route('fiados.index') }}"
                class="group rounded-3xl border border-rose-500/40 bg-rose-500/10 p-6 text-white shadow-lg shadow-rose-900/30 transition hover:border-rose-400 hover:bg-rose-500/20">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-500/40 text-2xl">
                    🔎
                </div>
                <h3 class="mt-4 text-xl font-semibold">Contas em Aberto</h3>
                <p class="mt-2 text-sm text-rose-100/80">Controle as vendas pendentes e registre o recebimento com um clique.</p>
            </a>

            <a href="{{ route('relatorio.vendas') }}"
                class="group rounded-3xl border border-slate-500/40 bg-slate-500/10 p-6 text-white shadow-lg shadow-slate-900/30 transition hover:border-slate-400 hover:bg-slate-500/20">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-500/40 text-2xl">
                    📊
                </div>
                <h3 class="mt-4 text-xl font-semibold">Relatório de Vendas</h3>
                <p class="mt-2 text-sm text-slate-100/80">Visualize métricas detalhadas e gráficos para acompanhar resultados.</p>
            </a>
        </div>
    </div>
@endsection
