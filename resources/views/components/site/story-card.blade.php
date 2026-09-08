@props([
    'layout' => 'stacked',
    'photo',
    'category',
    'headline',
    'paragraphs' => [],
    'location',
])

<div class="rise-in flex gap-stack-lg {{ $layout === 'horizontal' ? 'flex-col xl:flex-row xl:items-center' : 'flex-col' }}">
    <div class="{{ $layout === 'horizontal' ? 'xl:w-1/2' : 'w-full' }}">
        <x-site.image :src="asset('images/site/'.$photo)" :alt="$headline" shape="card" fit="full" :shadow="false" class="{{ $layout === 'horizontal' ? 'h-90' : 'h-60' }}" />
    </div>

    <div class="{{ $layout === 'horizontal' ? 'xl:w-1/2' : 'w-full' }}">
        <p class="text-xs font-medium tracking-widest text-primary uppercase">{{ $category }}</p>

        <h3 class="mt-stack-xs font-heading font-semibold text-navy {{ $layout === 'horizontal' ? 'text-3xl' : 'text-xl' }}">
            {{ $headline }}
        </h3>

        <div class="mt-stack-sm space-y-stack-sm">
            @foreach ($paragraphs as $paragraph)
                <p class="text-description text-body">{{ $paragraph }}</p>
            @endforeach
        </div>

        <div class="mt-stack-sm flex items-center gap-stack-xs">
            <img src="{{ asset('images/site/icon-location-pin.svg') }}" alt="" class="size-3">
            <p class="text-xs font-semibold text-navy">{{ $location }}</p>
        </div>
    </div>
</div>
