@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl rounded-3xl border border-slate-800/70 bg-slate-950/90 p-6 text-slate-100 shadow-2xl shadow-slate-900/70 backdrop-blur sm:p-8">
        <div class="flex flex-col gap-2 border-b border-slate-800/70 pb-6 text-center sm:text-left">
            <x-back-button :href="route('dashboard')" />
            <span class="text-xs font-semibold uppercase tracking-[0.4em] text-blue-400/80">Produtos</span>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-3xl font-bold text-white">Catálogo de Produtos</h2>
                <a href="{{ route('produtos.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-emerald-500/60 bg-emerald-500/20 px-5 py-2.5 text-sm font-semibold uppercase tracking-wide text-emerald-200 shadow-lg shadow-emerald-900/40 transition hover:bg-emerald-500/30">
                    <span class="text-lg">+</span> Novo Produto
                </a>
            </div>
            <p class="text-sm text-slate-400">Gerencie os itens disponíveis para venda, atualize preços e estoque em poucos cliques.</p>
        </div>

        {{-- 🔎 Barra de Pesquisa --}}
        <form method="GET" action="{{ route('produtos.index') }}"
            class="mt-8 flex flex-col gap-4 rounded-2xl border border-slate-800/70 bg-slate-900/70 p-5 shadow-inner shadow-slate-950/60 sm:flex-row sm:items-center">
            <div class="flex-1">
                <label for="search" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Buscar produto</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}"
                    placeholder="Digite o nome do produto..."
                    class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
            </div>
            <button type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-blue-500/60 bg-blue-600/90 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-blue-900/40 transition hover:border-blue-400 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/60 sm:w-auto">
                Buscar
            </button>
        </form>

        <div class="mt-8 rounded-3xl border border-slate-800/70">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] divide-y divide-slate-800/80">
                <thead class="bg-slate-900/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                        <th class="px-6 py-4">Imagem</th>
                        <th class="px-6 py-4">Nome</th>
                        <th class="px-6 py-4">Preço</th>
                        <th class="px-6 py-4">Qtd</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 bg-slate-950/70">
                    @forelse($produtos as $produto)
                        <tr class="transition hover:bg-slate-900/60">
                            <td class="px-6 py-4">
                                @if ($produto->foto)
                                    <img src="{{ asset('storage/' . $produto->foto) }}"
                                        alt="{{ $produto->nome }}"
                                        class="h-14 w-14 rounded-2xl border border-slate-800/60 object-cover shadow-md shadow-slate-950/60">
                                @else
                                    <span class="text-sm italic text-slate-500">Sem imagem</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-100">
                                {{ $produto->nome }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                {{ 'R$ ' . number_format($produto->preco, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-col">
                                    <span class="font-semibold {{ $produto->quantidade < 1 ? 'text-amber-300' : 'text-blue-200' }}">
                                        {{ $produto->quantidade }}
                                    </span>
                                    @if ($produto->quantidade < 1)
                                        <span class="mt-1 inline-flex items-center gap-1 rounded-full border border-amber-500/50 bg-amber-500/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-amber-200">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 3h.01M4.293 17.293a1 1 0 010-1.414l7.071-7.071a1 1 0 011.414 0l7.071 7.071a1 1 0 010 1.414l-7.071 7.071a1 1 0 01-1.414 0l-7.071-7.071z" />
                                            </svg>
                                            Sem estoque
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap items-center justify-center gap-3 text-sm font-semibold uppercase tracking-wide sm:justify-end">
                                    <a href="{{ route('produtos.edit', $produto) }}"
                                        class="rounded-xl border border-blue-500/40 px-3 py-2 text-blue-200 transition hover:border-blue-400 hover:bg-blue-500/10">
                                        Editar
                                    </a>
                                    <form action="{{ route('produtos.destroy', $produto) }}" method="POST"
                                        onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
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
                                Nenhum produto encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        {{-- 📌 Paginação mantendo a busca --}}
        <div class="mt-6">
            {{ $produtos->appends(['search' => request('search')])->links() }}
        </div>
    </div>
@endsection
