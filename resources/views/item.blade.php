@php $title = \App\Models\Section::itemTitle($item); @endphp

<x-layouts.app :title="$title.' — '.$page->title" :description="$item['description'] ?? null" :site="$site">
    <main>
        @include('sections.hero', ['content' => [
            'background' => $item['photo'] ?? $site['hero']['background'] ?? 'hero-bg.png',
            'heading_prefix' => '',
            'heading_highlight' => $title,
            'heading_suffix' => '',
        ]])

        <section class="px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
            <article class="mx-auto max-w-3xl">
                @if ($item['category'] ?? false)
                    <x-site.section-eyebrow :label="$item['category']" />
                @endif

                @if ($item['description'] ?? false)
                    <p class="mt-stack-sm font-heading text-2xl leading-snug font-medium text-navy">{{ $item['description'] }}</p>
                @endif

                @php
                    $details = \App\Models\Section::details($item);
                    $facts = array_filter([
                        'Period' => $item['date'] ?? $item['badge'] ?? '',
                        'Location' => $item['location'] ?? '',
                        'Results' => trim(($item['stat_value'] ?? '').' '.($item['stat_label'] ?? '')),
                    ]);
                @endphp

                @if ($facts)
                    <dl class="mt-stack-md grid gap-6 border-y border-navy/15 py-stack-sm xl:grid-cols-2">
                        @foreach ($facts as $label => $value)
                            <div class="min-w-0">
                                <dt class="text-sm text-muted">{{ $label }}</dt>
                                <dd class="mt-1 text-base font-semibold break-words text-navy">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @endif

                @if ($details !== '')
                    <div data-item-details class="mt-stack-md space-y-stack-sm text-description text-body [&_h2]:mt-stack-md [&_h2]:font-heading [&_h2]:text-2xl [&_h2]:font-semibold [&_h2]:text-navy [&_h3]:font-heading [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-navy [&_ol]:list-decimal [&_ol]:pl-6 [&_ul]:list-disc [&_ul]:pl-6 [&_a]:text-primary [&_a]:underline">
                        {!! $details !!}
                    </div>
                @endif

                @if (! empty($item['impact']))
                    <div class="mt-stack-md border-l-2 border-primary pl-stack-sm">
                        <h2 class="font-heading text-xl font-semibold">Impact</h2>
                        <p class="mt-stack-xs text-description text-body">{{ $item['impact'] }}</p>
                    </div>
                @endif

                @if (! empty($item['tags']))
                    <div class="mt-stack-md">
                        <h2 class="font-heading text-xl font-semibold">Areas of support</h2>
                        <ul class="mt-stack-xs list-disc space-y-2 pl-5 text-base text-body">
                            @foreach ($item['tags'] as $tag)
                                <li>{{ $tag }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (! empty($item['partners']))
                    <p class="mt-stack-md text-base text-body"><span class="font-semibold text-navy">Partners:</span> {{ implode(' · ', $item['partners']) }}</p>
                @endif

                <x-site.cta-button :href="route('page', $page->slug)" variant="solid" size="sm" class="mt-stack-lg">
                    &larr; Back to {{ $page->title }}
                </x-site.cta-button>
            </article>
        </section>
    </main>
</x-layouts.app>
