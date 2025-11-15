@extends('layouts.app')

@section('content')
    <div class="mx-auto mt-4 max-w-5xl rounded-3xl border border-slate-800/70 bg-slate-950/90 p-6 text-slate-100 shadow-2xl shadow-slate-900/70 backdrop-blur sm:p-8">
        <div class="flex flex-col gap-2 border-b border-slate-800/70 pb-6 text-center sm:text-left">
            <x-back-button :href="route('produtos.index')" />
            <span class="text-xs font-semibold uppercase tracking-[0.4em] text-blue-400/80">Produtos</span>
            <h2 class="text-3xl font-bold text-white">Editar Produto</h2>
            <p class="text-sm text-slate-400">Atualize as informações do item e mantenha o catálogo organizado.</p>
        </div>

        {{-- Imagem do produto em destaque --}}
        <div class="mt-8 flex flex-col items-center justify-center">
            @if ($produto->foto)
                <img src="{{ asset('storage/' . $produto->foto) }}"
                    alt="{{ $produto->nome }}"
                    class="h-64 w-64 rounded-3xl border border-slate-800/60 object-cover shadow-2xl shadow-slate-950/70">
            @else
                <div class="flex h-64 w-64 items-center justify-center rounded-3xl border border-dashed border-slate-800/70 bg-slate-900/70 text-sm font-medium uppercase tracking-wide text-slate-500">
                    Sem imagem
                </div>
            @endif
        </div>

        <form action="{{ route('produtos.update', $produto) }}" method="POST" enctype="multipart/form-data" class="mt-8">
            @method('PUT')
            @include('produtos._form', ['produto' => $produto])
        
        </form>
    </div>
@endsection
