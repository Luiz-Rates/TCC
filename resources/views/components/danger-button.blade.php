<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-2xl border border-rose-500/60 bg-rose-600/90 px-5 py-2.5 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-rose-900/40 transition hover:border-rose-400 hover:bg-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-500/60']) }}>
    {{ $slot }}
</button>
