@props(['href' => '#', 'variant' => 'solid', 'size' => 'md'])

@php
    $variants = [
        'solid' => 'bg-primary-ink text-white shadow-cta hover:opacity-90',
        'outline' => 'border border-white/80 text-white hover:bg-white/10',
    ];

    $sizes = [
        'md' => 'gap-stack-xs px-8 py-3.5 text-sm',
        'sm' => 'gap-stack-xs py-2 pl-5 pr-2 text-sm',
    ];
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'inline-flex min-h-11 shrink-0 items-center rounded-full font-semibold tracking-cta transition focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary-ink '
            .$variants[$variant].' '.$sizes[$size],
    ]) }}
>
    {{ $slot }}
</a>
