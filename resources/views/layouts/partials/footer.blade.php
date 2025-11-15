<footer class="mt-auto border-t border-slate-800/60 bg-slate-900/70 backdrop-blur">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-6 text-center text-slate-400 sm:flex-row sm:items-center sm:justify-between sm:text-left sm:px-6 lg:px-8">
        <div class="text-sm">
            &copy; {{ now()->year }} {{ config('app.name', 'NexKeep') }}. Todos os direitos reservados.
        </div>
        <div class="flex flex-wrap items-center justify-center gap-4 text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-500">
            <a href="mailto:suporte@nexkeep.com" class="transition hover:text-blue-300">Suporte</a>
            <span class="hidden h-1 w-1 rounded-full bg-slate-700 sm:inline-flex"></span>
            <span class="text-slate-500">Feito com Laravel &amp; Tailwind</span>
        </div>
    </div>
</footer>
