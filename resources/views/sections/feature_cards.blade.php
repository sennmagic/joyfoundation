<section class="bg-white px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
    <div class="mx-auto max-w-7xl border-t border-navy/10 pt-stack-lg">
        <div class="flex flex-col gap-stack-sm xl:flex-row xl:items-start xl:justify-between">
            <div>
                <x-site.section-eyebrow :label="$content['eyebrow']" />

                <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy sm:text-heading">
                    {{ $content['heading_prefix'] }}<span class="font-light text-primary">{{ $content['heading_highlight'] }}</span><br>
                    {{ $content['heading_line2'] }}
                </h2>
            </div>

            <p class="w-full max-w-96 text-description text-body">
                {{ $content['subtext'] }}
            </p>
        </div>

        <div class="mt-stack-lg grid grid-cols-1 gap-stack-lg border-t border-navy/10 pt-stack-lg xl:grid-cols-2">
            @foreach ($content['items'] as $index => $achievement)
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
