@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl rounded-3xl border border-slate-800/70 bg-slate-950/90 p-6 text-slate-100 shadow-2xl shadow-slate-900/70 backdrop-blur sm:p-8">
        <div class="flex flex-col gap-2 border-b border-slate-800/70 pb-6 text-center sm:text-left">
            <x-back-button :href="route('clientes.index')" />
            <span class="text-xs font-semibold uppercase tracking-[0.4em] text-blue-400/80">Clientes</span>
            <h2 class="text-3xl font-bold text-white">Editar Cliente</h2>
            <p class="text-sm text-slate-400">Mantenha os dados dos clientes atualizados para agilizar seu atendimento.</p>
        </div>

        <form action="{{ route('clientes.update', $cliente) }}" method="POST" class="mt-8">
            @method('PUT')
            @include('clientes._form', ['cliente' => $cliente])
        </form>
    </div>
@endsection
