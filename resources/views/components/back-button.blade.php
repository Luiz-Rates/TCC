@props([
    'href' => null,
    'label' => 'Voltar'
])

@php
    $target = $href ?? url()->previous();
@endphp

<a href="{{ $target }}"
    class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/70 bg-slate-900/70 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-200 transition hover:border-blue-400 hover:bg-blue-500/10">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    {{ $label }}
</a>
