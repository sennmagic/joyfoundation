<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\Enquiries\EnquiryResource;
use App\Filament\Resources\Enquiries\Pages\ManageEnquiries;
use App\Models\Enquiry;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\SectionSeeder;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SectionSeeder::class);
        Cache::flush();
    }

    /** @return array<string, string> */
    private function enquiry(): array
    {
        return ['name' => 'Sam Test', 'email' => 'sam@example.org', 'topic' => 'volunteering', 'message' => 'I would like to volunteer with your team.'];
    }

    public function test_contact_page_has_a_plain_accessible_form_and_preserves_edited_headings(): void
    {
        $page = Page::query()->where('slug', 'contact')->firstOrFail();
        $page->sections()->where('type', 'hero')->firstOrFail()->update(['data' => [
            'heading_prefix' => 'Write to ', 'heading_highlight' => 'our team', 'heading_suffix' => '.', 'background' => 'story-photo.png',
        ]]);
        $html = $this->get('/contact')->assertOk()->assertSee('Write to ')->assertSee('our team')
            ->assertDontSee('images/site/story-photo.png')->assertDontSee('Good things start')
            ->assertDontSee('A question, an introduction, an idea')->getContent();
        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $this->assertSame(1, $xpath->query('//form[@method="POST" and @action="'.route('contact.submit').'"]')->length);
        $this->assertSame(1, $xpath->query('//form//input[@name="_token"]')->length);
        foreach (['name', 'email', 'topic', 'message'] as $field) {
            $this->assertSame(1, $xpath->query('//form//label[@for="contact-'.$field.'"]')->length);
            $this->assertSame(1, $xpath->query('//form//*[@name="'.$field.'" and @required]')->length);
        }
        $this->assertSame(1, $xpath->query('//form//button[@type="submit"]')->length);
        $this->assertSame(0, $xpath->query('//*[@data-view-all]')->length);
        $this->assertSame(0, $xpath->query('//*[@id="contact-details"]//img | //*[@id="contact-details"]//figure')->length);
        $this->assertSame(0, $xpath->query('//*[@id="contact-form" and (contains(@class,"rounded-") or contains(@class,"shadow") or contains(@class,"border-t-primary"))]')->length);
        $this->assertSame('story-photo.png', $page->sections()->where('type', 'hero')->firstOrFail()->data['background']);
        $page->sections()->where('type', 'hero')->update(['is_visible' => false]);
        $this->get('/contact')->assertOk()->assertSee('Send a message')->assertSee('Main navigation')->assertDontSee('Write to ');
    }

    public function test_valid_submission_is_saved_once_and_redirects_to_an_honest_confirmation(): void
    {
        $this->post('/contact', [...$this->enquiry(), 'name' => '  Sam Test  ', 'is_read' => true, 'website' => ''])
            ->assertRedirect(route('page', ['slug' => 'contact']).'#contact-form')->assertSessionHas('contact_sent');
        $this->assertDatabaseCount('enquiries', 1);
        $this->assertDatabaseHas('enquiries', [...$this->enquiry(), 'is_read' => false]);
        $this->get('/contact')->assertOk()->assertSee('Your message has been received.')->assertDontSee('value="Sam Test"', false);
        $this->get('/contact')->assertOk()->assertDontSee('Your message has been received.');
        $this->assertDatabaseCount('enquiries', 1);
    }

    #[DataProvider('invalidInputs')]
    public function test_invalid_submissions_are_not_saved_and_fields_remain_safe_to_render(string $field, mixed $value): void
    {
        $this->post('/contact', [$field => $value] + $this->enquiry())
            ->assertRedirect(route('page', ['slug' => 'contact']).'#contact-form')->assertSessionHasErrors($field);
        $this->assertDatabaseCount('enquiries', 0);
        $this->get('/contact')->assertOk()->assertSee('Please check your message.');
    }

    public function test_every_topic_accepts_unicode_names_and_the_message_length_boundaries(): void
    {
        foreach (array_keys(Enquiry::TOPICS) as $topic) {
            $data = [...$this->enquiry(), 'topic' => $topic, 'name' => 'सीता', 'message' => str_repeat('a', $topic === 'general' ? 5000 : 10)];
            $this->post('/contact', $data)->assertSessionHasNoErrors()->assertSessionHas('contact_sent');
            $this->assertDatabaseHas('enquiries', $data);
        }
        $this->assertDatabaseCount('enquiries', 4);
    }

    /** @return array<string, array{string, mixed}> */
    public static function invalidInputs(): array
    {
        return [
            'blank name' => ['name', '   '], 'long name' => ['name', str_repeat('a', 121)],
            'bad email' => ['email', 'no-email'], 'email headers' => ['email', "sam@example.org\r\nBcc: x@example.org"],
            'array name' => ['name', ['invalid']], 'array email' => ['email', ['invalid']],
            'invalid topic' => ['topic', 'unrecognised'], 'array topic' => ['topic', ['invalid']],
            'short message' => ['message', 'Hi'], 'long message' => ['message', str_repeat('a', 5001)],
            'array message' => ['message', ['invalid']], 'honeypot' => ['website', 'spam.example.org'],
        ];
    }

    public function test_validation_preserves_text_escapes_markup_and_marks_the_invalid_field(): void
    {
        $message = '<script>alert("contact")</script> Can we help?';
        $this->post('/contact', [...$this->enquiry(), 'email' => '', 'message' => $message])->assertSessionHasErrors('email');
        $this->get('/contact')->assertOk()->assertSee('value="Sam Test"', false)->assertSee($message)
            ->assertDontSee($message, false)->assertSee('aria-invalid="true"', false)->assertSee('contact-email-error');
    }

    public function test_submission_requires_a_published_contact_page(): void
    {
        Page::query()->where('slug', 'contact')->update(['is_published' => false]);
        $this->post('/contact', $this->enquiry())->assertNotFound();
        Page::query()->where('slug', 'contact')->delete();
        $this->post('/contact', $this->enquiry())->assertNotFound();
        $this->assertDatabaseCount('enquiries', 0);
    }

    public function test_throttle_preserves_the_message_and_does_not_block_another_visitor(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/contact', $this->enquiry())->assertSessionHasNoErrors();
        }
        $this->post('/contact', $this->enquiry())->assertRedirect(route('page', ['slug' => 'contact']).'#contact-form')
            ->assertSessionHasErrors('submission')->assertSessionHasInput('message', $this->enquiry()['message']);
        $this->assertDatabaseCount('enquiries', 5);
        $this->get('/contact')->assertOk()->assertSee('Please wait one minute');
        $this->withServerVariables(['REMOTE_ADDR' => '192.0.2.10'])->post('/contact', $this->enquiry())->assertSessionHas('contact_sent');
        $this->assertDatabaseCount('enquiries', 6);
        $this->travel(61)->seconds();
        $this->post('/contact', $this->enquiry())->assertSessionHas('contact_sent');
        $this->assertDatabaseCount('enquiries', 7);
    }

    public function test_csrf_is_required_outside_the_testing_bypass(): void
    {
        $this->app['env'] = 'local';
        $this->post('/contact', $this->enquiry())->assertStatus(419);
        $this->assertDatabaseCount('enquiries', 0);
    }

    public function test_admin_can_read_search_mark_and_delete_messages_but_visitors_cannot_access_the_inbox(): void
    {
        $enquiry = Enquiry::factory()->create([...$this->enquiry(), 'message' => '<script>alert("inbox")</script> Volunteer enquiry.']);
        $this->get(EnquiryResource::getUrl('index'))->assertRedirect('/admin/login');
        $this->get('/contact')->assertDontSee('sam@example.org');
        $this->actingAs(User::factory()->create());
        $this->assertFalse(EnquiryResource::canCreate());
        $this->assertFalse(EnquiryResource::canEdit($enquiry));
        Livewire::test(ManageEnquiries::class)->assertCanSeeTableRecords([$enquiry])
            ->searchTable('sam@example.org')->assertCanSeeTableRecords([$enquiry])
            ->mountTableAction('view', $enquiry)->assertMountedActionModalSee('Volunteer enquiry.')
            ->assertMountedActionModalDontSeeHtml('<script>alert("inbox")</script>');
        Livewire::test(ManageEnquiries::class)->call('updateTableColumnState', 'is_read', (string) $enquiry->getKey(), true);
        $this->assertTrue($enquiry->refresh()->is_read);
        Livewire::test(ManageEnquiries::class)->callTableAction('delete', $enquiry);
        $this->assertDatabaseCount('enquiries', 0);
    }
}
