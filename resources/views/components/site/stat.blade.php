@props([
    'value',
    'caption' => [],
    'subCaption' => null,
    'color' => 'navy',
    'weight' => 'semibold',
    'valueSize' => 'text-4xl',
    'theme' => 'light',
    'captionWidth' => 'max-w-48',
])

@php
    $colors = ['navy' => 'text-navy', 'primary' => 'text-primary', 'white' => 'text-white'];
    $weights = ['semibold' => 'font-semibold', 'light' => 'font-light'];
    $captionColors = ['light' => 'text-muted', 'dark' => 'text-white/85'];
@endphp

<div {{ $attributes }}>
    <p class="font-heading {{ $valueSize }} {{ $weights[$weight] }} tracking-tight {{ $colors[$color] }}">{{ $value }}</p>

    <div class="mt-stack-xs {{ $captionWidth }} text-xs {{ $captionColors[$theme] }}">
        @foreach ($caption as $line)
            <p>{{ $line }}</p>
        @endforeach
    </div>

    @if ($subCaption)
        <p class="{{ $captionWidth }} text-xs {{ $captionColors[$theme] }}">{{ $subCaption }}</p>
    @endif
</div>
