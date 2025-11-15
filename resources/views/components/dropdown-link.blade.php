<a {{ $attributes->merge(['class' => 'block w-full rounded-xl px-4 py-2 text-start text-sm font-medium text-slate-200 transition hover:bg-slate-900/80 hover:text-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500/50']) }}>
    {{ $slot }}
</a>
