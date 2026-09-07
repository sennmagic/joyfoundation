@props(['items' => []])

<div class="relative overflow-hidden">
    <div class="absolute inset-y-0 left-0 z-10 w-24 bg-gradient-to-r from-white to-transparent"></div>
    <div class="absolute inset-y-0 right-0 z-10 w-24 bg-gradient-to-l from-white to-transparent"></div>

    <div class="flex w-max animate-marquee items-center gap-18">
        @foreach ([...$items, ...$items] as $item)
            <img
                src="{{ asset('images/site/'.$item['logo']) }}"
                alt="{{ $item['name'] }}"
                class="h-20 w-auto shrink-0 object-contain"
            >
        @endforeach
    </div>
</div>
