@csrf

<div class="space-y-5">
    <div class="space-y-2">
        <label for="nome" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nome</label>
        <input type="text" name="nome" id="nome" value="{{ old('nome', $cliente->nome ?? '') }}"
            class="w-full rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40"
            required>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div class="space-y-2">
            <label for="telefone" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Telefone</label>
            <input type="text" name="telefone" id="telefone" value="{{ old('telefone', $cliente->telefone ?? '') }}"
                class="w-full rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
        </div>

        <div class="space-y-2">
            <label for="email" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $cliente->email ?? '') }}"
                class="w-full rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">
        </div>
    </div>

    <div class="space-y-2">
        <label for="endereco" class="text-xs font-semibold uppercase tracking-wide text-slate-400">Endereço</label>
        <textarea name="endereco" id="endereco" rows="3"
            class="w-full rounded-2xl border border-slate-800 bg-slate-900/80 px-4 py-2.5 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40">{{ old('endereco', $cliente->endereco ?? '') }}</textarea>
    </div>

    <div class="flex justify-center sm:justify-end">
        <button type="submit"
            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-blue-500/60 bg-blue-600/90 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-blue-900/40 transition hover:border-blue-400 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/60 sm:w-auto">
            Salvar
        </button>
    </div>
</div>
