@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-blue-500/70 bg-blue-600/20 px-4 py-2 text-base font-semibold text-blue-100 transition focus:outline-none focus:border-blue-400'
            : 'block w-full border-l-4 border-transparent px-4 py-2 text-base font-semibold text-slate-400 transition hover:border-blue-500/50 hover:bg-slate-900/70 hover:text-blue-100 focus:outline-none focus:border-blue-400';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
