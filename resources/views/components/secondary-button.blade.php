<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-700 bg-slate-900/80 px-5 py-2.5 text-sm font-semibold uppercase tracking-wide text-slate-200 transition hover:border-blue-400 hover:text-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500/50 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
