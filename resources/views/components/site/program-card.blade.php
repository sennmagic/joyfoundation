@props(['photo', 'icon', 'category', 'title', 'description'])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center text-center']) }}>
    <div class="relative">
        <div class="flex size-72 items-center justify-center rounded-full border-2 border-primary/50 bg-white p-2 shadow-xl">
            <x-site.image :src="asset('images/site/'.$photo)" :alt="$title" shape="circle" fit="full" :shadow="false" />
        </div>

        <img src="{{ asset('images/site/'.$icon) }}" alt="" class="absolute right-0 bottom-0 size-14">
    </div>

    <span class="mt-stack-xs block h-3.5 w-px bg-primary/35"></span>

    <p class="mt-stack-xs text-xs font-semibold tracking-wide text-primary uppercase">{{ $category }}</p>
    <h3 class="mt-stack-xs font-heading text-lg font-semibold text-navy">{{ $title }}</h3>
    <p class="mt-stack-xs max-w-xs text-sm text-navy/70">{{ $description }}</p>

    <a href="#" class="mt-stack-sm inline-flex items-center gap-1 border-b border-primary/60 pb-1 text-xs font-semibold text-navy/80">
        Learn More <span class="text-primary">&rarr;</span>
    </a>
</div>
