@props(['label', 'labelColor' => 'text-navy/70'])

<div {{ $attributes->merge(['class' => 'flex items-center gap-stack-xs']) }}>
    <img src="{{ asset('images/site/icon-smiley.svg') }}" alt="" class="size-5">
    <span class="text-eyebrow font-medium {{ $labelColor }} uppercase">{{ $label }}</span>
</div>
