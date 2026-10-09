<section class="px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
    <div class="mx-auto grid max-w-7xl items-center gap-stack-lg lg:grid-cols-2">
        <div class="scroll-scale relative mx-auto w-full max-w-96">
            <x-site.image
                :src="asset('images/site/'.$content['photo'])"
                alt=""
                shape="rectangle"
            />

            <div class="absolute -right-8 -bottom-10 w-48 -rotate-6">
                <x-site.image
                    :src="asset('images/site/'.$content['photo_polaroid'])"
                    alt=""
                    shape="square"
                    class="border-4 border-white"
                />
            </div>

            @if (($content['badge_value'] ?? '') !== '')
                <div class="absolute -top-10 -left-9 -rotate-8 rounded-2xl bg-primary px-5 py-4 text-white shadow-cta-lg">
                    <p class="text-xs tracking-widest text-white/80">{{ $content['badge_label'] ?? '' }}</p>
                    <p class="font-heading text-3xl font-bold">{{ $content['badge_value'] }}</p>
                </div>
            @endif
        </div>

        <div class="rise-in max-w-xl">
            <x-site.section-eyebrow :label="$content['eyebrow']" />

            <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy sm:text-heading">
                {{ $content['heading_prefix'] }}<span class="font-light text-primary">{{ $content['heading_highlight'] }}</span>.
            </h2>

            <div class="mt-stack-sm space-y-stack-sm">
                @foreach ($content['paragraphs'] as $paragraph)
                    <p class="text-description text-body">{{ $paragraph }}</p>
                @endforeach
            </div>

            <x-site.cta-button href="#" variant="solid" size="sm" class="mt-stack-md">
                {{ $content['cta_label'] }}
                <span class="flex size-9 items-center justify-center rounded-full bg-white/20">
                    <img src="{{ asset('images/site/icon-arrow-right.svg') }}" alt="" class="size-4">
                </span>
            </x-site.cta-button>
        </div>
    </div>
</section>
