<?php

declare(strict_types=1);

use App\Models\Page;
use App\Models\Section;
use Database\Seeders\SectionSeeder;

beforeEach(function (): void {
    $this->seed(SectionSeeder::class);
});

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

    expect(Page::query()->count())->toBe(4)->and(Section::query()->count())->toBe(21);
});
