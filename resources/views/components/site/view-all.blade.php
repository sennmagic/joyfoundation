@props(['total', 'shown', 'label' => 'View all', 'href' => ''])

@if ($href !== '' && $total > $shown)
    <div {{ $attributes->merge(['class' => 'mt-stack-lg text-center']) }}>
        <x-site.cta-button :href="$href" variant="solid">
            {{ $label ?: 'View all' }}
        </x-site.cta-button>
    </div>
@endif
