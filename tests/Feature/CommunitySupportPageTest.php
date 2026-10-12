<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Section;
use Database\Seeders\SectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CommunitySupportPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_programs_and_community_support_have_separate_pages_and_homepage_links(): void
    {
        $this->seed(SectionSeeder::class);

        $this->get('/programs')->assertOk()->assertSee('Free Medical')->assertDontSee('Long-term initiatives.');
        $this->get('/long-term-community-support')->assertOk()
            ->assertSee('Long-Term Community Support')->assertSee('Long-term initiatives.')->assertDontSee('Free Medical');

        $home = Page::query()->where('slug', 'home')->firstOrFail();

        foreach (['card_grid' => 'programs', 'feature_list' => 'long-term-community-support'] as $type => $slug) {
            $list = $home->sections()->where('type', $type)->firstOrFail();
            $source = Section::query()->findOrFail($list->data['source_section_id']);
            $this->assertSame($slug, $source->page->slug);
            $this->assertSame('/'.$slug, $list->data['view_all_href']);
            $list->update(['data' => [...$list->data, 'limit' => 1]]);
        }

        $html = $this->get('/')->assertOk()->getContent();
        $document = new \DOMDocument;
        @$document->loadHTML($html);
        $xpath = new \DOMXPath($document);
        foreach (['/programs', '/long-term-community-support'] as $href) {
            $this->assertSame(1, $xpath->query('//nav//a[@href="'.$href.'"]')->length);
            $this->assertSame(1, $xpath->query('//main//section//a[not(ancestor::header) and @href="'.$href.'"]')->length);
        }

        $support = Page::query()->where('slug', 'long-term-community-support')->firstOrFail()->sections()->where('type', 'feature_list')->firstOrFail();
        $items = $support->data['initiatives'];
        $items[0]['details'] = '<p>Community-only details.</p>';
        $support->update(['data' => [...$support->data, 'initiatives' => $items]]);
        $slug = Str::slug(Section::itemTitle($items[0]));
        $this->get('/long-term-community-support/'.$slug)->assertOk()->assertSee('Community-only details.');
        $this->get('/programs/'.$slug)->assertNotFound();
    }

    public function test_existing_content_is_moved_without_losing_edits_or_homepage_references(): void
    {
        $programs = Page::query()->create(['title' => 'Programs', 'slug' => 'programs', 'is_published' => false]);
        $programs->sections()->create(['type' => 'hero', 'data' => [...config('home.hero'), 'background' => 'custom-hero.jpg']]);
        $source = $programs->sections()->create([
            'type' => 'feature_list', 'is_visible' => false,
            'data' => [...config('home.long_term_community'), 'intro' => 'Edited by the admin', 'limit' => null],
        ]);
        $originalData = $source->data;
        $home = Page::query()->create(['title' => 'Home', 'slug' => 'home']);
        $preview = $home->sections()->create(['type' => 'feature_list', 'data' => [
            'source_section_id' => $source->id, 'view_all_href' => '/programs', 'limit' => 3,
        ]]);
        $customLink = ['label' => 'Our work', 'href' => '/programs', 'children' => [['label' => 'Team', 'href' => '/team']]];
        $nav = Section::query()->create(['type' => 'nav', 'data' => ['items' => [$customLink]]]);
        $migration = require database_path('migrations/2026_10_12_100636_separate_community_support_page.php');

        $migration->up();

        $support = Page::query()->where('slug', 'long-term-community-support')->firstOrFail();
        $this->assertFalse($support->is_published);
        $this->assertSame($support->id, $source->refresh()->page_id);
        $this->assertFalse($source->is_visible);
        $this->assertSame($originalData, $source->data);
        $this->assertSame('custom-hero.jpg', $support->sections()->where('type', 'hero')->firstOrFail()->data['background']);
        $this->assertSame($source->id, $preview->refresh()->data['source_section_id']);
        $this->assertSame('/long-term-community-support', $preview->data['view_all_href']);
        $this->assertSame(3, $preview->data['limit']);
        $this->assertSame($customLink, $nav->refresh()->data['items'][0]);
        $this->assertSame('/long-term-community-support', $nav->data['items'][1]['href']);
        $this->assertFalse($programs->sections()->where('type', 'feature_list')->exists());

        $snapshot = [Page::all()->toArray(), Section::all()->toArray()];
        $migration->up();
        $this->assertSame($snapshot, [Page::all()->toArray(), Section::all()->toArray()]);
    }

    public function test_an_existing_community_page_and_navigation_entry_are_preserved(): void
    {
        $programs = Page::query()->create(['title' => 'Programs', 'slug' => 'programs']);
        $source = $programs->sections()->create(['type' => 'feature_list', 'data' => config('home.long_term_community')]);
        $support = Page::query()->create(['title' => 'Edited community title', 'slug' => 'long-term-community-support', 'is_published' => false]);
        $hero = $support->sections()->create(['type' => 'hero', 'data' => config('home.hero')]);
        $nav = Section::query()->create(['type' => 'nav', 'data' => ['items' => [
            ['label' => 'Community', 'href' => '/long-term-community-support'],
            ['label' => 'Programs', 'href' => '/programs'],
        ]]]);
        $originalNav = $nav->data;
        $migration = require database_path('migrations/2026_10_12_100636_separate_community_support_page.php');

        $migration->up();

        $this->assertSame($support->id, $source->refresh()->page_id);
        $this->assertSame('Edited community title', $support->refresh()->title);
        $this->assertFalse($support->is_published);
        $this->assertSame([$hero->id, $source->id], $support->sections()->pluck('id')->all());
        $this->assertSame($originalNav, $nav->refresh()->data);
    }
}
