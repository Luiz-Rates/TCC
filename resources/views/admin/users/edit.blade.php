@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl rounded-3xl border border-slate-800/70 bg-slate-950/90 p-6 text-slate-100 shadow-2xl shadow-slate-900/70 backdrop-blur sm:p-8">
        <div class="flex flex-col gap-2 border-b border-slate-800/70 pb-6 text-center sm:text-left">
            <x-back-button :href="route('admin.users.index')" />
            <span class="text-xs font-semibold uppercase tracking-[0.4em] text-blue-400/80">Administração</span>
            <h2 class="text-3xl font-bold text-white">Editar usuário</h2>
            <p class="text-sm text-slate-400">
                Atualize as informações de acesso e as permissões de {{ $user->name }}.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-8 space-y-8">
            @csrf
            @method('PUT')

            @include('admin.users.partials.form', ['user' => $user])

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-700 bg-slate-900/70 px-5 py-3 text-sm font-semibold uppercase tracking-wide text-slate-300 transition hover:border-slate-500 hover:text-slate-100">
                    Cancelar
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-blue-500/60 bg-blue-600/80 px-6 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-blue-900/40 transition hover:bg-blue-500">
                    Atualizar dados
                </button>
            </div>
        </form>
    </div>
@endsection
