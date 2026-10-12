<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Section;
use Database\Seeders\SectionSeeder;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageViewAllTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_homepage_section_has_one_view_all_button_even_when_all_items_fit(): void
    {
        $this->seed(SectionSeeder::class);
        $home = Page::query()->where('slug', 'home')->firstOrFail();
        $sections = $home->sections()->visible()->get();
        $destinations = [];

        foreach (['/', '/home'] as $url) {
            $document = new DOMDocument;
            @$document->loadHTML($this->get($url)->assertOk()->getContent());
            $xpath = new DOMXPath($document);
            $renderedSections = $xpath->query('//main/section');
            $this->assertCount(count(Section::TYPES), $renderedSections);

            foreach ($renderedSections as $index => $element) {
                $buttons = $xpath->query('.//a[@data-view-all]', $element);
                $this->assertCount(1, $buttons, $sections[$index]->type.' must have exactly one View all button');
                $href = $buttons->item(0)->getAttribute('href');
                $expected = $sections[$index]->data['view_all_href'] ?? '/about';
                $this->assertSame($expected, $href);
                $destinations[$href] = true;
            }
        }

        foreach (array_keys($destinations) as $href) {
            $this->get($href)->assertOk();
        }
    }

    public function test_view_all_buttons_never_appear_on_inside_pages_or_home_item_details(): void
    {
        $this->seed(SectionSeeder::class);

        foreach (Page::query()->where('slug', '!=', 'home')->with('sections')->get() as $page) {
            foreach ($page->sections as $section) {
                $section->update(['data' => [
                    ...$section->data, 'limit' => 1, 'view_all_href' => '/about', 'view_all_label' => 'View all',
                ]]);
            }

            $this->get('/'.$page->slug)->assertOk()->assertDontSee('data-view-all', false)->assertDontSee('View all');
        }

        $this->get('/home/free-medical-health-camps')->assertOk()->assertDontSee('data-view-all', false);
    }
}
