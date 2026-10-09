<section class="bg-white px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
    <div class="mx-auto max-w-7xl">
        <x-site.section-eyebrow :label="$content['eyebrow']" />

        <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy sm:text-heading">
            {{ $content['heading_prefix'] }}<span class="font-light text-primary">{{ $content['heading_highlight'] }}</span>{{ $content['heading_suffix'] }}
        </h2>

        <p class="mt-stack-sm max-w-2xl text-description text-body">{{ $content['subtext'] }}</p>

        <div class="mt-stack-md grid grid-cols-1 gap-stack-md xl:grid-cols-2">
            <div>
                <p class="text-micro font-medium text-primary uppercase">{{ $content['concluded_label'] }}</p>

                <div class="rise-in-solid mt-stack-xs overflow-hidden rounded-3xl bg-navy">
                    <div class="grid grid-cols-2">
                        @foreach ($content['featured']['photos'] as $photo)
                            <x-site.image
                                :src="asset('images/site/'.$photo)"
                                :alt="$content['featured']['title']"
                                shape="none"
                                fit="full"
                                :shadow="false"
                                class="h-70"
                            />
                        @endforeach
                    </div>

                    <div class="p-stack-md">
                        <h3 class="font-heading text-2xl font-semibold tracking-tight text-white">{{ $content['featured']['title'] }}</h3>
                        <p class="mt-stack-sm text-xs font-medium tracking-widest text-primary uppercase">{{ $content['featured']['date'] }}</p>
                        <p class="mt-stack-xs text-description text-white/60">{{ $content['featured']['description'] }}</p>

                        <div class="mt-stack-md flex flex-wrap gap-stack-xs">
                            @foreach ($content['featured']['stats'] as $stat)
                                <span class="rounded-full bg-white/10 px-4 py-2 text-xs font-semibold text-white">{{ $stat }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <p class="text-micro font-medium text-primary uppercase">{{ $content['upcoming_label'] }}</p>

                @php $items = $section->listItems(); @endphp

                <div class="mt-stack-xs divide-y divide-navy/10 border-t border-navy/10">
                    @foreach ($items as $event)
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
            </div>
        </div>

        <div class="mt-stack-md grid grid-cols-2 gap-stack-xs sm:grid-cols-3 xl:grid-cols-5">
            @foreach ($content['gallery'] as $photo)
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

        <x-site.view-all :total="count($section->items())" :shown="count($items)" :label="$content['view_all_label'] ?? 'View all'" :href="$content['view_all_href'] ?? ''" />

    </div>
</section>
