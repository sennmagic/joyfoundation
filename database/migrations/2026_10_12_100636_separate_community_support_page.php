<?php

declare(strict_types=1);

use App\Models\Page;
use App\Models\Section;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {
            $programs = Page::query()->where('slug', 'programs')->first();

            if ($programs === null) {
                return;
            }

            $sources = $programs->sections()->where('type', 'feature_list')->get();

            if ($sources->isEmpty()) {
                return;
            }

            $support = Page::query()->firstOrCreate(
                ['slug' => 'long-term-community-support'],
                ['title' => 'Long-Term Community Support', 'is_published' => $programs->is_published],
            );
            $hero = $programs->sections()->where('type', 'hero')->first();

            if ($hero !== null && ! $support->sections()->where('type', 'hero')->exists()) {
                $support->sections()->create([
                    'type' => 'hero',
                    'data' => [...$hero->data, 'heading_prefix' => '', 'heading_highlight' => $support->title, 'heading_suffix' => ''],
                    'is_visible' => $hero->is_visible,
                ]);
            }

            $order = (int) $support->sections()->max('sort_order');

            foreach ($sources as $source) {
                $source->update(['page_id' => $support->id, 'sort_order' => ++$order]);
            }

            foreach (Section::query()->where('type', 'feature_list')->get() as $section) {
                $data = $section->data;

                if (in_array((int) ($data['source_section_id'] ?? 0), $sources->modelKeys(), true)
                    && ($data['view_all_href'] ?? '') === '/programs') {
                    $section->update(['data' => [...$data, 'view_all_href' => '/long-term-community-support']]);
                }
            }

            foreach (Section::query()->whereNull('page_id')->where('type', 'nav')->get() as $nav) {
                $items = $nav->data['items'] ?? [];
                $links = array_column($items, 'href');
                $index = array_search('/programs', $links, true);

                if ($index !== false && ! in_array('/long-term-community-support', $links, true)) {
                    array_splice($items, $index + 1, 0, [[
                        'label' => 'Community Support', 'href' => '/long-term-community-support',
                    ]]);
                    $nav->update(['data' => [...$nav->data, 'items' => $items]]);
                }
            }
        });
    }

    public function down(): void
    {
        // Keep the page and its content: editors may have changed either since the move.
    }
};
