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
            ['label' => 'Contact Us', 'href' => '/contact'],
        ]]]);

        $this->page('Home', 'home', Section::TYPES, config('home.seo'));
        $this->page('About Us', 'about', ['hero', 'image_text', 'statements']);
        $this->page('Programs', 'programs', ['hero', 'card_grid', 'feature_list']);
        $this->page('Contact Us', 'contact', ['hero', 'text_band']);
    }

    /**
     * @param  list<string>  $types
     * @param  array{title?: string, description?: string}  $seo
     */
    private function page(string $title, string $slug, array $types, array $seo = []): void
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

            $page->sections()->create(['type' => $type, 'data' => $data]);
        }
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

        return $data;
    }
}
