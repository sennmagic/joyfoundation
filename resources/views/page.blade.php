<x-layouts.app :title="$page->meta_title ?? $page->title" :description="$page->meta_description">
    <main>
        @foreach ($sections as $section)
            @include("sections.{$section->type}", ['content' => $section->data])
        @endforeach
    </main>
</x-layouts.app>
