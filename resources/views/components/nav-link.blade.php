@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2 rounded-2xl border border-blue-500/60 bg-blue-600/20 px-4 py-2 text-sm font-semibold text-blue-100 shadow-sm shadow-blue-900/40 transition'
            : 'inline-flex items-center gap-2 rounded-2xl border border-transparent px-4 py-2 text-sm font-semibold text-slate-400 transition hover:border-blue-500/40 hover:text-blue-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
