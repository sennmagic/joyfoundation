@props([
    'value',
    'caption' => [],
    'subCaption' => null,
    'color' => 'navy',
    'weight' => 'semibold',
    'valueSize' => 'text-4xl',
    'theme' => 'light',
    'captionWidth' => 'max-w-48',
    'icon' => null,
])

@php
    $colors = ['navy' => 'text-navy', 'primary' => $theme === 'light' ? 'text-primary-ink' : 'text-primary', 'white' => 'text-white'];
    $weights = ['semibold' => 'font-semibold', 'light' => 'font-medium'];
    $captionColors = ['light' => 'text-muted', 'dark' => 'text-white/85'];
@endphp

<div {{ $attributes }}>
    @if ($icon)
        <span class="mb-stack-sm flex size-14 items-center justify-center rounded-2xl border border-primary/25 bg-primary/10 text-primary" aria-hidden="true">
            @svg('heroicon-o-'.$icon, 'size-7', ['aria-hidden' => 'true', 'focusable' => 'false', 'data-stat-icon' => $icon])
        </span>
    @endif

    <p class="font-heading {{ $valueSize }} {{ $weights[$weight] }} tracking-tight {{ $colors[$color] }}"><x-site.count-up :value="$value" /></p>

    <div class="mt-stack-xs {{ $captionWidth }} text-sm {{ $captionColors[$theme] }}">
        @foreach ($caption as $line)
            <p>{{ $line }}</p>
        @endforeach
    </div>

    @if ($subCaption)
        <p class="{{ $captionWidth }} text-sm {{ $captionColors[$theme] }}">{{ $subCaption }}</p>
    @endif
</div>
