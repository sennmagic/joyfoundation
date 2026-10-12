@props(['label', 'labelColor' => 'text-muted'])

<div {{ $attributes->merge(['class' => 'flex items-center gap-stack-xs']) }}>
    <img src="{{ asset('images/site/icon-smiley.svg') }}" alt="" class="size-5">
    <span class="text-eyebrow font-semibold {{ $labelColor }} uppercase">{{ $label }}</span>
</div>
