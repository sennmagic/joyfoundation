<?php

declare(strict_types=1);

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/** config/home.php still holds the pre-generic shapes, so it doubles as the legacy fixture. */
function legacyMigration(): Migration
{
    return require database_path('migrations/2026_10_09_073332_convert_legacy_section_data.php');
}

beforeEach(function (): void {
    $page = Page::query()->create(['title' => 'Home', 'slug' => 'home']);
    $this->statements = $page->sections()->create(['type' => 'statements', 'data' => config('home.mission_section')]);
    $this->imageText = $page->sections()->create(['type' => 'image_text', 'data' => config('home.story')]);
    $this->events = $page->sections()->create(['type' => 'events', 'data' => config('home.events')]);
});

test('legacy statements and image text rows are upgraded to the generic keys', function (): void {
    legacyMigration()->up();

    expect($this->statements->refresh()->data['blocks'])->toHaveCount(3)
        ->and($this->statements->data['blocks'][0]['label'])->toBe('— Mission')
        ->and($this->imageText->refresh()->data['badge_value'])->toBe('1996')
        ->and($this->imageText->data['badge_label'])->toBe('EST.');

    legacyMigration()->up();
    expect($this->statements->refresh()->data['blocks'])->toHaveCount(3)
        ->and($this->events->refresh()->data['view_all_label'])->toBe('View All Events')
        ->and($this->events->data)->toHaveKey('limit')
        ->and($this->events->data['limit'])->toBeNull();

    $this->get('/')->assertOk()->assertSee('— Mission')->assertSee('1996');
});

test('rows without blocks or badge still render, and a zero badge is shown', function (): void {
    $this->get('/')->assertOk()->assertDontSee('EST.');

    $this->imageText->update(['data' => [...$this->imageText->data, 'badge_label' => 'No.', 'badge_value' => '0']]);

    $this->get('/')->assertOk()->assertSee('No.');
});
