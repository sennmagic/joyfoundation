<section class="bg-navy px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col gap-stack-sm xl:flex-row xl:items-start xl:justify-between">
            <div>
                <x-site.section-eyebrow :label="$content['eyebrow']" label-color="text-primary" />

                <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-white sm:text-heading">
                    {{ $content['heading']['line1'] }}<br>
                    <span class="font-light text-primary">{{ $content['heading']['line2_highlight'] }}</span>{{ $content['heading']['line2_suffix'] }}
                </h2>
            </div>

            <p class="w-full max-w-96 text-description text-white/90">
                {{ $content['intro'] }}
            </p>
        </div>

        @php $items = $section->listItems(); @endphp

        <div class="mt-stack-lg">
            @foreach ($items as $initiative)
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
                    :href="\App\Models\Section::itemUrl($page, $initiative)"
                />
            @endforeach
        </div>

        <x-site.view-all :total="count($section->items())" :shown="count($items)" :label="$content['view_all_label'] ?? 'View all'" :href="$content['view_all_href'] ?? ''" />

    </div>
</section>
