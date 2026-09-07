@props(['month', 'day', 'category', 'title', 'location'])

<div {{ $attributes->merge(['class' => 'flex items-center gap-stack-sm']) }}>
    <div class="w-14 shrink-0 overflow-hidden rounded-xl bg-cream text-center">
        <p class="bg-primary py-1 text-micro font-semibold text-white uppercase">{{ $month }}</p>
        <p class="py-1 font-heading text-2xl font-semibold tracking-tight text-navy">{{ $day }}</p>
    </div>

    <div>
        <p class="text-micro font-medium text-primary uppercase">{{ $category }}</p>
        <h3 class="mt-0.5 font-heading text-lg font-semibold tracking-tight text-navy">{{ $title }}</h3>

        <div class="mt-1 flex items-center gap-1.5">
            <img src="{{ asset('images/site/icon-location-pin.svg') }}" alt="" class="size-3">
            <p class="text-xs text-body">{{ $location }}</p>
        </div>
    </div>
</div>
