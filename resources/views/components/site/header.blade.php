@props(['nav' => []])

<header class="absolute inset-x-0 top-0 z-10">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-gutter py-7 sm:px-gutter-lg">
        <a href="{{ url('/') }}" class="block">
            <img src="{{ asset('images/site/logo.png') }}" alt="Joy Foundation Nepal" class="size-logo object-contain">
        </a>

        <nav class="hidden items-center gap-1 rounded-full border border-white/15 bg-white/10 p-2 lg:flex">
            @foreach ($nav as $item)
                @php
                    $children = $item['children'] ?? [];
                    $active = collect([$item, ...$children])->contains(fn (array $link): bool => request()->is(trim($link['href'], '/') ?: '/'));
                @endphp

                <div class="group relative">
                    <a
                        href="{{ $item['href'] }}"
                        class="flex items-center gap-1.5 rounded-full px-5 py-2 text-sm font-medium transition {{ $active ? 'bg-primary text-white' : 'text-white/75 hover:text-white' }}"
                    >
                        {{ $item['label'] }}
                        @if ($children)
                            <svg class="size-3 transition group-hover:rotate-180" viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2.5 4.5 6 8l3.5-3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @endif
                    </a>

                    @if ($children)
                        <div class="invisible absolute top-full left-0 min-w-48 pt-2 opacity-0 transition group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100">
                            <div class="rounded-2xl border border-white/15 bg-navy p-2 shadow-xl">
                                @foreach ($children as $child)
                                    <a href="{{ $child['href'] }}" class="block rounded-xl px-4 py-2 text-sm font-medium whitespace-nowrap text-white/80 transition hover:bg-white/10 hover:text-white">
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
                class="flex size-10 items-center justify-center rounded-full border border-white/25 bg-white/10 text-white"
                aria-label="Search"
            >
                <img src="{{ asset('images/site/icon-search.svg') }}" alt="" class="size-4">
            </button>

            <x-site.cta-button href="#" variant="solid" size="sm">
                Donate
                <span class="flex size-7 items-center justify-center rounded-full bg-white/20">
                    <img src="{{ asset('images/site/icon-donate-header.svg') }}" alt="" class="size-3.5">
                </span>
            </x-site.cta-button>
        </div>
    </div>
</header>
