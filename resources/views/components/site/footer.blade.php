@props(['site' => []])

@php
    $navigation = array_filter($site['nav']['items'] ?? [], function (array $item): bool {
        $href = $item['href'] ?? '';
        return is_string($href) && (preg_match('#^/(?!/)[^\\\\\s]*$#', $href) || (filter_var($href, FILTER_VALIDATE_URL) && preg_match('#^https?://#i', $href)));
    });
    $social = array_filter($site['social']['items'] ?? [], function (array $item): bool {
        $url = $item['url'] ?? '';
        return is_string($url) && filter_var($url, FILTER_VALIDATE_URL) && preg_match('#^https?://#i', $url);
    });
@endphp

<footer data-site-footer class="border-t border-primary/20 bg-cream px-gutter pt-16 pb-8 sm:px-gutter-lg sm:pt-20">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col gap-stack-md border-b border-primary/20 pb-stack-md xl:flex-row xl:items-center xl:justify-between">
            <div>
                <p class="text-xs font-semibold tracking-widest text-primary-ink uppercase">Little acts. Lasting change.</p>
                <h2 class="mt-4 max-w-2xl font-heading text-4xl leading-tight font-medium tracking-tight sm:text-heading">
                    Together, we can<br><span class="font-medium text-primary-ink">share more joy.</span>
                </h2>
            </div>
            @unless (request()->routeIs('page') && request()->route('slug') === 'contact')
                <a href="{{ route('page', ['slug' => 'contact']) }}" class="group inline-flex min-h-14 w-fit shrink-0 items-center gap-6 rounded-full border border-primary/40 bg-white py-3 pr-3 pl-6 font-semibold text-primary-ink transition-colors hover:bg-primary-ink hover:text-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary-ink">
                    Let’s talk
                    <span class="flex size-11 items-center justify-center rounded-full bg-primary-ink text-white transition-colors group-hover:bg-white group-hover:text-primary-ink" aria-hidden="true">↗</span>
                </a>
            @endunless
        </div>

        <div class="grid items-start gap-stack-md py-stack-md xl:grid-cols-12 xl:gap-stack-lg">
            <div class="min-w-0 xl:col-span-4">
                <a href="{{ route('home') }}" class="inline-block rounded-xl focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary-ink">
                    <img src="{{ asset('images/site/logo.png') }}" alt="JOY Foundation Nepal — home" class="size-logo object-contain" loading="lazy" width="134" height="134">
                </a>
                <p class="mt-4 max-w-sm text-base leading-relaxed text-body">Showing up for people. Standing beside communities. Sharing the joy of giving.</p>
                @if ($social)
                    <div class="mt-stack-sm flex flex-wrap gap-x-5 gap-y-1" role="group" aria-label="Social links">
                        @foreach ($social as $item)
                            <a href="{{ $item['url'] }}" class="inline-flex min-h-11 items-center rounded-sm py-2 text-sm font-semibold text-primary-ink underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary-ink">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="min-w-0 xl:col-span-3">
                @if ($navigation)
                    <nav aria-label="Footer navigation">
                        <h3 class="mb-stack-sm font-heading text-xl font-semibold">Explore JOY</h3>
                        <ul class="space-y-1">
                            @foreach ($navigation as $item)
                                <li><a href="{{ $item['href'] }}" class="inline-flex min-h-11 items-center rounded-sm py-2 text-sm font-medium text-body underline-offset-4 hover:text-primary-ink hover:underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary-ink">{{ $item['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </nav>
                @endif
            </div>

            <div data-footer-contact class="min-w-0 xl:col-span-5">
                <h3 class="mb-stack-sm font-heading text-xl font-semibold">Contact</h3>
                <x-site.contact-details :contact="$site['contact'] ?? []" />
            </div>
        </div>

        <div class="flex flex-col gap-2 border-t border-primary/20 pt-6 text-xs leading-relaxed text-muted sm:flex-row sm:items-center sm:justify-between">
            <p>© {{ now()->year }} JOY Foundation Nepal. All rights reserved.</p>
            <p>Small beginnings. Shared possibilities.</p>
        </div>
    </div>
</footer>
