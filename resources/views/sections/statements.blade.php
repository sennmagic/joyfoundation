<section class="bg-cream px-gutter py-section-y sm:px-gutter-lg sm:py-section-y-lg">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col gap-stack-sm xl:flex-row xl:items-end xl:justify-between">
            <div>
                <x-site.section-eyebrow :label="$content['eyebrow']" />

                <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy sm:text-heading">
                    {{ $content['heading_prefix'] }}<span class="font-light text-primary">{{ $content['heading_highlight'] }}</span>{{ $content['heading_suffix'] }}
                </h2>
            </div>

            <p class="max-w-xs text-description text-body">
                {{ $content['intro'] }}
            </p>
        </div>

        <div class="mt-stack-lg space-y-stack-lg">
            <div class="rise-in relative flex flex-col gap-stack-xs xl:flex-row xl:gap-stack-md">
                <span class="pointer-events-none absolute -top-10 left-0 hidden font-heading text-9xl leading-none text-primary/25 xl:block" aria-hidden="true">&ldquo;</span>

                <p class="w-48 shrink-0 font-heading text-sm font-medium text-primary">— {{ $content['mission']['label'] }}</p>
                <x-site.rich-text
                    :segments="$content['mission']['segments']"
                    class="relative flex-1 font-heading text-2xl leading-snug font-medium text-navy sm:text-value"
                />
            </div>

            <div class="rise-in flex flex-col gap-stack-xs xl:flex-row xl:gap-stack-md">
                <p class="w-48 shrink-0 font-heading text-sm font-medium text-primary">— {{ $content['vision']['label'] }}</p>
                <x-site.rich-text
                    :segments="$content['vision']['segments']"
                    class="max-w-copy flex-1 font-heading text-2xl leading-snug font-medium text-navy sm:text-value"
                />
            </div>

            <div class="rise-in flex flex-col gap-stack-xs xl:flex-row xl:gap-stack-md">
                <p class="w-48 shrink-0 font-heading text-sm font-medium text-primary">{{ $content['guides']['label'] }}</p>
                <x-site.rich-text
                    :segments="$content['guides']['segments']"
                    class="flex-1 font-heading text-2xl leading-snug text-navy sm:text-value"
                />
            </div>
        </div>
    </div>
</section>
