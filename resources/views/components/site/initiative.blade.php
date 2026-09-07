@props([
    'photo',
    'badge',
    'category',
    'title' => [],
    'location',
    'description',
    'tags' => [],
    'impact',
    'partners' => [],
    'imageSide' => 'left',
])

<div class="grid gap-stack-lg border-t border-white/10 py-stack-lg xl:grid-cols-2 xl:items-center">
    <div class="{{ $imageSide === 'right' ? 'xl:order-2' : '' }}">
        <div class="relative h-96 overflow-hidden rounded-xl">
            <x-site.image :src="asset('images/site/'.$photo)" :alt="implode(' ', $title)" shape="card" fit="full" :shadow="false" />
            <div class="absolute inset-0 bg-gradient-to-b from-navy/0 to-navy/60"></div>
            <p class="absolute bottom-6 left-6 text-xs font-semibold tracking-widest text-white uppercase text-shadow-sm">{{ $badge }}</p>
        </div>
    </div>

    <div class="{{ $imageSide === 'right' ? 'xl:order-1' : '' }}">
        <p class="text-xs font-medium tracking-widest text-primary uppercase">{{ $category }}</p>

        <h3 class="mt-stack-xs font-heading text-4xl font-semibold text-white">
            @foreach ($title as $line)
                <span class="block">{{ $line }}</span>
            @endforeach
        </h3>

        <p class="mt-stack-sm text-xs text-white/75">{{ $location }}</p>

        <div class="mt-stack-sm border-t border-white/10 pt-stack-sm">
            <p class="text-description text-white/90">{{ $description }}</p>
        </div>

        <p class="mt-stack-sm text-xs text-primary/80">{{ implode(' · ', $tags) }}</p>

        <div class="mt-stack-sm flex gap-stack-xs">
            <span class="w-0.5 shrink-0 rounded-full bg-primary/80"></span>
            <p class="text-xs text-white/75">{{ $impact }}</p>
        </div>

        @if (count($partners) > 0)
            <p class="mt-stack-sm text-xs text-white/75">WITH {{ implode(' · ', $partners) }}</p>
        @endif
    </div>
</div>
