@props(['photo', 'icon', 'category', 'title', 'description', 'href' => ''])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center text-center']) }}>
    <div class="scroll-scale relative">
        <div class="flex size-72 items-center justify-center rounded-full bg-white p-2 shadow-xl">
            <x-site.image :src="asset('images/site/'.$photo)" :alt="$title" shape="circle" fit="full" :shadow="false" />
        </div>

        <svg class="ring-fill pointer-events-none absolute inset-0 size-72 -rotate-90" viewBox="0 0 288 288" fill="none" aria-hidden="true">
            <circle cx="144" cy="144" r="143" stroke="currentColor" stroke-width="4" class="text-primary" pathLength="100" />
        </svg>

        <img src="{{ asset('images/site/'.$icon) }}" alt="" class="absolute right-0 bottom-0 size-14">
    </div>

    <span class="mt-stack-xs block h-3.5 w-px bg-primary/35"></span>

    <p class="mt-stack-xs text-xs font-semibold tracking-wide text-primary uppercase">{{ $category }}</p>
    <h3 class="mt-stack-xs font-heading text-lg font-semibold text-navy">{{ $title }}</h3>
    <p class="mt-stack-xs max-w-xs text-description text-navy/70">{{ $description }}</p>

    @if ($href !== '')
        <a href="{{ $href }}" class="mt-stack-sm inline-flex items-center gap-1 border-b border-primary/60 pb-1 text-xs font-semibold text-navy/80">
            Learn More <span class="text-primary">&rarr;</span>
        </a>
    @endif
</div>
