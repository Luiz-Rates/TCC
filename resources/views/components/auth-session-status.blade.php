@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-sm font-semibold text-emerald-300']) }}>
        {{ $status }}
    </div>
@endif
