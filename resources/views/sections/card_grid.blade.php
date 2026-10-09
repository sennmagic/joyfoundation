<section class="bg-white px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
    <div class="mx-auto max-w-7xl">
        <x-site.section-eyebrow :label="$content['eyebrow']" />

        <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy sm:text-heading">
            {{ $content['heading_prefix'] }}<span class="font-light text-primary">{{ $content['heading_highlight'] }}</span>{{ $content['heading_suffix'] }}
        </h2>

        <p class="mt-stack-xs text-description text-body">{{ $content['subtext'] }}</p>

        <div class="mt-stack-lg grid grid-cols-1 gap-stack-lg border-t border-navy/10 pt-stack-lg xl:grid-cols-6">
            @foreach ($content['items'] as $index => $program)
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
