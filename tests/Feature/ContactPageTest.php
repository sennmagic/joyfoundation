<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\Sections\Pages\EditSection;
use App\Filament\Resources\Sections\Pages\ListSections;
use App\Models\Page;
use App\Models\Section;
use App\Models\User;
use Database\Seeders\SectionSeeder;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SectionSeeder::class);
    }

    public function test_every_public_page_has_one_footer_outside_main_and_contact_has_its_own_content(): void
    {
        foreach (['/', '/about', '/programs', '/long-term-community-support', '/events', '/contact', '/home/free-medical-health-camps'] as $url) {
            $xpath = $this->xpath($this->get($url)->assertOk()->getContent());
            $this->assertSame(1, $xpath->query('//footer[@data-site-footer]')->length, $url);
            $this->assertSame(0, $xpath->query('//main//footer')->length);
            $this->assertSame(0, $xpath->query('//footer//a[@href="#" or @href=""]')->length);
            $this->assertSame(1, $xpath->query('//footer//nav//a[@href="/programs"]')->length);
        }

        $this->get('/contact')->assertSee('Send a message')->assertSee('JOY Foundation Nepal')
            ->assertSee('Contact details will be available here soon.')->assertDontSee('data-view-all', false);
        $this->assertNotContains('contact', Section::TYPES);
    }

    public function test_admin_edit_updates_contact_page_and_footer_and_can_clear_all_details(): void
    {
        $this->actingAs(User::factory()->create());
        $contact = Section::query()->whereNull('page_id')->where('type', 'contact')->firstOrFail();
        Livewire::test(ListSections::class)->assertSee('Contact Details');
        $details = [
            'intro' => 'Come say hello to our team.', 'email' => 'hello@example.org',
            'phone' => '+977 (1) 555-0123', 'address' => "Test office\nKathmandu",
            'hours' => 'Sunday to Friday, 10am–5pm', 'map_url' => 'https://example.org/directions',
        ];

        Livewire::test(EditSection::class, ['record' => $contact->getRouteKey()])
            ->fillForm(['data' => $details])->call('save')->assertHasNoFormErrors();
        $this->assertSame($details, $contact->refresh()->data);

        foreach (['/', '/contact', '/about', '/home/free-medical-health-camps'] as $url) {
            $html = $this->get($url)->assertOk()->assertSee('Test office')->assertSee('Sunday to Friday')->getContent();
            $xpath = $this->xpath($html);
            $this->assertSame(1, $xpath->query('//footer//a[@href="mailto:hello@example.org"]')->length);
            $this->assertSame(1, $xpath->query('//footer//a[@href="tel:+97715550123"]')->length);
            $this->assertSame(1, $xpath->query('//footer//a[@href="https://example.org/directions"]')->length);
            if ($url === '/contact') {
                $this->assertSame(1, $xpath->query('//main//a[@href="mailto:hello@example.org"]')->length);
                $this->assertStringContainsString($details['intro'], $html);
            }
        }

        Livewire::test(EditSection::class, ['record' => $contact->getRouteKey()])
            ->fillForm(['data' => array_fill_keys(array_keys($details), '')])->call('save')->assertHasNoFormErrors();
        $this->get('/contact')->assertOk()->assertDontSee('hello@example.org')->assertDontSee('tel:', false)
            ->assertSee('Contact details will be available here soon.');
        $this->get('/admin')->assertOk()->assertDontSee('data-site-footer', false);
    }

    public function test_contact_form_is_left_of_the_details_and_both_views_pair_values_with_matching_icons(): void
    {
        $contact = Section::query()->whereNull('page_id')->where('type', 'contact')->firstOrFail();
        $contact->update(['data' => [
            'email' => str_repeat('team', 12).'@example.org', 'phone' => '+977 (1) 555-0123',
            'address' => "Community office\nKathmandu, Nepal", 'hours' => "Sunday to Friday\n10am to 5pm",
            'map_url' => 'https://example.org/directions',
        ]]);
        $xpath = $this->xpath($this->get('/contact')->assertOk()->getContent());
        $this->assertSame(1, $xpath->query('//*[@data-contact-layout and contains(@class,"xl:grid-cols-12")]/div[@id="contact-form" and contains(@class,"xl:col-span-7")]')->length);
        $this->assertSame(1, $xpath->query('//*[@id="contact-form"]/following-sibling::aside[@id="contact-information" and contains(@class,"xl:col-span-5")]')->length);
        $formHeading = $xpath->query('//*[@id="enquiry-heading"]')->item(0);
        $detailsHeading = $xpath->query('//*[@id="contact-information-heading"]')->item(0);
        $this->assertSame($formHeading->getAttribute('class'), $detailsHeading->getAttribute('class'));
        $this->assertStringNotContainsString('sr-only', $formHeading->getAttribute('class'));
        $this->assertStringNotContainsString('xl:border-l', $xpath->query('//*[@id="contact-information"]')->item(0)->getAttribute('class'));

        foreach (['//*[@id="contact-information"]', '//footer'] as $container) {
            $this->assertSame(5, $xpath->query($container.'//*[@data-contact-icon]')->length);
            foreach (['Email' => 'envelope', 'Phone' => 'phone', 'Address' => 'map-pin', 'Office hours' => 'clock', 'Directions' => 'map'] as $label => $icon) {
                $rows = $xpath->query($container.'//dl/div[dt[normalize-space(.)="'.$label.'"]]');
                $this->assertSame(1, $rows->length, $container.' '.$label);
                $this->assertSame(1, $xpath->query('.//dd/*[local-name()="svg" and @data-contact-icon="'.$icon.'" and @aria-hidden="true" and @focusable="false" and @stroke-width="1.5"]', $rows->item(0))->length);
                $this->assertSame(1, $xpath->query('.//dd[contains(@class,"items-start")]//*[contains(@class,"min-w-0") and contains(@class,"overflow-wrap:anywhere")]', $rows->item(0))->length);
            }
        }
        $this->assertSame(5, $xpath->query('//footer//dl//dt[contains(@class,"sr-only")]')->length);
        $this->assertSame(5, $xpath->query('//*[@id="contact-information"]//dt[contains(@class,"sr-only")]')->length);
        $this->assertSame($xpath->query('//*[@id="contact-information"]//dl')->item(0)->getAttribute('class'), $xpath->query('//footer//dl')->item(0)->getAttribute('class'));
        $this->assertSame(1, $xpath->query('//footer//*[@data-footer-contact and contains(@class,"xl:col-span-5")]')->length);

        $contact->update(['data' => ['phone' => '+977 1 5550123']]);
        $xpath = $this->xpath($this->get('/contact')->assertOk()->getContent());
        $this->assertSame(2, $xpath->query('//*[@data-contact-icon="phone"]')->length);
        $this->assertSame(2, $xpath->query('//*[@data-contact-icon]')->length);
    }

    public function test_empty_contact_details_keep_the_right_column_without_fake_values_or_orphan_icons(): void
    {
        $xpath = $this->xpath($this->get('/contact')->assertOk()->getContent());
        $this->assertSame(1, $xpath->query('//*[@id="contact-form"]/following-sibling::aside[@id="contact-information"]')->length);
        $this->assertSame(0, $xpath->query('//*[@data-contact-icon]')->length);
        $this->assertSame(1, $xpath->query('//*[@id="contact-information"]//p[contains(.,"Contact details will be available here soon.")]')->length);
    }

    #[DataProvider('invalidDetails')]
    public function test_admin_rejects_invalid_contact_values(string $field, string $value): void
    {
        $this->actingAs(User::factory()->create());
        $contact = Section::query()->whereNull('page_id')->where('type', 'contact')->firstOrFail();
        Livewire::test(EditSection::class, ['record' => $contact->getRouteKey()])
            ->fillForm(['data' => [$field => $value]])->call('save')->assertHasFormErrors(['data.'.$field]);
        $this->assertEmpty($contact->refresh()->data);
    }

    /** @return array<string, array{string, string}> */
    public static function invalidDetails(): array
    {
        return [
            'invalid email' => ['email', 'not-an-email'],
            'email headers' => ['email', "hello@example.org\r\nBcc: other@example.org"],
            'script URL' => ['map_url', 'javascript:alert(1)'],
            'FTP URL' => ['map_url', 'ftp://example.org'],
            'invalid phone' => ['phone', 'javascript:123'],
            'long address' => ['address', str_repeat('a', 1001)],
        ];
    }

    public function test_hidden_deleted_and_missing_contact_settings_never_leak_or_reappear_on_get(): void
    {
        $contact = Section::query()->whereNull('page_id')->where('type', 'contact')->firstOrFail();
        $contact->update(['data' => ['email' => 'private@example.org', 'intro' => 'Hidden intro'], 'is_visible' => false]);
        foreach (['/', '/contact'] as $url) {
            $this->get($url)->assertOk()->assertDontSee('private@example.org')->assertDontSee('Hidden intro');
        }
        $contact->delete();
        $count = Section::query()->count();
        $this->get('/contact')->assertOk()->assertSee('Contact details will be available here soon.');
        $this->get('/')->assertOk();
        $this->assertSame($count, Section::query()->count());
    }

    public function test_stored_markup_is_escaped_and_unsafe_contact_and_social_links_are_omitted(): void
    {
        Section::query()->where('type', 'contact')->update(['data' => json_encode([
            'intro' => '<script>alert("intro")</script>', 'address' => '<img src=x onerror=alert(1)>',
            'email' => 'bad@example.org?bcc=other@example.org', 'phone' => 'javascript:123',
            'map_url' => 'javascript:alert(1)', 'hours' => ['unexpected'],
        ], JSON_THROW_ON_ERROR)]);
        Section::query()->where('type', 'social')->update(['data' => json_encode(['items' => [
            ['label' => 'Unsafe', 'url' => 'javascript:alert(1)', 'icon' => 'icon-facebook.svg'],
            ['label' => 'Valid social', 'url' => 'https://example.org/joy', 'icon' => 'icon-facebook.svg'],
        ]], JSON_THROW_ON_ERROR)]);

        $html = $this->get('/contact')->assertOk()->assertSee('<script>alert("intro")</script>')
            ->assertSee('<img src=x onerror=alert(1)>')->getContent();
        $xpath = $this->xpath($html);
        $this->assertSame(0, $xpath->query('//*[@id="contact-details"]//script | //footer//script')->length);
        $this->assertSame(0, $xpath->query('//*[@id="contact-details"]//a[starts-with(@href,"javascript:")] | //footer//a[starts-with(@href,"javascript:")]')->length);
        $this->assertSame(0, $xpath->query('//footer//a[starts-with(@href,"mailto:") or starts-with(@href,"tel:")]')->length);
        $this->assertSame(1, $xpath->query('//footer//a[@href="https://example.org/joy"]')->length);
    }

    public function test_contact_migration_and_seeder_preserve_existing_edits_and_are_idempotent(): void
    {
        Section::query()->whereNull('page_id')->where('type', 'contact')->delete();
        $before = Section::all()->toArray();
        $migration = require database_path('migrations/2026_10_12_145828_add_contact_details_setting.php');
        $migration->up();
        $contact = Section::query()->whereNull('page_id')->where('type', 'contact')->firstOrFail();
        $this->assertSame([], $contact->data);
        $this->assertSame($before, Section::query()->where('id', '!=', $contact->id)->get()->toArray());
        $contact->update(['data' => ['email' => 'keep@example.org'], 'is_visible' => false]);
        $snapshot = [Page::all()->toArray(), Section::all()->toArray()];
        $migration->up();
        $this->seed(SectionSeeder::class);
        $this->assertSame($snapshot, [Page::all()->toArray(), Section::all()->toArray()]);
    }

    public function test_custom_contact_page_content_and_publication_rules_are_preserved(): void
    {
        $page = Page::query()->where('slug', 'contact')->firstOrFail();
        $page->sections()->where('type', 'text_band')->firstOrFail()->update(['data' => ['text' => 'An edited welcome from our team.']]);
        $this->get('/contact')->assertOk()->assertSee('An edited welcome from our team.')->assertSee('Send a message');
        $page->update(['is_published' => false]);
        $this->get('/contact')->assertNotFound();
    }

    public function test_footer_respects_hidden_navigation_and_social_settings(): void
    {
        Section::query()->whereIn('type', ['nav', 'social'])->update(['is_visible' => false]);
        $xpath = $this->xpath($this->get('/')->assertOk()->getContent());
        $this->assertSame(0, $xpath->query('//footer//nav')->length);
        $this->assertSame(0, $xpath->query('//footer//*[@aria-label="Social links"]')->length);
    }

    public function test_guests_cannot_edit_contact_settings(): void
    {
        $contact = Section::query()->whereNull('page_id')->where('type', 'contact')->firstOrFail();
        $this->get('/admin/sections/'.$contact->id.'/edit')->assertRedirect('/admin/login');
    }

    private function xpath(string $html): DOMXPath
    {
        $document = new DOMDocument;
        @$document->loadHTML($html);

        return new DOMXPath($document);
    }
}
