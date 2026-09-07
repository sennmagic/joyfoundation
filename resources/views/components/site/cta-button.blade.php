@props(['href' => '#', 'variant' => 'solid', 'size' => 'md'])

@php
    $variants = [
        'solid' => 'bg-primary text-white shadow-cta hover:opacity-90',
        'outline' => 'border border-white/80 text-white hover:bg-white/10',
    ];

    $sizes = [
        'md' => 'gap-stack-xs px-8 py-3.5 text-xs',
        'sm' => 'gap-stack-xs py-2 pl-7 pr-2 text-xs',
    ];
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center rounded-full font-semibold uppercase tracking-cta transition '
            .$variants[$variant].' '.$sizes[$size],
    ]) }}
>
    {{ $slot }}
</a>
