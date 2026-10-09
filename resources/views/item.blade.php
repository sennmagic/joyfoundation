@php $title = \App\Models\Section::itemTitle($item); @endphp

<x-layouts.app :title="$title.' — '.$page->title" :description="$item['description'] ?? null">
    <main>
        @include('sections.hero', ['content' => [
            'background' => $item['photo'] ?? $site['hero']['background'] ?? 'hero-bg.png',
            'heading_prefix' => '',
            'heading_highlight' => $title,
            'heading_suffix' => '',
        ]])

        <section class="px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
            <div class="mx-auto max-w-3xl">
                @if ($item['category'] ?? false)
                    <x-site.section-eyebrow :label="$item['category']" />
                @endif

                @if ($item['description'] ?? false)
                    <p class="mt-stack-sm font-heading text-2xl leading-snug font-medium text-navy">{{ $item['description'] }}</p>
                @endif

                <div class="mt-stack-md space-y-stack-sm text-description text-body [&_h2]:mt-stack-md [&_h2]:font-heading [&_h2]:text-2xl [&_h2]:font-semibold [&_h2]:text-navy [&_h3]:font-heading [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-navy [&_ol]:list-decimal [&_ol]:pl-6 [&_ul]:list-disc [&_ul]:pl-6 [&_a]:text-primary [&_a]:underline">
                    {!! \App\Models\Section::details($item) !!}
                </div>

                @if (! empty($item['tags']))
                    <p class="mt-stack-md text-xs text-primary/80">{{ implode(' · ', $item['tags']) }}</p>
                @endif

                @if (! empty($item['partners']))
                    <p class="mt-stack-xs text-xs font-semibold text-navy/70">WITH {{ implode(' · ', $item['partners']) }}</p>
                @endif

                <x-site.cta-button :href="route('page', $page->slug)" variant="solid" size="sm" class="mt-stack-lg">
                    &larr; Back to {{ $page->title }}
                </x-site.cta-button>
            </div>
        </section>
    </main>
</x-layouts.app>
