@props([
    'photo',
    'category',
    'date',
    'title',
    'location',
    'statValue',
    'statLabel',
    'statTheme' => 'primary',
    'index',
    'description',
    'tags' => [],
    'impact',
    'partners' => [],
])

@php
    $statBg = $statTheme === 'primary' ? 'bg-primary' : 'bg-navy';
@endphp

<div class="overflow-hidden rounded-3xl">
    <div class="relative h-80">
        <x-site.image :src="asset('images/site/'.$photo)" :alt="$title" fit="full" :shadow="false" class="absolute inset-0" />
        <div class="absolute inset-0 bg-gradient-to-b from-navy/0 to-navy/90"></div>

        <div class="absolute top-7 left-7 flex items-center gap-2">
            <span class="size-1 rounded-full bg-primary"></span>
            <p class="text-xs font-semibold tracking-widest text-white uppercase">{{ $category }}</p>
        </div>

        <p class="absolute top-7 right-7 text-xs font-semibold text-white">{{ $date }}</p>

        <div class="absolute bottom-7 left-7">
            <h3 class="font-heading text-3xl font-semibold text-white">{{ $title }}</h3>
            <p class="mt-stack-xs text-xs text-white/95">{{ $location }}</p>
        </div>
    </div>

    <div class="flex items-center justify-between px-7 py-4 {{ $statBg }}">
        <div class="flex items-center gap-stack-xs text-white">
            <p class="font-heading text-3xl font-semibold">{{ $statValue }}</p>
            <p class="w-36 text-xs font-semibold tracking-wide uppercase">{{ $statLabel }}</p>
        </div>

        <p class="text-xs font-semibold tracking-wide text-white/75">{{ $index }}/04</p>
    </div>

    <div class="space-y-stack-xs bg-white px-7 pt-6 pb-7">
        <p class="text-sm text-navy/90">{{ $description }}</p>

        <div class="border-t border-navy/10 pt-stack-xs">
            <p class="text-xs text-navy/65">{{ implode(' · ', $tags) }}</p>
        </div>

        <div class="flex gap-stack-xs">
            <span class="w-0.5 shrink-0 rounded-full bg-primary/85"></span>
            <p class="text-xs text-navy/80">{{ $impact }}</p>
        </div>

        @if (count($partners) > 0)
            <p class="text-xs font-semibold text-primary/85">WITH {{ implode(' · ', $partners) }}</p>
        @endif
    </div>
</div>
