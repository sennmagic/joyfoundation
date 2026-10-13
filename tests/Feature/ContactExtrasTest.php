<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\Sections\Pages\EditSection;
use App\Models\Section;
use App\Models\User;
use Database\Seeders\SectionSeeder;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ContactExtrasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SectionSeeder::class);
    }

    public function test_admin_can_update_and_clear_whatsapp_and_the_contact_map(): void
    {
        $this->actingAs(User::factory()->create());
        $contact = Section::query()->where('type', 'contact')->firstOrFail();
        $values = ['whatsapp_number' => '+9779812345678', 'whatsapp_message' => 'Hello JOY! Volunteering & support?', 'map_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!2d85.32!3d27.71'];
        Livewire::test(EditSection::class, ['record' => $contact->getRouteKey()])
            ->fillForm(['data' => $values])->call('save')->assertHasNoFormErrors();
        foreach ($values as $key => $value) {
            $this->assertSame($value, $contact->refresh()->data[$key] ?? null);
        }
        foreach (['/', '/contact', '/about', '/programs/free-medical-health-camps'] as $url) {
            $xpath = $this->xpath($this->get($url)->assertOk()->getContent());
            $button = $xpath->query('//a[@data-whatsapp]');
            $this->assertSame(1, $button->length);
            $this->assertSame('https://wa.me/9779812345678?text='.rawurlencode($values['whatsapp_message']), $button->item(0)->getAttribute('href'));
            $this->assertSame('_blank', $button->item(0)->getAttribute('target'));
            $this->assertStringContainsString('noopener', $button->item(0)->getAttribute('rel'));
            $this->assertStringContainsString('WhatsApp', $button->item(0)->getAttribute('aria-label'));
            $this->assertStringContainsString('fixed', $button->item(0)->getAttribute('class'));
            $map = $xpath->query('//iframe[@data-contact-map]');
            $this->assertSame($url === '/contact' ? 1 : 0, $map->length);
            if ($url === '/contact') {
                $this->assertSame($values['map_embed_url'], $map->item(0)->getAttribute('src'));
                $this->assertSame('lazy', $map->item(0)->getAttribute('loading'));
                $this->assertNotSame('', $map->item(0)->getAttribute('title'));
                $this->assertSame(1, $xpath->query('//*[@id="contact-form"]/following-sibling::aside[@id="contact-information"]')->length);
            }
        }
        $this->get('/admin')->assertDontSee('data-whatsapp', false)->assertDontSee('data-contact-map', false);
        Livewire::test(EditSection::class, ['record' => $contact->getRouteKey()])
            ->fillForm(['data' => array_fill_keys(array_keys($values), '')])->call('save')->assertHasNoFormErrors();
        $this->get('/contact')->assertDontSee('data-whatsapp', false)->assertDontSee('data-contact-map', false);
    }

    #[DataProvider('unsafeValues')]
    public function test_invalid_settings_are_rejected_in_admin_and_not_rendered_if_stored(string $field, mixed $value): void
    {
        $this->actingAs(User::factory()->create());
        $contact = Section::query()->where('type', 'contact')->firstOrFail();
        Livewire::test(EditSection::class, ['record' => $contact->getRouteKey()])
            ->fillForm(['data' => [$field => $value]])->call('save')->assertHasFormErrors(['data.'.$field]);
        $contact->update(['data' => [$field => $value]]);
        $this->get('/contact')->assertOk()->assertDontSee('data-whatsapp', false)->assertDontSee('data-contact-map', false);
    }

    /** @return array<string, array{string, string}> */
    public static function unsafeValues(): array
    {
        return [
            'script map' => ['map_embed_url', 'javascript:alert(1)'],
            'untrusted host' => ['map_embed_url', 'https://example.org/maps/embed?pb=abc'],
            'lookalike host' => ['map_embed_url', 'https://www.google.com.evil.example/maps/embed?pb=abc'],
            'user info URL' => ['map_embed_url', 'https://www.google.com@evil.example/maps/embed?pb=abc'],
            'raw iframe' => ['map_embed_url', '<iframe src="https://www.google.com/maps/embed?pb=abc"></iframe>'],
            'ordinary share URL' => ['map_embed_url', 'https://maps.app.goo.gl/example'],
            'plain HTTP' => ['map_embed_url', 'http://www.google.com/maps/embed?pb=abc'],
            'empty map payload' => ['map_embed_url', 'https://www.google.com/maps/embed?pb='],
            'oversized map URL' => ['map_embed_url', 'https://www.google.com/maps/embed?pb='.str_repeat('a', 4096)],
            'non-map Google page' => ['map_embed_url', 'https://www.google.com/search?q=abc'],
            'local phone' => ['whatsapp_number', '0981234567'],
            'script phone' => ['whatsapp_number', 'javascript:123456789'],
            'long phone' => ['whatsapp_number', str_repeat('9', 16)],
        ];
    }

    public function test_controls_work_independently_and_opening_messages_cannot_change_the_link(): void
    {
        $contact = Section::query()->where('type', 'contact')->firstOrFail();
        $number = '9779812345678';
        $message = '<script>alert("hello")</script> & नमस्ते JOY';
        $contact->update(['data' => ['whatsapp_number' => $number, 'whatsapp_message' => $message]]);
        $response = $this->get('/contact')->assertOk()->assertDontSee('data-contact-map', false);
        $xpath = $this->xpath($response->getContent());
        $this->assertSame('https://wa.me/'.$number.'?text='.rawurlencode($message), $xpath->query('//a[@data-whatsapp]')->item(0)->getAttribute('href'));
        foreach ([['bad'], str_repeat('x', 501)] as $invalidMessage) {
            $contact->update(['data' => ['whatsapp_number' => $number, 'whatsapp_message' => $invalidMessage]]);
            $xpath = $this->xpath($this->get('/contact')->assertOk()->getContent());
            $this->assertSame('https://wa.me/'.$number, $xpath->query('//a[@data-whatsapp]')->item(0)->getAttribute('href'));
        }
        $contact->update(['data' => ['map_embed_url' => 'https://www.google.com/maps/embed?pb=abc']]);
        $this->get('/contact')->assertOk()->assertDontSee('data-whatsapp', false)->assertSee('data-contact-map', false);
        $this->actingAs(User::factory()->create());
        Livewire::test(EditSection::class, ['record' => $contact->getRouteKey()])
            ->fillForm(['data' => ['whatsapp_message' => str_repeat('x', 501)]])->call('save')->assertHasFormErrors(['data.whatsapp_message']);
    }

    public function test_missing_hidden_deleted_or_malformed_settings_leave_no_fake_controls(): void
    {
        $contact = Section::query()->where('type', 'contact')->firstOrFail();
        $this->get('/contact')->assertDontSee('data-whatsapp', false)->assertDontSee('data-contact-map', false);
        $contact->update(['data' => ['whatsapp_number' => ['bad'], 'map_embed_url' => ['bad']]]);
        $this->get('/contact')->assertOk()->assertDontSee('data-whatsapp', false)->assertDontSee('data-contact-map', false);
        $contact->update(['data' => ['whatsapp_number' => '9779812345678', 'map_embed_url' => 'https://www.google.com/maps/embed?pb=abc'], 'is_visible' => false]);
        $this->get('/contact')->assertDontSee('data-whatsapp', false)->assertDontSee('data-contact-map', false);
        $contact->delete();
        $this->get('/contact')->assertOk()->assertDontSee('data-whatsapp', false)->assertDontSee('data-contact-map', false);
    }

    private function xpath(string $html): DOMXPath
    {
        $document = new DOMDocument;
        @$document->loadHTML($html);

        return new DOMXPath($document);
    }
}
