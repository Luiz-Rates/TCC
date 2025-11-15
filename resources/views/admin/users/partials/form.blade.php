@php
    /** @var \App\Models\User|null $user */
    $isEdit = isset($user);
@endphp

<div class="grid gap-6 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="name" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Nome completo</label>
        <input
            type="text"
            name="name"
            id="name"
            value="{{ old('name', $user->name ?? '') }}"
            required
            class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40"
            placeholder="Ex: Maria Oliveira"
        />
    </div>

    <div class="sm:col-span-2">
        <label for="email" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">E-mail</label>
        <input
            type="email"
            name="email"
            id="email"
            value="{{ old('email', $user->email ?? '') }}"
            required
            class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40"
            placeholder="cliente@exemplo.com"
        />
    </div>

    <div class="sm:col-span-2">
        <label for="password" class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">
            <span>Senha</span>
            @if ($isEdit)
                <span class="text-[0.65rem] font-normal uppercase tracking-[0.3em] text-slate-500">Opcional</span>
            @endif
        </label>
        <input
            type="password"
            name="password"
            id="password"
            @unless($isEdit) required @endunless
            class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40"
            placeholder="Digite uma senha segura"
        />
        @if ($isEdit)
            <p class="mt-2 text-xs text-slate-500">Preencha apenas se quiser definir uma nova senha para o usuário.</p>
        @endif
    </div>

    <div class="sm:col-span-2">
        <label for="password_confirmation" class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Confirmar senha</label>
        <input
            type="password"
            name="password_confirmation"
            id="password_confirmation"
            @unless($isEdit) required @endunless
            class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-3 text-slate-100 shadow-inner shadow-slate-950/60 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40"
            placeholder="Repita a senha digitada"
        />
    </div>

    <div class="sm:col-span-2">
        <label class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Permissões</label>
        <div class="mt-3 flex items-center justify-between rounded-2xl border border-slate-800 bg-slate-950/70 px-5 py-4 shadow-inner shadow-slate-950/60">
            <div>
                <p class="text-sm font-semibold text-slate-100">Administrador</p>
                <p class="text-xs text-slate-500">Pode acessar todas as funcionalidades e gerenciar outros usuários.</p>
            </div>
            <label class="flex items-center gap-3">
                <input
                    type="checkbox"
                    name="is_admin"
                    class="h-5 w-5 rounded border-slate-700 bg-slate-900 text-blue-500 focus:ring-blue-500"
                    value="1"
                    {{ old('is_admin', $user->is_admin ?? false) ? 'checked' : '' }}
                />
                <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Conceder acesso admin</span>
            </label>
        </div>
    </div>
</div>
