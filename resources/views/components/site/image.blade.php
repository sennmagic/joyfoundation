@props(['src', 'alt' => '', 'shape' => 'rectangle', 'fit' => 'auto', 'shadow' => true])

@php
    $shapes = [
        'rectangle' => 'rounded-3xl',
        'square' => 'rounded-2xl',
        'circle' => 'rounded-full',
        'card' => 'rounded-xl',
        'none' => '',
    ];

    $fits = [
        'auto' => 'h-auto w-full',
        'full' => 'size-full',
    ];
@endphp

<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    loading="lazy"
    {{ $attributes->merge(['class' => 'object-cover '.$fits[$fit].' '.$shapes[$shape].' '.($shadow ? 'shadow-xl' : '')]) }}
>
