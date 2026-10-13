<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\RelationManagers\SectionsRelationManager;
use App\Models\Page;
use App\Models\Section;
use App\Models\User;
use Database\Seeders\SectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class ItemDetailPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SectionSeeder::class);
    }

    public function test_all_programs_community_initiatives_and_achievements_link_to_individual_pages_before_full_copy_is_added(): void
    {
        $home = Page::query()->where('slug', 'home')->firstOrFail();
        foreach (['card_grid', 'feature_list', 'feature_cards'] as $type) {
            $source = Section::query()->where('type', $type)->where('page_id', '!=', $home->id)->firstOrFail();
            $key = Section::ITEM_KEYS[$type];
            $source->update(['data' => [...$source->data, $key => array_map(fn (array $item): array => [...$item, 'details' => ''], $source->items())]]);
            foreach ($source->items() as $item) {
                $slug = Str::slug(Section::itemTitle($item));
                foreach ([$home, $source->page] as $page) {
                    $url = route('page.item', [$page->slug, $slug]);
                    $this->assertSame($url, Section::itemUrl($page, $item));
                    $this->get('/'.$page->slug)->assertOk()->assertSee('href="'.$url.'"', false);
                    $response = $this->get($url)->assertOk()->assertSee(Section::itemTitle($item))->assertSee($item['description'])->assertDontSee('data-view-all', false);
                    foreach (['location', 'impact', 'stat_value', 'stat_label'] as $field) {
                        if (! empty($item[$field])) {
                            $response->assertSee($item[$field]);
                        }
                    }
                }
            }
        }
    }

    public function test_full_copy_is_editable_in_admin_without_replacing_the_brief(): void
    {
        $this->actingAs(User::factory()->create());
        $home = Page::query()->where('slug', 'home')->firstOrFail();
        foreach (['card_grid', 'feature_list', 'feature_cards'] as $type) {
            $source = Section::query()->where('type', $type)->where('page_id', '!=', $home->id)->firstOrFail();
            $key = Section::ITEM_KEYS[$type];
            $items = $source->data[$key];
            $brief = $items[0]['description'];
            $editor = Livewire::test(SectionsRelationManager::class, ['ownerRecord' => $source->page, 'pageClass' => EditPage::class])
                ->mountTableAction('edit', $source);
            $rowKey = array_key_first($editor->get('mountedActions.0.data.data.'.$key));
            $editor->set('mountedActions.0.data.data.'.$key.'.'.$rowKey.'.details', '<h2>Full project story</h2><p>Detailed outcomes supplied by the editor.</p><ul><li>Verified project milestone</li></ul>')
                ->callMountedTableAction()->assertHasNoTableActionErrors();
            $this->assertSame($brief, $source->refresh()->data[$key][0]['description']);
            $this->get('/')->assertDontSee('Detailed outcomes supplied by the editor.');
            $response = $this->get(Section::itemUrl($source->page, $source->items()[0]))->assertOk()
                ->assertSee('<h2>Full project story</h2>', false)->assertSee('Detailed outcomes supplied by the editor.')
                ->assertSee($brief);
            $document = new \DOMDocument;
            @$document->loadHTML($response->getContent());
            $xpath = new \DOMXPath($document);
            $this->assertSame(1, $xpath->query('//*[@data-item-details]//ul/li[normalize-space(.)="Verified project milestone"]')->length);
        }
    }

    public function test_hidden_or_unpublished_sources_do_not_expose_items_through_homepage_aliases(): void
    {
        $home = Page::query()->where('slug', 'home')->firstOrFail();
        $source = Section::query()->where('type', 'feature_list')->where('page_id', '!=', $home->id)->firstOrFail();
        $item = $source->items()[0];
        $source->update(['is_visible' => false]);
        $this->get('/')->assertDontSee(Section::itemTitle($item));
        $this->get('/home/'.Str::slug(Section::itemTitle($item)))->assertNotFound();
        $source->update(['is_visible' => true]);
        $source->page->update(['is_published' => false]);
        $this->get('/')->assertDontSee(Section::itemTitle($item));
        $this->get('/home/'.Str::slug(Section::itemTitle($item)))->assertNotFound();
        $this->get('/'.$source->page->slug.'/'.Str::slug(Section::itemTitle($item)))->assertNotFound();
    }

    public function test_empty_story_and_event_details_still_do_not_publish_empty_pages(): void
    {
        foreach (['article_grid', 'events'] as $type) {
            $source = Section::query()->where('type', $type)->whereHas('page', fn ($query) => $query->where('slug', '!=', 'home'))->firstOrFail();
            $item = $source->items()[0];
            $this->assertSame('', Section::itemUrl($source->page, $item));
            $this->get('/'.$source->page->slug.'/'.Str::slug(Section::itemTitle($item)))->assertNotFound();
        }
    }
}
