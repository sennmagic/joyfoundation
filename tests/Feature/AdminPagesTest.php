<?php

declare(strict_types=1);

use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Filament\Resources\Pages\RelationManagers\SectionsRelationManager;
use App\Filament\Resources\Sections\Pages\EditSection;
use App\Filament\Resources\Sections\Pages\ListSections;
use App\Models\Page;
use App\Models\Section;
use App\Models\User;
use Database\Seeders\SectionSeeder;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Image;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->seed(SectionSeeder::class);
    $this->actingAs(User::factory()->create());
});

function sectionsOf(Page $page): Testable
{
    return Livewire::test(SectionsRelationManager::class, ['ownerRecord' => $page, 'pageClass' => EditPage::class]);
}

test('the pages list and the site settings list render', function (): void {
    Livewire::test(ListPages::class)->assertOk()->assertSee('About Us')->assertSee('/about');
    Livewire::test(ListSections::class)->assertOk()->assertSee('Navigation')->assertSee('Social Links');
});

test('the sidebar lists every page under Pages', function (): void {
    Page::query()->where('slug', 'contact')->update(['is_published' => false]);

    $this->get('/admin/pages')
        ->assertOk()
        ->assertSeeInOrder(['About Us', 'Contact Us', 'Home', 'Programs', 'New page'])
        ->assertSee('/admin/pages/create')
        ->assertSee('Draft');
});

test('a page can be created and is served at its slug', function (): void {
    Livewire::test(CreatePage::class)
        ->fillForm(['title' => 'Our Team', 'slug' => 'team'])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->get('/team')->assertOk()->assertSee('<title>Our Team</title>', false);
});

test('renaming a page keeps its slug', function (): void {
    $home = Page::query()->where('slug', 'home')->firstOrFail();

    Livewire::test(EditPage::class, ['record' => $home->getRouteKey()])
        ->fillForm(['title' => 'Welcome'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($home->refresh()->slug)->toBe('home');
    $this->get('/')->assertOk()->assertSee('<title>', false);
});

test('slugs the router cannot serve are rejected', function (string $slug): void {
    Livewire::test(CreatePage::class)
        ->fillForm(['title' => 'Team', 'slug' => $slug])
        ->call('create')
        ->assertHasFormErrors(['slug']);
})->with(['our_team', 'Team', 'up', 'admin']);

test('every section type has a preview image for the picker', function (): void {
    foreach (Section::TYPES as $type) {
        expect(public_path("images/admin/sections/{$type}.png"))->toBeFile();
    }

    $picker = 'mountedActionSchema0';

    sectionsOf(Page::query()->firstOrFail())
        ->mountTableAction('create')
        ->assertSchemaComponentExists('type', $picker, fn (Select $select): bool => str_contains($select->getOptions()['hero'], '<img src="http://localhost/images/admin/sections/hero.png"'))
        ->assertSchemaComponentExists('preview', $picker, fn (Image $image): bool => $image->isHidden())
        ->setTableActionData(['type' => 'hero'])
        ->assertSchemaComponentExists('preview', $picker, fn (Image $image): bool => $image->isVisible() && str_ends_with($image->getUrl(), '/images/admin/sections/hero.png'));
});

test('a section can be added to a page from the relation manager', function (): void {
    $about = Page::query()->where('slug', 'about')->firstOrFail();

    sectionsOf($about)
        ->mountTableAction('create')
        ->setTableActionData(['type' => 'text_band'])
        ->setTableActionData(['data' => ['text' => 'A line only on the About page']])
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();

    expect($about->sections()->where('type', 'text_band')->value('sort_order'))->toBe(4);
    $this->get('/about')->assertSee('A line only on the About page');
    $this->get('/')->assertDontSee('A line only on the About page');
});

test('saving every section from its edit form leaves the pages unchanged', function (): void {
    foreach (Page::all() as $page) {
        $before = $this->get("/{$page->slug}")->getContent();

        foreach ($page->sections as $section) {
            sectionsOf($page)->mountTableAction('edit', $section)->callMountedTableAction()->assertHasNoTableActionErrors();
        }

        expect($this->get("/{$page->slug}")->getContent())->toBe($before, "page {$page->slug} changed");
    }
});

test('site settings can be edited', function (): void {
    $nav = Section::query()->where('type', 'nav')->firstOrFail();

    Livewire::test(EditSection::class, ['record' => $nav->getRouteKey()])
        ->fillForm(['data' => ['items' => [['label' => 'Start', 'href' => '/']]]])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->get('/')->assertSee('Start')->assertDontSee('Contact Us');
});

test('page sections are not reachable through the site settings resource', function (): void {
    $hero = Section::query()->where('type', 'hero')->firstOrFail();

    expect(fn () => Livewire::test(EditSection::class, ['record' => $hero->getRouteKey()]))
        ->toThrow(ModelNotFoundException::class);
});
