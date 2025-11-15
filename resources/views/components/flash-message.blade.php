@props([
    'type' => 'info',
    'message',
    'items' => [],
    'autoClose' => true,
    'timeout' => 5000,
])

@php
    $styles = [
        'success' => [
            'wrapper' => 'border-emerald-500/40 bg-emerald-500/10 text-emerald-100',
            'icon' => '✅',
        ],
        'error' => [
            'wrapper' => 'border-rose-500/40 bg-rose-500/10 text-rose-100',
            'icon' => '⚠️',
        ],
        'warning' => [
            'wrapper' => 'border-amber-500/40 bg-amber-500/10 text-amber-100',
            'icon' => '⚠️',
        ],
        'info' => [
            'wrapper' => 'border-blue-500/40 bg-blue-500/10 text-blue-100',
            'icon' => 'ℹ️',
        ],
    ];

    $style = $styles[$type] ?? $styles['info'];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition.opacity
    @if ($autoClose)
        x-init="setTimeout(() => show = false, {{ (int) $timeout }})"
    @endif
    role="alert"
    class="rounded-2xl border px-4 py-3 shadow-xl shadow-black/20 sm:px-5 sm:py-4 {{ $style['wrapper'] }}"
>
    <div class="flex items-start gap-3">
        <span class="text-lg sm:text-xl">{{ $style['icon'] }}</span>

        <div class="flex-1 text-sm leading-relaxed">
            <p class="font-semibold">{{ $message }}</p>

            @if (!empty($items))
                <ul class="mt-2 space-y-1 text-xs sm:text-sm">
                    @foreach ($items as $item)
                        <li class="flex items-start gap-2">
                            <span class="mt-1 h-1.5 w-1.5 flex-none rounded-full bg-current"></span>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <button
            type="button"
            @click="show = false"
            class="rounded-full border border-white/20 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-white/70 transition hover:border-white/40 hover:text-white sm:text-xs"
        >
            fechar
        </button>
    </div>
</div>
