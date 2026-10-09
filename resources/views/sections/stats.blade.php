<section class="bg-cream">
    <div class="mx-auto max-w-7xl border-t border-navy/10 px-gutter pt-stack-lg pb-stack-md sm:px-gutter-lg">
        <div class="flex flex-col gap-stack-sm xl:flex-row xl:items-end xl:justify-between">
            <div>
                <x-site.section-eyebrow :label="$content['eyebrow']" />

                <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy sm:text-heading-sm">
                    {{ $content['heading']['line1'] }}<br>
                    {{ $content['heading']['line2_prefix'] }}<span class="font-light text-primary">{{ $content['heading']['line2_highlight'] }}</span>
                </h2>
            </div>

            <div class="w-full max-w-96 text-description text-muted">
                @foreach ($content['note'] as $line)
                    <p>{{ $line }}</p>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl border-t border-navy/10 px-gutter sm:px-gutter-lg">
        <div class="grid gap-stack-lg pt-stack-md pb-section-y sm:pb-section-y-lg xl:grid-cols-6 xl:gap-4 xl:divide-x xl:divide-navy/10">
            <div class="rise-in relative xl:col-span-2 xl:pr-4">
                <span class="pointer-events-none absolute -top-6 -left-1 hidden font-heading text-ghost font-semibold text-primary/5 xl:block" aria-hidden="true">{{ $content['headline_stat']['ghost'] }}</span>

                <p class="relative font-heading text-6xl font-semibold tracking-tight text-navy sm:text-7xl"><x-site.count-up :value="$content['headline_stat']['value']" /></p>
                <span class="mt-stack-xs block h-1 w-12 rounded-full bg-primary"></span>

                <div class="mt-stack-xs w-full max-w-96 text-sm text-muted">
                    @foreach ($content['headline_stat']['caption'] as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                </div>
            </div>

            @foreach ($content['stats'] as $stat)
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
            @foreach ($content['dark_stats'] as $stat)
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
