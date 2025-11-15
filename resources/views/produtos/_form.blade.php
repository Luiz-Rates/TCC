@csrf

<div class="space-y-5">
    <div class="space-y-2">
        <label for="nome" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nome</label>
        <input type="text" name="nome" id="nome" value="{{ old('nome', $produto->nome ?? '') }}"
            class="w-full rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40"
            required>
    </div>

    <div class="space-y-2">
        <label for="descricao" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Descrição</label>
        <textarea name="descricao" id="descricao" rows="3"
            class="w-full rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">{{ old('descricao', $produto->descricao ?? '') }}</textarea>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div class="space-y-2">
            <label for="preco" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Preço</label>
            <input type="number" step="0.01" min="0" name="preco" id="preco" value="{{ old('preco', $produto->preco ?? '') }}"
                class="w-full rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40"
                required>
        </div>

        <div class="space-y-2">
            <label for="quantidade" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Quantidade</label>
            <input type="number" min="0" step="1" name="quantidade" id="quantidade" value="{{ old('quantidade', $produto->quantidade ?? '') }}"
                class="w-full rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40"
                required>
        </div>
    </div>

    <div class="space-y-2">
        <label for="foto" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Foto</label>
        <input type="file" name="foto" id="foto"
            class="w-full rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-2.5 text-slate-100 file:me-4 file:rounded-xl file:border-0 file:bg-blue-600/80 file:px-4 file:py-2 file:text-sm file:font-semibold file:uppercase file:tracking-wide file:text-white hover:file:bg-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
        @if (!empty($produto->foto))
            <div class="mt-3 inline-flex items-center gap-3 rounded-2xl border border-slate-800/60 bg-slate-900/70 p-3">
                <img src="{{ asset('storage/' . $produto->foto) }}" width="96" class="h-24 w-24 rounded-2xl object-cover shadow-md shadow-slate-950/60" alt="{{ $produto->nome }}">
                <span class="text-sm text-slate-400">Imagem atual</span>
            </div>
        @endif
    </div>

    <div class="flex justify-center sm:justify-end">
        <button type="submit"
            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-blue-500/60 bg-blue-600/90 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-blue-900/40 transition hover:border-blue-400 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/60 sm:w-auto">
            Salvar
        </button>
    </div>
</div>
