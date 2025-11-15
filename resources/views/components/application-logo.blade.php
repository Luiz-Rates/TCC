@php
    $customClass = trim($attributes->get('class', ''));
    $classes = $customClass ?: 'w-44 sm:w-56 h-auto drop-shadow-xl';
@endphp

<img
    src="{{ asset('images/nexkeep-bg.png') }}"
    alt="NexKeep"
    class="{{ $classes }}"
    {{ $attributes->except('class') }}
>
