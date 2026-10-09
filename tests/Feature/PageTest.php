<?php

declare(strict_types=1);

use App\Models\Page;
use App\Models\Section;
use Database\Seeders\SectionSeeder;

beforeEach(function (): void {
    $this->seed(SectionSeeder::class);
});

/** The section that owns the homepage's items of this layout (content lives on its own page). */
function sourceOf(string $type): Section
{
    $home = Page::query()->where('slug', 'home')->firstOrFail()->sections()->where('type', $type)->firstOrFail();

    return Section::query()->findOrFail($home->data['source_section_id']);
}

test('the homepage renders every seeded section from the database', function (): void {
    $this->get('/')
        ->assertOk()
        ->assertSee('Share The')
        ->assertSee('Upcoming Events')
        ->assertSee('images/site/hero-bg.png')
        ->assertSee('Contact Us');
});

test('each page renders only its own sections', function (): void {
    $this->get('/about')->assertOk()->assertSee('Our Story')->assertDontSee('Upcoming Events');
    $this->get('/')->assertSee('Upcoming Events');
});

test('edits made to a section are rendered', function (): void {
    $hero = Page::query()->where('slug', 'home')->firstOrFail()->sections()->where('type', 'hero')->firstOrFail();
    $hero->update(['data' => [...$hero->data, 'heading_highlight' => 'Kindness']]);

    $this->get('/')->assertSee('Kindness')->assertDontSee('>Joy<', false);
});

test('hidden sections are not rendered', function (): void {
    Section::query()->where('type', 'text_band')->update(['is_visible' => false]);

    $this->get('/')->assertOk()->assertDontSee('sm:text-band');
});

test('sections render in sort order', function (): void {
    Section::query()->where('type', 'image_text')->update(['sort_order' => 100]);

    $html = $this->get('/')->assertOk()->getContent();

    expect(strpos($html, 'Upcoming Events'))->toBeLessThan(strpos($html, 'Our Story'));
});

test('nav items can carry a dropdown of child links', function (): void {
    $nav = Section::query()->where('type', 'nav')->firstOrFail();
    $items = $nav->data['items'];
    $items[1]['children'] = [['label' => 'Our Team', 'href' => '/team']];
    $nav->update(['data' => ['items' => $items]]);

    $this->get('/')->assertOk()->assertSee('Our Team')->assertSee('href="/team"', false);
});

test('the image text badge is optional', function (): void {
    $this->get('/')->assertSee('EST.')->assertSee('1996');

    $block = Page::query()->where('slug', 'home')->firstOrFail()->sections()->where('type', 'image_text')->firstOrFail();
    $block->update(['data' => [...$block->data, 'badge_value' => '']]);

    $this->get('/')->assertDontSee('EST.');
});

test('the homepage shows the items ticked on the source page', function (): void {
    $source = sourceOf('card_grid');
    $items = $source->data['items'];
    $items[1]['featured'] = false;
    $source->update(['data' => [...$source->data, 'items' => $items]]);

    $this->get('/')->assertSee('Free Medical')->assertDontSee('Eye Care Initiatives');
    $this->get('/programs')->assertSee('Eye Care Initiatives');

    $source->delete();
    $this->get('/')->assertOk()->assertDontSee('Free Medical');
});

test('list sections show six items and a view all link when there are more', function (): void {
    $home = Page::query()->where('slug', 'home')->firstOrFail()->sections()->where('type', 'card_grid')->firstOrFail();
    $source = sourceOf('card_grid');
    $items = array_map(fn (int $i): array => [...$source->data['items'][0], 'title' => "Program number {$i}", 'details' => ''], range(1, 8));
    $source->update(['data' => [...$source->data, 'items' => $items]]);

    $home->update(['data' => [...$home->data, 'view_all_label' => 'All programs']]);
    $this->get('/')->assertSee('Program number 6')->assertDontSee('Program number 7')->assertSee('All programs')->assertSee('href="/programs"', false);

    $home->update(['data' => [...$home->data, 'limit' => 8]]);
    $this->get('/')->assertSee('Program number 8')->assertDontSee('All programs');

    $home->update(['data' => [...$home->data, 'limit' => null]]);
    $this->get('/')->assertSee('Program number 8');

    $home->update(['data' => [...$home->data, 'limit' => 'abc', 'source_section_id' => null, 'items' => []]]);
    $this->get('/')->assertOk();
});

