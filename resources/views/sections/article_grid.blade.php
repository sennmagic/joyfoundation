<section class="bg-white px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
    <div class="mx-auto max-w-7xl">
        <x-site.section-eyebrow :label="$content['eyebrow']" />

        <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy sm:text-heading">
            {{ $content['heading_prefix'] }}<span class="font-light text-primary">{{ $content['heading_highlight'] }}</span>{{ $content['heading_suffix'] }}
        </h2>

        <p class="mt-stack-xs text-description text-body">{{ $content['subtext'] }}</p>

        @php $items = $section->listItems(); @endphp

        @if ($items !== [])
            <div class="mt-stack-lg border-t border-navy/10 pt-stack-lg">
                <x-site.story-card
                    layout="horizontal"
                    :photo="$items[0]['photo']"
                    :category="$items[0]['category']"
                    :headline="$items[0]['headline']"
                    :paragraphs="$items[0]['paragraphs']"
                    :location="$items[0]['location']"
                    :href="\App\Models\Section::itemUrl($page, $items[0])"
                />
            </div>
        @endif

        <div class="mt-stack-lg grid grid-cols-1 gap-stack-lg border-t border-navy/10 pt-stack-lg xl:grid-cols-2">
            @foreach (array_slice($items, 1) as $story)
                <x-site.story-card
                    layout="stacked"
                    :photo="$story['photo']"
                    :category="$story['category']"
                    :headline="$story['headline']"
                    :paragraphs="$story['paragraphs']"
                    :location="$story['location']"
                    :href="\App\Models\Section::itemUrl($page, $story)"
                />
            @endforeach
        </div>

        <x-site.view-all :total="count($section->items())" :shown="count($items)" :label="$content['view_all_label'] ?? 'View all'" :href="$content['view_all_href'] ?? ''" />


        <div class="mt-stack-lg border-t border-navy/10 pt-stack-lg text-center">
            <span class="mx-auto block h-1 w-10 rounded-full bg-primary"></span>
            <x-site.rich-text
                :segments="$content['closing']"
                class="mt-stack-sm text-xl text-muted"
            />
        </div>
    </div>
</section>
