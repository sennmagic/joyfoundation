<section class="bg-white py-section-y sm:py-section-y-lg">
    <div class="mx-auto max-w-7xl px-gutter sm:px-gutter-lg">
        <div class="flex flex-col gap-stack-sm xl:flex-row xl:items-start xl:justify-between">
            <div>
                <x-site.section-eyebrow :label="$content['eyebrow']" />

                <h2 class="mt-stack-sm font-heading text-4xl font-semibold tracking-tight text-navy">
                    {{ $content['heading_prefix'] }}<span class="font-light text-primary">{{ $content['heading_highlight'] }}</span>.
                </h2>
            </div>

            <div class="w-full max-w-80 text-description text-body">
                @foreach ($content['note'] as $line)
                    <p>{{ $line }}</p>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mt-stack-lg">
        <x-site.partners-marquee :items="$content['items']" />
    </div>
</section>
