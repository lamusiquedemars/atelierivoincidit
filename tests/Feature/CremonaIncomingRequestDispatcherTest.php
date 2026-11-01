<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Maracuja\SiteCore\Contact\Data\ContactRequestData;
use Maracuja\SiteCore\Contact\Models\Inquiry;
use Maracuja\SiteCore\CremonaConnector\Services\CremonaLeadReceiver;
use Tests\TestCase;

class CremonaIncomingRequestDispatcherTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_contact_and_first_party_attribution_to_cremona(): void
    {
        $this->configureCremona();
        Http::fake(['cremona.test/*' => Http::response([], 201)]);
        $submission = Inquiry::query()->create([
            'name' => 'Ivo', 'email' => 'info@atelierivoincidit.fr', 'message' => 'Je souhaite essayer un archet.',
        ]);
        $contact = ContactRequestData::fromArray(['name' => 'Ivo', 'email' => 'info@atelierivoincidit.fr', 'message' => 'Je souhaite essayer un archet.', 'metadata' => ['attribution' => ['utm_campaign' => 'atelier-archets', 'gclid' => 'click-1']]]);
        app(CremonaLeadReceiver::class)->dispatch($submission, $contact);

        Http::assertSent(function ($request) use ($submission): bool {
            return $request->url() === 'https://cremona.test/api/v1/incoming-requests'
                && $request->hasHeader('Authorization', 'Bearer secret-token')
                && $request->hasHeader('Idempotency-Key', 'maracuja-site:contact:'.$submission->getKey())
                && $request['attribution']['utm_campaign'] === 'atelier-archets'
                && $request['attribution']['gclid'] === 'click-1';
        });
    }

    public function test_it_is_inactive_without_its_server_configuration(): void
    {
        Http::fake();
        $submission = Inquiry::query()->create([
            'name' => 'Ivo', 'email' => 'info@atelierivoincidit.fr', 'message' => 'Bonjour.',
        ]);

        app(CremonaLeadReceiver::class)->dispatch($submission, ContactRequestData::fromArray(['name' => 'Ivo', 'email' => 'info@atelierivoincidit.fr', 'message' => 'Bonjour.']));

        Http::assertNothingSent();
    }

    public function test_it_records_a_retryable_delivery_when_cremona_is_unavailable(): void
    {
        $this->configureCremona();
        Http::fake(['cremona.test/*' => Http::response([], 503)]);
        $submission = Inquiry::query()->create([
            'name' => 'Ivo', 'email' => 'info@atelierivoincidit.fr', 'message' => 'Bonjour.',
        ]);

        $contact = ContactRequestData::fromArray(['name' => 'Ivo', 'email' => 'info@atelierivoincidit.fr', 'message' => 'Bonjour.']);
        app(CremonaLeadReceiver::class)->dispatch($submission, $contact);
        app(CremonaLeadReceiver::class)->dispatch($submission, $contact);

        $this->assertDatabaseHas('cremona_deliveries', [
            'inquiry_id' => $submission->getKey(),
            'status' => 'pending',
            'response_status' => 503,
            'attempts' => 2,
        ]);
        $this->assertDatabaseCount('cremona_deliveries', 1);
    }

    private function configureCremona(): void
    {
        config()->set('maracuja-cremona-connector.enabled', true);
        config()->set('maracuja-cremona-connector.endpoint', 'https://cremona.test/api/v1/incoming-requests');
        config()->set('maracuja-cremona-connector.token', 'secret-token');
        config()->set('maracuja-cremona-connector.site_reference', 'atelierivoincidit.fr');
    }
}
