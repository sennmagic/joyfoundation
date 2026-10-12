@props(['nav' => [], 'overlay' => true])

@php
    $nav = array_filter($nav, fn (array $item): bool => trim($item['href'], '/') !== 'contact');
@endphp

<header @class(['z-10', 'absolute inset-x-0 top-0' => $overlay, 'relative' => ! $overlay])>
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-gutter py-6 xl:px-gutter-lg">
        <a href="{{ url('/') }}" class="block shrink-0">
            <img src="{{ asset('images/site/logo.png') }}" alt="Joy Foundation Nepal" class="size-20 object-contain xl:size-24 2xl:size-logo">
        </a>

        <nav aria-label="Main navigation" @class(['hidden items-center gap-1 rounded-full border bg-white/10 p-2 xl:flex', 'border-white/15' => $overlay, 'border-navy/15' => ! $overlay])>
            @foreach ($nav as $item)
                @php
                    $children = $item['children'] ?? [];
                    $active = collect([$item, ...$children])->contains(fn (array $link): bool => request()->is(trim($link['href'], '/') ?: '/'));
                @endphp

                <div class="group relative">
                    <a
                        href="{{ $item['href'] }}"
                        @class([
                            'flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-semibold whitespace-nowrap transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-ink',
                            'bg-primary-ink text-white' => $active,
                            'text-white hover:bg-white/10' => ! $active && $overlay,
                            'text-body hover:bg-navy/5 hover:text-primary-ink' => ! $active && ! $overlay,
                        ])
                    >
                        {{ $item['label'] }}
                        @if ($children)
                            <svg class="size-3 transition group-hover:rotate-180" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2.5 4.5 6 8l3.5-3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @endif
                    </a>

                    @if ($children)
                        <div class="invisible absolute top-full left-0 min-w-48 pt-2 opacity-0 transition group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100">
                            <div class="rounded-2xl border border-primary/20 bg-cream p-2 shadow-xl">
                                @foreach ($children as $child)
                                    <a href="{{ $child['href'] }}" class="block rounded-xl px-4 py-3 text-sm font-semibold whitespace-nowrap text-body transition hover:bg-white hover:text-primary-ink focus-visible:outline-2 focus-visible:outline-primary-ink">
                                        {{ $child['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </nav>

        <div class="flex items-center gap-stack-xs">
            <button
                type="button"
                @class(['hidden size-11 items-center justify-center rounded-full border 2xl:flex', 'border-white/25 bg-white/10 text-white' => $overlay, 'border-navy/20 bg-white text-navy' => ! $overlay])
                aria-label="Search"
            >
                <img src="{{ asset('images/site/icon-search.svg') }}" alt="" @class(['size-4', 'brightness-0' => ! $overlay])>
            </button>

            <x-site.cta-button :href="route('page', ['slug' => 'contact'])" variant="solid" size="sm">
                Contact Us
                <span class="hidden size-7 items-center justify-center rounded-full bg-white/20 sm:flex">
                    <img src="{{ asset('images/site/icon-donate-header.svg') }}" alt="" class="size-3.5">
                </span>
            </x-site.cta-button>

            @if ($nav)
                <details class="xl:hidden">
                    <summary aria-label="Navigation menu" @class(['flex size-11 cursor-pointer list-none items-center justify-center rounded-full border bg-white/10 focus-visible:outline-2 focus-visible:outline-offset-4 [&::-webkit-details-marker]:hidden', 'border-white/25 text-white focus-visible:outline-white' => $overlay, 'border-navy/20 text-body focus-visible:outline-navy' => ! $overlay])>
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </summary>
                    <nav aria-label="Mobile navigation" class="absolute inset-x-gutter top-full max-h-96 overflow-y-auto rounded-2xl border border-primary/20 bg-cream p-3 shadow-xl">
                        @foreach ($nav as $item)
                            <a href="{{ $item['href'] }}" class="block rounded-xl px-4 py-3 text-base font-semibold text-body hover:bg-white hover:text-primary-ink focus-visible:outline-2 focus-visible:outline-primary-ink">{{ $item['label'] }}</a>
                            @foreach ($item['children'] ?? [] as $child)
                                <a href="{{ $child['href'] }}" class="ml-4 block border-l border-primary/30 px-4 py-3 text-sm text-body hover:text-primary-ink focus-visible:outline-2 focus-visible:outline-primary-ink">{{ $child['label'] }}</a>
                            @endforeach
                        @endforeach
                    </nav>
                </details>
            @endif
        </div>
    </div>
</header>
