<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Copies config/home.php into pages + sections so every block is editable in the admin.
     * The homepage gets every block; About, Programs and Contact get a hero plus starter blocks.
     */
    public function run(): void
    {
        if (Page::query()->exists()) {
            return;
        }

        Section::query()->create(['type' => 'social', 'data' => ['items' => $this->block('social')]]);
        Section::query()->create(['type' => 'nav', 'data' => ['items' => [
            ['label' => 'Home', 'href' => '/'],
            ['label' => 'About Us', 'href' => '/about'],
            ['label' => 'Programs', 'href' => '/programs'],
            ['label' => 'Events', 'href' => '/events'],
            ['label' => 'Contact Us', 'href' => '/contact'],
        ]]]);

        $this->page('About Us', 'about', ['hero', 'image_text', 'statements', 'feature_cards', 'article_grid']);
        $this->page('Programs', 'programs', ['hero', 'card_grid', 'feature_list']);
        $this->page('Events', 'events', ['hero', 'events']);
        $this->page('Contact Us', 'contact', ['hero', 'text_band']);
        $home = $this->page('Home', 'home', Section::TYPES, config('home.seo'));

        foreach ($home->sections()->whereIn('type', Section::LIST_TYPES)->get() as $section) {
            $source = Section::query()->where('type', $section->type)->where('page_id', '!=', $home->id)->with('page')->firstOrFail();

            $section->update(['data' => [
                ...$section->data,
                Section::ITEM_KEYS[$section->type] => [],
                'source_section_id' => $source->id,
                'view_all_href' => '/'.$source->page->slug,
            ]]);
        }
    }

    /**
     * @param  list<string>  $types
     * @param  array{title?: string, description?: string}  $seo
     */
    private function page(string $title, string $slug, array $types, array $seo = []): Page
    {
        $page = Page::query()->create([
            'title' => $title,
            'slug' => $slug,
            'meta_title' => $seo['title'] ?? null,
            'meta_description' => $seo['description'] ?? null,
        ]);

        foreach ($types as $type) {
            $data = $this->block($type);

            if ($type === 'hero' && $slug !== 'home') {
                $data = [...$data, 'heading_prefix' => '', 'heading_highlight' => $title, 'heading_suffix' => ''];
            }

            if (in_array($type, Section::LIST_TYPES, true)) {
                $key = Section::ITEM_KEYS[$type];
                $data = $slug === 'home'
                    ? [...$data, 'limit' => 6]
                    : [...$data, 'limit' => null, 'view_all_href' => '', $key => array_map(fn (array $item): array => [...$item, 'featured' => true], $data[$key])];
            }

            $page->sections()->create(['type' => $type, 'data' => $data]);
        }

        return $page;
    }

    /** @return array<mixed> */
    private function block(string $type): array
    {
        $data = config('home.'.(Section::CONFIG_KEYS[$type] ?? $type));

        array_walk_recursive($data, function (mixed &$value): void {
            if (is_string($value)) {
                $value = str_replace('images/site/', '', $value);
            }
        });

        return match ($type) {
            'card_grid' => [...$data, 'items' => array_map(
                fn (array $item): array => [...$item, 'details' => '<p>Full details for this item go here. Edit the item in the admin to replace this text with the complete story, photos and outcomes.</p>'],
                $data['items'],
            )],
            'events' => [...$data, 'view_all_label' => $data['cta_label']],
            'image_text' => [...$data, 'badge_label' => 'EST.', 'badge_value' => $data['established_year']],
            'statements' => [...$data, 'blocks' => [
                ['label' => '— '.$data['mission']['label'], 'segments' => $data['mission']['segments']],
                ['label' => '— '.$data['vision']['label'], 'segments' => $data['vision']['segments']],
                $data['guides'],
            ]],
            default => $data,
        };
    }
}