test('listing pages show every item with no view all link', function (): void {
    $grid = Page::query()->where('slug', 'programs')->firstOrFail()->sections()->where('type', 'card_grid')->firstOrFail();
    $items = array_map(fn (int $i): array => [...$grid->data['items'][0], 'title' => "Program number {$i}", 'details' => ''], range(1, 8));
    $grid->update(['data' => [...$grid->data, 'items' => $items]]);

    $this->get('/programs')->assertSee('Program number 8')->assertDontSee('View all');
});

test('items with details get a shared detail page', function (): void {
    $this->get('/')->assertSee('href="http://localhost/home/free-medical-health-camps"', false);
    $this->get('/home/free-medical-health-camps')->assertOk()
        ->assertSee('Free Medical &amp; Health Camps', false)
        ->assertSee('Back to Home')
        ->assertSee('<p>Full details for this item', false);
    $this->get('/programs/free-medical-health-camps')->assertOk();
});

test('duplicate and unsluggable titles still get distinct detail pages', function (): void {
    $grid = sourceOf('card_grid');
    $base = $grid->data['items'][0];
    $grid->update(['data' => [...$grid->data, 'items' => [
        [...$base, 'title' => 'Same Name', 'details' => '<p>FIRST BODY</p>'],
        [...$base, 'title' => 'Same-Name', 'details' => '<p>SECOND BODY</p>'],
        [...$base, 'title' => '!!!', 'details' => '<p>THIRD BODY</p>'],
    ]]]);

    $this->get('/')->assertOk()
        ->assertSee('/home/same-name"', false)->assertSee('/home/same-name-2"', false)->assertSee('/home/item"', false);
    $this->get('/home/same-name')->assertSee('FIRST BODY')->assertDontSee('SECOND BODY');
    $this->get('/home/same-name-2')->assertSee('SECOND BODY');
    $this->get('/home/item')->assertSee('THIRD BODY');
});

test('details are rendered as safe html', function (): void {
    $grid = sourceOf('card_grid');
    $items = $grid->data['items'];
    $items[0]['details'] = '<h2 onclick="x()">Heading</h2><p style="color:red">Text <a href=javascript:alert(1)>bad</a> <a onmouseover="alert(1)" href = "https://example.org" target="_blank">good</a> <a href="JaVaScRiPt:alert(1)">bad2</a></p><img src=x onerror="alert(1)"><script>alert(1)</script><div><span>kept text</span></div>';
    $items[1]['details'] = '<p>&nbsp;</p>';
    $grid->update(['data' => [...$grid->data, 'items' => $items]]);

    $this->get('/home/free-medical-health-camps')->assertOk()
        ->assertSee('<h2>Heading</h2><p>Text <a>bad</a> <a href="https://example.org">good</a> <a>bad2</a></p>kept text', false)
        ->assertDontSee('onerror', false)->assertDontSee('onmouseover', false)->assertDontSee('alert(1)', false)->assertDontSee('javascript:', false)->assertDontSee('target=', false);

    $this->get('/')->assertDontSee('/home/eye-care-initiatives"', false);
    $this->get('/home/eye-care-initiatives')->assertNotFound();
});

test('items without details have no link and no detail page', function (): void {
    $grid = sourceOf('card_grid');
    $items = array_map(fn (array $item): array => [...$item, 'details' => '<p></p>'], $grid->data['items']);
    $grid->update(['data' => [...$grid->data, 'items' => $items]]);

    $this->get('/')->assertDontSee('/home/free-medical-health-camps');
    $this->get('/home/free-medical-health-camps')->assertNotFound();
    $this->get('/home/no-such-item')->assertNotFound();
    $this->get('/about/free-medical-health-camps')->assertNotFound();
});

test('unpublished and unknown pages return 404', function (): void {
    Page::query()->where('slug', 'about')->update(['is_published' => false]);

    $this->get('/about')->assertNotFound();
    $this->get('/missing')->assertNotFound();
});

test('hiding or deleting the nav and social settings keeps the pages up', function (): void {
    Section::query()->whereIn('type', ['nav', 'social'])->update(['is_visible' => false]);
    $this->get('/')->assertOk()->assertDontSee('Contact Us')->assertDontSee('icon-facebook.svg');

    Section::query()->whereIn('type', ['nav', 'social'])->delete();
    $this->get('/')->assertOk();
});

test('the seeder does not duplicate rows when run twice', function (): void {
    $this->seed(SectionSeeder::class);

    expect(Page::query()->count())->toBe(5)->and(Section::query()->count())->toBe(25);
});
