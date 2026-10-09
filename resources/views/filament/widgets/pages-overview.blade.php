{{-- Filament's compiled CSS has no utility classes for custom views, so layout is inline. --}}
<x-filament-widgets::widget>
    <div style="display:grid;gap:1.5rem;grid-template-columns:repeat(auto-fit,minmax(20rem,1fr))">
        <x-filament::section style="grid-column:span 2">
            <x-slot name="heading">Your pages</x-slot>
            <x-slot name="description">Each page is built from sections. Open a page to add, reorder or edit its sections.</x-slot>

            @foreach ($this->getPages() as $page)
                <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:.75rem 0;border-top:1px solid rgba(128,128,128,.25)">
                    <div>
                        <div style="font-weight:600;display:flex;align-items:center;gap:.5rem">
                            {{ $page->title }}
                            @unless ($page->is_published)
                                <x-filament::badge color="gray">Draft</x-filament::badge>
                            @endunless
                        </div>
                        <div style="font-size:.875rem;opacity:.65">/{{ $page->slug === 'home' ? '' : $page->slug }} · {{ $page->sections_count }} {{ Str::plural('section', $page->sections_count) }}</div>
                    </div>

                    <div style="display:flex;gap:.5rem;flex-shrink:0">
                        <x-filament::button :href="route('page', $page->slug)" tag="a" size="sm" color="gray" icon="heroicon-o-arrow-top-right-on-square" target="_blank">
                            View
                        </x-filament::button>
                        <x-filament::button :href="\App\Filament\Resources\Pages\PageResource::getUrl('edit', ['record' => $page])" tag="a" size="sm" icon="heroicon-o-pencil-square">
                            Edit
                        </x-filament::button>
                    </div>
                </div>
            @endforeach

            <div style="padding-top:1rem;border-top:1px solid rgba(128,128,128,.25)">
                <x-filament::button :href="\App\Filament\Resources\Pages\PageResource::getUrl('create')" tag="a" size="sm" color="gray" icon="heroicon-o-plus">
                    New page
                </x-filament::button>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">How it works</x-slot>

            <ol style="list-style:decimal;padding-left:1.25rem;display:grid;gap:.75rem;font-size:.875rem;line-height:1.5">
                <li><strong>Pick a page</strong> from the list or the sidebar.</li>
                <li><strong>Add section</strong> and choose a layout from the picture list. Each layout is one block of the page.</li>
                <li><strong>Fill in the text and images</strong>, then save. Drag rows to reorder, or switch a row off to hide it.</li>
                <li><strong>Site Settings</strong> holds the menu and social links shared by every page.</li>
            </ol>
        </x-filament::section>
    </div>
</x-filament-widgets::widget>
