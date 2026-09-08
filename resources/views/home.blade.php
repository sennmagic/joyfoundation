<x-layouts.app :title="$content['seo']['title']" :description="$content['seo']['description']">
    <main>
        <section class="relative overflow-hidden bg-navy">
            <img src="{{ asset($content['hero']['background']) }}" alt="" class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/40 to-black/55"></div>

            <x-site.header :nav="$content['nav']" />

            <x-site.social-rail
                :items="$content['social']"
                class="absolute top-1/2 left-6 hidden -translate-y-1/2 lg:flex sm:left-20"
            />

            <div class="relative mx-auto flex max-w-3xl flex-col items-center gap-stack-md px-gutter py-40 text-center sm:py-56">
                <h1 class="font-heading text-5xl font-semibold tracking-tight text-white text-shadow-lg sm:text-6xl lg:text-hero">
                    {{ $content['hero']['heading_prefix'] }}<span class="text-primary">{{ $content['hero']['heading_highlight'] }}</span>{{ $content['hero']['heading_suffix'] }}
                </h1>

                <div class="flex flex-wrap items-center justify-center gap-stack-sm">
                    <x-site.cta-button href="#" variant="solid">
                        <img src="{{ asset('images/site/icon-donate-left.svg') }}" alt="" class="size-4">
                        Donate Now
                        <img src="{{ asset('images/site/icon-donate-right.svg') }}" alt="" class="size-4">
                    </x-site.cta-button>

                    <x-site.cta-button href="#" variant="outline">
                        <img src="{{ asset('images/site/icon-partner.svg') }}" alt="" class="size-4">
                        Become a Partner
                    </x-site.cta-button>
                </div>
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

        <section class="bg-primary px-gutter py-16 text-center">
            <p class="rise-in mx-auto max-w-4xl text-xl leading-relaxed text-white sm:text-band">
                {{ $content['intro']['text'] }}
            </p>
        </section>

        <section class="px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
            <div class="mx-auto grid max-w-7xl items-center gap-stack-lg lg:grid-cols-2">
                <div class="scroll-scale relative mx-auto w-full max-w-96">
                    <x-site.image
                        :src="asset($content['story']['photo'])"
                        alt="JOY Foundation volunteers at work"
                        shape="rectangle"
                    />

                    <div class="absolute -right-8 -bottom-10 w-48 -rotate-6">
                        <x-site.image
                            :src="asset($content['story']['photo_polaroid'])"
                            alt="JOY Foundation archive photo"
                            shape="square"
                            class="border-4 border-white"
                        />
                    </div>

                    <div class="absolute -top-10 -left-9 -rotate-8 rounded-2xl bg-primary px-5 py-4 text-white shadow-cta-lg">
                        <p class="text-xs tracking-widest text-white/80">EST.</p>
                        <p class="font-heading text-3xl font-bold">{{ $content['story']['established_year'] }}</p>
                    </div>
                </div>

                <div class="rise-in max-w-xl">
                    <x-site.section-eyebrow :label="$content['story']['eyebrow']" />

                    <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy sm:text-heading">
                        {{ $content['story']['heading_prefix'] }}<span class="font-light text-primary">{{ $content['story']['heading_highlight'] }}</span>.
                    </h2>

                    <div class="mt-stack-sm space-y-stack-sm">
                        @foreach ($content['story']['paragraphs'] as $paragraph)
                            <p class="text-description text-body">{{ $paragraph }}</p>
                        @endforeach
                    </div>

                    <x-site.cta-button href="#" variant="solid" size="sm" class="mt-stack-md">
                        {{ $content['story']['cta_label'] }}
                        <span class="flex size-9 items-center justify-center rounded-full bg-white/20">
                            <img src="{{ asset('images/site/icon-arrow-right.svg') }}" alt="" class="size-4">
                        </span>
                    </x-site.cta-button>
                </div>
            </div>
        </section>

        <section class="bg-cream px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
            <div class="mx-auto max-w-7xl">
                <div class="flex flex-col gap-stack-sm xl:flex-row xl:items-end xl:justify-between">
                    <div>
                        <x-site.section-eyebrow :label="$content['mission_section']['eyebrow']" />

                        <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy sm:text-heading">
                            {{ $content['mission_section']['heading_prefix'] }}<span class="font-light text-primary">{{ $content['mission_section']['heading_highlight'] }}</span>{{ $content['mission_section']['heading_suffix'] }}
                        </h2>
                    </div>

                    <p class="max-w-xs text-description text-body">
                        {{ $content['mission_section']['intro'] }}
                    </p>
                </div>

                <div class="mt-stack-lg space-y-stack-lg">
                    <div class="rise-in relative flex flex-col gap-stack-xs xl:flex-row xl:gap-stack-md">
                        <span class="pointer-events-none absolute -top-10 left-0 hidden font-heading text-9xl leading-none text-primary/25 xl:block" aria-hidden="true">&ldquo;</span>

                        <p class="w-48 shrink-0 font-heading text-sm font-medium text-primary">— {{ $content['mission_section']['mission']['label'] }}</p>
                        <x-site.rich-text
                            :segments="$content['mission_section']['mission']['segments']"
                            class="relative flex-1 font-heading text-2xl leading-snug font-medium text-navy sm:text-value"
                        />
                    </div>

                    <div class="rise-in flex flex-col gap-stack-xs xl:flex-row xl:gap-stack-md">
                        <p class="w-48 shrink-0 font-heading text-sm font-medium text-primary">— {{ $content['mission_section']['vision']['label'] }}</p>
                        <x-site.rich-text
                            :segments="$content['mission_section']['vision']['segments']"
                            class="max-w-copy flex-1 font-heading text-2xl leading-snug font-medium text-navy sm:text-value"
                        />
                    </div>

                    <div class="rise-in flex flex-col gap-stack-xs xl:flex-row xl:gap-stack-md">
                        <p class="w-48 shrink-0 font-heading text-sm font-medium text-primary">{{ $content['mission_section']['guides']['label'] }}</p>
                        <x-site.rich-text
                            :segments="$content['mission_section']['guides']['segments']"
                            class="flex-1 font-heading text-2xl leading-snug text-navy sm:text-value"
                        />
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-cream">
            <div class="mx-auto max-w-7xl border-t border-navy/10 px-gutter pt-stack-lg pb-stack-md sm:px-gutter-lg">
                <div class="flex flex-col gap-stack-sm xl:flex-row xl:items-end xl:justify-between">
                    <div>
                        <x-site.section-eyebrow :label="$content['impact']['eyebrow']" />

                        <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy sm:text-heading-sm">
                            {{ $content['impact']['heading']['line1'] }}<br>
                            {{ $content['impact']['heading']['line2_prefix'] }}<span class="font-light text-primary">{{ $content['impact']['heading']['line2_highlight'] }}</span>
                        </h2>
                    </div>

                    <div class="w-full max-w-96 text-description text-muted">
                        @foreach ($content['impact']['note'] as $line)
                            <p>{{ $line }}</p>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mx-auto max-w-7xl border-t border-navy/10 px-gutter sm:px-gutter-lg">
                <div class="grid gap-stack-lg pt-stack-md pb-section-y sm:pb-section-y-lg xl:grid-cols-6 xl:gap-4 xl:divide-x xl:divide-navy/10">
                    <div class="rise-in relative xl:col-span-2 xl:pr-4">
                        <span class="pointer-events-none absolute -top-6 -left-1 hidden font-heading text-ghost font-semibold text-primary/5 xl:block" aria-hidden="true">{{ $content['impact']['headline_stat']['ghost'] }}</span>

                        <p class="relative font-heading text-6xl font-semibold tracking-tight text-navy sm:text-7xl"><x-site.count-up :value="$content['impact']['headline_stat']['value']" /></p>
                        <span class="mt-stack-xs block h-1 w-12 rounded-full bg-primary"></span>

                        <div class="mt-stack-xs w-full max-w-96 text-sm text-muted">
                            @foreach ($content['impact']['headline_stat']['caption'] as $line)
                                <p>{{ $line }}</p>
                            @endforeach
                        </div>
                    </div>

                    @foreach ($content['impact']['stats'] as $stat)
                        <x-site.stat
                            :value="$stat['value']"
                            :caption="$stat['caption']"
                            :color="$stat['color']"
                            :weight="$stat['weight']"
                            value-size="text-stat"
                            class="rise-in xl:px-4"
                        />
                    @endforeach
                </div>
            </div>

            <div class="border-t border-navy/10 bg-navy">
                <div class="mx-auto grid max-w-7xl gap-stack-lg px-gutter py-stack-lg sm:px-gutter-lg xl:grid-cols-4 xl:divide-x xl:divide-white/5">
                    @foreach ($content['impact']['dark_stats'] as $stat)
                        <x-site.stat
                            :value="$stat['value']"
                            :caption="[$stat['caption']]"
                            :sub-caption="$stat['sub_caption']"
                            color="white"
                            :weight="$stat['weight']"
                            value-size="text-stat-dark"
                            theme="dark"
                            caption-width="max-w-72"
                            class="rise-in xl:px-5"
                        />
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-white px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
            <div class="mx-auto max-w-7xl">
                <x-site.section-eyebrow :label="$content['programs']['eyebrow']" />

                <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy sm:text-heading">
                    {{ $content['programs']['heading_prefix'] }}<span class="font-light text-primary">{{ $content['programs']['heading_highlight'] }}</span>{{ $content['programs']['heading_suffix'] }}
                </h2>

                <p class="mt-stack-xs text-description text-body">{{ $content['programs']['subtext'] }}</p>

                <div class="mt-stack-lg grid grid-cols-1 gap-stack-lg border-t border-navy/10 pt-stack-lg xl:grid-cols-6">
                    @foreach ($content['programs']['items'] as $index => $program)
                        <x-site.program-card
                            :photo="$program['photo']"
                            :icon="$program['icon']"
                            :category="$program['category']"
                            :title="$program['title']"
                            :description="$program['description']"
                            class="xl:col-span-2 {{ $index === 3 ? 'xl:col-start-2' : '' }} {{ $index === 4 ? 'xl:col-start-4' : '' }}"
                        />
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-navy px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
            <div class="mx-auto max-w-7xl">
                <div class="flex flex-col gap-stack-sm xl:flex-row xl:items-start xl:justify-between">
                    <div>
                        <x-site.section-eyebrow :label="$content['long_term_community']['eyebrow']" label-color="text-primary" />

                        <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-white sm:text-heading">
                            {{ $content['long_term_community']['heading']['line1'] }}<br>
                            <span class="font-light text-primary">{{ $content['long_term_community']['heading']['line2_highlight'] }}</span>{{ $content['long_term_community']['heading']['line2_suffix'] }}
                        </h2>
                    </div>

                    <p class="w-full max-w-96 text-description text-white/90">
                        {{ $content['long_term_community']['intro'] }}
                    </p>
                </div>

                <div class="mt-stack-lg">
                    @foreach ($content['long_term_community']['initiatives'] as $initiative)
                        <x-site.initiative
                            :photo="$initiative['photo']"
                            :badge="$initiative['badge']"
                            :category="$initiative['category']"
                            :title="$initiative['title']"
                            :location="$initiative['location']"
                            :description="$initiative['description']"
                            :tags="$initiative['tags']"
                            :impact="$initiative['impact']"
                            :partners="$initiative['partners']"
                            :image-side="$loop->odd ? 'left' : 'right'"
                        />
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-white px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
            <div class="mx-auto max-w-7xl border-t border-navy/10 pt-stack-lg">
                <div class="flex flex-col gap-stack-sm xl:flex-row xl:items-start xl:justify-between">
                    <div>
                        <x-site.section-eyebrow :label="$content['achievements']['eyebrow']" />

                        <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy sm:text-heading">
                            {{ $content['achievements']['heading_prefix'] }}<span class="font-light text-primary">{{ $content['achievements']['heading_highlight'] }}</span><br>
                            {{ $content['achievements']['heading_line2'] }}
                        </h2>
                    </div>

                    <p class="w-full max-w-96 text-description text-body">
                        {{ $content['achievements']['subtext'] }}
                    </p>
                </div>

                <div class="mt-stack-lg grid grid-cols-1 gap-stack-lg border-t border-navy/10 pt-stack-lg xl:grid-cols-2">
                    @foreach ($content['achievements']['items'] as $index => $achievement)
                        <x-site.achievement-card
                            :photo="$achievement['photo']"
                            :category="$achievement['category']"
                            :date="$achievement['date']"
                            :title="$achievement['title']"
                            :location="$achievement['location']"
                            :stat-value="$achievement['stat_value']"
                            :stat-label="$achievement['stat_label']"
                            :stat-theme="$achievement['stat_theme']"
                            :index="str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)"
                            :description="$achievement['description']"
                            :tags="$achievement['tags']"
                            :impact="$achievement['impact']"
                            :partners="$achievement['partners']"
                        />
                    @endforeach
                </div>
            </div>
        </section>

        <section class="bg-white py-section-y sm:py-section-y-lg">
            <div class="mx-auto max-w-7xl px-gutter sm:px-gutter-lg">
                <div class="flex flex-col gap-stack-sm xl:flex-row xl:items-start xl:justify-between">
                    <div>
                        <x-site.section-eyebrow :label="$content['partners']['eyebrow']" />

                        <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy">
                            {{ $content['partners']['heading_prefix'] }}<span class="font-light text-primary">{{ $content['partners']['heading_highlight'] }}</span>.
                        </h2>
                    </div>

                    <div class="w-full max-w-80 text-description text-body">
                        @foreach ($content['partners']['note'] as $line)
                            <p>{{ $line }}</p>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-stack-lg">
                <x-site.partners-marquee :items="$content['partners']['items']" />
            </div>
        </section>

        <section class="bg-white px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
            <div class="mx-auto max-w-7xl">
                <x-site.section-eyebrow :label="$content['stories']['eyebrow']" />

                <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy sm:text-heading">
                    {{ $content['stories']['heading_prefix'] }}<span class="font-light text-primary">{{ $content['stories']['heading_highlight'] }}</span>{{ $content['stories']['heading_suffix'] }}
                </h2>

                <p class="mt-stack-xs text-description text-body">{{ $content['stories']['subtext'] }}</p>

                @php $featuredStory = $content['stories']['items'][0]; @endphp

                <div class="mt-stack-lg border-t border-navy/10 pt-stack-lg">
                    <x-site.story-card
                        layout="horizontal"
                        :photo="$featuredStory['photo']"
                        :category="$featuredStory['category']"
                        :headline="$featuredStory['headline']"
                        :paragraphs="$featuredStory['paragraphs']"
                        :location="$featuredStory['location']"
                    />
                </div>

                <div class="mt-stack-lg grid grid-cols-1 gap-stack-lg border-t border-navy/10 pt-stack-lg xl:grid-cols-2">
                    @foreach (array_slice($content['stories']['items'], 1) as $story)
                        <x-site.story-card
                            layout="stacked"
                            :photo="$story['photo']"
                            :category="$story['category']"
                            :headline="$story['headline']"
                            :paragraphs="$story['paragraphs']"
                            :location="$story['location']"
                        />
                    @endforeach
                </div>

                <div class="mt-stack-lg border-t border-navy/10 pt-stack-lg text-center">
                    <span class="mx-auto block h-1 w-10 rounded-full bg-primary"></span>
                    <x-site.rich-text
                        :segments="$content['stories']['closing']"
                        class="mt-stack-sm text-xl text-muted"
                    />
                </div>
            </div>
        </section>

        <section class="bg-white px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
            <div class="mx-auto max-w-7xl">
                <x-site.section-eyebrow :label="$content['events']['eyebrow']" />

                <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy sm:text-heading">
                    {{ $content['events']['heading_prefix'] }}<span class="font-light text-primary">{{ $content['events']['heading_highlight'] }}</span>{{ $content['events']['heading_suffix'] }}
                </h2>

                <p class="mt-stack-sm max-w-2xl text-description text-body">{{ $content['events']['subtext'] }}</p>

                <p class="mt-stack-md text-micro font-medium text-primary uppercase">{{ $content['events']['concluded_label'] }}</p>

                <div class="rise-in-solid mt-stack-xs grid grid-cols-1 overflow-hidden rounded-3xl bg-navy xl:grid-cols-2">
                    <div class="grid grid-cols-2">
                        @foreach ($content['events']['featured']['photos'] as $photo)
                            <x-site.image
                                :src="asset('images/site/'.$photo)"
                                :alt="$content['events']['featured']['title']"
                                shape="none"
                                fit="full"
                                :shadow="false"
                                class="h-70"
                            />
                        @endforeach
                    </div>

                    <div class="p-stack-md">
                        <h3 class="font-heading text-2xl font-semibold tracking-tight text-white">{{ $content['events']['featured']['title'] }}</h3>
                        <p class="mt-stack-sm text-xs font-medium tracking-widest text-primary uppercase">{{ $content['events']['featured']['date'] }}</p>
                        <p class="mt-stack-xs text-description text-white/60">{{ $content['events']['featured']['description'] }}</p>

                        <div class="mt-stack-md flex flex-wrap gap-stack-xs">
                            @foreach ($content['events']['featured']['stats'] as $stat)
                                <span class="rounded-full bg-white/10 px-4 py-2 text-xs font-semibold text-white">{{ $stat }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-stack-xs grid grid-cols-2 gap-stack-xs sm:grid-cols-3 xl:grid-cols-5">
                    @foreach ($content['events']['gallery'] as $photo)
                        <x-site.image
                            :src="asset('images/site/'.$photo)"
                            alt=""
                            shape="card"
                            fit="full"
                            :shadow="false"
                            class="scroll-scale h-40"
                        />
                    @endforeach
                </div>

                <p class="mt-stack-lg text-micro font-medium text-primary uppercase">{{ $content['events']['upcoming_label'] }}</p>

                <div class="mt-stack-xs divide-y divide-navy/10 border-t border-navy/10">
                    @foreach ($content['events']['upcoming'] as $event)
                        <x-site.event-row
                            :month="$event['month']"
                            :day="$event['day']"
                            :category="$event['category']"
                            :title="$event['title']"
                            :location="$event['location']"
                            class="py-stack-sm"
                        />
                    @endforeach
                </div>

                <div class="mt-stack-lg text-center">
                    <x-site.cta-button href="#" variant="solid">
                        {{ $content['events']['cta_label'] }}
                    </x-site.cta-button>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
