<section class="relative overflow-hidden bg-navy">
    <img src="{{ asset('images/site/'.$content['background']) }}" alt="" class="absolute inset-0 h-full w-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/40 to-black/55"></div>

    <x-site.header :nav="$site['nav']['items'] ?? []" />

    <x-site.social-rail
        :items="$site['social']['items'] ?? []"
        class="absolute top-1/2 left-6 hidden -translate-y-1/2 lg:flex sm:left-20"
    />

    <div class="relative mx-auto flex max-w-3xl flex-col items-center gap-stack-md px-gutter py-40 text-center sm:py-56">
        <h1 class="font-heading text-5xl font-semibold tracking-tight text-white text-shadow-lg sm:text-6xl lg:text-hero">
            {{ $content['heading_prefix'] }}<span class="text-primary">{{ $content['heading_highlight'] }}</span>{{ $content['heading_suffix'] }}
        </h1>
    </div>

    <div class="pointer-events-none absolute inset-x-0 bottom-0 h-24 overflow-hidden" aria-hidden="true">
        <div class="wave-bob h-full">
            <div class="wave-drift flex h-full w-max">
                <img src="{{ asset('images/site/wave-divider.svg') }}" alt="" class="h-full w-screen shrink-0">
                <img src="{{ asset('images/site/wave-divider.svg') }}" alt="" class="h-full w-screen shrink-0">
            </div>
        </div>
    </div>
</section>
