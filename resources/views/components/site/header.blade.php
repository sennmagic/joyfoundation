@props(['nav' => []])

<header class="absolute inset-x-0 top-0 z-10">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-gutter py-7 sm:px-gutter-lg">
        <a href="{{ url('/') }}" class="block">
            <img src="{{ asset('images/site/logo.png') }}" alt="Joy Foundation Nepal" class="size-logo object-contain">
        </a>

        <nav class="hidden items-center gap-1 rounded-full border border-white/15 bg-white/10 p-2 lg:flex">
            @foreach ($nav as $item)
                @php
                    $active = $item['href'] === '/' && request()->is('/');
                @endphp

                <a
                    href="{{ $item['href'] }}"
                    class="rounded-full px-5 py-2 text-sm font-medium transition {{ $active ? 'bg-primary text-white' : 'text-white/75 hover:text-white' }}"
                >
                    {{ $item['label'] }}
                </a>
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
