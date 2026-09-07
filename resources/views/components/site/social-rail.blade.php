@props(['items' => []])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center gap-stack-xs']) }}>
    <span class="h-16 w-px bg-white/40"></span>

    @foreach ($items as $item)
        <a
            href="{{ $item['url'] }}"
            aria-label="{{ $item['label'] }}"
            class="flex size-10 items-center justify-center rounded-full border border-white/25 bg-white/10"
        >
            <img src="{{ asset('images/site/'.$item['icon']) }}" alt="" class="size-4">
        </a>
    @endforeach

    <span class="h-16 w-px bg-white/40"></span>
</div>
k,