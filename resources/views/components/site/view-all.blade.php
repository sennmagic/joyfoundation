@props(['type', 'content' => []])

@if (request()->routeIs('home') || (request()->routeIs('page') && request()->route('slug') === 'home'))
    @php
        $destination = match ($type) {
            'card_grid' => 'programs',
            'feature_list' => 'long-term-community-support',
            'events' => 'events',
            default => 'about',
        };
        $href = trim($content['view_all_href'] ?? '') ?: route('page', ['slug' => $destination], false);
        $label = trim($content['view_all_label'] ?? '');
        if ($label === '' || strcasecmp($label, 'View all') === 0) {
            $label = match ($type) {
                'hero' => 'Discover JOY',
                'text_band' => 'Get to know us',
                'image_text' => 'Discover our story',
                'statements' => 'What guides us',
                'stats' => 'Explore our impact',
                'card_grid' => 'Explore all programs',
                'feature_list' => 'All community initiatives',
                'feature_cards' => 'More of our impact',
                'logo_marquee' => 'Meet our partners',
                'article_grid' => 'Read all stories',
                'events' => 'View all events',
                default => 'View all',
            };
        }
        $light = in_array($type, ['hero', 'feature_list'], true);
    @endphp

    <a
        href="{{ $href }}"
        data-view-all
        {{ $attributes->class([
            'group/view-all inline-flex min-h-11 w-fit items-center gap-stack-xs rounded-full py-1 text-sm font-semibold focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-current',
            'text-white' => $light,
            'text-primary-ink' => ! $light,
            'bg-white px-4' => $type === 'text_band',
        ]) }}
    >
        <span class="decoration-current underline-offset-4 group-hover/view-all:underline">{{ $label }}</span>
        <span @class([
            'flex size-10 shrink-0 items-center justify-center rounded-full border transition-colors duration-200',
            'border-white/40 text-white group-hover/view-all:border-white group-hover/view-all:bg-white group-hover/view-all:text-primary-ink' => $light,
            'border-primary/40 bg-primary/5 text-primary-ink group-hover/view-all:border-primary-ink group-hover/view-all:bg-primary-ink group-hover/view-all:text-white' => ! $light,
        ]) aria-hidden="true">
            <svg class="size-4 transition-transform duration-200 motion-safe:group-hover/view-all:-translate-y-0.5 motion-safe:group-hover/view-all:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M7 17 17 7M7 7h10v10" />
            </svg>
        </span>
    </a>
@endif
